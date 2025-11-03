<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Tag\StoreTagRequest;
use App\Http\Requests\Api\Tenant\Tag\UpdateTagRequest;
use App\Http\Resources\Api\Tenant\TagResource;
use Modules\Attributes\Entities\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_tags_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Tag::select(['id', 'tag_text', 'created_at', 'updated_at'])
                    ->latest()
                    ->paginate(20);
            });
            return TagResource::collection($paginated);
        } catch (\Exception $e) {
            $tags = Tag::latest()->paginate(20);
            return TagResource::collection($tags);
        }
    }

    public function store(StoreTagRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $tag = Tag::create($request->validated());
        $this->clearCache('tenant_tags*');
        
        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully',
            'data' => new TagResource($tag),
        ], 201);
    }

    public function show(Tag $tag): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_tag_{$tag->id}", 600, function () use ($tag) {
                return $tag;
            });
            return response()->json([
                'success' => true,
                'message' => 'Tag retrieved successfully',
                'data' => new TagResource($cached),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Tag retrieved successfully',
                'data' => new TagResource($tag),
            ]);
        }
    }

    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $tag->update($request->validated());
        $this->clearCache('tenant_tags*');
        $this->clearCache("tenant_tag_{$tag->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully',
            'data' => new TagResource($tag),
        ]);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $tag->id;
        $tag->delete();
        $this->clearCache('tenant_tags*');
        $this->clearCache("tenant_tag_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Tag deleted successfully']);
    }
}

