<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\SubCategory\StoreSubCategoryRequest;
use App\Http\Requests\Api\Tenant\SubCategory\UpdateSubCategoryRequest;
use App\Http\Resources\Api\Tenant\SubCategoryResource;
use Modules\Attributes\Entities\SubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class SubCategoryController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_sub_categories_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return SubCategory::select(['id', 'category_id', 'name', 'slug', 'description', 'image_id', 'status_id', 'created_at', 'updated_at'])
                    ->with(['category:id,name', 'image:id,path', 'status:id,name'])
                    ->latest()
                    ->paginate(20);
            });
            return SubCategoryResource::collection($paginated);
        } catch (\Exception $e) {
            $subCategories = SubCategory::with(['category', 'image', 'status'])->latest()->paginate(20);
            return SubCategoryResource::collection($subCategories);
        }
    }

    public function store(StoreSubCategoryRequest $request): JsonResponse
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

        $subCategory = SubCategory::create($data);
        $this->clearCache('tenant_sub_categories*');
        
        return response()->json([
            'success' => true,
            'message' => 'Sub category created successfully',
            'data' => new SubCategoryResource($subCategory->load(['category', 'image', 'status'])),
        ], 201);
    }

    public function show(SubCategory $subCategory): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_sub_category_{$subCategory->id}", 600, function () use ($subCategory) {
                $subCategory->load(['category', 'image', 'status', 'childCategory']);
                return $subCategory;
            });
            return response()->json([
                'success' => true,
                'message' => 'Sub category retrieved successfully',
                'data' => new SubCategoryResource($cached),
            ]);
        } catch (\Exception $e) {
            $subCategory->load(['category', 'image', 'status', 'childCategory']);
            return response()->json([
                'success' => true,
                'message' => 'Sub category retrieved successfully',
                'data' => new SubCategoryResource($subCategory),
            ]);
        }
    }

    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        if (isset($data['slug']) && !empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        } elseif (isset($data['name']) && empty($subCategory->slug)) {
            $data['slug'] = Str::slug($data['name'], '-', null);
        }

        $subCategory->update($data);
        $this->clearCache('tenant_sub_categories*');
        $this->clearCache("tenant_sub_category_{$subCategory->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Sub category updated successfully',
            'data' => new SubCategoryResource($subCategory->load(['category', 'image', 'status'])),
        ]);
    }

    public function destroy(SubCategory $subCategory): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $subCategory->id;
        $subCategory->delete();
        $this->clearCache('tenant_sub_categories*');
        $this->clearCache("tenant_sub_category_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Sub category deleted successfully']);
    }
}

