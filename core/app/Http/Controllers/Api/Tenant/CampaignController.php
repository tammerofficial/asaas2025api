<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Campaign\StoreCampaignRequest;
use App\Http\Requests\Api\Tenant\Campaign\UpdateCampaignRequest;
use App\Http\Resources\Api\Tenant\CampaignResource;
use Modules\Campaign\Entities\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class CampaignController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_campaigns_' . request()->get('page', 1), 300, function () {
                return Campaign::select(['id', 'title', 'status', 'start_date', 'end_date', 'created_at', 'updated_at'])->latest()->paginate(20);
            });
            return CampaignResource::collection($cached);
        } catch (\Exception $e) {
            return CampaignResource::collection(Campaign::latest()->paginate(20));
        }
    }
    public function store(StoreCampaignRequest $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $campaign = Campaign::create($request->validated());
        $this->clearCache('tenant_campaigns*');
        return response()->json(['success' => true, 'message' => 'Campaign created successfully', 'data' => new CampaignResource($campaign)], 201);
    }
    public function show(Campaign $campaign): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_campaign_{$campaign->id}", 600, function () use ($campaign) { return $campaign; });
            return response()->json(['success' => true, 'message' => 'Campaign retrieved successfully', 'data' => new CampaignResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Campaign retrieved successfully', 'data' => new CampaignResource($campaign)]);
        }
    }
    public function update(UpdateCampaignRequest $request, Campaign $campaign): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $campaign->update($request->validated());
        $this->clearCache('tenant_campaigns*');
        $this->clearCache("tenant_campaign_{$campaign->id}*");
        return response()->json(['success' => true, 'message' => 'Campaign updated successfully', 'data' => new CampaignResource($campaign)]);
    }
    public function destroy(Campaign $campaign): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $id = $campaign->id;
        $campaign->delete();
        $this->clearCache('tenant_campaigns*');
        $this->clearCache("tenant_campaign_{$id}*");
        return response()->json(['success' => true, 'message' => 'Campaign deleted successfully']);
    }
    public function activate(Campaign $campaign): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $campaign->update(['status' => 1]);
        $this->clearCache('tenant_campaigns*');
        $this->clearCache("tenant_campaign_{$campaign->id}*");
        return response()->json(['success' => true, 'message' => 'Campaign activated successfully', 'data' => new CampaignResource($campaign)]);
    }
    public function deactivate(Campaign $campaign): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $campaign->update(['status' => 0]);
        $this->clearCache('tenant_campaigns*');
        $this->clearCache("tenant_campaign_{$campaign->id}*");
        return response()->json(['success' => true, 'message' => 'Campaign deactivated successfully', 'data' => new CampaignResource($campaign)]);
    }
}

