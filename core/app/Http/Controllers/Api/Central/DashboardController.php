<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\Central\DashboardResource;
use App\Models\Tenant;
use App\Models\PaymentLogs;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseApiController
{
    /**
     * Get dashboard statistics
     */
    public function index(): JsonResponse
    {
        try {
            $key = $this->getCacheKey('central_dashboard_stats');
            $stats = $this->remember($key, $this->getStatsTtl(), function () {
                // Get all counts in parallel queries
                $tenantStats = Tenant::selectRaw('
                    COUNT(*) as total_tenants,
                    SUM(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as active_tenants
                ')->first();
                
                $orderStats = PaymentLogs::selectRaw('
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
                    'total_tenants' => (int) ($tenantStats->total_tenants ?? 0),
                    'active_tenants' => (int) ($tenantStats->active_tenants ?? 0),
                    'total_users' => User::count(),
                    'total_orders' => (int) ($orderStats->total_orders ?? 0),
                    'total_revenue' => (float) ($revenue->total_revenue ?? 0),
                    'pending_orders' => (int) ($orderStats->pending_orders ?? 0),
                    'completed_orders' => (int) ($orderStats->completed_orders ?? 0),
                    'monthly_revenue' => (float) ($revenue->monthly_revenue ?? 0),
                ];
            }, ['tag:dashboard', 'tag:central']);

            return response()->json([
                'success' => true,
                'message' => 'Dashboard statistics retrieved successfully',
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get detailed statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $key = $this->getCacheKey('central_dashboard_stats_detailed');
            $stats = $this->remember($key, $this->getStatsTtl(), function () {
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

                $orderStats = PaymentLogs::selectRaw('
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
                        'total' => (int) ($tenantStats->total ?? 0),
                        'active' => (int) ($tenantStats->active ?? 0),
                        'inactive' => (int) ($tenantStats->inactive ?? 0),
                    ],
                    'users' => [
                        'total' => (int) ($userStats->total ?? 0),
                        'verified' => (int) ($userStats->verified ?? 0),
                        'unverified' => (int) ($userStats->unverified ?? 0),
                    ],
                    'orders' => [
                        'total' => (int) ($orderStats->total ?? 0),
                        'pending' => (int) ($orderStats->pending ?? 0),
                        'complete' => (int) ($orderStats->complete ?? 0),
                        'failed' => (int) ($orderStats->failed ?? 0),
                    ],
                    'revenue' => [
                        'total' => (float) ($revenue->total ?? 0),
                        'monthly' => (float) ($revenue->monthly ?? 0),
                        'yearly' => (float) ($revenue->yearly ?? 0),
                    ],
                ];
            }, ['tag:dashboard', 'tag:central']);

            return response()->json([
                'success' => true,
                'message' => 'Detailed statistics retrieved successfully',
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get recent payment logs
     */
    public function recentOrders(): JsonResponse
    {
        try {
            $key = $this->getCacheKey('central_recent_orders');
            $recentOrders = $this->remember($key, $this->getRecentOrdersTtl(), function () {
                return PaymentLogs::with(['user:id,name,email', 'package:id,title,price'])
                    ->select('id', 'name', 'email', 'package_name', 'package_price', 'payment_status', 'created_at', 'user_id', 'package_id')
                    ->latest()
                    ->limit(10)
                    ->get();
            }, ['tag:dashboard', 'tag:orders', 'tag:central']);

            return response()->json([
                'success' => true,
                'message' => 'Recent orders retrieved successfully',
                'data' => $recentOrders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get chart data for analytics
     */
    public function chartData(): JsonResponse
    {
        try {
            $key = $this->getCacheKey('central_chart_data');
            $chartData = $this->remember($key, $this->getChartDataTtl(), function () {
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

                // Orders by status (use PaymentLogs instead of Order)
                $ordersByStatus = PaymentLogs::select('payment_status', DB::raw('COUNT(*) as count'))
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
            }, ['tag:dashboard', 'tag:central']);

            return response()->json([
                'success' => true,
                'message' => 'Chart data retrieved successfully',
                'data' => $chartData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

