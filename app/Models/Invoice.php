<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table='invoices';
    protected $fillable = [
        'order_id' , 'company_price' , 'madar_price' , 'total_price' , 'active' , 'transfer_id', 'created_at_hijri', 'driver_cost', 'driver_paied'
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function Transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }
    public function BranchData()
    {
        return $this->morphOne(BranchData::class, 'taggable');
    }
}
