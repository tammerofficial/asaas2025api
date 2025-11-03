<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\ProductAttribute\StoreProductAttributeRequest;
use App\Http\Requests\Api\Tenant\ProductAttribute\UpdateProductAttributeRequest;
use App\Http\Resources\Api\Tenant\ProductAttributeResource;
use Modules\Product\Entities\ProductAttribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductAttributeController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_product_attributes_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return ProductAttribute::select(['id', 'title', 'terms', 'created_at', 'updated_at'])
                    ->latest()
                    ->paginate(20);
            });
            return ProductAttributeResource::collection($paginated);
        } catch (\Exception $e) {
            $attributes = ProductAttribute::latest()->paginate(20);
            return ProductAttributeResource::collection($attributes);
        }
    }

    public function store(StoreProductAttributeRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        $data['terms'] = json_encode($data['terms'] ?? []);

        $attribute = ProductAttribute::create($data);
        $this->clearCache('tenant_product_attributes*');
        
        return response()->json([
            'success' => true,
            'message' => 'Product attribute created successfully',
            'data' => new ProductAttributeResource($attribute),
        ], 201);
    }

    public function show(ProductAttribute $productAttribute): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_product_attribute_{$productAttribute->id}", 600, function () use ($productAttribute) {
                return $productAttribute;
            });
            return response()->json([
                'success' => true,
                'message' => 'Product attribute retrieved successfully',
                'data' => new ProductAttributeResource($cached),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Product attribute retrieved successfully',
                'data' => new ProductAttributeResource($productAttribute),
            ]);
        }
    }

    public function update(UpdateProductAttributeRequest $request, ProductAttribute $productAttribute): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        if (isset($data['terms'])) {
            $data['terms'] = json_encode($data['terms']);
        }

        $productAttribute->update($data);
        $this->clearCache('tenant_product_attributes*');
        $this->clearCache("tenant_product_attribute_{$productAttribute->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Product attribute updated successfully',
            'data' => new ProductAttributeResource($productAttribute),
        ]);
    }

    public function destroy(ProductAttribute $productAttribute): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $productAttribute->id;
        $productAttribute->delete();
        $this->clearCache('tenant_product_attributes*');
        $this->clearCache("tenant_product_attribute_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Product attribute deleted successfully']);
    }
}

