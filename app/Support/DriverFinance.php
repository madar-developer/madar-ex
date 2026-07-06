<?php

namespace App\Support;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Collection;

class DriverFinance
{
    /**
     * Resolve company without the Order::getCompanyAttribute() accessor,
     * which only selects id/phone/name/adress_details and omits c_o_d_cost.
     */
    public static function resolveCompany(Order $order): ?Company
    {
        if ($order->relationLoaded('Company')) {
            return $order->getRelation('Company');
        }

        return $order->Company()->first();
    }

    public static function resolveDriver(Order $order): ?Driver
    {
        if ($order->relationLoaded('Driver')) {
            return $order->getRelation('Driver');
        }

        return $order->Driver()->first();
    }

    public static function codShipmentCost(Order $order): float
    {
        // if ((int) $order->payment_method_id != '1') {
        //     return 0;
        // }

        $company = self::resolveCompany($order);

        return (float) ($company->c_o_d_cost ?? 0);
    }

    public static function driverCommission(Order $order, ?Driver $driver = null): float
    {
        $shipmentCost = self::codShipmentCost($order);
        if ($shipmentCost <= 0) {
            return 0;
        }

        $driver = $driver ?? self::resolveDriver($order);
        if (!$driver) {
            return 0;
        }

        return round($shipmentCost * ((float) ($driver->commission ?? 0) / 100), 2);
    }

    public static function shipmentNet(Order $order, ?Driver $driver = null): float
    {
        return round(self::codShipmentCost($order) - self::driverCommission($order, $driver), 2);
    }

    public static function codOrderAmount(Order $order): float
    {
        if ((int) $order->payment_method_id !== 1) {
            return 0;
        }

        return (float) ($order->getRawOriginal('price') ?? $order->attributes['price'] ?? 0);
    }

    /**
     * @param  Collection<int, Order>|array<int, Order>  $orders
     * @return array{total_amount: float, driver_amount: float, net_profit: float}
     */
    public static function batchTotals($orders, Driver $driver): array
    {
        $totalAmount = 0;
        $driverAmount = 0;

        foreach ($orders as $order) {
            $totalAmount += self::codOrderAmount($order);
            $driverAmount += self::driverCommission($order, $driver);
        }

        $totalAmount = round($totalAmount, 2);
        $driverAmount = round($driverAmount, 2);

        return [
            'total_amount' => $totalAmount,
            'driver_amount' => $driverAmount,
            'net_profit' => round($totalAmount - $driverAmount, 2),
        ];
    }
}
