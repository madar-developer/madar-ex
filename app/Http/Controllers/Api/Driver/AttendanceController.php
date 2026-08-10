<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AttendanceRequest;
use App\Http\Resources\Api\AttendanceGeofenceResource;
use App\Http\Resources\Api\AttendanceRecordResource;
use App\Models\AttendanceRecord;
use App\Services\GeofenceService;
use Auth;

class AttendanceController extends Controller
{
    protected GeofenceService $geofenceService;

    public function __construct(GeofenceService $geofenceService)
    {
        $this->geofenceService = $geofenceService;
    }

    /**
     * List active geofence zones for the mobile app map.
     */
    public function geofences()
    {
        $geofences = \App\Models\AttendanceGeofence::active()->get();

        return response()->json([
            'data' => AttendanceGeofenceResource::collection($geofences),
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    /**
     * Today's attendance status for the authenticated driver.
     */
    public function today()
    {
        $driver = Auth::guard('api-driver')->user();

        $todayRecords = AttendanceRecord::where('driver_id', $driver->id)
            ->today()
            ->where('within_geofence', true)
            ->orderBy('created_at')
            ->with('geofence')
            ->get();

        $lastCheckIn = $todayRecords->where('type', AttendanceRecord::TYPE_CHECK_IN)->last();
        $lastCheckOut = $todayRecords->where('type', AttendanceRecord::TYPE_CHECK_OUT)->last();

        $isCheckedIn = $lastCheckIn && (!$lastCheckOut || $lastCheckIn->created_at > $lastCheckOut->created_at);

        return response()->json([
            'data' => [
                'is_checked_in' => $isCheckedIn,
                'can_check_in' => !$isCheckedIn,
                'can_check_out' => (bool) $isCheckedIn,
                'records' => AttendanceRecordResource::collection($todayRecords),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    /**
     * Record check-in if driver is within an active geofence.
     */
    public function checkIn(AttendanceRequest $request)
    {
        return $this->recordAttendance($request, AttendanceRecord::TYPE_CHECK_IN);
    }

    /**
     * Record check-out if driver is within an active geofence.
     */
    public function checkOut(AttendanceRequest $request)
    {
        return $this->recordAttendance($request, AttendanceRecord::TYPE_CHECK_OUT);
    }

    /**
     * Attendance history for the authenticated driver.
     */
    public function history()
    {
        $driver = Auth::guard('api-driver')->user();

        $records = AttendanceRecord::where('driver_id', $driver->id)
            ->where('within_geofence', true)
            ->with('geofence')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'data' => AttendanceRecordResource::collection($records),
            'meta' => [
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'total' => $records->total(),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    protected function recordAttendance(AttendanceRequest $request, string $type)
    {
        $driver = Auth::guard('api-driver')->user();
        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;

        $validationError = $this->validateAttendanceState($driver->id, $type);
        if ($validationError) {
            return response()->json([
                'data' => new \stdClass,
                'message' => $validationError,
                'code' => getMsgCode('error'),
            ], 422);
        }

        $match = $this->geofenceService->findMatchingGeofence($latitude, $longitude);

        if (!$match) {
            $nearest = $this->geofenceService->findNearestGeofence($latitude, $longitude);

            $message = 'أنت خارج نطاق الحضور المسموح';
            if ($nearest) {
                $remaining = round($nearest['distance'] - $nearest['geofence']->radius_meters);
                $message = "أنت خارج نطاق الحضور. أقرب موقع: {$nearest['geofence']->name} ({$remaining} متر خارج النطاق)";
            }

            AttendanceRecord::create([
                'driver_id' => $driver->id,
                'geofence_id' => $nearest ? $nearest['geofence']->id : null,
                'type' => $type,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'distance_meters' => $nearest ? $nearest['distance'] : null,
                'within_geofence' => false,
                'notes' => $message,
            ]);

            return response()->json([
                'data' => new \stdClass,
                'message' => $message,
                'code' => getMsgCode('error'),
            ], 422);
        }

        $record = AttendanceRecord::create([
            'driver_id' => $driver->id,
            'geofence_id' => $match['geofence']->id,
            'type' => $type,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'distance_meters' => $match['distance'],
            'within_geofence' => true,
        ]);

        $record->load('geofence');

        $typeLabel = $type === AttendanceRecord::TYPE_CHECK_IN ? 'تم تسجيل الحضور بنجاح' : 'تم تسجيل الانصراف بنجاح';

        return response()->json([
            'data' => new AttendanceRecordResource($record),
            'message' => $typeLabel,
            'code' => getMsgCode('success'),
        ]);
    }

    protected function validateAttendanceState(int $driverId, string $type): ?string
    {
        $todayRecords = AttendanceRecord::where('driver_id', $driverId)
            ->today()
            ->where('within_geofence', true)
            ->orderBy('created_at')
            ->get();

        $lastCheckIn = $todayRecords->where('type', AttendanceRecord::TYPE_CHECK_IN)->last();
        $lastCheckOut = $todayRecords->where('type', AttendanceRecord::TYPE_CHECK_OUT)->last();

        $isCheckedIn = $lastCheckIn && (!$lastCheckOut || $lastCheckIn->created_at > $lastCheckOut->created_at);

        if ($type === AttendanceRecord::TYPE_CHECK_IN && $isCheckedIn) {
            return 'لقد سجلت حضورك بالفعل اليوم. يرجى تسجيل الانصراف أولاً';
        }

        if ($type === AttendanceRecord::TYPE_CHECK_OUT && !$isCheckedIn) {
            return 'لم تسجل حضورك اليوم. يرجى تسجيل الحضور أولاً';
        }

        return null;
    }
}
