<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth('api-driver')->user();
        $notifications = $user->notifications()->latest()->get();

        return response()->json([
            'data' => [
                'notifications' => NotificationResource::collection($notifications),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    public function markAsRead(Request $request)
    {
        $user = auth('api-driver')->user();
        $user->unreadNotifications->where('id', $request->get('id'))->markAsRead();
        $notifications = $user->notifications()->latest()->get();

        return response()->json([
            'data' => [
                'notifications' => NotificationResource::collection($notifications),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }
}
