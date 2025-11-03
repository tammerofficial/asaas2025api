<?php
namespace App\Http\Resources\Api\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => (int) ($this->status ?? 0),
            'is_active' => (bool) ($this->status == 1),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

