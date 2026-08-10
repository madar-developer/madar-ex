<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceGeofenceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'radius_meters' => (int) $this->radius_meters,
        ];
    }
}
