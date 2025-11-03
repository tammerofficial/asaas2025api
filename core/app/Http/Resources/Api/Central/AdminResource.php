<?php

namespace App\Http\Resources\Api\Central;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'email_verified' => (bool) $this->email_verified,
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('name');
            }),
            'permissions' => $this->whenLoaded('roles', function () {
                return $this->getAllPermissions()->pluck('name');
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
