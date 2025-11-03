<?php

namespace App\Http\Resources\Api\Central;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'oder_id' => $this->oder_id,
            'status' => $this->status,
            'checkout_type' => $this->checkout_type,
            'payment_status' => $this->payment_status,
            'custom_fields' => $this->custom_fields ? json_decode($this->custom_fields, true) : [],
            'attachment' => $this->attachment,
            'package_name' => $this->package_name,
            'package_price' => (float) $this->package_price,
            'package_id' => $this->package_id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'package' => $this->whenLoaded('package', function () {
                return [
                    'id' => $this->package->id,
                    'title' => $this->package->title,
                    'price' => (float) $this->package->price,
                ];
            }),
            'payment_log' => $this->whenLoaded('paymentlog', function () {
                return [
                    'id' => $this->paymentlog->id,
                    'transaction_id' => $this->paymentlog->transaction_id,
                    'payment_status' => $this->paymentlog->payment_status,
                    'package_gateway' => $this->paymentlog->package_gateway,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
