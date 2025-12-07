<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CityGroup extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $table='city_groups';
    protected $fillable = [
        'name', 'delivery_cost'
    ];
    protected $appends = [
        'cities',
    ];

    public function CityGroupCity()
    {
        return $this->hasMany(CityGroupCity::class, 'city_group_id');
    }
    public function City()
    {
        return $this->belongsToMany(City::class, 'city_group_cities');
    }

    public function getCitiesAttribute()
    {
        return $this->CityGroupCity()->pluck('city_id')->toArray();
    }
}
