<?php
namespace App\Http\Resources\Api\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class NewsletterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => (int) ($this->status ?? 0),
            'is_subscribed' => (bool) ($this->status == 1),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

