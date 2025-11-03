<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'title' => $this->category->title,
                    'status' => $this->category->status,
                ] : null;
            }),
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ] : null;
            }),
            'admin_id' => $this->admin_id,
            'admin' => $this->whenLoaded('admin', function () {
                return $this->admin ? [
                    'id' => $this->admin->id,
                    'name' => $this->admin->name,
                    'email' => $this->admin->email,
                ] : null;
            }),
            'title' => $this->title,
            'slug' => $this->when($this->relationLoaded('slug'), function () {
                return $this->slug?->slug;
            }, $this->slug ?? null),
            'blog_content' => $this->blog_content,
            'image' => $this->image ? url($this->image) : null,
            'author' => $this->author,
            'excerpt' => $this->excerpt,
            'status' => (int) ($this->status ?? 0),
            'is_published' => (bool) ($this->status == 1),
            'image_gallery' => $this->image_gallery ? json_decode($this->image_gallery, true) : [],
            'views' => (int) ($this->views ?? 0),
            'video_url' => $this->video_url,
            'visibility' => (int) ($this->visibility ?? 0),
            'featured' => (bool) ($this->featured ?? false),
            'tags' => $this->tags,
            'comments_count' => $this->whenLoaded('comments', function () {
                return $this->comments ? $this->comments->count() : 0;
            }),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

