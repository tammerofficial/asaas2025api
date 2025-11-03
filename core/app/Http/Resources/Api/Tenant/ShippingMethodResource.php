<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'zone_id' => $this->zone_id,
            'zone' => $this->whenLoaded('zone', function () {
                return $this->zone ? [
                    'id' => $this->zone->id,
                    'name' => $this->zone->name,
                ] : null;
            }),
            'is_default' => (bool) ($this->is_default ?? false),
            'options' => $this->whenLoaded('options', function () {
                return $this->options ? [
                    'id' => $this->options->id,
                    'title' => $this->options->title,
                    'status' => (bool) ($this->options->status ?? true),
                    'tax_status' => (bool) ($this->options->tax_status ?? true),
                    'cost' => (float) ($this->options->cost ?? 0),
                    'minimum_order_amount' => (float) ($this->options->minimum_order_amount ?? 0),
                    'coupon' => $this->options->coupon,
                    'setting_preset' => $this->options->setting_preset,
                ] : null;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

