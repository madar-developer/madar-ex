<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'days_count' => (int) $this->days_count,
            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
