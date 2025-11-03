<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Tenant\CustomerResource;
use App\Http\Resources\Api\Tenant\OrderResource;
use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(): AnonymousResourceCollection
    {
        // Select only required columns
        $customers = User::select([
            'id', 'name', 'email', 'username', 'mobile', 'company', 'address', 
            'postal_code', 'country', 'state', 'city', 'image', 'email_verified', 
            'has_subdomain', 'created_at', 'updated_at'
        ])
        ->with(['userCountry:id,name', 'userState:id,name', 'userCity:id,name', 'wallet:id,user_id,balance'])
        ->latest()
        ->paginate(20);

        return CustomerResource::collection($customers);
    }

    /**
     * Display the specified customer
     */
    public function show(User $customer): JsonResponse
    {
        $customer->load([
            'userCountry',
            'userState',
            'userCity',
            'wallet',
            'payment_log',
            'delivery_address',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully',
            'data' => new CustomerResource($customer),
        ]);
    }

    /**
     * Get customer orders
     */
    public function orders(User $customer): AnonymousResourceCollection
    {
        $orders = ProductOrder::where('user_id', $customer->id)
            ->with(['shipping', 'getCountry', 'getState', 'getCity'])
            ->latest()
            ->paginate(20);

        return OrderResource::collection($orders);
    }

    /**
     * Get customer statistics
     */
    public function stats(User $customer): JsonResponse
    {
        // Single optimized query instead of 4 separate queries
        $stats = ProductOrder::where('user_id', $customer->id)
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = ? AND payment_status = ? THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN payment_status = ? THEN total_amount ELSE 0 END) as total_spent
            ', ['complete', 'success', 'pending', 'processing', 'success'])
            ->first();

        $totalOrders = (int) $stats->total_orders;
        $totalSpent = (float) ($stats->total_spent ?? 0);

        return response()->json([
            'success' => true,
            'message' => 'Customer statistics retrieved successfully',
            'data' => [
                'customer' => new CustomerResource($customer),
                'statistics' => [
                    'total_orders' => $totalOrders,
                    'completed_orders' => (int) $stats->completed_orders,
                    'pending_orders' => (int) $stats->pending_orders,
                    'total_spent' => $totalSpent,
                    'average_order_value' => $totalOrders > 0 ? ($totalSpent / $totalOrders) : 0,
                ],
            ],
        ]);
    }
}

