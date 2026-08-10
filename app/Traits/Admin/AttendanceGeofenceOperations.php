<?php

namespace App\Traits\Admin;

use App\Models\AttendanceGeofence;
use DB;

trait AttendanceGeofenceOperations
{
    public function register($request)
    {
        $data = $request->only(['name', 'latitude', 'longitude', 'radius_meters']);
        $data['active'] = $request->has('active') ? 1 : 0;

        DB::beginTransaction();
        $geofence = AttendanceGeofence::create($data);
        DB::commit();

        return $geofence;
    }

    public function UpdateRecords(AttendanceGeofence $geofence, $request)
    {
        $data = $request->only(['name', 'latitude', 'longitude', 'radius_meters']);
        $data['active'] = $request->has('active') ? 1 : 0;

        $geofence->update($data);

        return $geofence;
    }
}
