<?php
namespace App\Http\Resources\Api\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', fn() => $this->user ? ['id' => $this->user->id, 'name' => $this->user->name, 'email' => $this->user->email] : null),
            'balance' => (float) ($this->balance ?? 0),
            'status' => (int) ($this->status ?? 1),
            'is_active' => (bool) ($this->status == 1),
            'settings' => $this->whenLoaded('walletSettings', fn() => $this->walletSettings ? ['id' => $this->walletSettings->id, 'user_id' => $this->walletSettings->user_id] : null),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

