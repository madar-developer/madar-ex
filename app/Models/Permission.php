<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
        'role_id', 'permission', 
    ];
    protected $guarded = ['id'];

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
