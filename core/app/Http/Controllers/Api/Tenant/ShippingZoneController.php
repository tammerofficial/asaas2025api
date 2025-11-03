<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Shipping\Zone\StoreShippingZoneRequest;
use App\Http\Requests\Api\Tenant\Shipping\Zone\UpdateShippingZoneRequest;
use App\Http\Resources\Api\Tenant\ShippingZoneResource;
use Modules\ShippingModule\Entities\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShippingZoneController extends BaseApiController
{
    /**
     * Display a listing of shipping zones
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache zones for 10 minutes
            $cacheKey = 'tenant_shipping_zones_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 600, function () {
                return Zone::select(['id', 'name', 'created_at', 'updated_at'])
                    ->with(['region:id,zone_id,country,state'])
                    ->latest()
                    ->paginate(20);
            });

            return ShippingZoneResource::collection($paginated);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $zones = Zone::with(['region'])->latest()->paginate(20);

            return ShippingZoneResource::collection($zones);
        }
    }

    /**
     * Store a newly created shipping zone
     */
    public function store(StoreShippingZoneRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $zone = Zone::create($request->validated());

        // Clear cache
        $this->clearCache('tenant_shipping_zones*');

        return response()->json([
            'success' => true,
            'message' => 'Shipping zone created successfully',
            'data' => new ShippingZoneResource($zone->load(['region'])),
        ], 201);
    }

    /**
     * Display the specified shipping zone
     */
    public function show(Zone $shippingZone): JsonResponse
    {
        try {
            // Cache individual zone for 10 minutes
            $cachedZone = $this->remember("tenant_shipping_zone_{$shippingZone->id}", 600, function () use ($shippingZone) {
                $shippingZone->load(['region']);
                return $shippingZone;
            });

            return response()->json([
                'success' => true,
                'message' => 'Shipping zone retrieved successfully',
                'data' => new ShippingZoneResource($cachedZone),
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $shippingZone->load(['region']);

            return response()->json([
                'success' => true,
                'message' => 'Shipping zone retrieved successfully',
                'data' => new ShippingZoneResource($shippingZone),
            ]);
        }
    }

    /**
     * Update the specified shipping zone
     */
    public function update(UpdateShippingZoneRequest $request, Zone $shippingZone): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $shippingZone->update($request->validated());

        // Clear related cache
        $this->clearCache('tenant_shipping_zones*');
        $this->clearCache("tenant_shipping_zone_{$shippingZone->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Shipping zone updated successfully',
            'data' => new ShippingZoneResource($shippingZone->load(['region'])),
        ]);
    }

    /**
     * Remove the specified shipping zone
     */
    public function destroy(Zone $shippingZone): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $zoneId = $shippingZone->id;
        $shippingZone->delete();

        // Clear related cache
        $this->clearCache('tenant_shipping_zones*');
        $this->clearCache("tenant_shipping_zone_{$zoneId}*");

        return response()->json([
            'success' => true,
            'message' => 'Shipping zone deleted successfully',
        ]);
    }
}

