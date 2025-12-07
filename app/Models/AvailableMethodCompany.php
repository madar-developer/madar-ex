<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableMethodCompany extends Model
{
    protected $table='available_method_companies';
    protected $fillable = [
        'available_method__id', 'company_id' 

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
