<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\ChildCategory\StoreChildCategoryRequest;
use App\Http\Requests\Api\Tenant\ChildCategory\UpdateChildCategoryRequest;
use App\Http\Resources\Api\Tenant\ChildCategoryResource;
use Modules\Attributes\Entities\ChildCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ChildCategoryController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_child_categories_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return ChildCategory::select(['id', 'category_id', 'sub_category_id', 'name', 'slug', 'description', 'image_id', 'status_id', 'created_at', 'updated_at'])
                    ->with(['category:id,name', 'sub_category:id,name', 'image:id,path', 'status:id,name'])
                    ->latest()
                    ->paginate(20);
            });
            return ChildCategoryResource::collection($paginated);
        } catch (\Exception $e) {
            $childCategories = ChildCategory::with(['category', 'sub_category', 'image', 'status'])->latest()->paginate(20);
            return ChildCategoryResource::collection($childCategories);
        }
    }

    public function store(StoreChildCategoryRequest $request): JsonResponse
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

        $childCategory = ChildCategory::create($data);
        $this->clearCache('tenant_child_categories*');
        
        return response()->json([
            'success' => true,
            'message' => 'Child category created successfully',
            'data' => new ChildCategoryResource($childCategory->load(['category', 'sub_category', 'image', 'status'])),
        ], 201);
    }

    public function show(ChildCategory $childCategory): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_child_category_{$childCategory->id}", 600, function () use ($childCategory) {
                $childCategory->load(['category', 'sub_category', 'image', 'status']);
                return $childCategory;
            });
            return response()->json([
                'success' => true,
                'message' => 'Child category retrieved successfully',
                'data' => new ChildCategoryResource($cached),
            ]);
        } catch (\Exception $e) {
            $childCategory->load(['category', 'sub_category', 'image', 'status']);
            return response()->json([
                'success' => true,
                'message' => 'Child category retrieved successfully',
                'data' => new ChildCategoryResource($childCategory),
            ]);
        }
    }

    public function update(UpdateChildCategoryRequest $request, ChildCategory $childCategory): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        if (isset($data['slug']) && !empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        } elseif (isset($data['name']) && empty($childCategory->slug)) {
            $data['slug'] = Str::slug($data['name'], '-', null);
        }

        $childCategory->update($data);
        $this->clearCache('tenant_child_categories*');
        $this->clearCache("tenant_child_category_{$childCategory->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Child category updated successfully',
            'data' => new ChildCategoryResource($childCategory->load(['category', 'sub_category', 'image', 'status'])),
        ]);
    }

    public function destroy(ChildCategory $childCategory): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $childCategory->id;
        $childCategory->delete();
        $this->clearCache('tenant_child_categories*');
        $this->clearCache("tenant_child_category_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Child category deleted successfully']);
    }
}

