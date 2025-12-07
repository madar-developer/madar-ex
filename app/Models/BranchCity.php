<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchCity extends Model
{
    protected $table='branch_cities';
    protected $fillable = [
        'city_id', 'branch_id' 

    ];

    public function City()
    {
        return $this->belongsto(City::class, 'city_id');
    }

    public function Branch()
    {
        return $this->belongsto(Branch::class, 'branch_id');
    }
}
