<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Blog\Category\StoreBlogCategoryRequest;
use App\Http\Requests\Api\Tenant\Blog\Category\UpdateBlogCategoryRequest;
use App\Http\Resources\Api\Tenant\BlogCategoryResource;
use App\Http\Resources\Api\Tenant\BlogResource;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BlogCategoryController extends BaseApiController
{
    /**
     * Display a listing of blog categories
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache categories for 10 minutes
            $cacheKey = 'tenant_blog_categories_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 600, function () {
                return BlogCategory::select(['id', 'title', 'status', 'slug', 'created_at', 'updated_at'])
                    ->with(['slug:id,slug,morphable_id,morphable_type'])
                    ->latest()
                    ->paginate(20);
            });

            return BlogCategoryResource::collection($paginated);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $categories = BlogCategory::with(['slug'])
                ->latest()
                ->paginate(20);

            return BlogCategoryResource::collection($categories);
        }
    }

    /**
     * Store a newly created blog category
     */
    public function store(StoreBlogCategoryRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $category = BlogCategory::create($request->validated());

        // Clear cache
        $this->clearCache('tenant_blog_categories*');

        return response()->json([
            'success' => true,
            'message' => 'Blog category created successfully',
            'data' => new BlogCategoryResource($category->load(['slug'])),
        ], 201);
    }

    /**
     * Display the specified blog category
     */
    public function show(BlogCategory $blogCategory): JsonResponse
    {
        try {
            // Cache individual category for 10 minutes
            $cachedCategory = $this->remember("tenant_blog_category_{$blogCategory->id}", 600, function () use ($blogCategory) {
                $blogCategory->load(['slug']);
                return $blogCategory;
            });

            return response()->json([
                'success' => true,
                'message' => 'Blog category retrieved successfully',
                'data' => new BlogCategoryResource($cachedCategory),
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $blogCategory->load(['slug']);

            return response()->json([
                'success' => true,
                'message' => 'Blog category retrieved successfully',
                'data' => new BlogCategoryResource($blogCategory),
            ]);
        }
    }

    /**
     * Update the specified blog category
     */
    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $blogCategory->update($request->validated());

        // Clear related cache
        $this->clearCache('tenant_blog_categories*');
        $this->clearCache("tenant_blog_category_{$blogCategory->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Blog category updated successfully',
            'data' => new BlogCategoryResource($blogCategory->load(['slug'])),
        ]);
    }

    /**
     * Remove the specified blog category
     */
    public function destroy(BlogCategory $blogCategory): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $categoryId = $blogCategory->id;
        $blogCategory->delete();

        // Clear related cache
        $this->clearCache('tenant_blog_categories*');
        $this->clearCache("tenant_blog_category_{$categoryId}*");

        return response()->json([
            'success' => true,
            'message' => 'Blog category deleted successfully',
        ]);
    }

    /**
     * Get blogs by category
     */
    public function blogs(BlogCategory $blogCategory): AnonymousResourceCollection
    {
        try {
            // Cache blogs by category for 5 minutes
            $cacheKey = "tenant_blog_category_{$blogCategory->id}_blogs_" . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () use ($blogCategory) {
                return Blog::where('category_id', $blogCategory->id)
                    ->select([
                        'id', 'category_id', 'user_id', 'admin_id', 'title', 'slug', 'blog_content',
                        'image', 'author', 'excerpt', 'status', 'image_gallery', 'views', 'video_url',
                        'visibility', 'featured', 'created_by', 'tags', 'created_at', 'updated_at'
                    ])
                    ->with([
                        'category:id,title,status',
                        'user:id,name,email',
                        'admin:id,name,email',
                        'slug:id,slug,morphable_id,morphable_type'
                    ])
                    ->latest()
                    ->paginate(20);
            });

            return BlogResource::collection($paginated);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $blogs = Blog::where('category_id', $blogCategory->id)
                ->with(['category', 'user', 'admin', 'slug'])
                ->latest()
                ->paginate(20);

            return BlogResource::collection($blogs);
        }
    }
}

