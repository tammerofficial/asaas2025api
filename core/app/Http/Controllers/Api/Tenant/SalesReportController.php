<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\Tenant\SalesReportResource;
use App\Models\ProductOrder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\SalesReport\Http\Services\SalesReport;

class SalesReportController extends BaseApiController
{
    public function index(): JsonResponse
    {
        try {
            $cacheKey = 'tenant_sales_report_' . now()->format('Y-m-d');
            $report = $this->remember($cacheKey, 300, function () {
                $orders = ProductOrder::completed()->orderBy('id', 'desc')->get();
                $reports = SalesReport::reports($orders);
                
                return [
                    'total_sale' => $reports['total_sale'] ?? 0,
                    'total_profit' => $reports['total_profit'] ?? 0,
                    'total_revenue' => $reports['total_revenue'] ?? 0,
                    'total_cost' => $reports['total_cost'] ?? 0,
                    'total_orders' => $orders->count(),
                    'products' => $reports['products'] ?? [],
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Sales report retrieved successfully',
                'data' => $report,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate sales report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function today(): JsonResponse
    {
        try {
            $cacheKey = 'tenant_sales_report_today_' . now()->format('Y-m-d');
            $report = $this->remember($cacheKey, 300, function () {
                $orders = ProductOrder::completed()
                    ->whereDate('updated_at', today())
                    ->orderBy('updated_at', 'asc')
                    ->get()
                    ->groupBy(function ($query) {
                        return Carbon::parse($query->updated_at)->format('D h A');
                    });
                
                $reports = SalesReport::reportByMonthsOrYears($orders);
                
                return $this->prepareChartData($reports);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Today sales report retrieved successfully',
                'data' => $report,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate today sales report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function weekly(): JsonResponse
    {
        try {
            $cacheKey = 'tenant_sales_report_weekly_' . now()->format('Y-W');
            $report = $this->remember($cacheKey, 300, function () {
                $firstWorkday = get_static_option('first_workday') ?? 'sunday';
                $orders = $this->getWeeklyReport($firstWorkday);
                
                $reports = SalesReport::reportByMonthsOrYears($orders);
                
                return $this->prepareChartData($reports);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Weekly sales report retrieved successfully',
                'data' => $report,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate weekly sales report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function monthly(): JsonResponse
    {
        try {
            $cacheKey = 'tenant_sales_report_monthly_' . now()->format('Y-m');
            $report = $this->remember($cacheKey, 300, function () {
                $orders = ProductOrder::completed()
                    ->orderBy('updated_at', 'asc')
                    ->get()
                    ->groupBy(function ($query) {
                        return Carbon::parse($query->updated_at)->format('M Y');
                    });
                
                $reports = SalesReport::reportByMonthsOrYears($orders);
                
                return $this->prepareChartData($reports);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Monthly sales report retrieved successfully',
                'data' => $report,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate monthly sales report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function yearly(): JsonResponse
    {
        try {
            $cacheKey = 'tenant_sales_report_yearly_' . now()->format('Y');
            $report = $this->remember($cacheKey, 300, function () {
                $orders = ProductOrder::completed()
                    ->orderBy('id', 'desc')
                    ->get()
                    ->groupBy(function ($query) {
                        return Carbon::parse($query->updated_at)->format('Y');
                    });
                
                $reports = SalesReport::reportByMonthsOrYears($orders);
                
                return $this->prepareChartData($reports);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Yearly sales report retrieved successfully',
                'data' => $report,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate yearly sales report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function getWeeklyReport($dayOfWeek)
    {
        $dayOfWeek = ucfirst(strtolower($dayOfWeek));
        $now = Carbon::now();
        $startDate = $now->copy();
        
        while ($startDate->format('l') !== $dayOfWeek) {
            $startDate->subDay();
        }
        
        $endDate = $startDate->copy()->addDays(7);
        
        return ProductOrder::whereBetween('updated_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->orderBy('updated_at', 'asc')
            ->get()
            ->groupBy(function ($query) {
                return Carbon::parse($query->updated_at)->format('D');
            });
    }

    private function prepareChartData($data)
    {
        $categories = [];
        $salesData = [];
        $profitData = [];
        $revenueData = [];
        $costData = [];

        foreach ($data ?? [] as $period => $values) {
            $categories[] = $period;
            $salesData[] = $values['total_sale'] ?? 0;
            $profitData[] = $values['total_profit'] ?? 0;
            $revenueData[] = $values['total_revenue'] ?? 0;
            $costData[] = $values['total_cost'] ?? 0;
        }

        $maxValue = 0;
        if (!empty($profitData) && !empty($revenueData) && !empty($costData)) {
            $maxValue = max(array_merge($profitData, $revenueData, $costData));
        }

        return [
            'categories' => $categories,
            'salesData' => $salesData,
            'profitData' => $profitData,
            'revenueData' => $revenueData,
            'costData' => $costData,
            'max_value' => $maxValue,
        ];
    }
}

