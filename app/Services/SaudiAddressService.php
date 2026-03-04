<?php

namespace App\Services;

use Naif\Saudiaddress\Exceptions\SaudiAddressException;
use Naif\Saudiaddress\Facades\SaudiAddress;

class SaudiAddressService
{
    /**
     * Get all regions.
     *
     * @param string|null $language
     * @return mixed
     */
    public function regions($language = null)
    {
        return $this->execute(function () use ($language) {
            return $language ? SaudiAddress::regions($language) : SaudiAddress::regions();
        });
    }

    /**
     * Get cities (all or by region id).
     *
     * @param int|null $regionId
     * @param string|null $language
     * @return mixed
     */
    public function cities($regionId = null, $language = null)
    {
        return $this->execute(function () use ($regionId, $language) {
            if ($regionId === null) {
                return $language ? SaudiAddress::cities(null, $language) : SaudiAddress::cities();
            }

            return $language ? SaudiAddress::cities($regionId, $language) : SaudiAddress::cities($regionId);
        });
    }

    /**
     * Get districts by city id.
     *
     * @param int $cityId
     * @param string|null $language
     * @return mixed
     */
    public function districts($cityId, $language = null)
    {
        return $this->execute(function () use ($cityId, $language) {
            return $language ? SaudiAddress::districts($cityId, $language) : SaudiAddress::districts($cityId);
        });
    }

    /**
     * Reverse geocode by latitude and longitude.
     *
     * @param float $latitude
     * @param float $longitude
     * @return mixed
     */
    public function geocode($latitude, $longitude)
    {
        return $this->execute(function () use ($latitude, $longitude) {
            return SaudiAddress::geoCode($latitude, $longitude);
        });
    }

    /**
     * Verify national address fields.
     *
     * @param int $buildingNumber
     * @param int $postCode
     * @param int $additionalNumber
     * @return bool
     */
    public function verify($buildingNumber, $postCode, $additionalNumber)
    {
        return (bool) $this->execute(function () use ($buildingNumber, $postCode, $additionalNumber) {
            return SaudiAddress::verify($buildingNumber, $postCode, $additionalNumber);
        });
    }

    /**
     * Search addresses using free-text query.
     *
     * @param string $query
     * @param int $page
     * @param string|null $language
     * @return mixed
     */
    public function freeTextSearch($query, $page = 1, $language = null)
    {
        return $this->execute(function () use ($query, $page, $language) {
            return $language
                ? SaudiAddress::freeTextSearch($query, $page, $language)
                : SaudiAddress::freeTextSearch($query, $page);
        });
    }

    /**
     * Search addresses using fixed parameters.
     *
     * @param array $params
     * @param int $page
     * @return mixed
     */
    public function fixedSearch(array $params, $page = 1)
    {
        return $this->execute(function () use ($params, $page) {
            return SaudiAddress::fixedSearch($params, $page);
        });
    }

    /**
     * Resolve address data by short address code.
     *
     * @param string $shortCode
     * @return mixed
     */
    public function shortAddress($shortCode)
    {
        return $this->execute(function () use ($shortCode) {
            return SaudiAddress::shortAddress($shortCode);
        });
    }

    /**
     * Execute API call and normalize package exceptions.
     *
     * @param \Closure $callback
     * @return mixed
     */
    protected function execute(\Closure $callback)
    {
        try {
            return $callback();
        } catch (SaudiAddressException $exception) {
            throw new \RuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
