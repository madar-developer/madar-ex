<?php

namespace App\Services;

use App\Models\Order;
use App\Models\RegionArea;

class RegionAreaService
{
    public function codeFor($latitude, $longitude): ?string
    {
        if ($latitude === null || $longitude === null || $latitude === '' || $longitude === '') {
            return null;
        }

        $lat = (float) $latitude;
        $lng = (float) $longitude;
        if ($lat == 0.0 && $lng == 0.0) {
            return null;
        }

        $areas = RegionArea::active()->latest('id')->get();
        foreach ($areas as $area) {
            if ($this->contains($lat, $lng, $area->coordinates ?? [])) {
                return (string) $area->code;
            }
        }

        return null;
    }

    public function applyToOrder(Order $order): void
    {
        $order->region_area_num = $this->codeFor($order->latitude, $order->longitude);
    }

    public function syncOrdersForArea(RegionArea $area): void
    {
        Order::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->select('id', 'latitude', 'longitude', 'region_area_num')
            ->chunkById(200, function ($orders) use ($area) {
                foreach ($orders as $order) {
                    $inside = $this->contains((float) $order->latitude, (float) $order->longitude, $area->coordinates ?? []);
                    if ($inside) {
                        if ($order->region_area_num !== $area->code) {
                            Order::where('id', $order->id)->update(['region_area_num' => $area->code]);
                        }
                    } elseif ($order->region_area_num === $area->code) {
                        Order::where('id', $order->id)->update([
                            'region_area_num' => $this->codeFor($order->latitude, $order->longitude),
                        ]);
                    }
                }
            });
    }

    public function reassignOrdersWithCode(string $code): void
    {
        Order::query()
            ->where('region_area_num', $code)
            ->select('id', 'latitude', 'longitude')
            ->chunkById(200, function ($orders) {
                foreach ($orders as $order) {
                    Order::where('id', $order->id)->update([
                        'region_area_num' => $this->codeFor($order->latitude, $order->longitude),
                    ]);
                }
            });
    }

    public function contains(float $lat, float $lng, array $polygon): bool
    {
        $n = count($polygon);
        if ($n < 3) {
            return false;
        }

        $inside = false;
        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $yi = (float) ($polygon[$i]['lat'] ?? 0);
            $xi = (float) ($polygon[$i]['lng'] ?? 0);
            $yj = (float) ($polygon[$j]['lat'] ?? 0);
            $xj = (float) ($polygon[$j]['lng'] ?? 0);
            $denom = ($yj - $yi) ?: 1e-12;
            $intersect = (($yi > $lat) !== ($yj > $lat))
                && ($lng < ($xj - $xi) * ($lat - $yi) / $denom + $xi);
            if ($intersect) {
                $inside = ! $inside;
            }
        }

        return $inside;
    }
}
