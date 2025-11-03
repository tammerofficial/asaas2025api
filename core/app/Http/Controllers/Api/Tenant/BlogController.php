<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Blog\StoreBlogRequest;
use App\Http\Requests\Api\Tenant\Blog\UpdateBlogRequest;
use App\Http\Resources\Api\Tenant\BlogResource;
use Modules\Blog\Entities\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BlogController extends BaseApiController
{
    /**
     * Display a listing of blogs
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache blogs for 5 minutes
            $cacheKey = 'tenant_blogs_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Blog::select([
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
            $blogs = Blog::with(['category', 'user', 'admin', 'slug'])
                ->latest()
                ->paginate(20);

            return BlogResource::collection($blogs);
        }
    }

    /**
     * Store a newly created blog
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $data = $request->validated();
        
        // Set created_by based on authenticated user
        $admin = auth('sanctum')->user();
        if ($admin) {
            $data['admin_id'] = $admin->id;
            $data['created_by'] = 'admin';
        }

        $blog = Blog::create($data);

        // Clear cache
        $this->clearCache('tenant_blogs*');

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully',
            'data' => new BlogResource($blog->load(['category', 'user', 'admin', 'slug'])),
        ], 201);
    }

    /**
     * Display the specified blog
     */
    public function show(Blog $blog): JsonResponse
    {
        try {
            // Cache individual blog for 10 minutes
            $cachedBlog = $this->remember("tenant_blog_{$blog->id}", 600, function () use ($blog) {
                $blog->load([
                    'category:id,title,status',
                    'user:id,name,email',
                    'admin:id,name,email',
                    'slug:id,slug,morphable_id,morphable_type',
                    'comments'
                ]);
                return $blog;
            });

            return response()->json([
                'success' => true,
                'message' => 'Blog retrieved successfully',
                'data' => new BlogResource($cachedBlog),
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $blog->load(['category', 'user', 'admin', 'slug', 'comments']);

            return response()->json([
                'success' => true,
                'message' => 'Blog retrieved successfully',
                'data' => new BlogResource($blog),
            ]);
        }
    }

    /**
     * Update the specified blog
     */
    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $blog->update($request->validated());

        // Clear related cache
        $this->clearCache('tenant_blogs*');
        $this->clearCache("tenant_blog_{$blog->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully',
            'data' => new BlogResource($blog->load(['category', 'user', 'admin', 'slug'])),
        ]);
    }

    /**
     * Remove the specified blog
     */
    public function destroy(Blog $blog): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $blogId = $blog->id;
        $blog->delete();

        // Clear related cache
        $this->clearCache('tenant_blogs*');
        $this->clearCache("tenant_blog_{$blogId}*");

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully',
        ]);
    }

    /**
     * Publish the specified blog
     */
    public function publish(Blog $blog): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $blog->update(['status' => 1]);

        // Clear cache
        $this->clearCache('tenant_blogs*');
        $this->clearCache("tenant_blog_{$blog->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Blog published successfully',
            'data' => new BlogResource($blog->load(['category', 'user', 'admin', 'slug'])),
        ]);
    }

    /**
     * Unpublish the specified blog
     */
    public function unpublish(Blog $blog): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $blog->update(['status' => 0]);

        // Clear cache
        $this->clearCache('tenant_blogs*');
        $this->clearCache("tenant_blog_{$blog->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Blog unpublished successfully',
            'data' => new BlogResource($blog->load(['category', 'user', 'admin', 'slug'])),
        ]);
    }
}

