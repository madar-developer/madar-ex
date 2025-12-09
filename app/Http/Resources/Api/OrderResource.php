<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Cache PaymentMethod to avoid duplicate queries
        $paymentMethod = $this->whenLoaded('PaymentMethod') ? $this->PaymentMethod : $this->PaymentMethod()->first();
        $p_m = $paymentMethod ? new PMResource($paymentMethod) : (object)['id' => 0, 'name' => "no payment method"];
        
        // Cache Company to avoid duplicate queries
        $company = $this->whenLoaded('Company') ? $this->Company : $this->Company()->first();
        
        return [
            'id' => $this->id,
            'recipent_name' => $this->recipent_name,
            'phone' => $this->phone,
            'city_id' => $this->city_id,
            'adress_details' => $this->adress_details,
            'payment_method_id' => $this->payment_method_id,
            'packages_number' => $this->packages_number,
            'price' => $this->price,
            'notes' => $this->notes,
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'refrence_no' => $this->refrence_no,
            'serial' => $this->serial,
            'serial_no' => $this->serial_no,
            'driver_id' => $this->driver_id,
            'collected' => $this->collected,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'signature' => $this->signature,
            'description' => $this->description,
            'receive_date' => $this->receive_date,
            'delivery_date' => $this->delivery_date,
            'pick_up_date' => $this->pick_up_date,
            'weight' => $this->weight,
            'status_txt' => $this->status_txt,
            'status_image' => $this->status_image,
            'status_color' => $this->status_color,
            'available_statuses' => $this->available_statuses,
            'company' => $company ? new CompanyResource($company) : null,
            'payment_method' => $p_m,
        ];
    }
}
