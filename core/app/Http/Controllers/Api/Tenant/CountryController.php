<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\Tenant\CountryResource;
use Modules\CountryManage\Entities\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class CountryController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_countries', 600, function () {
                return Country::select(['id', 'name', 'status', 'created_at', 'updated_at'])->where('status', 1)->orderBy('name')->get();
            });
            return CountryResource::collection($cached);
        } catch (\Exception $e) {
            return CountryResource::collection(Country::where('status', 1)->orderBy('name')->get());
        }
    }
    public function show(Country $country): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_country_{$country->id}", 600, function () use ($country) { return $country; });
            return response()->json(['success' => true, 'message' => 'Country retrieved successfully', 'data' => new CountryResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Country retrieved successfully', 'data' => new CountryResource($country)]);
        }
    }
}

