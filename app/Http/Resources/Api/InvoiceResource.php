<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Order;

class InvoiceResource extends JsonResource
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
            'invoice_url' => route('driver-finance-collect.pdf2', $this->id),
            'total_amount' => $this->total_amount,
            'date' => $this->created_at->toDateString(),
            'orders' => OrderResource ::collection(Order::whereIn('id', explode(',', $this->orders))->get()),
        ];
    }
}