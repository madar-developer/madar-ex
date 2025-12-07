<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCacheType extends Model
{
    protected $table='company_cache_types';
    protected $fillable = [
        'available_method_id', 'company_id' , 'title' , 'description' , 'active'
    ];


    public function AvailableMethod()
    {
        return $this->belongsto(AvailableMethod::class, 'available_method_id');
    }

    public function Company()
    {
        return $this->belongsto(Company::class, 'company_id');
    }
}
