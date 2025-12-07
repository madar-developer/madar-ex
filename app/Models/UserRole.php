<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'admin_roles';
    protected $fillable = [
        'role_id', 'admin_id',
    ];
    protected $guarded = ['id'];

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function User()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function Permission()
    {
        return $this->belongsTo(Permission::class, 'role_id');
    }
}
