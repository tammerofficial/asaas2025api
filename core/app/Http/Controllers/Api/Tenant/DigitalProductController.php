<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\DigitalProduct\StoreDigitalProductRequest;
use App\Http\Requests\Api\Tenant\DigitalProduct\UpdateDigitalProductRequest;
use App\Http\Resources\Api\Tenant\DigitalProductResource;
use Modules\DigitalProduct\Entities\DigitalProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class DigitalProductController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_digital_products_' . request()->get('page', 1), 300, function () {
                return DigitalProduct::select(['id', 'name', 'price', 'status', 'created_at', 'updated_at'])->latest()->paginate(20);
            });
            return DigitalProductResource::collection($cached);
        } catch (\Exception $e) {
            return DigitalProductResource::collection(DigitalProduct::latest()->paginate(20));
        }
    }
    public function store(StoreDigitalProductRequest $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $product = DigitalProduct::create($request->validated());
        $this->clearCache('tenant_digital_products*');
        return response()->json(['success' => true, 'message' => 'Digital product created successfully', 'data' => new DigitalProductResource($product)], 201);
    }
    public function show(DigitalProduct $digitalProduct): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_digital_product_{$digitalProduct->id}", 600, function () use ($digitalProduct) { return $digitalProduct; });
            return response()->json(['success' => true, 'message' => 'Digital product retrieved successfully', 'data' => new DigitalProductResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Digital product retrieved successfully', 'data' => new DigitalProductResource($digitalProduct)]);
        }
    }
    public function update(UpdateDigitalProductRequest $request, DigitalProduct $digitalProduct): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $digitalProduct->update($request->validated());
        $this->clearCache('tenant_digital_products*');
        $this->clearCache("tenant_digital_product_{$digitalProduct->id}*");
        return response()->json(['success' => true, 'message' => 'Digital product updated successfully', 'data' => new DigitalProductResource($digitalProduct)]);
    }
    public function destroy(DigitalProduct $digitalProduct): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $id = $digitalProduct->id;
        $digitalProduct->delete();
        $this->clearCache('tenant_digital_products*');
        $this->clearCache("tenant_digital_product_{$id}*");
        return response()->json(['success' => true, 'message' => 'Digital product deleted successfully']);
    }
    public function activate(DigitalProduct $digitalProduct): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $digitalProduct->update(['status' => 1]);
        $this->clearCache('tenant_digital_products*');
        $this->clearCache("tenant_digital_product_{$digitalProduct->id}*");
        return response()->json(['success' => true, 'message' => 'Digital product activated successfully', 'data' => new DigitalProductResource($digitalProduct)]);
    }
    public function deactivate(DigitalProduct $digitalProduct): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $digitalProduct->update(['status' => 0]);
        $this->clearCache('tenant_digital_products*');
        $this->clearCache("tenant_digital_product_{$digitalProduct->id}*");
        return response()->json(['success' => true, 'message' => 'Digital product deactivated successfully', 'data' => new DigitalProductResource($digitalProduct)]);
    }
}

