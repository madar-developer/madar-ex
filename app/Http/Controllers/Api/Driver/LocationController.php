<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateDriverLocationRequest;
use App\Services\DriverLocationService;
use Auth;

class LocationController extends Controller
{
    protected DriverLocationService $locationService;

    public function __construct(DriverLocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Driver app posts live GPS. Fires company location_notify_url webhooks
     * for this driver's at_office orders.
     */
    public function update(UpdateDriverLocationRequest $request)
    {
        $driver = Auth::guard('api-driver')->user();
        $fireWebhooks = true;
        if ($request->has('fire_webhooks')) {
            $fireWebhooks = filter_var($request->input('fire_webhooks'), FILTER_VALIDATE_BOOLEAN);
        }

        $result = $this->locationService->update(
            $driver,
            (float) $request->input('lat'),
            (float) $request->input('lng'),
            $request->filled('timestamp') ? (int) $request->input('timestamp') : null,
            $fireWebhooks
        );

        return response()->json([
            'data' => $result,
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }
}
