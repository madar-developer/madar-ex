<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarMaintenance extends Model
{
    protected $table='car_maintenances';
    protected $fillable = [
        'car_id', 'cost' , 'type' , 'month' , 'notes' ,
    ];

    public function Car()
    {
        return $this->belongsto(Car::class, 'car_id');
    }

    public function BranchData()
    {
        return $this->morphMany(BranchData::class, 'taggable');
    }
}
