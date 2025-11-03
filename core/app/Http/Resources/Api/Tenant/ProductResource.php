<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'slug' => $this->slug?->slug ?? $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'brand_id' => $this->brand_id,
            'brand' => $this->whenLoaded('brand', function () {
                return $this->brand ? [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name,
                ] : null;
            }),
            'status' => $this->whenLoaded('status', function () {
                return $this->status ? [
                    'id' => $this->status->id,
                    'name' => $this->status->name,
                ] : null;
            }),
            'status_id' => $this->status_id,
            'cost' => (float) ($this->cost ?? 0),
            'price' => (float) ($this->price ?? 0),
            'sale_price' => (float) ($this->sale_price ?? 0),
            'image' => $this->whenLoaded('image', function () {
                return $this->image ? [
                    'id' => $this->image->id,
                    'path' => $this->image->path ? url($this->image->path) : null,
                    'alt' => $this->image->alt ?? null,
                ] : null;
            }),
            'image_id' => $this->image_id,
            'badge' => $this->whenLoaded('badge', function () {
                return $this->badge ? [
                    'id' => $this->badge->id,
                    'name' => $this->badge->name,
                ] : null;
            }),
            'badge_id' => $this->badge_id,
            'min_purchase' => (int) ($this->min_purchase ?? 1),
            'max_purchase' => (int) ($this->max_purchase ?? 0),
            'is_refundable' => (bool) ($this->is_refundable ?? false),
            'is_inventory_warn_able' => (bool) ($this->is_inventory_warn_able ?? false),
            'is_in_house' => (bool) ($this->is_in_house ?? false),
            'is_taxable' => (bool) ($this->is_taxable ?? false),
            'tax_class_id' => $this->tax_class_id,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ] : null;
            }),
            'inventory_count' => $this->inventory_detail_count ?? 0,
            'inventory' => $this->whenLoaded('inventory', function () {
                return $this->inventory ? [
                    'stock_count' => $this->inventory->stock_count ?? 0,
                ] : null;
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

