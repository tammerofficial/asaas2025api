<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\City\StoreCityRequest;
use App\Http\Requests\Api\Tenant\City\UpdateCityRequest;
use App\Http\Resources\Api\Tenant\CityResource;
use Modules\CountryManage\Entities\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cacheKey = 'tenant_cities_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return City::select(['id', 'name', 'country_id', 'state_id', 'status', 'created_at', 'updated_at'])
                    ->with(['country:id,name', 'state:id,name'])
                    ->latest()
                    ->paginate(20);
            });
            return CityResource::collection($paginated);
        } catch (\Exception $e) {
            $cities = City::with(['country', 'state'])->latest()->paginate(20);
            return CityResource::collection($cities);
        }
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $city = City::create($request->validated());
        $this->clearCache('tenant_cities*');
        
        return response()->json([
            'success' => true,
            'message' => 'City created successfully',
            'data' => new CityResource($city->load(['country', 'state'])),
        ], 201);
    }

    public function show(City $city): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_city_{$city->id}", 600, function () use ($city) {
                $city->load(['country', 'state']);
                return $city;
            });
            return response()->json([
                'success' => true,
                'message' => 'City retrieved successfully',
                'data' => new CityResource($cached),
            ]);
        } catch (\Exception $e) {
            $city->load(['country', 'state']);
            return response()->json([
                'success' => true,
                'message' => 'City retrieved successfully',
                'data' => new CityResource($city),
            ]);
        }
    }

    public function update(UpdateCityRequest $request, City $city): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }

        $city->update($request->validated());
        $this->clearCache('tenant_cities*');
        $this->clearCache("tenant_city_{$city->id}*");
        
        return response()->json([
            'success' => true,
            'message' => 'City updated successfully',
            'data' => new CityResource($city->load(['country', 'state'])),
        ]);
    }

    public function destroy(City $city): JsonResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        }
        
        $id = $city->id;
        $city->delete();
        $this->clearCache('tenant_cities*');
        $this->clearCache("tenant_city_{$id}*");
        
        return response()->json(['success' => true, 'message' => 'City deleted successfully']);
    }
}

