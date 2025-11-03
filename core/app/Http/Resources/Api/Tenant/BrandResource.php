<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->when($this->relationLoaded('slug'), function () {
                return $this->slug?->slug ?? null;
            }, $this->slug ?? null),
            'description' => $this->description,
            'title' => $this->title,
            'logo' => $this->whenLoaded('logo', function () {
                return $this->logo ? [
                    'id' => $this->logo->id,
                    'path' => $this->logo->path ? url($this->logo->path) : null,
                ] : null;
            }),
            'banner' => $this->whenLoaded('banner', function () {
                return $this->banner ? [
                    'id' => $this->banner->id,
                    'path' => $this->banner->path ? url($this->banner->path) : null,
                ] : null;
            }),
            'image_id' => $this->image_id,
            'banner_id' => $this->banner_id,
            'url' => $this->url,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

