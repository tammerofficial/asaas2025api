<?php
namespace App\Http\Resources\Api\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ProductReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product', fn() => $this->product ? ['id' => $this->product->id, 'name' => $this->product->name] : null),
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', fn() => $this->user ? ['id' => $this->user->id, 'name' => $this->user->name, 'email' => $this->user->email] : null),
            'rating' => (int) ($this->rating ?? 0),
            'review_text' => $this->review_text,
            'status' => (int) ($this->status ?? 0),
            'is_approved' => (bool) ($this->status == 1),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

