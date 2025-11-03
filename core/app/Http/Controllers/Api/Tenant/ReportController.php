<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\JsonResponse;
class ReportController extends BaseApiController
{
    public function sales(): JsonResponse
    {
        try {
            $cached = $this->remember('tenant_report_sales_' . now()->format('Y-m'), 600, function () {
                $orders = \Modules\Product\Entities\ProductOrder::selectRaw('COUNT(*) as total_orders, SUM(CASE WHEN payment_status = ? THEN total_amount ELSE 0 END) as total_revenue, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_orders', ['success', 'complete'])->first();
                return $orders;
            });
            return response()->json(['success' => true, 'message' => 'Sales report retrieved successfully', 'data' => $cached]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate sales report'], 500);
        }
    }
    public function products(): JsonResponse
    {
        try {
            $cached = $this->remember('tenant_report_products_' . now()->format('Y-m'), 600, function () {
                $products = \Modules\Product\Entities\Product::selectRaw('COUNT(*) as total_products, SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as active_products', [\App\Enums\StatusEnums::PUBLISH])->first();
                return $products;
            });
            return response()->json(['success' => true, 'message' => 'Products report retrieved successfully', 'data' => $cached]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate products report'], 500);
        }
    }
    public function customers(): JsonResponse
    {
        try {
            $cached = $this->remember('tenant_report_customers_' . now()->format('Y-m'), 600, function () {
                $customers = \App\Models\User::selectRaw('COUNT(*) as total_customers, SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as new_customers', [now()->subMonth()])->first();
                return $customers;
            });
            return response()->json(['success' => true, 'message' => 'Customers report retrieved successfully', 'data' => $cached]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate customers report'], 500);
        }
    }
    public function orders(): JsonResponse
    {
        try {
            $cached = $this->remember('tenant_report_orders_' . now()->format('Y-m'), 600, function () {
                $orders = \Modules\Product\Entities\ProductOrder::selectRaw('COUNT(*) as total_orders, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending_orders, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_orders, AVG(total_amount) as average_order_value', ['pending', 'complete'])->first();
                return $orders;
            });
            return response()->json(['success' => true, 'message' => 'Orders report retrieved successfully', 'data' => $cached]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate orders report'], 500);
        }
    }
}

