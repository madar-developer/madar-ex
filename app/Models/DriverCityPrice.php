<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverCityPrice extends Model
{
    protected $table='driver_city_prices';
    protected $fillable = [
        'city_id', 'driver_id', 'delivery_cost'
    ];

    public function Driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
