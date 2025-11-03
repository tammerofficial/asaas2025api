<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Media\UploadMediaRequest;
use App\Http\Requests\Api\Tenant\Media\BulkDeleteMediaRequest;
use App\Http\Resources\Api\Tenant\MediaResource;
use App\Models\MediaUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends BaseApiController
{
    /**
     * Display a listing of media files
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache media for 5 minutes
            $cacheKey = 'tenant_media_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return MediaUploader::select([
                    'id', 'title', 'alt', 'size', 'path', 'dimensions',
                    'user_type', 'user_id', 'load_from', 'is_synced', 'created_at', 'updated_at'
                ])
                ->latest()
                ->paginate(20);
            });

            return MediaResource::collection($paginated);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $media = MediaUploader::latest()->paginate(20);

            return MediaResource::collection($media);
        }
    }

    /**
     * Upload a media file
     */
    public function upload(UploadMediaRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $file = $request->file('file');
        $user = auth('sanctum')->user();
        
        if (!$file || !$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file upload',
            ], 422);
        }

        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('tenant/' . $tenant->id . '/uploads', $filename, 'public');

        // Get file dimensions for images
        $dimensions = null;
        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imagePath = Storage::disk('public')->path($path);
            $imageInfo = getimagesize($imagePath);
            if ($imageInfo) {
                $dimensions = $imageInfo[0] . 'x' . $imageInfo[1];
            }
        }

        // Create media record
        $media = MediaUploader::create([
            'title' => $request->input('title', $file->getClientOriginalName()),
            'alt' => $request->input('alt', $file->getClientOriginalName()),
            'size' => $file->getSize(),
            'path' => $path,
            'dimensions' => $dimensions,
            'user_type' => 'admin',
            'user_id' => $user?->id,
            'load_from' => 'tenant',
            'is_synced' => 0,
        ]);

        // Clear cache
        $this->clearCache('tenant_media*');

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => new MediaResource($media),
        ], 201);
    }

    /**
     * Display the specified media file
     */
    public function show(MediaUploader $media): JsonResponse
    {
        try {
            // Cache individual media for 10 minutes
            $cachedMedia = $this->remember("tenant_media_{$media->id}", 600, function () use ($media) {
                return $media;
            });

            return response()->json([
                'success' => true,
                'message' => 'Media retrieved successfully',
                'data' => new MediaResource($cachedMedia),
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            return response()->json([
                'success' => true,
                'message' => 'Media retrieved successfully',
                'data' => new MediaResource($media),
            ]);
        }
    }

    /**
     * Update the specified media file
     */
    public function update(Request $request, MediaUploader $media): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        $media->update($validated);

        // Clear related cache
        $this->clearCache('tenant_media*');
        $this->clearCache("tenant_media_{$media->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'data' => new MediaResource($media),
        ]);
    }

    /**
     * Remove the specified media file
     */
    public function destroy(MediaUploader $media): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        // Delete physical file
        if ($media->path && Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }

        $mediaId = $media->id;
        $media->delete();

        // Clear related cache
        $this->clearCache('tenant_media*');
        $this->clearCache("tenant_media_{$mediaId}*");

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully',
        ]);
    }

    /**
     * Bulk delete media files
     */
    public function bulkDelete(BulkDeleteMediaRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $mediaIds = $request->input('media_ids', []);
        $mediaFiles = MediaUploader::whereIn('id', $mediaIds)->get();

        $deletedCount = 0;
        foreach ($mediaFiles as $media) {
            // Delete physical file
            if ($media->path && Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }
            $media->delete();
            $deletedCount++;
        }

        // Clear cache
        $this->clearCache('tenant_media*');

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} media file(s)",
            'deleted_count' => $deletedCount,
        ]);
    }
}

