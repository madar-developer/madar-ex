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
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
