<?php

namespace App\Http\Resources\Api\Tenant;

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
            'order_number' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_gateway' => $this->payment_gateway,
            'transaction_id' => $this->transaction_id,
            'total_amount' => (float) $this->total_amount,
            'coupon' => $this->coupon,
            'coupon_discounted' => (float) ($this->coupon_discounted ?? 0),
            'checkout_type' => $this->checkout_type,
            'country' => $this->country ? [
                'id' => $this->getCountry->id ?? null,
                'name' => $this->getCountry->name ?? null,
            ] : null,
            'state' => $this->state ? [
                'id' => $this->getState->id ?? null,
                'name' => $this->getState->name ?? null,
            ] : null,
            'city' => $this->city ? [
                'id' => $this->getCity->id ?? null,
                'name' => $this->getCity->name ?? null,
            ] : null,
            'address' => $this->address,
            'zipcode' => $this->zipcode,
            'message' => $this->message,
            'shipping_address' => $this->shipping ? [
                'id' => $this->shipping->id,
                'name' => $this->shipping->name,
                'email' => $this->shipping->email,
                'phone' => $this->shipping->phone,
                'address' => $this->shipping->address,
            ] : null,
            'order_details' => $this->order_details ? json_decode($this->order_details, true) : null,
            'payment_meta' => $this->payment_meta ? json_decode($this->payment_meta, true) : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

