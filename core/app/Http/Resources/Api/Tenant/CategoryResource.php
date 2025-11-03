<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->when($this->relationLoaded('slug'), function () {
                return $this->slug?->slug ?? null;
            }, function () {
                // If slug relationship is not loaded, try direct access
                return $this->slug ?? null;
            }),
            'description' => $this->description,
            'image' => $this->image ? [
                'id' => $this->image->id,
                'path' => $this->image->path ? url($this->image->path) : null,
                'alt' => $this->image->alt ?? null,
            ] : null,
            'status' => $this->status ? [
                'id' => $this->status->id,
                'name' => $this->status->name,
            ] : null,
            'status_id' => $this->status_id,
            'sub_categories_count' => $this->subCategory ? $this->subCategory->count() : 0,
            'sub_categories' => $this->whenLoaded('subCategory', function () {
                return $this->subCategory->map(function ($subCategory) {
                    return [
                        'id' => $subCategory->id,
                        'name' => $subCategory->name,
                        'slug' => $subCategory->slug?->slug ?? $subCategory->slug,
                    ];
                });
            }),
            'products_count' => $this->whenCounted('product'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

