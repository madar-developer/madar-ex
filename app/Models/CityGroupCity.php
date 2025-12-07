<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityGroupCity extends Model
{
    protected $table='city_group_cities';
    protected $fillable = [
        'city_group_id', 'city_id', 
    ];

    public function City()
    {
        return $this->belongsto(City::class, 'city_id');
    }
    public function CityGroup()
    {
        return $this->belongsto(CityGroup::class, 'city_group_id');
    }
}
