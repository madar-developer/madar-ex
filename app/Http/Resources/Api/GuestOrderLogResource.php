<?php

namespace App\Http\Resources\Api;

use App\Models\OrderStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestOrderLogResource extends JsonResource
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
            'status' => (OrderStatus::where('key', $this->status)->first())? OrderStatus::where('key', $this->status)->first()->name : '',
            'date' => $this->created_at->toDateString(),
            'details' => $this->details,
            'image' => $this->image,
            'color' => $this->color,
        ];
    }
}
