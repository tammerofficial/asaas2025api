<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'email' => $this->email,
            'username' => $this->username,
            'mobile' => $this->mobile,
            'company' => $this->company,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'country' => $this->country ? [
                'id' => $this->userCountry->id ?? null,
                'name' => $this->userCountry->name ?? null,
            ] : null,
            'state' => $this->state ? [
                'id' => $this->userState->id ?? null,
                'name' => $this->userState->name ?? null,
            ] : null,
            'city' => $this->city ? [
                'id' => $this->userCity->id ?? null,
                'name' => $this->userCity->name ?? null,
            ] : null,
            'image' => $this->image ? url($this->image) : null,
            'email_verified' => (bool) $this->email_verified,
            'has_subdomain' => (bool) $this->has_subdomain,
            'wallet_balance' => $this->wallet ? (float) ($this->wallet->balance ?? 0) : 0,
            'delivery_address' => $this->delivery_address ? [
                'id' => $this->delivery_address->id,
                'name' => $this->delivery_address->name,
                'phone' => $this->delivery_address->phone,
                'address' => $this->delivery_address->address,
            ] : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

