<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCityGroup extends Model
{
    protected $table='company_city_groups';
    protected $fillable = [
        'city_group_id', 'company_id', 'delivery_cost'
    ];

    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function CityGroup()
    {
        return $this->belongsTo(CityGroup::class, 'city_group_id');
    }
}
