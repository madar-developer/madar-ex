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
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
