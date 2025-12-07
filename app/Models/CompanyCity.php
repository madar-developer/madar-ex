<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCity extends Model
{
    protected $table='company_cities';
    protected $fillable = [
        'city_id', 'company_id', 'delivery_cost'
    ];

    public function Company()
    {
        return $this->belongsto(Company::class, 'company_id');
    }
    public function City()
    {
        return $this->belongsto(City::class, 'city_id');
    }
}
