<?php

namespace App\Http\Resources\Api\Central;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
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
            'name' => $this->name ?? null,
            'data' => $this->data ?? [],
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'domains' => $this->whenLoaded('domains', function () {
                return $this->domains->map(function ($domain) {
                    return [
                        'id' => $domain->id,
                        'domain' => $domain->domain,
                    ];
                });
            }),
            'domain' => $this->whenLoaded('domains', function () {
                return $this->domains->first()->domain ?? null;
            }),
            'is_active' => !is_null($this->user_id),
            'payment_log' => $this->whenLoaded('payment_log', function () {
                return [
                    'id' => $this->payment_log->id,
                    'package_name' => $this->payment_log->package_name,
                    'package_price' => $this->payment_log->package_price,
                    'payment_status' => $this->payment_log->payment_status,
                    'expire_date' => $this->payment_log->expire_date?->toISOString(),
                ];
            }),
            'expire_date' => $this->expire_date?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
