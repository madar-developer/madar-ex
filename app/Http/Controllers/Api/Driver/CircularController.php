<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CircularResource;
use App\Models\Circular;
use App\Models\CircularRead;
use Illuminate\Http\Request;

class CircularController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                'circulars' => CircularResource::collection($this->driverCirculars()),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $circular = Circular::activeForDriver()->findOrFail($request->get('id'));
        $driverId = auth('api-driver')->id();

        $read = CircularRead::firstOrCreate(
            [
                'circular_id' => $circular->id,
                'driver_id' => $driverId,
            ],
            [
                'read_at' => now(),
            ]
        );

        if ($read->read_at === null) {
            $read->update(['read_at' => now()]);
        }

        return response()->json([
            'data' => [
                'circulars' => CircularResource::collection($this->driverCirculars()),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    protected function driverCirculars()
    {
        $driverId = auth('api-driver')->id();

        return Circular::activeForDriver()
            ->with(['reads' => function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            }])
            ->get();
    }
}
