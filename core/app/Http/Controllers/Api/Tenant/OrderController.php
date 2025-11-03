<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Order\UpdateOrderRequest;
use App\Http\Resources\Api\Tenant\OrderResource;
use App\Models\ProductOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends BaseApiController
{
    /**
     * Display a listing of orders
     */
    public function index(): AnonymousResourceCollection
    {
        // Select only required columns to reduce data transfer
        $orders = ProductOrder::select([
            'id', 'name', 'email', 'phone', 'user_id', 'status', 'payment_status', 
            'payment_gateway', 'transaction_id', 'total_amount', 'coupon', 'coupon_discounted',
            'checkout_type', 'country', 'state', 'city', 'address', 'zipcode', 'message',
            'shipping_address_id', 'order_details', 'payment_meta', 'created_at', 'updated_at'
        ])
        ->with(['shipping:id,name,email,phone,address', 'getCountry:id,name', 'getState:id,name', 'getCity:id,name', 'product_coupon:id,code'])
        ->latest()
        ->paginate(20);

        return OrderResource::collection($orders);
    }

    /**
     * Display the specified order
     */
    public function show(ProductOrder $order): JsonResponse
    {
        $order->load([
            'shipping',
            'getCountry',
            'getState',
            'getCity',
            'product_coupon',
            'sale_details'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully',
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(UpdateOrderRequest $request, ProductOrder $order): JsonResponse
    {
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status ?? $order->payment_status,
        ]);

        // Clear related cache
        $tenant = tenant();
        if ($tenant) {
            $this->clearCache("tenant_dashboard_stats_{$tenant->id}*");
            $this->clearCache("tenant_recent_orders_{$tenant->id}*");
            $this->clearCache("tenant_chart_data_{$tenant->id}*");
        }

        // Reload relationships without using fresh() which reloads entire model
        $order->load(['shipping', 'getCountry', 'getState', 'getCity', 'product_coupon']);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel(ProductOrder $order): JsonResponse
    {
        if ($order->status === 'cancel') {
            return response()->json([
                'success' => false,
                'message' => 'Order is already cancelled',
            ], 400);
        }

        $order->update([
            'status' => 'cancel',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => new OrderResource($order->fresh()),
        ]);
    }
}

