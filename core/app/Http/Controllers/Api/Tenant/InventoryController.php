<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Inventory\StoreInventoryRequest;
use App\Http\Requests\Api\Tenant\Inventory\UpdateInventoryRequest;
use App\Http\Resources\Api\Tenant\InventoryResource;
use Modules\Inventory\Entities\Inventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InventoryController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_inventory_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Inventory::select([
                    'id', 'product_id', 'stock_count', 'stock_alert_quantity',
                    'sold_count', 'admin_id', 'created_at', 'updated_at'
                ])
                ->with(['product:id,name', 'admin:id,name'])
                ->latest()
                ->paginate(20);
            });
            return InventoryResource::collection($paginated);
        } catch (\Exception $e) {
            $inventory = Inventory::with(['product', 'admin'])->latest()->paginate(20);
            return InventoryResource::collection($inventory);
        }
    }

    public function store(StoreInventoryRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        $inventory = Inventory::create($request->validated());
        $this->clearCache('tenant_inventory*');
        return response()->json([
            'success' => true,
            'message' => 'Inventory created successfully',
            'data' => new InventoryResource($inventory->load(['product', 'admin'])),
        ], 201);
    }

    public function show(Inventory $inventory): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_inventory_{$inventory->id}", 600, function () use ($inventory) {
                $inventory->load(['product', 'admin']);
                return $inventory;
            });
            return response()->json([
                'success' => true,
                'message' => 'Inventory retrieved successfully',
                'data' => new InventoryResource($cached),
            ]);
        } catch (\Exception $e) {
            $inventory->load(['product', 'admin']);
            return response()->json([
                'success' => true,
                'message' => 'Inventory retrieved successfully',
                'data' => new InventoryResource($inventory),
            ]);
        }
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        $inventory->update($request->validated());
        $this->clearCache('tenant_inventory*');
        $this->clearCache("tenant_inventory_{$inventory->id}*");
        return response()->json([
            'success' => true,
            'message' => 'Inventory updated successfully',
            'data' => new InventoryResource($inventory->load(['product', 'admin'])),
        ]);
    }

    public function destroy(Inventory $inventory): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        $id = $inventory->id;
        $inventory->delete();
        $this->clearCache('tenant_inventory*');
        $this->clearCache("tenant_inventory_{$id}*");
        return response()->json(['success' => true, 'message' => 'Inventory deleted successfully']);
    }

    public function adjustStock(Inventory $inventory, \Illuminate\Http\Request $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        $validated = $request->validate([
            'adjustment' => ['required', 'integer'],
            'type' => ['required', 'string', 'in:add,subtract'],
        ]);
        if ($validated['type'] === 'add') {
            $inventory->increment('stock_count', $validated['adjustment']);
        } else {
            $inventory->decrement('stock_count', $validated['adjustment']);
        }
        $this->clearCache('tenant_inventory*');
        $this->clearCache("tenant_inventory_{$inventory->id}*");
        return response()->json([
            'success' => true,
            'message' => 'Stock adjusted successfully',
            'data' => new InventoryResource($inventory->fresh()->load(['product', 'admin'])),
        ]);
    }
}

