<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
    ];
    protected $guarded = ['id'];

    public function Permission()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }

    public function UserRole()
    {
        return $this->hasMany(UserRole::class, 'role_id');
    }
}
