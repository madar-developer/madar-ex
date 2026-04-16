<?php

namespace App\Exports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class CitiesReferenceExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new CitiesOnlySheetExport(),
            new DistrictsSheetExport(),
        ];
    }
}

class CitiesOnlySheetExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return City::query()
            ->where('parent', 0)
            ->orderBy('id')
            ->get()
            ->map(function (City $city) {
                $locale = app()->getLocale();
                $fallbackLocale = config('app.fallback_locale', 'en');

                $resolve = function ($value) use ($locale, $fallbackLocale): string {
                    if (is_array($value)) {
                        return (string)($value[$locale] ?? $value[$fallbackLocale] ?? '');
                    }

                    if (is_string($value)) {
                        $trimmed = trim($value);
                        if ($trimmed !== '' && $trimmed[0] === '{') {
                            $decoded = json_decode($trimmed, true);
                            if (is_array($decoded)) {
                                return (string)($decoded[$locale] ?? $decoded[$fallbackLocale] ?? '');
                            }
                        }
                        return $value;
                    }

                    return '';
                };

                $translated = $city->getTranslation('name', $locale);
                if ($translated === null || $translated === '') {
                    // Fallback: sometimes the attribute can be stored as raw JSON.
                    $translated = $city->getAttribute('name');
                }

                return [
                    'id' => $city->id,
                    'name' => $resolve($translated),
                ];
            });
    }

    public function headings(): array
    {
        return ['id', 'name'];
    }

    public function title(): string
    {
        return 'Cities';
    }
}

class DistrictsSheetExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return City::query()
            ->with('Parent')
            ->where('parent', '!=', 0)
            ->orderBy('id')
            ->get()
            ->map(function (City $district) {
                $locale = app()->getLocale();
                $fallbackLocale = config('app.fallback_locale', 'en');

                $resolve = function ($value) use ($locale, $fallbackLocale): string {
                    if (is_array($value)) {
                        return (string)($value[$locale] ?? $value[$fallbackLocale] ?? '');
                    }

                    if (is_string($value)) {
                        $trimmed = trim($value);
                        if ($trimmed !== '' && $trimmed[0] === '{') {
                            $decoded = json_decode($trimmed, true);
                            if (is_array($decoded)) {
                                return (string)($decoded[$locale] ?? $decoded[$fallbackLocale] ?? '');
                            }
                        }
                        return $value;
                    }

                    return '';
                };

                $cityName = '';
                if ($district->Parent) {
                    $translatedCity = $district->Parent->getTranslation('name', $locale);
                    if ($translatedCity === null || $translatedCity === '') {
                        $translatedCity = $district->Parent->getAttribute('name');
                    }
                    $cityName = $resolve($translatedCity);
                }

                $districtName = $district->getTranslation('name', $locale);
                if ($districtName === null || $districtName === '') {
                    $districtName = $district->getAttribute('name');
                }

                return [
                    'id' => $district->id,
                    'city' => $cityName,
                    'name' => $resolve($districtName),
                ];
            });
    }

    public function headings(): array
    {
        return ['id', 'city', 'name'];
    }

    public function title(): string
    {
        return 'Districts';
    }
}
