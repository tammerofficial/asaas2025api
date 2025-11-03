<?php
namespace App\Http\Resources\Api\Central;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class SupportTicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'via' => $this->via,
            'operating_system' => $this->operating_system,
            'user_agent' => $this->user_agent,
            'description' => $this->description,
            'subject' => $this->subject,
            'status' => $this->status,
            'priority' => $this->priority,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', fn() => $this->user ? ['id' => $this->user->id, 'name' => $this->user->name, 'email' => $this->user->email] : null),
            'admin_id' => $this->admin_id,
            'admin' => $this->whenLoaded('admin', fn() => $this->admin ? ['id' => $this->admin->id, 'name' => $this->admin->name] : null),
            'department_id' => $this->department_id,
            'department' => $this->whenLoaded('department', fn() => $this->department ? ['id' => $this->department->id, 'name' => $this->department->name] : null),
            'messages_count' => $this->whenLoaded('messages', fn() => $this->messages ? $this->messages->count() : 0),
            'messages' => $this->whenLoaded('messages', fn() => $this->messages->map(fn($m) => ['id' => $m->id, 'message' => $m->message, 'type' => $m->type, 'created_at' => $m->created_at?->toISOString()])),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

