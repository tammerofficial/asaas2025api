<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Unit\StoreUnitRequest;
use App\Http\Requests\Api\Tenant\Unit\UpdateUnitRequest;
use App\Http\Resources\Api\Tenant\UnitResource;
use Modules\Attributes\Entities\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UnitController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_units_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return Unit::select(['id', 'name', 'created_at', 'updated_at'])
                    ->latest()
                    ->paginate(20);
            });
            return UnitResource::collection($paginated);
        } catch (\Exception $e) {
            $units = Unit::latest()->paginate(20);
            return UnitResource::collection($units);
        }
    }

    public function store(StoreUnitRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $unit = Unit::create($request->validated());
        $this->clearCache('tenant_units*');
        
        return response()->json([
            'success' => true,
            'message' => 'Unit created successfully',
            'data' => new UnitResource($unit),
        ], 201);
    }

    public function show(Unit $unit): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_unit_{$unit->id}", 600, function () use ($unit) {
                return $unit;
            });
            return response()->json([
                'success' => true,
                'message' => 'Unit retrieved successfully',
                'data' => new UnitResource($cached),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Unit retrieved successfully',
                'data' => new UnitResource($unit),
            ]);
        }
    }

    public function update(UpdateUnitRequest $request, Unit $unit): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $unit->update($request->validated());
        $this->clearCache('tenant_units*');
        $this->clearCache("tenant_unit_{$unit->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'Unit updated successfully',
            'data' => new UnitResource($unit),
        ]);
    }

    public function destroy(Unit $unit): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $unit->id;
        $unit->delete();
        $this->clearCache('tenant_units*');
        $this->clearCache("tenant_unit_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'Unit deleted successfully']);
    }
}

