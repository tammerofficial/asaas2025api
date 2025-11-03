<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\DeliveryOption\StoreDeliveryOptionRequest;
use App\Http\Requests\Api\Tenant\DeliveryOption\UpdateDeliveryOptionRequest;
use App\Http\Resources\Api\Tenant\DeliveryOptionResource;
use Modules\Attributes\Entities\DeliveryOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeliveryOptionController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_delivery_options_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return DeliveryOption::select(['id', 'icon', 'title', 'sub_title', 'created_at', 'updated_at'])
                    ->latest()
                    ->paginate(20);
            });
            return DeliveryOptionResource::collection($paginated);
        } catch (\Exception $e) {
            $deliveryOptions = DeliveryOption::latest()->paginate(20);
            return DeliveryOptionResource::collection($deliveryOptions);
        }
    }

    public function store(StoreDeliveryOptionRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $deliveryOption = DeliveryOption::create($request->validated());
        $this->clearCache('tenant_delivery_options*');
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery option created successfully',
            'data' => new DeliveryOptionResource($deliveryOption),
        ], 201);
    }

    public function show(DeliveryOption $deliveryOption): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_delivery_option_{$deliveryOption->id}", 600, function () use ($deliveryOption) {
                return $deliveryOption;
            });
            return response()->json([
                'success' => true,
                'message' => 'Delivery option retrieved successfully',
                'data' => new DeliveryOptionResource($cached),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Delivery option retrieved successfully',
                'data' => new DeliveryOptionResource($deliveryOption),
            ]);
        }
    }

    public function update(UpdateDeliveryOptionRequest $request, DeliveryOption $deliveryOption): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $deliveryOption->update($request->validated());
        $this->clearCache('tenant_delivery_options*');
        $this->clearCache("tenant_delivery_option_{$deliveryOption->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery option updated successfully',
            'data' => new DeliveryOptionResource($deliveryOption),
        ]);
    }

    public function destroy(DeliveryOption $deliveryOption): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $deliveryOption->id;
        $deliveryOption->delete();
        $this->clearCache('tenant_delivery_options*');
        $this->clearCache("tenant_delivery_option_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Delivery option deleted successfully']);
    }
}

