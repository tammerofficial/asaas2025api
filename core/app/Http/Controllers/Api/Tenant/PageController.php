<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Page\StorePageRequest;
use App\Http\Requests\Api\Tenant\Page\UpdatePageRequest;
use App\Http\Resources\Api\Tenant\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PageController extends BaseApiController
{
    /**
     * Display a listing of pages
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache pages for 5 minutes
            $cacheKey = 'tenant_pages_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Page::select([
                    'id', 'title', 'page_content', 'slug', 'visibility', 'page_builder',
                    'status', 'breadcrumb', 'navbar_variant', 'footer_variant', 'created_at', 'updated_at'
                ])
                ->with(['slug:id,slug,morphable_id,morphable_type', 'metainfo'])
                ->latest()
                ->paginate(20);
            });

            return PageResource::collection($paginated);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $pages = Page::with(['slug', 'metainfo'])
                ->latest()
                ->paginate(20);

            return PageResource::collection($pages);
        }
    }

    /**
     * Store a newly created page
     */
    public function store(StorePageRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $page = Page::create($request->validated());

        // Clear cache
        $this->clearCache('tenant_pages*');

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully',
            'data' => new PageResource($page->load(['slug', 'metainfo'])),
        ], 201);
    }

    /**
     * Display the specified page
     */
    public function show(Page $page): JsonResponse
    {
        try {
            // Cache individual page for 10 minutes
            $cachedPage = $this->remember("tenant_page_{$page->id}", 600, function () use ($page) {
                $page->load(['slug:id,slug,morphable_id,morphable_type', 'metainfo']);
                return $page;
            });

            return response()->json([
                'success' => true,
                'message' => 'Page retrieved successfully',
                'data' => new PageResource($cachedPage),
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $page->load(['slug', 'metainfo']);

            return response()->json([
                'success' => true,
                'message' => 'Page retrieved successfully',
                'data' => new PageResource($page),
            ]);
        }
    }

    /**
     * Update the specified page
     */
    public function update(UpdatePageRequest $request, Page $page): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $page->update($request->validated());

        // Clear related cache
        $this->clearCache('tenant_pages*');
        $this->clearCache("tenant_page_{$page->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully',
            'data' => new PageResource($page->load(['slug', 'metainfo'])),
        ]);
    }

    /**
     * Remove the specified page
     */
    public function destroy(Page $page): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $pageId = $page->id;
        $page->delete();

        // Clear related cache
        $this->clearCache('tenant_pages*');
        $this->clearCache("tenant_page_{$pageId}*");

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully',
        ]);
    }

    /**
     * Publish the specified page
     */
    public function publish(Page $page): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $page->update(['status' => 1]);

        // Clear cache
        $this->clearCache('tenant_pages*');
        $this->clearCache("tenant_page_{$page->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Page published successfully',
            'data' => new PageResource($page->load(['slug', 'metainfo'])),
        ]);
    }

    /**
     * Unpublish the specified page
     */
    public function unpublish(Page $page): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $page->update(['status' => 0]);

        // Clear cache
        $this->clearCache('tenant_pages*');
        $this->clearCache("tenant_page_{$page->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Page unpublished successfully',
            'data' => new PageResource($page->load(['slug', 'metainfo'])),
        ]);
    }
}

