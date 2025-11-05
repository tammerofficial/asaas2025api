<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\Central\OrderResource;
use App\Models\Order;
use App\Models\PaymentLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends BaseApiController
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $page = $request->get('page', 1);
        $key = $this->getCacheKey("central_orders_list_page_{$page}");
        
        $orders = $this->remember($key, $this->getOrdersTtl(), function () {
            return Order::with(['user', 'package', 'paymentlog'])
                ->latest()
                ->paginate(20);
        }, ['tag:orders', 'tag:central']);

        return OrderResource::collection($orders);
    }

    /**
     * Display the specified order
     */
    public function show(Order $order): JsonResponse
    {
        $key = $this->getCacheKey("central_order_details_{$order->id}");
        
        $orderData = $this->remember($key, $this->getOrderDetailsTtl(), function () use ($order) {
            return $order->load(['user', 'package', 'paymentlog']);
        }, ['tag:orders', 'tag:central', "tag:order:{$order->id}"]);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully',
            'data' => new OrderResource($orderData),
        ]);
    }

    /**
     * Get payment logs for an order
     */
    public function paymentLogs(Order $order): JsonResponse
    {
        $key = $this->getCacheKey("central_order_payment_log_{$order->id}");
        
        $paymentLog = $this->remember($key, $this->getOrderDetailsTtl(), function () use ($order) {
            return PaymentLogs::where('order_id', $order->id)
                ->with(['user', 'package', 'tenant'])
                ->first();
        }, ['tag:orders', 'tag:central', "tag:order:{$order->id}"]);

        if (!$paymentLog) {
            return response()->json([
                'success' => false,
                'message' => 'Payment log not found for this order',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment log retrieved successfully',
            'data' => $paymentLog,
        ]);
    }
}

