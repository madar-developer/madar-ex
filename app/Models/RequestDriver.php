<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDriver extends Model
{
    protected $table = 'request_drivers';
    protected $fillable = [
        'company_id', 'name', 'phone', 'pickup_date', 'time_slot', 'status', 'shipments', 'address'
    ];
    protected $guarded = ['id'];

    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
