<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\JsonResponse;

class SiteAnalyticsController extends BaseApiController
{
    public function index(): JsonResponse
    {
        try {
            $cacheKey = 'tenant_site_analytics_' . now()->format('Y-m-d');
            $analytics = $this->remember($cacheKey, 300, function () {
                // Get basic analytics data
                $totalUsers = \App\Models\User::count();
                $totalOrders = \Modules\Product\Entities\ProductOrder::count();
                $totalProducts = \Modules\Product\Entities\Product::count();
                $totalRevenue = \Modules\Product\Entities\ProductOrder::where('payment_status', 'success')
                    ->sum('total_amount');
                
                // Get today's stats
                $todayUsers = \App\Models\User::whereDate('created_at', today())->count();
                $todayOrders = \Modules\Product\Entities\ProductOrder::whereDate('created_at', today())->count();
                $todayRevenue = \Modules\Product\Entities\ProductOrder::where('payment_status', 'success')
                    ->whereDate('created_at', today())
                    ->sum('total_amount');
                
                // Get this month's stats
                $monthUsers = \App\Models\User::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                $monthOrders = \Modules\Product\Entities\ProductOrder::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                $monthRevenue = \Modules\Product\Entities\ProductOrder::where('payment_status', 'success')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total_amount');
                
                return [
                    'overview' => [
                        'total_users' => $totalUsers,
                        'total_orders' => $totalOrders,
                        'total_products' => $totalProducts,
                        'total_revenue' => (float) $totalRevenue,
                    ],
                    'today' => [
                        'users' => $todayUsers,
                        'orders' => $todayOrders,
                        'revenue' => (float) $todayRevenue,
                    ],
                    'this_month' => [
                        'users' => $monthUsers,
                        'orders' => $monthOrders,
                        'revenue' => (float) $monthRevenue,
                    ],
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Site analytics retrieved successfully',
                'data' => $analytics,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve site analytics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function visitors(): JsonResponse
    {
        try {
            $cacheKey = 'tenant_site_analytics_visitors_' . now()->format('Y-m-d');
            $visitors = $this->remember($cacheKey, 300, function () {
                // Get visitor statistics
                $todayVisitors = \App\Models\User::whereDate('created_at', today())->count();
                $weekVisitors = \App\Models\User::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count();
                $monthVisitors = \App\Models\User::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                
                return [
                    'today' => $todayVisitors,
                    'this_week' => $weekVisitors,
                    'this_month' => $monthVisitors,
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Visitor statistics retrieved successfully',
                'data' => $visitors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve visitor statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function orders(): JsonResponse
    {
        try {
            $cacheKey = 'tenant_site_analytics_orders_' . now()->format('Y-m-d');
            $orders = $this->remember($cacheKey, 300, function () {
                $pendingOrders = \Modules\Product\Entities\ProductOrder::where('status', 'pending')->count();
                $processingOrders = \Modules\Product\Entities\ProductOrder::where('status', 'processing')->count();
                $completedOrders = \Modules\Product\Entities\ProductOrder::where('status', 'complete')->count();
                $cancelledOrders = \Modules\Product\Entities\ProductOrder::where('status', 'cancel')->count();
                
                return [
                    'pending' => $pendingOrders,
                    'processing' => $processingOrders,
                    'completed' => $completedOrders,
                    'cancelled' => $cancelledOrders,
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Order statistics retrieved successfully',
                'data' => $orders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

