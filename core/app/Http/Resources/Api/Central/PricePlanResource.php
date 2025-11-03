<?php

namespace App\Http\Resources\Api\Central;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricePlanResource extends JsonResource
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
            'features' => $this->features,
            'type' => $this->type,
            'status' => $this->status,
            'price' => (float) $this->price,
            'free_trial' => (int) $this->free_trial,
            'faq' => $this->faq,
            'page_permission_feature' => (bool) $this->page_permission_feature,
            'blog_permission_feature' => (bool) $this->blog_permission_feature,
            'product_permission_feature' => (bool) $this->product_permission_feature,
            'storage_permission_feature' => (bool) $this->storage_permission_feature,
            'package_badge' => $this->package_badge,
            'package_description' => $this->package_description,
            'plan_features' => $this->whenLoaded('plan_features', function () {
                return $this->plan_features->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'name' => $feature->name,
                        'value' => $feature->value,
                    ];
                });
            }),
            'plan_themes' => $this->whenLoaded('plan_themes', function () {
                return $this->plan_themes->pluck('theme_slug');
            }),
            'plan_payment_gateways' => $this->whenLoaded('plan_payment_gateways', function () {
                return $this->plan_payment_gateways->pluck('gateway');
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
