<?php
namespace App\Http\Controllers\Api\Central;
use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\JsonResponse;
class ReportController extends BaseApiController
{
    public function tenants(): JsonResponse
    {
        try {
            $cached = $this->remember('central_report_tenants_' . now()->format('Y-m'), 600, function () {
                $tenants = \App\Models\Tenant::selectRaw('COUNT(*) as total_tenants, SUM(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as active_tenants, SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as new_tenants', [now()->subMonth()])->first();
                return $tenants;
            });
            return response()->json(['success' => true, 'message' => 'Tenants report retrieved successfully', 'data' => $cached]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate tenants report'], 500);
        }
    }
    public function revenue(): JsonResponse
    {
        try {
            $cached = $this->remember('central_report_revenue_' . now()->format('Y-m'), 600, function () {
                $revenue = \App\Models\PaymentLogs::selectRaw('SUM(CASE WHEN payment_status = ? THEN package_price ELSE 0 END) as total_revenue, COUNT(*) as total_payments', ['success'])->first();
                return $revenue;
            });
            return response()->json(['success' => true, 'message' => 'Revenue report retrieved successfully', 'data' => $cached]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate revenue report'], 500);
        }
    }
    public function subscriptions(): JsonResponse
    {
        try {
            $cached = $this->remember('central_report_subscriptions_' . now()->format('Y-m'), 600, function () {
                $subscriptions = \App\Models\PaymentLogs::selectRaw('COUNT(*) as total_subscriptions, SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END) as active_subscriptions', ['success'])->first();
                return $subscriptions;
            });
            return response()->json(['success' => true, 'message' => 'Subscriptions report retrieved successfully', 'data' => $cached]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate subscriptions report'], 500);
        }
    }
    public function plans(): JsonResponse
    {
        try {
            $cached = $this->remember('central_report_plans_' . now()->format('Y-m'), 600, function () {
                $plans = \App\Models\PricePlan::selectRaw('COUNT(*) as total_plans, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as active_plans', ['publish'])->first();
                return $plans;
            });
            return response()->json(['success' => true, 'message' => 'Plans report retrieved successfully', 'data' => $cached]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate plans report'], 500);
        }
    }
}

