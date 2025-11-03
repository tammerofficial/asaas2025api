<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Shipping\Method\StoreShippingMethodRequest;
use App\Http\Requests\Api\Tenant\Shipping\Method\UpdateShippingMethodRequest;
use App\Http\Resources\Api\Tenant\ShippingMethodResource;
use Modules\ShippingModule\Entities\ShippingMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShippingMethodController extends BaseApiController
{
    /**
     * Display a listing of shipping methods
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache methods for 5 minutes
            $cacheKey = 'tenant_shipping_methods_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return ShippingMethod::select([
                    'id', 'name', 'zone_id', 'is_default', 'created_at', 'updated_at'
                ])
                ->with([
                    'zone:id,name',
                    'options:id,shipping_method_id,title,status,tax_status,cost,minimum_order_amount,setting_preset,coupon'
                ])
                ->latest()
                ->paginate(20);
            });

            return ShippingMethodResource::collection($paginated);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $methods = ShippingMethod::with(['zone', 'options'])->latest()->paginate(20);

            return ShippingMethodResource::collection($methods);
        }
    }

    /**
     * Store a newly created shipping method
     */
    public function store(StoreShippingMethodRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $validated = $request->validated();
        $options = $validated['options'] ?? null;
        unset($validated['options']);

        $method = ShippingMethod::create($validated);

        // Create options if provided
        if ($options && $method->options) {
            $method->options()->updateOrCreate(
                ['shipping_method_id' => $method->id],
                $options
            );
        }

        // Clear cache
        $this->clearCache('tenant_shipping_methods*');

        return response()->json([
            'success' => true,
            'message' => 'Shipping method created successfully',
            'data' => new ShippingMethodResource($method->load(['zone', 'options'])),
        ], 201);
    }

    /**
     * Display the specified shipping method
     */
    public function show(ShippingMethod $shippingMethod): JsonResponse
    {
        try {
            // Cache individual method for 10 minutes
            $cachedMethod = $this->remember("tenant_shipping_method_{$shippingMethod->id}", 600, function () use ($shippingMethod) {
                $shippingMethod->load(['zone', 'options']);
                return $shippingMethod;
            });

            return response()->json([
                'success' => true,
                'message' => 'Shipping method retrieved successfully',
                'data' => new ShippingMethodResource($cachedMethod),
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $shippingMethod->load(['zone', 'options']);

            return response()->json([
                'success' => true,
                'message' => 'Shipping method retrieved successfully',
                'data' => new ShippingMethodResource($shippingMethod),
            ]);
        }
    }

    /**
     * Update the specified shipping method
     */
    public function update(UpdateShippingMethodRequest $request, ShippingMethod $shippingMethod): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $validated = $request->validated();
        $options = $validated['options'] ?? null;
        unset($validated['options']);

        $shippingMethod->update($validated);

        // Update options if provided
        if ($options) {
            $shippingMethod->options()->updateOrCreate(
                ['shipping_method_id' => $shippingMethod->id],
                $options
            );
        }

        // Clear related cache
        $this->clearCache('tenant_shipping_methods*');
        $this->clearCache("tenant_shipping_method_{$shippingMethod->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Shipping method updated successfully',
            'data' => new ShippingMethodResource($shippingMethod->load(['zone', 'options'])),
        ]);
    }

    /**
     * Remove the specified shipping method
     */
    public function destroy(ShippingMethod $shippingMethod): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $methodId = $shippingMethod->id;
        $shippingMethod->delete();

        // Clear related cache
        $this->clearCache('tenant_shipping_methods*');
        $this->clearCache("tenant_shipping_method_{$methodId}*");

        return response()->json([
            'success' => true,
            'message' => 'Shipping method deleted successfully',
        ]);
    }
}

