<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchData extends Model
{
    protected $table='branch_data';
    protected $fillable = [
        'taggable_id', 'taggable_type' , 'admin_id' 

    ];

    public function Admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
    public function taggable()
    {
        return $this->morphTo();
    }
    
    
    
}
