<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Tenant\DashboardResource;
use App\Models\Admin;
use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Entities\Blog;
use Modules\Product\Entities\Product;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index(): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        // Use Redis caching to improve performance (5 minutes cache)
        $stats = cache()->store('redis')->remember("tenant_dashboard_stats_{$tenant->id}", 300, function () use ($tenant) {
            // Optimize queries with selectRaw
            $orderStats = ProductOrder::selectRaw('
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN payment_status = ? THEN total_amount ELSE 0 END) as total_sales,
                SUM(CASE WHEN payment_status = ? AND MONTH(created_at) = ? AND YEAR(created_at) = ? THEN total_amount ELSE 0 END) as monthly_sales
            ', ['pending', 'success', 'success', 'success', now()->month, now()->year])
            ->first();

            return [
                'total_products' => Product::count(),
                'total_orders' => (int) $orderStats->total_orders,
                'total_customers' => User::count(),
                'total_sales' => (float) ($orderStats->total_sales ?? 0),
                'pending_orders' => (int) $orderStats->pending_orders,
                'completed_orders' => (int) $orderStats->completed_orders,
                'total_blogs' => Blog::count(),
                'total_admins' => Admin::count(),
                'monthly_sales' => (float) ($orderStats->monthly_sales ?? 0),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Dashboard statistics retrieved successfully',
            'data' => $stats,
        ]);
    }

    /**
     * Get detailed statistics
     */
    public function stats(): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        // Use Redis caching with optimized queries
        $stats = cache()->store('redis')->remember("tenant_dashboard_stats_detailed_{$tenant->id}", 300, function () use ($tenant) {
            $productStats = Product::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as draft
            ', [\App\Enums\StatusEnums::PUBLISH, \App\Enums\StatusEnums::DRAFT])->first();

            $orderStats = ProductOrder::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled
            ', ['pending', 'in_progress', 'complete', 'cancel'])->first();

            $customerStats = User::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN email_verified = 1 THEN 1 ELSE 0 END) as verified,
                SUM(CASE WHEN email_verified = 0 THEN 1 ELSE 0 END) as unverified
            ')->first();

            $sales = ProductOrder::where('payment_status', 'success')
                ->selectRaw('
                    SUM(total_amount) as total,
                    SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN total_amount ELSE 0 END) as monthly,
                    SUM(CASE WHEN YEAR(created_at) = ? THEN total_amount ELSE 0 END) as yearly
                ', [now()->month, now()->year, now()->year])
                ->first();

            return [
                'products' => [
                    'total' => (int) $productStats->total,
                    'active' => (int) $productStats->active,
                    'draft' => (int) $productStats->draft,
                ],
                'orders' => [
                    'total' => (int) $orderStats->total,
                    'pending' => (int) $orderStats->pending,
                    'processing' => (int) $orderStats->processing,
                    'completed' => (int) $orderStats->completed,
                    'cancelled' => (int) $orderStats->cancelled,
                ],
                'customers' => [
                    'total' => (int) $customerStats->total,
                    'verified' => (int) $customerStats->verified,
                    'unverified' => (int) $customerStats->unverified,
                ],
                'sales' => [
                    'total' => (float) ($sales->total ?? 0),
                    'monthly' => (float) ($sales->monthly ?? 0),
                    'yearly' => (float) ($sales->yearly ?? 0),
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Detailed statistics retrieved successfully',
            'data' => $stats,
        ]);
    }

    /**
     * Get recent orders
     */
    public function recentOrders(): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        // Cache recent orders for 1 minute
        $recentOrders = cache()->store('redis')->remember("tenant_recent_orders_{$tenant->id}", 60, function () {
            return ProductOrder::with(['user:id,name,email'])
                ->select('id', 'name', 'email', 'total_amount', 'payment_gateway', 'payment_status', 'status', 'created_at')
                ->latest()
                ->limit(10)
                ->get();
        });

        return response()->json([
            'success' => true,
            'message' => 'Recent orders retrieved successfully',
            'data' => $recentOrders,
        ]);
    }

    /**
     * Get chart data for analytics
     */
    public function chartData(): JsonResponse
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context not found',
            ], 404);
        }

        // Cache chart data for 5 minutes
        $chartData = cache()->store('redis')->remember("tenant_chart_data_{$tenant->id}", 300, function () {
            // Monthly sales for last 12 months
            $monthlySales = ProductOrder::where('payment_status', 'success')
                ->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total_amount) as sales')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            // Orders by status
            $ordersByStatus = ProductOrder::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();

            // Products growth
            $productsGrowth = Product::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            // Daily sales for last 30 days
            $dailySales = ProductOrder::where('payment_status', 'success')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_amount) as sales')
                )
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            return [
                'monthly_sales' => $monthlySales,
                'daily_sales' => $dailySales,
                'orders_by_status' => $ordersByStatus,
                'products_growth' => $productsGrowth,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Chart data retrieved successfully',
            'data' => $chartData,
        ]);
    }
}
