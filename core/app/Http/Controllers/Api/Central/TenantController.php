<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Central\Tenant\StoreTenantRequest;
use App\Http\Requests\Api\Central\Tenant\UpdateTenantRequest;
use App\Http\Resources\Api\Central\TenantResource;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TenantController extends Controller
{
    /**
     * Display a listing of tenants
     */
    public function index(): AnonymousResourceCollection
    {
        $tenants = Tenant::with(['user', 'domains', 'payment_log'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

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
                'domain' => $request->domain,
            ]);
        }

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
        $tenant->load(['user', 'domains', 'payment_log']);

        return response()->json([
            'success' => true,
            'message' => 'Tenant retrieved successfully',
            'data' => new TenantResource($tenant),
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
        $tenant->delete();

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

        return response()->json([
            'success' => true,
            'message' => 'Tenant deactivated successfully',
            'data' => new TenantResource($tenant->load(['user', 'domains'])),
        ]);
    }
}

