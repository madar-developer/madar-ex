<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class GuestOrderResource extends JsonResource
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
            'recipent_name' => $this->recipent_name,
            'status' => $this->status,
            'description' => $this->description,
            'weight' => $this->weight,
            'status_txt' => $this->status_txt,
            'status_image' => $this->status_image,
            'status_color' => $this->status_color,
            'company_name' => $this->Company->name ?? '',
            'city_name' => $this->City->name ?? '',
            'district_name' => $this->District->name ?? '',
            'qr_code' => 'data:image/png;base64,'.\DNS1D::getBarcodePNG($this->refrence_no.'', 'C39+'),
        ];
    }
}
