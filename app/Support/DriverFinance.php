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

    /**
     * City delivery cost from company price list (company_city_groups).
     */
    public static function cityDeliveryCost(Order $order): float
    {
        $city = $order->relationLoaded('City') ? $order->City : $order->City()->first();
        if (!$city) {
            return 0;
        }

        if ((string) $city->delivery_cost === '1') {
            $groupCity = $city;
        } else {
            $groupCity = $city->relationLoaded('Parent') ? $city->Parent : $city->Parent()->first();
        }

        if (!$groupCity) {
            return 0;
        }

        $cityGroup = $groupCity->CityGroup()->select('city_groups.delivery_cost', 'city_groups.id')->first();
        if (!$cityGroup) {
            return 0;
        }

        $company = self::resolveCompany($order);
        if (!$company) {
            return 0;
        }

        $companyCityGroup = $company->CompanyCityGroup()->where('city_group_id', $cityGroup->id)->first();

        return (float) ($companyCityGroup->delivery_cost ?? 0);
    }

    /**
     * COD surcharge from company (payment_method_id = 1 only).
     */
    public static function codFee(Order $order): float
    {
        if ((int) $order->payment_method_id !== 1) {
            return 0;
        }

        $company = self::resolveCompany($order);

        return (float) ($company->c_o_d_cost ?? 0);
    }

    /** @deprecated Use codFee() — kept for existing call sites */
    public static function codShipmentCost(Order $order): float
    {
        return self::codFee($order);
    }

    /**
     * Full shipment cost: city delivery + COD fee (matches invoice madar_price for delivered orders).
     */
    public static function shipmentCost(Order $order): float
    {
        $invoice = $order->relationLoaded('Invoice') ? $order->Invoice : $order->Invoice()->first();
        if ($invoice && (float) $invoice->madar_price > 0) {
            return (float) $invoice->madar_price;
        }

        return round(self::cityDeliveryCost($order) + self::codFee($order), 2);
    }

    public static function driverCommission(Order $order, ?Driver $driver = null): float
    {
        $shipmentCost = self::codFee($order);
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
        return round(self::shipmentCost($order) - self::driverCommission($order, $driver), 2);
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
     */
    public static function batchShipmentCost($orders): float
    {
        $total = 0;

        foreach ($orders as $order) {
            $total += self::shipmentCost($order);
        }

        return round($total, 2);
    }

    /**
     * @param  Collection<int, Order>|array<int, Order>  $orders
     * @return array{total_amount: float, driver_amount: float, net_profit: float, shipment_cost: float}
     */
    public static function batchTotals($orders, Driver $driver): array
    {
        $totalAmount = 0;
        $driverAmount = 0;
        $shipmentCost = 0;

        foreach ($orders as $order) {
            $totalAmount += self::codOrderAmount($order);
            $driverAmount += self::driverCommission($order, $driver);
            $shipmentCost += self::shipmentCost($order);
        }

        $totalAmount = round($totalAmount, 2);
        $driverAmount = round($driverAmount, 2);
        $shipmentCost = round($shipmentCost, 2);

        return [
            'total_amount' => $totalAmount,
            'driver_amount' => $driverAmount,
            'net_profit' => round($shipmentCost - $driverAmount, 2),
            'shipment_cost' => $shipmentCost,
        ];
    }
}
