<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\NotificationResource;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth('api-driver')->user();
        $notifications = $this->driverNotifications($user);

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
        $notifications = $this->driverNotifications($user);

        return response()->json([
            'data' => [
                'notifications' => NotificationResource::collection($notifications),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    protected function driverNotifications($driver)
    {
        return DatabaseNotification::query()
            ->where('notifiable_id', $driver->id)
            ->whereIn('notifiable_type', [Driver::class, 'App\\Driver'])
            ->latest()
            ->get();
    }
}
