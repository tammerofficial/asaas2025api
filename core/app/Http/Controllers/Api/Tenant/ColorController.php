<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Color\StoreColorRequest;
use App\Http\Requests\Api\Tenant\Color\UpdateColorRequest;
use App\Http\Resources\Api\Tenant\ColorResource;
use Modules\Attributes\Entities\Color;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ColorController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_colors_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Color::select(['id', 'name', 'color_code', 'slug', 'created_at', 'updated_at'])
                    ->latest()
                    ->paginate(20);
            });
            return ColorResource::collection($paginated);
        } catch (\Exception $e) {
            $colors = Color::latest()->paginate(20);
            return ColorResource::collection($colors);
        }
    }

    public function store(StoreColorRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name'] ?? '', '-', null);
        } else {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        }

        $color = Color::create($data);
        $this->clearCache('tenant_colors*');
        
        return response()->json([
            'success' => true,
            'message' => 'Color created successfully',
            'data' => new ColorResource($color),
        ], 201);
    }

    public function show(Color $color): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_color_{$color->id}", 600, function () use ($color) {
                return $color;
            });
            return response()->json([
                'success' => true,
                'message' => 'Color retrieved successfully',
                'data' => new ColorResource($cached),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Color retrieved successfully',
                'data' => new ColorResource($color),
            ]);
        }
    }

    public function update(UpdateColorRequest $request, Color $color): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $data = $request->validated();
        if (isset($data['slug']) && !empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug'], '-', null);
        } elseif (isset($data['name']) && empty($color->slug)) {
            $data['slug'] = Str::slug($data['name'], '-', null);
        }

        $color->update($data);
        $this->clearCache('tenant_colors*');
        $this->clearCache("tenant_color_{$color->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Color updated successfully',
            'data' => new ColorResource($color),
        ]);
    }

    public function destroy(Color $color): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $color->id;
        $color->delete();
        $this->clearCache('tenant_colors*');
        $this->clearCache("tenant_color_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Color deleted successfully']);
    }
}

