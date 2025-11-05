<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Central\Tenant\StoreTenantRequest;
use App\Http\Requests\Api\Central\Tenant\UpdateTenantRequest;
use App\Http\Resources\Api\Central\TenantResource;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TenantController extends BaseApiController
{
    /**
     * Display a listing of tenants
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $page = $request->get('page', 1);
        $key = $this->getCacheKey("central_tenants_list_page_{$page}");
        
        $tenants = $this->remember($key, $this->getListsTtl(), function () {
            return Tenant::with(['user', 'domains', 'payment_log'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }, ['tag:tenants', 'tag:central']);

        return TenantResource::collection($tenants);
    }

    /**
     * Store a newly created tenant
     */
    public function store(StoreTenantRequest $request): JsonResponse
    {
        $tenant = Tenant::create($request->validated());

        // Create default domain if provided
        if ($request->has('domain')) {
            $tenant->domains()->create([
                'domain' => trim($request->domain),
            ]);
        }

        // Clear cache after create
        $this->clearCacheTags(['tag:tenants', 'tag:central', 'tag:dashboard']);

        return response()->json([
            'success' => true,
            'message' => 'Tenant created successfully',
            'data' => new TenantResource($tenant->load(['user', 'domains'])),
        ], 201);
    }

    /**
     * Display the specified tenant
     */
    public function show(Tenant $tenant): JsonResponse
    {
        $key = $this->getCacheKey("central_tenant_details_{$tenant->id}");
        
        $tenantData = $this->remember($key, $this->getDetailsTtl(), function () use ($tenant) {
            return $tenant->load(['user', 'domains', 'payment_log']);
        }, ['tag:tenants', 'tag:central', "tag:tenant:{$tenant->id}"]);

        return response()->json([
            'success' => true,
            'message' => 'Tenant retrieved successfully',
            'data' => new TenantResource($tenantData),
        ]);
    }

    /**
     * Update the specified tenant
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant): JsonResponse
    {
        $tenant->update($request->validated());

        // Update domain if provided
        if ($request->has('domain')) {
            $tenant->domains()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                ['domain' => $request->domain]
            );
        }

        // Clear cache after update
        $this->clearCacheTags(['tag:tenants', 'tag:central', 'tag:dashboard', "tag:tenant:{$tenant->id}"]);

        return response()->json([
            'success' => true,
            'message' => 'Tenant updated successfully',
            'data' => new TenantResource($tenant->load(['user', 'domains'])),
        ]);
    }

    /**
     * Remove the specified tenant
     */
    public function destroy(Tenant $tenant): JsonResponse
    {
        $tenantId = $tenant->id;
        $tenant->delete();

        // Clear cache after delete
        $this->clearCacheTags(['tag:tenants', 'tag:central', 'tag:dashboard', "tag:tenant:{$tenantId}"]);

        return response()->json([
            'success' => true,
            'message' => 'Tenant deleted successfully',
        ]);
    }

    /**
     * Activate tenant
     */
    public function activate(Tenant $tenant): JsonResponse
    {
        $tenant->update(['user_id' => $tenant->user_id ?? 1]); // Ensure user_id is set

        // Clear cache after activation
        $this->clearCacheTags(['tag:tenants', 'tag:central', 'tag:dashboard', "tag:tenant:{$tenant->id}"]);

        return response()->json([
            'success' => true,
            'message' => 'Tenant activated successfully',
            'data' => new TenantResource($tenant->load(['user', 'domains'])),
        ]);
    }

    /**
     * Deactivate tenant
     */
    public function deactivate(Tenant $tenant): JsonResponse
    {
        $tenant->update(['user_id' => null]);

        // Clear cache after deactivation
        $this->clearCacheTags(['tag:tenants', 'tag:central', 'tag:dashboard', "tag:tenant:{$tenant->id}"]);

        return response()->json([
            'success' => true,
            'message' => 'Tenant deactivated successfully',
            'data' => new TenantResource($tenant->load(['user', 'domains'])),
        ]);
    }
}

