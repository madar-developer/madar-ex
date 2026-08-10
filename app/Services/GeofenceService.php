<?php

namespace App\Services;

use App\Models\AttendanceGeofence;

class GeofenceService
{
    /**
     * Calculate distance between two coordinates in meters (Haversine formula).
     */
    public function distanceMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000;

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lng2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return round($angle * $earthRadius, 2);
    }

    /**
     * Find the nearest active geofence that contains the given coordinates.
     *
     * @return array{geofence: AttendanceGeofence, distance: float}|null
     */
    public function findMatchingGeofence(float $latitude, float $longitude): ?array
    {
        $geofences = AttendanceGeofence::active()->get();
        $nearest = null;
        $nearestDistance = null;

        foreach ($geofences as $geofence) {
            $distance = $this->distanceMeters(
                $latitude,
                $longitude,
                (float) $geofence->latitude,
                (float) $geofence->longitude
            );

            if ($distance <= $geofence->radius_meters) {
                if ($nearest === null || $distance < $nearestDistance) {
                    $nearest = $geofence;
                    $nearestDistance = $distance;
                }
            }
        }

        if ($nearest === null) {
            return null;
        }

        return [
            'geofence' => $nearest,
            'distance' => $nearestDistance,
        ];
    }

    /**
     * Get the nearest geofence and distance even if outside the radius.
     *
     * @return array{geofence: AttendanceGeofence, distance: float}|null
     */
    public function findNearestGeofence(float $latitude, float $longitude): ?array
    {
        $geofences = AttendanceGeofence::active()->get();

        if ($geofences->isEmpty()) {
            return null;
        }

        $nearest = null;
        $nearestDistance = null;

        foreach ($geofences as $geofence) {
            $distance = $this->distanceMeters(
                $latitude,
                $longitude,
                (float) $geofence->latitude,
                (float) $geofence->longitude
            );

            if ($nearest === null || $distance < $nearestDistance) {
                $nearest = $geofence;
                $nearestDistance = $distance;
            }
        }

        return [
            'geofence' => $nearest,
            'distance' => $nearestDistance,
        ];
    }
}
