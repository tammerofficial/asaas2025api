<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Central\PaymentResource;
use App\Models\PaymentLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payment logs
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = PaymentLogs::with(['user', 'package', 'tenant']);

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by tenant
        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $payments = $query->latest()->paginate(20);

        return PaymentResource::collection($payments);
    }

    /**
     * Display the specified payment log
     */
    public function show(PaymentLogs $payment): JsonResponse
    {
        $payment->load(['user', 'package', 'tenant']);

        return response()->json([
            'success' => true,
            'message' => 'Payment log retrieved successfully',
            'data' => new PaymentResource($payment),
        ]);
    }

    /**
     * Update the specified payment log
     */
    public function update(Request $request, PaymentLogs $payment): JsonResponse
    {
        $validated = $request->validate([
            'payment_status' => 'sometimes|in:pending,complete,failed,cancelled',
            'status' => 'sometimes|string',
            'transaction_id' => 'sometimes|string|max:255',
        ]);

        $payment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Payment log updated successfully',
            'data' => new PaymentResource($payment->load(['user', 'package', 'tenant'])),
        ]);
    }
}

