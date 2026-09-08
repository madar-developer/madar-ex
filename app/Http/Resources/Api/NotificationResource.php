<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $data = is_array($this->data) ? $this->data : [];

        return [
            'id' => $this->id,
            'text' => $data['text'] ?? null,
            'related_id' => $data['related_id'] ?? null,
            'type' => $data['type'] ?? null,
            'redirect' => $data['redirect'] ?? null,
            'is_read' => $this->read_at !== null,
            'read_at' => $this->read_at,
            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
