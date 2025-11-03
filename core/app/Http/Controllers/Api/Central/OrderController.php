<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Central\OrderResource;
use App\Models\Order;
use App\Models\PaymentLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(): AnonymousResourceCollection
    {
        $orders = Order::with(['user', 'package', 'paymentlog'])
            ->latest()
            ->paginate(20);

        return OrderResource::collection($orders);
    }

    /**
     * Display the specified order
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['user', 'package', 'paymentlog']);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully',
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * Get payment logs for an order
     */
    public function paymentLogs(Order $order): JsonResponse
    {
        $paymentLog = PaymentLogs::where('order_id', $order->id)
            ->with(['user', 'package', 'tenant'])
            ->first();

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

