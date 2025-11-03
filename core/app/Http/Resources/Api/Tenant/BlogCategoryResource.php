<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCategoryResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->when($this->relationLoaded('slug'), function () {
                return $this->slug?->slug;
            }, $this->slug ?? null),
            'status' => (int) ($this->status ?? 0),
            'is_active' => (bool) ($this->status == 1),
            'blogs_count' => $this->whenLoaded('blogs', function () {
                return $this->blogs ? $this->blogs->count() : 0;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

