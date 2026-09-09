<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class CircularResource extends JsonResource
{
    public function toArray($request)
    {
        $readAt = $this->relationLoaded('reads')
            ? optional($this->reads->first())->read_at
            : ($this->read_at ?? null);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'days_count' => (int) $this->days_count,
            'is_read' => $readAt !== null ? 1 : 0,
            'read_at' => optional($readAt)->format('Y-m-d H:i:s'),
            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
