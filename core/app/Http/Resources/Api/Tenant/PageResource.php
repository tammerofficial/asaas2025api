<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'page_content' => $this->page_content,
            'slug' => $this->when($this->relationLoaded('slug'), function () {
                return $this->slug?->slug;
            }, $this->slug ?? null),
            'visibility' => (int) ($this->visibility ?? 0),
            'is_visible' => (bool) ($this->visibility == 1),
            'page_builder' => (int) ($this->page_builder ?? 0),
            'has_page_builder' => (bool) ($this->page_builder == 1),
            'status' => (int) ($this->status ?? 0),
            'is_published' => (bool) ($this->status == 1),
            'breadcrumb' => (int) ($this->breadcrumb ?? 0),
            'has_breadcrumb' => (bool) ($this->breadcrumb == 1),
            'navbar_variant' => $this->navbar_variant,
            'footer_variant' => $this->footer_variant,
            'meta_info' => $this->whenLoaded('metainfo', function () {
                return $this->metainfo ? [
                    'title' => $this->metainfo->title ?? null,
                    'description' => $this->metainfo->description ?? null,
                    'image' => $this->metainfo->image ?? null,
                    'og_image' => $this->metainfo->og_image ?? null,
                ] : null;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

