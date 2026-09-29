<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CompanyDriverAssigner
{
    /**
     * Orders in these statuses are finished and do not count toward a driver's load.
     */
    public const CLOSED_STATUSES = ['delivered', 'returned', 'cancelled'];

    /**
     * Pick the assigned driver with the fewest non-delivered orders.
     * Equal loads go to the lowest driver id so placement stays stable.
     */
    public function pickDriverId(int $companyId): ?int
    {
        if ($companyId <= 0) {
            return null;
        }

        $hasDrivers = DB::table('company_driver')
            ->where('company_id', $companyId)
            ->exists();

        if (! $hasDrivers) {
            return null;
        }

        return DB::transaction(function () use ($companyId) {
            $driverIds = DB::table('company_driver')
                ->where('company_id', $companyId)
                ->orderBy('driver_id')
                ->lockForUpdate()
                ->pluck('driver_id');

            if ($driverIds->isEmpty()) {
                return null;
            }

            $counts = Order::query()
                ->whereIn('driver_id', $driverIds)
                ->whereNotIn('status', self::CLOSED_STATUSES)
                ->selectRaw('driver_id, COUNT(*) as open_count')
                ->groupBy('driver_id')
                ->pluck('open_count', 'driver_id');

            $chosen = null;
            $lowest = null;

            foreach ($driverIds as $driverId) {
                $driverId = (int) $driverId;
                $count = (int) ($counts[$driverId] ?? 0);

                if ($lowest === null || $count < $lowest) {
                    $lowest = $count;
                    $chosen = $driverId;
                }
            }

            return $chosen;
        });
    }
}
