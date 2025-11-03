<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tenant\Product\StoreProductRequest;
use App\Http\Requests\Api\Tenant\Product\UpdateProductRequest;
use App\Http\Resources\Api\Tenant\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Product\Entities\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(): AnonymousResourceCollection
    {
        // Select only required columns and optimize relationships
        $products = Product::select([
            'id', 'name', 'slug', 'summary', 'description', 'brand_id', 'status_id',
            'cost', 'price', 'sale_price', 'image_id', 'badge_id', 'min_purchase',
            'max_purchase', 'is_refundable', 'is_inventory_warn_able', 'is_in_house',
            'is_taxable', 'tax_class_id', 'created_at', 'updated_at'
        ])
        ->with(['category:id,name', 'brand:id,name', 'status:id,name', 'inventory:id,product_id,stock_count'])
        ->latest()
        ->paginate(20);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $product = Product::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => new ProductResource($product->load(['category', 'brand', 'status', 'inventory'])),
        ], 201);
    }

    /**
     * Display the specified product
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'brand', 'status', 'inventory', 'inventoryDetail']);

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $product->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product->load(['category', 'brand', 'status', 'inventory'])),
        ]);
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    /**
     * Activate a product
     */
    public function activate(Product $product): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $product->update([
            'status_id' => \App\Enums\StatusEnums::PUBLISH,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product activated successfully',
            'data' => new ProductResource($product->load(['category', 'brand', 'status', 'inventory'])),
        ]);
    }

    /**
     * Deactivate a product
     */
    public function deactivate(Product $product): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $product->update([
            'status_id' => \App\Enums\StatusEnums::DRAFT,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product deactivated successfully',
            'data' => new ProductResource($product->load(['category', 'brand', 'status', 'inventory'])),
        ]);
    }
}

