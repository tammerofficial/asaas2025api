<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'title' => $this->category->title,
                    'status' => $this->category->status,
                ] : null;
            }),
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image ? url($this->image) : null,
            'meta_tag' => $this->meta_tag,
            'meta_description' => $this->meta_description,
            'status' => (int) ($this->status ?? 0),
            'is_active' => (bool) ($this->status == 1),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

