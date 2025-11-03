<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Size\StoreSizeRequest;
use App\Http\Requests\Api\Tenant\Size\UpdateSizeRequest;
use App\Http\Resources\Api\Tenant\SizeResource;
use Modules\Attributes\Entities\Size;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class SizeController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_sizes_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Size::select(['id', 'name', 'size_code', 'slug', 'created_at', 'updated_at'])
                    ->latest()
                    ->paginate(20);
            });
            return SizeResource::collection($paginated);
        } catch (\Exception $e) {
            $sizes = Size::latest()->paginate(20);
            return SizeResource::collection($sizes);
        }
    }

    public function store(StoreSizeRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name'] ?? '', '-', null);
        } else {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        }

        $size = Size::create($data);
        $this->clearCache('tenant_sizes*');
        
        return response()->json([
            'success' => true,
            'message' => 'Size created successfully',
            'data' => new SizeResource($size),
        ], 201);
    }

    public function show(Size $size): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_size_{$size->id}", 600, function () use ($size) {
                return $size;
            });
            return response()->json([
                'success' => true,
                'message' => 'Size retrieved successfully',
                'data' => new SizeResource($cached),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Size retrieved successfully',
                'data' => new SizeResource($size),
            ]);
        }
    }

    public function update(UpdateSizeRequest $request, Size $size): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        if (isset($data['slug']) && !empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        } elseif (isset($data['name']) && empty($size->slug)) {
            $data['slug'] = Str::slug($data['name'], '-', null);
        }

        $size->update($data);
        $this->clearCache('tenant_sizes*');
        $this->clearCache("tenant_size_{$size->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Size updated successfully',
            'data' => new SizeResource($size),
        ]);
    }

    public function destroy(Size $size): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $size->id;
        $size->delete();
        $this->clearCache('tenant_sizes*');
        $this->clearCache("tenant_size_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Size deleted successfully']);
    }
}

