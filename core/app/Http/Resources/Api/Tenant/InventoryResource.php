<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product', function () {
                return $this->product ? ['id' => $this->product->id, 'name' => $this->product->name] : null;
            }),
            'stock_count' => (int) ($this->stock_count ?? 0),
            'stock_alert_quantity' => (int) ($this->stock_alert_quantity ?? 0),
            'sold_count' => (int) ($this->sold_count ?? 0),
            'is_low_stock' => $this->stock_alert_quantity ? $this->stock_count <= $this->stock_alert_quantity : false,
            'admin_id' => $this->admin_id,
            'admin' => $this->whenLoaded('admin', function () {
                return $this->admin ? ['id' => $this->admin->id, 'name' => $this->admin->name] : null;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

