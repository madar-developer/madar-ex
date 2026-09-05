<?php

namespace App\Services;

use App\Jobs\SendLocationWebhookJob;
use App\Models\Driver;
use App\Models\Order;
use Carbon\Carbon;

class DriverLocationService
{
    const MIN_DISTANCE_METERS = 50;
    const MIN_INTERVAL_SECONDS = 30;

    /**
     * Save driver GPS and fire company location webhooks for at_office orders.
     *
     * @return array{updated: bool, webhooks_dispatched: int, location: array}
     */
    public function update(Driver $driver, float $lat, float $lng, ?int $timestampMs = null, bool $fireWebhooks = true): array
    {
        $previousLat = $driver->last_latitude !== null ? (float) $driver->last_latitude : null;
        $previousLng = $driver->last_longitude !== null ? (float) $driver->last_longitude : null;
        $previousAt = $driver->last_location_at;

        $updatedAt = $timestampMs
            ? Carbon::createFromTimestampMs($timestampMs)
            : Carbon::now();

        $driver->last_latitude = $lat;
        $driver->last_longitude = $lng;
        $driver->last_location_at = $updatedAt;
        $driver->save();

        $location = [
            'lat' => $lat,
            'lng' => $lng,
            'timestamp' => $updatedAt->getTimestamp() * 1000,
            'updated_at' => $updatedAt->toDateTimeString(),
        ];

        $webhooksDispatched = 0;
        if ($fireWebhooks && $this->shouldFireWebhooks($previousLat, $previousLng, $previousAt, $lat, $lng, $updatedAt)) {
            $webhooksDispatched = $this->dispatchLocationWebhooks($driver, $location);
        }

        return [
            'updated' => true,
            'webhooks_dispatched' => $webhooksDispatched,
            'location' => $location,
        ];
    }

    protected function shouldFireWebhooks(
        ?float $previousLat,
        ?float $previousLng,
        $previousAt,
        float $lat,
        float $lng,
        Carbon $updatedAt
    ): bool {
        if ($previousLat === null || $previousLng === null || !$previousAt) {
            return true;
        }

        if ($previousAt->diffInSeconds($updatedAt) < self::MIN_INTERVAL_SECONDS) {
            return false;
        }

        return $this->haversineMeters($previousLat, $previousLng, $lat, $lng) >= self::MIN_DISTANCE_METERS;
    }

    protected function dispatchLocationWebhooks(Driver $driver, array $location): int
    {
        $orders = Order::with('Company')
            ->where('driver_id', $driver->id)
            ->where('status', OrderTrackingService::TRACKABLE_STATUS)
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            $company = $order->Company;
            if (!$company || empty($company->location_notify_url)) {
                continue;
            }

            SendLocationWebhookJob::dispatch(
                $company->location_notify_url,
                $order->refrence_no,
                $order->serial,
                $order->id,
                $driver->id,
                $location['lat'],
                $location['lng'],
                $location['timestamp'],
                $order->status
            );
            $count++;
        }

        return $count;
    }

    protected function haversineMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 2 * $earthRadius * asin(min(1, sqrt($a)));
    }
}
