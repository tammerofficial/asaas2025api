<?php

namespace App\Http\Resources\Api\Central;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->option_name,
            'value' => $this->option_value,
            'unique_key' => $this->unique_key ?? null,
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

