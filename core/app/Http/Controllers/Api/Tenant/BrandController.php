<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Brand\StoreBrandRequest;
use App\Http\Requests\Api\Tenant\Brand\UpdateBrandRequest;
use App\Http\Resources\Api\Tenant\BrandResource;
use Modules\Attributes\Entities\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class BrandController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_brands_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Brand::select(['id', 'name', 'slug', 'description', 'title', 'image_id', 'banner_id', 'url', 'created_at', 'updated_at'])
                    ->with(['logo:id,path', 'banner:id,path'])
                    ->latest()
                    ->paginate(20);
            });
            return BrandResource::collection($paginated);
        } catch (\Exception $e) {
            $brands = Brand::with(['logo', 'banner'])->latest()->paginate(20);
            return BrandResource::collection($brands);
        }
    }

    public function store(StoreBrandRequest $request): JsonResponse
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

        $brand = Brand::create($data);
        $this->clearCache('tenant_brands*');
        
        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully',
            'data' => new BrandResource($brand->load(['logo', 'banner'])),
        ], 201);
    }

    public function show(Brand $brand): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_brand_{$brand->id}", 600, function () use ($brand) {
                $brand->load(['logo', 'banner']);
                return $brand;
            });
            return response()->json([
                'success' => true,
                'message' => 'Brand retrieved successfully',
                'data' => new BrandResource($cached),
            ]);
        } catch (\Exception $e) {
            $brand->load(['logo', 'banner']);
            return response()->json([
                'success' => true,
                'message' => 'Brand retrieved successfully',
                'data' => new BrandResource($brand),
            ]);
        }
    }

    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        if (isset($data['slug']) && !empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        } elseif (isset($data['name']) && empty($brand->slug)) {
            $data['slug'] = Str::slug($data['name'], '-', null);
        }

        $brand->update($data);
        $this->clearCache('tenant_brands*');
        $this->clearCache("tenant_brand_{$brand->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully',
            'data' => new BrandResource($brand->load(['logo', 'banner'])),
        ]);
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $brand->id;
        $brand->delete();
        $this->clearCache('tenant_brands*');
        $this->clearCache("tenant_brand_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Brand deleted successfully']);
    }
}

