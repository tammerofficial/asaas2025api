<?php
namespace App\Http\Resources\Api\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class RefundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', fn() => $this->user ? ['id' => $this->user->id, 'name' => $this->user->name, 'email' => $this->user->email] : null),
            'order_id' => $this->order_id,
            'order' => $this->whenLoaded('order', fn() => $this->order ? ['id' => $this->order->id, 'name' => $this->order->name] : null),
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product', fn() => $this->product ? ['id' => $this->product->id, 'name' => $this->product->name] : null),
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

