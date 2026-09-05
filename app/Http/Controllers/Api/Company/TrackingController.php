<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Services\OrderTrackingService;
use Auth;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    protected OrderTrackingService $trackingService;

    public function __construct(OrderTrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    /**
     * Company app/JWT: track order by refrence_no (or id).
     *
     * GET/POST /api/v1/company/orders/tracking?refrence_no=...
     */
    public function show(Request $request)
    {
        $company = Auth::guard('api-company')->user();

        $order = null;
        if ($request->filled('refrence_no')) {
            $order = $company->Order()
                ->with(['Driver', 'City', 'District', 'PaymentMethod'])
                ->where('refrence_no', $request->get('refrence_no'))
                ->first();
        } elseif ($request->filled('order_id') || $request->filled('id')) {
            $id = $request->get('order_id', $request->get('id'));
            $order = $company->Order()
                ->with(['Driver', 'City', 'District', 'PaymentMethod'])
                ->where('id', $id)
                ->first();
        }

        if (!$order) {
            return response()->json([
                'data' => [],
                'errors' => ['not found'],
                'message' => 'order not found',
                'code' => 404,
            ], 404);
        }

        $tracking = $this->trackingService->forCompany($order);

        return response()->json([
            'data' => $tracking,
            'message' => $tracking['message'],
            'code' => getMsgCode('success'),
        ]);
    }
}
