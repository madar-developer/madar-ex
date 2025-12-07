<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverCity extends Model
{
    protected $table='driver_cities';
    protected $fillable = [
        'driver_id', 'city_id', 
    ];

    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function Driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }


}
