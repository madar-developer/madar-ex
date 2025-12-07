<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PayWayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'available_method_id' => $this->available_method_id,
            'title' => $this->title,
            'description' => $this->description,
            'available_method' => new AMResource($this->AvailableMethod()->first()),
        ];
    }
}
