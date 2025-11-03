<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Service\StoreServiceRequest;
use App\Http\Requests\Api\Tenant\Service\UpdateServiceRequest;
use App\Http\Resources\Api\Tenant\ServiceResource;
use Modules\Service\Entities\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ServiceController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_services_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Service::select([
                    'id', 'category_id', 'slug', 'title', 'description', 'image',
                    'meta_tag', 'meta_description', 'status', 'created_at', 'updated_at'
                ])
                ->with(['category:id,title'])
                ->latest()
                ->paginate(20);
            });
            return ServiceResource::collection($paginated);
        } catch (\Exception $e) {
            $services = Service::with(['category'])->latest()->paginate(20);
            return ServiceResource::collection($services);
        }
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        
        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title'], '-', null);
        } else {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        }

        $service = Service::create($data);
        $this->clearCache('tenant_services*');
        
        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => new ServiceResource($service->load(['category'])),
        ], 201);
    }

    public function show(Service $service): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_service_{$service->id}", 600, function () use ($service) {
                $service->load(['category']);
                return $service;
            });
            return response()->json([
                'success' => true,
                'message' => 'Service retrieved successfully',
                'data' => new ServiceResource($cached),
            ]);
        } catch (\Exception $e) {
            $service->load(['category']);
            return response()->json([
                'success' => true,
                'message' => 'Service retrieved successfully',
                'data' => new ServiceResource($service),
            ]);
        }
    }

    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        
        // Handle slug
        if (isset($data['slug']) && !empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        } elseif (isset($data['title']) && empty($service->slug)) {
            $data['slug'] = Str::slug($data['title'], '-', null);
        }

        $service->update($data);
        $this->clearCache('tenant_services*');
        $this->clearCache("tenant_service_{$service->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => new ServiceResource($service->load(['category'])),
        ]);
    }

    public function destroy(Service $service): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $service->id;
        $service->delete();
        $this->clearCache('tenant_services*');
        $this->clearCache("tenant_service_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Service deleted successfully']);
    }
}

