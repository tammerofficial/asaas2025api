<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tenant\Category\StoreCategoryRequest;
use App\Http\Requests\Api\Tenant\Category\UpdateCategoryRequest;
use App\Http\Resources\Api\Tenant\CategoryResource;
use App\Http\Resources\Api\Tenant\ProductResource;
use Modules\Attributes\Entities\Category;
use Modules\Product\Entities\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(): AnonymousResourceCollection
    {
        // Select only required columns
        $categories = Category::select(['id', 'name', 'slug', 'description', 'image_id', 'status_id', 'created_at', 'updated_at'])
            ->with(['image:id,path,alt', 'status:id,name', 'subCategory:id,name,category_id', 'slug:id,slug,morphable_id,morphable_type'])
            ->latest()
            ->paginate(20);

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created category
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category->load(['image', 'status', 'slug'])),
        ], 201);
    }

    /**
     * Display the specified category
     */
    public function show(Category $category): JsonResponse
    {
        $category->load(['image', 'status', 'subCategory', 'slug']);

        return response()->json([
            'success' => true,
            'message' => 'Category retrieved successfully',
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Update the specified category
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => new CategoryResource($category->fresh()->load(['image', 'status', 'slug'])),
        ]);
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    /**
     * Get category products
     */
    public function products(Category $category): AnonymousResourceCollection
    {
        $products = $category->product()
            ->with(['status', 'badge', 'image', 'inventory'])
            ->latest()
            ->paginate(20);

        return ProductResource::collection($products);
    }
}

