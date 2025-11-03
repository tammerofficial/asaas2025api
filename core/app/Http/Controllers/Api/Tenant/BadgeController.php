<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Badge\StoreBadgeRequest;
use App\Http\Requests\Api\Tenant\Badge\UpdateBadgeRequest;
use App\Http\Resources\Api\Tenant\BadgeResource;
use Modules\Badge\Entities\Badge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class BadgeController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_badges_' . request()->get('page', 1), 300, function () {
                return Badge::select(['id', 'name', 'status', 'created_at', 'updated_at'])->latest()->paginate(20);
            });
            return BadgeResource::collection($cached);
        } catch (\Exception $e) {
            return BadgeResource::collection(Badge::latest()->paginate(20));
        }
    }
    public function store(StoreBadgeRequest $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $badge = Badge::create($request->validated());
        $this->clearCache('tenant_badges*');
        return response()->json(['success' => true, 'message' => 'Badge created successfully', 'data' => new BadgeResource($badge)], 201);
    }
    public function show(Badge $badge): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_badge_{$badge->id}", 600, function () use ($badge) { return $badge; });
            return response()->json(['success' => true, 'message' => 'Badge retrieved successfully', 'data' => new BadgeResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Badge retrieved successfully', 'data' => new BadgeResource($badge)]);
        }
    }
    public function update(UpdateBadgeRequest $request, Badge $badge): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $badge->update($request->validated());
        $this->clearCache('tenant_badges*');
        $this->clearCache("tenant_badge_{$badge->id}*");
        return response()->json(['success' => true, 'message' => 'Badge updated successfully', 'data' => new BadgeResource($badge)]);
    }
    public function destroy(Badge $badge): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $id = $badge->id;
        $badge->delete();
        $this->clearCache('tenant_badges*');
        $this->clearCache("tenant_badge_{$id}*");
        return response()->json(['success' => true, 'message' => 'Badge deleted successfully']);
    }
}

