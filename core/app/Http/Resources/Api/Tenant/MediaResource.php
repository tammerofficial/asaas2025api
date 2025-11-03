<?php

namespace App\Http\Resources\Api\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'alt' => $this->alt,
            'size' => (int) ($this->size ?? 0),
            'size_formatted' => $this->formatBytes($this->size ?? 0),
            'path' => $this->path ? url('storage/' . $this->path) : null,
            'dimensions' => $this->dimensions,
            'user_type' => $this->user_type,
            'user_id' => $this->user_id,
            'load_from' => $this->load_from,
            'is_synced' => (bool) ($this->is_synced ?? false),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

