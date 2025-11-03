<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Service\Category\StoreServiceCategoryRequest;
use App\Http\Requests\Api\Tenant\Service\Category\UpdateServiceCategoryRequest;
use App\Http\Resources\Api\Tenant\ServiceCategoryResource;
use Modules\Service\Entities\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceCategoryController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_service_categories_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return ServiceCategory::select(['id', 'title', 'status', 'created_at', 'updated_at'])
                    ->latest()
                    ->paginate(20);
            });
            return ServiceCategoryResource::collection($paginated);
        } catch (\Exception $e) {
            $categories = ServiceCategory::latest()->paginate(20);
            return ServiceCategoryResource::collection($categories);
        }
    }

    public function store(StoreServiceCategoryRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $category = ServiceCategory::create($request->validated());
        $this->clearCache('tenant_service_categories*');
        
        return response()->json([
            'success' => true,
            'message' => 'Service category created successfully',
            'data' => new ServiceCategoryResource($category),
        ], 201);
    }

    public function show(ServiceCategory $serviceCategory): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_service_category_{$serviceCategory->id}", 600, function () use ($serviceCategory) {
                return $serviceCategory;
            });
            return response()->json([
                'success' => true,
                'message' => 'Service category retrieved successfully',
                'data' => new ServiceCategoryResource($cached),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Service category retrieved successfully',
                'data' => new ServiceCategoryResource($serviceCategory),
            ]);
        }
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $serviceCategory->update($request->validated());
        $this->clearCache('tenant_service_categories*');
        $this->clearCache("tenant_service_category_{$serviceCategory->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Service category updated successfully',
            'data' => new ServiceCategoryResource($serviceCategory),
        ]);
    }

    public function destroy(ServiceCategory $serviceCategory): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $serviceCategory->id;
        $serviceCategory->delete();
        $this->clearCache('tenant_service_categories*');
        $this->clearCache("tenant_service_category_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Service category deleted successfully']);
    }

    public function services(ServiceCategory $serviceCategory): AnonymousResourceCollection
    {
        try {
            $cacheKey = "tenant_service_category_{$serviceCategory->id}_services_" . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () use ($serviceCategory) {
                return \Modules\Service\Entities\Service::where('category_id', $serviceCategory->id)
                    ->select(['id', 'category_id', 'slug', 'title', 'description', 'image', 'status', 'created_at', 'updated_at'])
                    ->latest()
                    ->paginate(20);
            });
            return ServiceResource::collection($paginated);
        } catch (\Exception $e) {
            $services = \Modules\Service\Entities\Service::where('category_id', $serviceCategory->id)->latest()->paginate(20);
            return ServiceResource::collection($services);
        }
    }
}

