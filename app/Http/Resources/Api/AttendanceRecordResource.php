<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceRecordResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'type_label' => $this->type === 'check_in' ? 'حضور' : 'انصراف',
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'distance_meters' => $this->distance_meters,
            'within_geofence' => (bool) $this->within_geofence,
            'geofence' => $this->whenLoaded('geofence', function () {
                return [
                    'id' => $this->geofence->id,
                    'name' => $this->geofence->name,
                ];
            }),
            'recorded_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
