<?php

namespace App\Services;

use App\Models\Order;

class OrderTrackingService
{
    /**
     * Status where live driver tracking is available (out for delivery).
     */
    public const TRACKABLE_STATUS = 'at_office';

    protected FirestoreDriverLocationService $firestoreLocation;

    public function __construct(FirestoreDriverLocationService $firestoreLocation)
    {
        $this->firestoreLocation = $firestoreLocation;
    }

    /**
     * Build company-facing tracking response for an order.
     * Driver location is read live from Firestore (same source as admin order show map).
     *
     * @return array{tracking_available: bool, status: string, order: array, driver_location: ?array, message: string}
     */
    public function forCompany(Order $order): array
    {
        $order->loadMissing(['Driver', 'City', 'District', 'Company', 'PaymentMethod']);

        $orderPayload = [
            'id' => $order->id,
            'refrence_no' => $order->refrence_no,
            'serial' => $order->serial,
            'serial_no' => $order->serial_no,
            'status' => $order->status,
            'status_txt' => $order->status_txt,
            'recipent_name' => $order->recipent_name,
            'phone' => $order->phone,
            'city_name' => $order->City->name ?? null,
            'district_name' => $order->District->name ?? null,
            'adress_details' => $order->adress_details,
            'latitude' => $order->latitude,
            'longitude' => $order->longitude,
            'packages_number' => $order->packages_number,
            'price' => $order->price,
            'driver_id' => $order->driver_id,
            'driver_name' => $order->Driver
                ? trim($order->Driver->first_name.' '.$order->Driver->last_name)
                : null,
            'created_at' => optional($order->created_at)->format('Y-m-d H:i:s'),
        ];

        if ($order->status !== self::TRACKABLE_STATUS) {
            return [
                'tracking_available' => false,
                'status' => $order->status,
                'order' => $orderPayload,
                'driver_location' => null,
                'message' => 'Tracking is only available when order status is at_office. Current status: '.$order->status,
            ];
        }

        $driverLocation = null;
        if ($order->driver_id) {
            $driverLocation = $this->firestoreLocation->getDriverLocation((int) $order->driver_id);
            if ($driverLocation && empty($driverLocation['driver_name']) && $order->Driver) {
                $driverLocation['driver_name'] = trim($order->Driver->first_name.' '.$order->Driver->last_name);
            }
        }

        return [
            'tracking_available' => true,
            'status' => $order->status,
            'order' => $orderPayload,
            'driver_location' => $driverLocation,
            'message' => $driverLocation
                ? 'success'
                : 'Order is trackable but driver location is not available yet',
        ];
    }
}
