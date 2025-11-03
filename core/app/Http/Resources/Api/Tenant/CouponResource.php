<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'title' => $this->title,
            'code' => $this->code,
            'discount' => (float) ($this->discount ?? 0),
            'discount_type' => $this->discount_type,
            'discount_on' => $this->discount_on,
            'discount_on_details' => $this->discount_on_details,
            'expire_date' => $this->expire_date?->format('Y-m-d'),
            'is_expired' => $this->expire_date ? now()->greaterThan($this->expire_date) : false,
            'status' => $this->status ?? 'draft',
            'is_active' => ($this->status ?? 'draft') === 'publish',
            'minimum_quantity' => (int) ($this->minimum_quantity ?? 0),
            'minimum_spend' => (float) ($this->minimum_spend ?? 0),
            'maximum_spend' => (float) ($this->maximum_spend ?? 0),
            'usage_limit_per_coupon' => (int) ($this->usage_limit_per_coupon ?? 0),
            'usage_limit_per_user' => (int) ($this->usage_limit_per_user ?? 0),
            'usage_count' => $this->whenLoaded('product_orders', function () {
                return $this->product_orders ? $this->product_orders->count() : 0;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

