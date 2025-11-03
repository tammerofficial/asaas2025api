<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Refund\StoreRefundRequest;
use App\Http\Requests\Api\Tenant\Refund\UpdateRefundRequest;
use App\Http\Resources\Api\Tenant\RefundResource;
use Modules\RefundModule\Entities\RefundProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class RefundController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_refunds_' . request()->get('page', 1), 300, function () {
                return RefundProduct::select(['id', 'user_id', 'order_id', 'product_id', 'status', 'created_at', 'updated_at'])->with(['product:id,name', 'order:id,name', 'user:id,name,email'])->latest()->paginate(20);
            });
            return RefundResource::collection($cached);
        } catch (\Exception $e) {
            return RefundResource::collection(RefundProduct::with(['product', 'order', 'user'])->latest()->paginate(20));
        }
    }
    public function store(StoreRefundRequest $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $refund = RefundProduct::create($request->validated());
        $this->clearCache('tenant_refunds*');
        return response()->json(['success' => true, 'message' => 'Refund created successfully', 'data' => new RefundResource($refund->load(['product', 'order', 'user']))], 201);
    }
    public function show(RefundProduct $refund): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_refund_{$refund->id}", 600, function () use ($refund) {
                $refund->load(['product:id,name', 'order:id,name', 'user:id,name,email']);
                return $refund;
            });
            return response()->json(['success' => true, 'message' => 'Refund retrieved successfully', 'data' => new RefundResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Refund retrieved successfully', 'data' => new RefundResource($refund->load(['product', 'order', 'user']))]);
        }
    }
    public function update(UpdateRefundRequest $request, RefundProduct $refund): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $refund->update($request->validated());
        $this->clearCache('tenant_refunds*');
        $this->clearCache("tenant_refund_{$refund->id}*");
        return response()->json(['success' => true, 'message' => 'Refund updated successfully', 'data' => new RefundResource($refund->load(['product', 'order', 'user']))]);
    }
    public function destroy(RefundProduct $refund): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $id = $refund->id;
        $refund->delete();
        $this->clearCache('tenant_refunds*');
        $this->clearCache("tenant_refund_{$id}*");
        return response()->json(['success' => true, 'message' => 'Refund deleted successfully']);
    }
    public function approve(RefundProduct $refund): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $refund->update(['status' => 'approved']);
        $this->clearCache('tenant_refunds*');
        $this->clearCache("tenant_refund_{$refund->id}*");
        return response()->json(['success' => true, 'message' => 'Refund approved successfully', 'data' => new RefundResource($refund->load(['product', 'order', 'user']))]);
    }
    public function reject(RefundProduct $refund): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $refund->update(['status' => 'rejected']);
        $this->clearCache('tenant_refunds*');
        $this->clearCache("tenant_refund_{$refund->id}*");
        return response()->json(['success' => true, 'message' => 'Refund rejected successfully', 'data' => new RefundResource($refund->load(['product', 'order', 'user']))]);
    }
}

