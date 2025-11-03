<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'statistics' => [
                'total_products' => $this->resource['total_products'] ?? 0,
                'total_orders' => $this->resource['total_orders'] ?? 0,
                'total_sales' => (float) ($this->resource['total_sales'] ?? 0),
                'total_customers' => $this->resource['total_customers'] ?? 0,
                'pending_orders' => $this->resource['pending_orders'] ?? 0,
                'completed_orders' => $this->resource['completed_orders'] ?? 0,
            ],
            'recent_orders' => $this->when(isset($this->resource['recent_orders']), function () {
                return $this->resource['recent_orders'];
            }),
            'chart_data' => $this->when(isset($this->resource['chart_data']), function () {
                return $this->resource['chart_data'];
            }),
        ];
    }
}

