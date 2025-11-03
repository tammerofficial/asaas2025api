<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Coupon\StoreCouponRequest;
use App\Http\Requests\Api\Tenant\Coupon\UpdateCouponRequest;
use App\Http\Resources\Api\Tenant\CouponResource;
use Modules\CouponManage\Entities\ProductCoupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CouponController extends BaseApiController
{
    /**
     * Display a listing of coupons
     */
    public function index(): AnonymousResourceCollection
    {
        try {
            // Cache coupons for 5 minutes
            $cacheKey = 'tenant_coupons_' . request()->get('page', 1);
            $paginated = $this->remember($cacheKey, 300, function () {
                return ProductCoupon::select([
                    'id', 'title', 'code', 'discount', 'discount_type', 'discount_on',
                    'discount_on_details', 'expire_date', 'status', 'minimum_quantity',
                    'minimum_spend', 'maximum_spend', 'usage_limit_per_coupon',
                    'usage_limit_per_user', 'created_at', 'updated_at'
                ])
                ->latest()
                ->paginate(20);
            });

            return CouponResource::collection($paginated);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $coupons = ProductCoupon::latest()->paginate(20);

            return CouponResource::collection($coupons);
        }
    }

    /**
     * Store a newly created coupon
     */
    public function store(StoreCouponRequest $request): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $coupon = ProductCoupon::create($request->validated());

        // Clear cache
        $this->clearCache('tenant_coupons*');

        return response()->json([
            'success' => true,
            'message' => 'Coupon created successfully',
            'data' => new CouponResource($coupon),
        ], 201);
    }

    /**
     * Display the specified coupon
     */
    public function show(ProductCoupon $coupon): JsonResponse
    {
        try {
            // Cache individual coupon for 10 minutes
            $cachedCoupon = $this->remember("tenant_coupon_{$coupon->id}", 600, function () use ($coupon) {
                $coupon->load(['product_orders']);
                return $coupon;
            });

            return response()->json([
                'success' => true,
                'message' => 'Coupon retrieved successfully',
                'data' => new CouponResource($cachedCoupon),
            ]);
        } catch (\Exception $e) {
            // Fallback if cache fails
            $coupon->load(['product_orders']);

            return response()->json([
                'success' => true,
                'message' => 'Coupon retrieved successfully',
                'data' => new CouponResource($coupon),
            ]);
        }
    }

    /**
     * Update the specified coupon
     */
    public function update(UpdateCouponRequest $request, ProductCoupon $coupon): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $coupon->update($request->validated());

        // Clear related cache
        $this->clearCache('tenant_coupons*');
        $this->clearCache("tenant_coupon_{$coupon->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Coupon updated successfully',
            'data' => new CouponResource($coupon),
        ]);
    }

    /**
     * Remove the specified coupon
     */
    public function destroy(ProductCoupon $coupon): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $couponId = $coupon->id;
        $coupon->delete();

        // Clear related cache
        $this->clearCache('tenant_coupons*');
        $this->clearCache("tenant_coupon_{$couponId}*");

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully',
        ]);
    }

    /**
     * Activate the specified coupon
     */
    public function activate(ProductCoupon $coupon): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $coupon->update(['status' => 'publish']);

        // Clear cache
        $this->clearCache('tenant_coupons*');
        $this->clearCache("tenant_coupon_{$coupon->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Coupon activated successfully',
            'data' => new CouponResource($coupon),
        ]);
    }

    /**
     * Deactivate the specified coupon
     */
    public function deactivate(ProductCoupon $coupon): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $coupon->update(['status' => 'draft']);

        // Clear cache
        $this->clearCache('tenant_coupons*');
        $this->clearCache("tenant_coupon_{$coupon->id}*");

        return response()->json([
            'success' => true,
            'message' => 'Coupon deactivated successfully',
            'data' => new CouponResource($coupon),
        ]);
    }

    /**
     * Validate coupon code
     */
    public function validate(string $code): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        $coupon = ProductCoupon::where('code', $code)
            ->where('status', 'publish')
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found or inactive',
            ], 404);
        }

        // Check if coupon is expired
        if ($coupon->expire_date && now()->greaterThan($coupon->expire_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon has expired',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Coupon is valid',
            'data' => new CouponResource($coupon),
        ]);
    }
}

