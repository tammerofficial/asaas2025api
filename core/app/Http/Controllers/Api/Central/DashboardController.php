<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Central\DashboardResource;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\PaymentLogs;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index(): JsonResponse
    {
        // Use Redis caching to improve performance (5 minutes cache)
        $stats = cache()->store('redis')->remember('central_dashboard_stats', 300, function () {
            // Get all counts in parallel queries
            $tenantStats = Tenant::selectRaw('
                COUNT(*) as total_tenants,
                SUM(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as active_tenants
            ')->first();
            
            $orderStats = Order::selectRaw('
                COUNT(*) as total_orders,
                SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END) as completed_orders
            ', ['pending', 'complete'])->first();
            
            $revenue = PaymentLogs::where('payment_status', 'complete')
                ->selectRaw('
                    SUM(package_price) as total_revenue,
                    SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN package_price ELSE 0 END) as monthly_revenue
                ', [now()->month, now()->year])
                ->first();

            return [
                'total_tenants' => (int) $tenantStats->total_tenants,
                'active_tenants' => (int) $tenantStats->active_tenants,
                'total_users' => User::count(),
                'total_orders' => (int) $orderStats->total_orders,
                'total_revenue' => (float) ($revenue->total_revenue ?? 0),
                'pending_orders' => (int) $orderStats->pending_orders,
                'completed_orders' => (int) $orderStats->completed_orders,
                'monthly_revenue' => (float) ($revenue->monthly_revenue ?? 0),
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
        // Use Redis caching with optimized queries
        $stats = cache()->store('redis')->remember('central_dashboard_stats_detailed', 300, function () {
            // Optimize with selectRaw
            $tenantStats = Tenant::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN user_id IS NULL THEN 1 ELSE 0 END) as inactive
            ')->first();

            $userStats = User::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN email_verified = 1 THEN 1 ELSE 0 END) as verified,
                SUM(CASE WHEN email_verified = 0 THEN 1 ELSE 0 END) as unverified
            ')->first();

            $orderStats = Order::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END) as complete,
                SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END) as failed
            ', ['pending', 'complete', 'failed'])->first();

            $revenue = PaymentLogs::where('payment_status', 'complete')
                ->selectRaw('
                    SUM(package_price) as total,
                    SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN package_price ELSE 0 END) as monthly,
                    SUM(CASE WHEN YEAR(created_at) = ? THEN package_price ELSE 0 END) as yearly
                ', [now()->month, now()->year, now()->year])
                ->first();

            return [
                'tenants' => [
                    'total' => (int) $tenantStats->total,
                    'active' => (int) $tenantStats->active,
                    'inactive' => (int) $tenantStats->inactive,
                ],
                'users' => [
                    'total' => (int) $userStats->total,
                    'verified' => (int) $userStats->verified,
                    'unverified' => (int) $userStats->unverified,
                ],
                'orders' => [
                    'total' => (int) $orderStats->total,
                    'pending' => (int) $orderStats->pending,
                    'complete' => (int) $orderStats->complete,
                    'failed' => (int) $orderStats->failed,
                ],
                'revenue' => [
                    'total' => (float) ($revenue->total ?? 0),
                    'monthly' => (float) ($revenue->monthly ?? 0),
                    'yearly' => (float) ($revenue->yearly ?? 0),
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
     * Get recent payment logs
     */
    public function recentOrders(): JsonResponse
    {
        // Cache recent orders for 1 minute
        $recentOrders = cache()->store('redis')->remember('central_recent_orders', 60, function () {
            return PaymentLogs::with(['user:id,name,email', 'package:id,title,price'])
                ->select('id', 'name', 'email', 'package_name', 'package_price', 'payment_status', 'created_at', 'user_id', 'package_id')
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
        // Cache chart data for 5 minutes
        $chartData = cache()->store('redis')->remember('central_chart_data', 300, function () {
            // Monthly revenue for last 12 months
            $monthlyRevenue = PaymentLogs::where('payment_status', 'complete')
                ->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(package_price) as revenue')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            // Orders by status
            $ordersByStatus = Order::select('payment_status', DB::raw('COUNT(*) as count'))
                ->groupBy('payment_status')
                ->get();

            // Tenants growth
            $tenantsGrowth = Tenant::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            return [
                'monthly_revenue' => $monthlyRevenue,
                'orders_by_status' => $ordersByStatus,
                'tenants_growth' => $tenantsGrowth,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Chart data retrieved successfully',
            'data' => $chartData,
        ]);
    }
}

