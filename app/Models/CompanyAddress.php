<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    protected $table='company_addresses';
    protected $fillable = [
        'address', 'nick_name' , 'street_name' , 'building' , 'floor' , 'flat' , 'longitude' , 'latitude' , 'company_id' , 'city_id', 'main',
    ];

    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function Order()
    {
        return $this->hasMany(Order::class, 'company_address_id');
    }

}
