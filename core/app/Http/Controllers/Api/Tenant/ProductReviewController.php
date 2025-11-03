<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\ProductReview\StoreProductReviewRequest;
use App\Http\Requests\Api\Tenant\ProductReview\UpdateProductReviewRequest;
use App\Http\Resources\Api\Tenant\ProductReviewResource;
use App\Models\ProductReviews;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class ProductReviewController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_product_reviews_' . request()->get('page', 1), 300, function () {
                return ProductReviews::select(['id', 'product_id', 'user_id', 'rating', 'review_text', 'status', 'created_at', 'updated_at'])->with(['product:id,name', 'user:id,name,email'])->latest()->paginate(20);
            });
            return ProductReviewResource::collection($cached);
        } catch (\Exception $e) {
            return ProductReviewResource::collection(ProductReviews::with(['product', 'user'])->latest()->paginate(20));
        }
    }
    public function store(StoreProductReviewRequest $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $review = ProductReviews::create($request->validated());
        $this->clearCache('tenant_product_reviews*');
        return response()->json(['success' => true, 'message' => 'Product review created successfully', 'data' => new ProductReviewResource($review->load(['product', 'user']))], 201);
    }
    public function show(ProductReviews $productReview): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_product_review_{$productReview->id}", 600, function () use ($productReview) {
                $productReview->load(['product:id,name', 'user:id,name,email']);
                return $productReview;
            });
            return response()->json(['success' => true, 'message' => 'Product review retrieved successfully', 'data' => new ProductReviewResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Product review retrieved successfully', 'data' => new ProductReviewResource($productReview->load(['product', 'user']))]);
        }
    }
    public function update(UpdateProductReviewRequest $request, ProductReviews $productReview): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $productReview->update($request->validated());
        $this->clearCache('tenant_product_reviews*');
        $this->clearCache("tenant_product_review_{$productReview->id}*");
        return response()->json(['success' => true, 'message' => 'Product review updated successfully', 'data' => new ProductReviewResource($productReview->load(['product', 'user']))]);
    }
    public function destroy(ProductReviews $productReview): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $id = $productReview->id;
        $productReview->delete();
        $this->clearCache('tenant_product_reviews*');
        $this->clearCache("tenant_product_review_{$id}*");
        return response()->json(['success' => true, 'message' => 'Product review deleted successfully']);
    }
    public function approve(ProductReviews $productReview): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $productReview->update(['status' => 1]);
        $this->clearCache('tenant_product_reviews*');
        $this->clearCache("tenant_product_review_{$productReview->id}*");
        return response()->json(['success' => true, 'message' => 'Product review approved successfully', 'data' => new ProductReviewResource($productReview->load(['product', 'user']))]);
    }
    public function reject(ProductReviews $productReview): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $productReview->update(['status' => 0]);
        $this->clearCache('tenant_product_reviews*');
        $this->clearCache("tenant_product_review_{$productReview->id}*");
        return response()->json(['success' => true, 'message' => 'Product review rejected successfully', 'data' => new ProductReviewResource($productReview->load(['product', 'user']))]);
    }
}

