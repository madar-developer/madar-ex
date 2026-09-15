<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\NotificationResource;
use App\Models\Driver;
use App\Models\Order;
use App\Notifications\DriverNotification;
use Illuminate\Http\Request;
use Validator;

class TestDriverNotificationController extends Controller
{
    /**
     * Send an order-assigned notification to a driver (type = order).
     */
    public function order(Request $request)
    {
        $driver = $this->findDriver($request);
        if (!$driver instanceof Driver) {
            return $driver;
        }

        $order = $request->filled('order_id') ? Order::find($request->get('order_id')) : null;
        $relatedId = $order ? $order->id : null;

        $titleAr = $request->get('title_ar', 'طلب جديد');
        $titleEn = $request->get('title_en', 'New order');
        $contentAr = $request->get('content_ar', $relatedId
            ? 'تم تعيين الطلب رقم '.$relatedId.' إليك'
            : 'تم تعيين طلب جديد إليك');
        $contentEn = $request->get('content_en', $relatedId
            ? 'Order #'.$relatedId.' has been assigned to you'
            : 'A new order has been assigned to you');

        return $this->send(
            $driver,
            $titleAr,
            $contentAr,
            'order',
            $relatedId,
            $relatedId ? 'orders/'.$relatedId : '#',
            $titleEn,
            $contentEn
        );
    }

    /**
     * Send an attendance / logged-in notification to a driver (type = attendance).
     */
    public function attendance(Request $request)
    {
        $driver = $this->findDriver($request);
        if (!$driver instanceof Driver) {
            return $driver;
        }

        $titleAr = $request->get('title_ar', 'تسجيل الدخول');
        $titleEn = $request->get('title_en', 'Login');
        $contentAr = $request->get('content_ar', 'تم تسجيل دخولك الآن');
        $contentEn = $request->get('content_en', 'You are logged in now');

        return $this->send(
            $driver,
            $titleAr,
            $contentAr,
            'attendance',
            null,
            '#',
            $titleEn,
            $contentEn
        );
    }

    /**
     * Send a general notification to a driver (type = general).
     */
    public function general(Request $request)
    {
        $driver = $this->findDriver($request);
        if (!$driver instanceof Driver) {
            return $driver;
        }

        $titleAr = $request->get('title_ar', $request->get('title', 'إشعار عام'));
        $titleEn = $request->get('title_en', $request->get('title', 'General notification'));
        $contentAr = $request->get('content_ar', $request->get('content', 'لديك إشعار عام جديد'));
        $contentEn = $request->get('content_en', $request->get('content', 'You have a new general notification'));

        return $this->send(
            $driver,
            $titleAr,
            $contentAr,
            'general',
            null,
            '#',
            $titleEn,
            $contentEn
        );
    }

    protected function findDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => $validator->errors()->all(),
                'message' => implode(' , ', $validator->errors()->all()),
                'code' => getMsgCode('validationErrors'),
            ]);
        }

        $driver = Driver::find($request->get('driver_id'));
        if (!$driver) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['driver not found'],
                'message' => 'driver not found',
                'code' => getMsgCode('notFound'),
            ]);
        }

        return $driver;
    }

    protected function send(
        Driver $driver,
        string $titleAr,
        string $contentAr,
        string $type,
        $relatedId,
        string $redirect,
        string $titleEn,
        string $contentEn
    ) {
        $driverNotification = new DriverNotification(
            $titleAr,
            $contentAr,
            $type,
            $relatedId,
            $redirect,
            $titleEn,
            $contentEn
        );
        $driver->notify($driverNotification);

        $notification = $driver->notifications()->latest()->first();

        return response()->json([
            'data' => [
                'notification' => new NotificationResource($notification),
                'token_count' => count($driverNotification->fcmTokens),
                'fcm_sent' => $driverNotification->fcmResult !== null,
                'fcm_result' => $driverNotification->fcmResult,
            ],
            'message' => count($driverNotification->fcmTokens) ? 'success' : 'notification stored, but driver has no FCM token',
            'code' => getMsgCode('success'),
        ]);
    }
}
