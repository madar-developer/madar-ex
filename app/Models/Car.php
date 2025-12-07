<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table='cars';
    protected $fillable = [
        'name', 'structure_no' , 'color' , 'manufacturing_year' , 'car_type' , 'work_city', 'plate_num', 'license_expiration_date',
         'kms', 'notes', 'insurance_expiration_date', 'type' , 'form_image' , 'insurance_expiration_date_hijri',
         'license_expiration_date_hijri'
    ];

    public function Driver()
    {
        return $this->hasMany(Driver::class, 'car_id');
    }
    public function CarMaintenance()
    {
        return $this->hasMany(CarMaintenance::class, 'car_id');
    }
    public function BranchData()
    {
        return $this->morphOne(BranchData::class, 'taggable');
    }
}
