<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Driver;
use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('Permission:driver_show', ['only' => 'index']);
    }

    public function index(Request $request)
    {
        $title = 'سجل الحضور والانصراف';

        $query = AttendanceRecord::with(['driver', 'geofence'])
            ->orderByDesc('created_at');

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('within_geofence')) {
            $query->where('within_geofence', $request->within_geofence === '1');
        }

        $records = $query->paginate(30);
        $drivers = Driver::orderBy('first_name')->get(['id', 'first_name', 'last_name']);

        return view('admin.attendance-records.index', compact('records', 'drivers', 'title'));
    }
}
