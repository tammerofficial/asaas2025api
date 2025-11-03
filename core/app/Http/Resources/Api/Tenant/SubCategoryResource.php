<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ] : null;
            }),
            'name' => $this->name,
            'slug' => $this->when($this->relationLoaded('slug'), function () {
                return $this->slug?->slug ?? null;
            }, $this->slug ?? null),
            'description' => $this->description,
            'image' => $this->whenLoaded('image', function () {
                return $this->image ? [
                    'id' => $this->image->id,
                    'path' => $this->image->path ? url($this->image->path) : null,
                ] : null;
            }),
            'image_id' => $this->image_id,
            'status' => $this->whenLoaded('status', function () {
                return $this->status ? [
                    'id' => $this->status->id,
                    'name' => $this->status->name,
                ] : null;
            }),
            'status_id' => $this->status_id,
            'child_categories_count' => $this->whenLoaded('childCategory', function () {
                return $this->childCategory ? $this->childCategory->count() : 0;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

