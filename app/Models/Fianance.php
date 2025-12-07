<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fianance extends Model
{
    protected $table='fianances';
    protected $fillable = [
        'branch_id', 'employee_id', 'amount' , 'type' , 'verified', 'added_by_id', 'verified_by_id', 'in_out', 'details',
    ];

    public function Branch()
    {
        return $this->belongsTo(Admin::class, 'branch_id');
    }
    // public function Admin()
    // {
    //     return $this->belongsTo(Admin::class, 'employee_id');
    // }
}
