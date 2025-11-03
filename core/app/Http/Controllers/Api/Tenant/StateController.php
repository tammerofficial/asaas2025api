<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\Tenant\StateResource;
use Modules\CountryManage\Entities\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class StateController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_states_' . request()->get('country_id', 'all'), 600, function () {
                $query = State::select(['id', 'country_id', 'name', 'status', 'created_at', 'updated_at'])->where('status', 1);
                if (request()->has('country_id')) {
                    $query->where('country_id', request()->get('country_id'));
                }
                return $query->orderBy('name')->get();
            });
            return StateResource::collection($cached);
        } catch (\Exception $e) {
            $query = State::where('status', 1);
            if (request()->has('country_id')) {
                $query->where('country_id', request()->get('country_id'));
            }
            return StateResource::collection($query->orderBy('name')->get());
        }
    }
    public function show(State $state): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_state_{$state->id}", 600, function () use ($state) { return $state; });
            return response()->json(['success' => true, 'message' => 'State retrieved successfully', 'data' => new StateResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'State retrieved successfully', 'data' => new StateResource($state)]);
        }
    }
}

