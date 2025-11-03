<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingZoneResource extends JsonResource
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
            'region' => $this->whenLoaded('region', function () {
                return $this->region ? [
                    'id' => $this->region->id,
                    'zone_id' => $this->region->zone_id,
                    'country' => $this->region->country,
                    'state' => $this->region->state,
                ] : null;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

