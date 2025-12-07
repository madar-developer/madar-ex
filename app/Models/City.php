<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $table='cities';
    protected $fillable = [
        'name', 'parent', 'delivery_cost', 'city_code',
    ];

    public function Parent()
    {
        return $this->belongsTo(City::class, 'parent');
    }
    public function CityGroup()
    {
        return $this->belongsToMany(CityGroup::class, 'city_group_cities');
    }

    public function Order()
    {
        return $this->Hasmany(Order::class, 'city_id');
    }
    public function BranchCity()
    {
        return $this->Hasmany(BranchCity::class, 'city_id');
    }

    public function Company()
    {
        return $this->Hasmany(Company::class, 'city_id');
    }
    public function CompanyAddress()
    {
        return $this->Hasmany(CompanyAddress::class, 'city_id');
    }
    public function DriverCity()
    {
        return $this->Hasmany(DriverCity::class, 'city_id');
    }
    public function Admin()
    {
        return $this->Hasmany(Admin::class, 'city_id');
    }
}
