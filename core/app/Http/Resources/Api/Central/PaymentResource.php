<?php

namespace App\Http\Resources\Api\Central;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'email' => $this->email,
            'name' => $this->name,
            'package_name' => $this->package_name,
            'package_price' => (float) $this->package_price,
            'package_gateway' => $this->package_gateway,
            'package_id' => $this->package_id,
            'theme_slug' => $this->theme_slug,
            'user_id' => $this->user_id,
            'tenant_id' => $this->tenant_id,
            'attachments' => $this->attachments,
            'custom_fields' => $this->custom_fields ? json_decode($this->custom_fields, true) : [],
            'status' => $this->status,
            'track' => $this->track,
            'transaction_id' => $this->transaction_id,
            'payment_status' => $this->payment_status,
            'start_date' => $this->start_date?->toISOString(),
            'expire_date' => $this->expire_date?->toISOString(),
            'renew_status' => $this->renew_status,
            'is_renew' => (bool) $this->is_renew,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'tenant' => $this->whenLoaded('tenant', function () {
                return [
                    'id' => $this->tenant->id,
                    'name' => $this->tenant->name,
                    'domain' => $this->tenant->domains->first()->domain ?? null,
                ];
            }),
            'package' => $this->whenLoaded('package', function () {
                return [
                    'id' => $this->package->id,
                    'title' => $this->package->title,
                    'price' => (float) $this->package->price,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
