<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttendanceGeofenceRequest;
use App\Http\Requests\Admin\UpdateAttendanceGeofenceRequest;
use App\Models\AttendanceGeofence;
use App\Traits\Admin\AttendanceGeofenceOperations;

class AttendanceGeofenceController extends Controller
{
    use AttendanceGeofenceOperations;

    public function __construct()
    {
        $this->middleware('Permission:driver_show', ['only' => 'index']);
        $this->middleware('Permission:driver_add', ['only' => 'create', 'store']);
        $this->middleware('Permission:driver_edit', ['only' => 'edit', 'update']);
        $this->middleware('Permission:driver_delete', ['only' => 'destroy']);
    }

    public function index()
    {
        $title = 'دوائر الحضور والانصراف';
        $geofences = AttendanceGeofence::orderByDesc('id')->get();

        return view('admin.attendance-geofences.index', compact('geofences', 'title'));
    }

    public function create()
    {
        $title = 'إضافة دائرة حضور';

        return view('admin.attendance-geofences.add', compact('title'));
    }

    public function store(StoreAttendanceGeofenceRequest $request)
    {
        $this->register($request);

        return redirect('/dashboard/attendance-geofences')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit($id)
    {
        $geofence = AttendanceGeofence::findOrFail($id);
        $title = 'تعديل دائرة حضور';

        return view('admin.attendance-geofences.edit', compact('geofence', 'title'));
    }

    public function update(UpdateAttendanceGeofenceRequest $request, $id)
    {
        $geofence = AttendanceGeofence::findOrFail($id);
        $this->UpdateRecords($geofence, $request);

        return redirect('/dashboard/attendance-geofences')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy(AttendanceGeofence $attendance_geofence)
    {
        $attendance_geofence->delete();

        return 'success';
    }
}
