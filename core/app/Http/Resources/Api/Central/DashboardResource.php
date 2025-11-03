<?php

namespace App\Http\Resources\Api\Central;

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
            'total_tenants' => $this->resource['total_tenants'] ?? 0,
            'active_tenants' => $this->resource['active_tenants'] ?? 0,
            'total_users' => $this->resource['total_users'] ?? 0,
            'total_orders' => $this->resource['total_orders'] ?? 0,
            'total_revenue' => $this->resource['total_revenue'] ?? 0,
            'pending_orders' => $this->resource['pending_orders'] ?? 0,
            'completed_orders' => $this->resource['completed_orders'] ?? 0,
            'monthly_revenue' => $this->resource['monthly_revenue'] ?? 0,
        ];
    }
}
