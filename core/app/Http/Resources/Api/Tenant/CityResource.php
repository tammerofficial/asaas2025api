<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country_id' => $this->country_id,
            'country' => $this->whenLoaded('country', function () {
                return $this->country ? [
                    'id' => $this->country->id,
                    'name' => $this->country->name,
                ] : null;
            }),
            'state_id' => $this->state_id,
            'state' => $this->whenLoaded('state', function () {
                return $this->state ? [
                    'id' => $this->state->id,
                    'name' => $this->state->name,
                ] : null;
            }),
            'status' => (int) ($this->status ?? 0),
            'is_active' => (bool) ($this->status == 1),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

