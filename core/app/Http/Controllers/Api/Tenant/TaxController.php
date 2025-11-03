<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Tax\StoreTaxRequest;
use App\Http\Requests\Api\Tenant\Tax\UpdateTaxRequest;
use App\Http\Resources\Api\Tenant\TaxResource;
use Modules\TaxModule\Entities\TaxClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class TaxController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_taxes_' . request()->get('page', 1), 300, function () {
                return TaxClass::select(['id', 'name', 'rate', 'status', 'created_at', 'updated_at'])->latest()->paginate(20);
            });
            return TaxResource::collection($cached);
        } catch (\Exception $e) {
            return TaxResource::collection(TaxClass::latest()->paginate(20));
        }
    }
    public function store(StoreTaxRequest $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $tax = TaxClass::create($request->validated());
        $this->clearCache('tenant_taxes*');
        return response()->json(['success' => true, 'message' => 'Tax created successfully', 'data' => new TaxResource($tax)], 201);
    }
    public function show(TaxClass $tax): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_tax_{$tax->id}", 600, function () use ($tax) { return $tax; });
            return response()->json(['success' => true, 'message' => 'Tax retrieved successfully', 'data' => new TaxResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Tax retrieved successfully', 'data' => new TaxResource($tax)]);
        }
    }
    public function update(UpdateTaxRequest $request, TaxClass $tax): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $tax->update($request->validated());
        $this->clearCache('tenant_taxes*');
        $this->clearCache("tenant_tax_{$tax->id}*");
        return response()->json(['success' => true, 'message' => 'Tax updated successfully', 'data' => new TaxResource($tax)]);
    }
    public function destroy(TaxClass $tax): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $id = $tax->id;
        $tax->delete();
        $this->clearCache('tenant_taxes*');
        $this->clearCache("tenant_tax_{$id}*");
        return response()->json(['success' => true, 'message' => 'Tax deleted successfully']);
    }
}

