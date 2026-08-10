<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    const TYPE_CHECK_IN = 'check_in';
    const TYPE_CHECK_OUT = 'check_out';

    protected $fillable = [
        'driver_id',
        'geofence_id',
        'type',
        'latitude',
        'longitude',
        'distance_meters',
        'within_geofence',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'distance_meters' => 'float',
        'within_geofence' => 'boolean',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function geofence()
    {
        return $this->belongsTo(AttendanceGeofence::class, 'geofence_id');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
