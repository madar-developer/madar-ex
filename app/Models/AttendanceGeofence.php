<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceGeofence extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius_meters',
        'active',
        'admin_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'radius_meters' => 'integer',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class, 'geofence_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
