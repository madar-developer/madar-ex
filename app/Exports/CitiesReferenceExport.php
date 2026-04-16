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
                $localeShort = explode('-', (string) $locale)[0];
                $fallbackLocale = config('app.fallback_locale', 'en');

                $fallbackLocaleShort = explode('-', (string) $fallbackLocale)[0];

                $resolve = function ($value) use ($locale, $localeShort, $fallbackLocale, $fallbackLocaleShort): string {
                    $decodeUnicodeEscapes = function (string $str): string {
                        if ($str === '') return '';
                        // If it's already a normal string, just return it.
                        if (!str_contains($str, '\\u')) {
                            return $str;
                        }

                        // Decode sequences like "\u0627" -> "ا" by JSON-decoding as a quoted string.
                        $escaped = str_replace('"', '\\"', $str);
                        $decoded = json_decode('"' . $escaped . '"', true);
                        return is_string($decoded) ? $decoded : $str;
                    };

                    $pickFromArray = function (array $arr) use ($locale, $localeShort, $fallbackLocale, $fallbackLocaleShort): string {
                        if (array_key_exists($locale, $arr)) return $decodeUnicodeEscapes((string) $arr[$locale]);
                        if (array_key_exists($localeShort, $arr)) return $decodeUnicodeEscapes((string) $arr[$localeShort]);
                        if (array_key_exists($fallbackLocale, $arr)) return $decodeUnicodeEscapes((string) $arr[$fallbackLocale]);
                        if (array_key_exists($fallbackLocaleShort, $arr)) return $decodeUnicodeEscapes((string) $arr[$fallbackLocaleShort]);
                        $first = reset($arr);
                        return is_null($first) ? '' : $decodeUnicodeEscapes((string) $first);
                    };

                    if (is_array($value)) {
                        return $pickFromArray($value);
                    }

                    if (!is_string($value)) {
                        return '';
                    }

                    $trimmed = trim($value);
                    if ($trimmed === '') {
                        return '';
                    }

                    // JSON object string -> decode directly.
                    $decoded = json_decode($trimmed, true);
                    if (is_array($decoded)) {
                        return $pickFromArray($decoded);
                    }

                    // JSON string that contains a JSON object.
                    if (is_string($decoded)) {
                        $innerDecoded = json_decode(trim($decoded), true);
                        if (is_array($innerDecoded)) {
                            return $pickFromArray($innerDecoded);
                        }
                    }

                    // Last attempt: extract the {...} part and decode.
                    if (str_contains($trimmed, '{') && str_contains($trimmed, '}')) {
                        $start = strpos($trimmed, '{');
                        $end = strrpos($trimmed, '}');
                        $maybeObject = substr($trimmed, $start, $end - $start + 1);
                        $maybeDecoded = json_decode($maybeObject, true);
                        if (is_array($maybeDecoded)) {
                            return $pickFromArray($maybeDecoded);
                        }
                    }

                    // Last-resort: extract {"ar":"..."} via regex (avoids JSON decode issues).
                    $keysToTry = array_values(array_unique([$locale, $localeShort, $fallbackLocale, $fallbackLocaleShort]));
                    foreach ($keysToTry as $key) {
                        $pattern = '/"' . preg_quote((string) $key, '/') . '"\s*:\s*"([^"]*)"/u';
                        if (preg_match($pattern, $trimmed, $m) === 1) {
                            return $decodeUnicodeEscapes((string) $m[1]);
                        }
                    }

                    // Otherwise return as-is (Excel will show it).
                    return $value;
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
                $localeShort = explode('-', (string) $locale)[0];
                $fallbackLocale = config('app.fallback_locale', 'en');

                $fallbackLocaleShort = explode('-', (string) $fallbackLocale)[0];

                $resolve = function ($value) use ($locale, $localeShort, $fallbackLocale, $fallbackLocaleShort): string {
                    $decodeUnicodeEscapes = function (string $str): string {
                        if ($str === '') return '';
                        if (!str_contains($str, '\\u')) {
                            return $str;
                        }

                        $escaped = str_replace('"', '\\"', $str);
                        $decoded = json_decode('"' . $escaped . '"', true);
                        return is_string($decoded) ? $decoded : $str;
                    };

                    $pickFromArray = function (array $arr) use ($locale, $localeShort, $fallbackLocale, $fallbackLocaleShort): string {
                        if (array_key_exists($locale, $arr)) return $decodeUnicodeEscapes((string) $arr[$locale]);
                        if (array_key_exists($localeShort, $arr)) return $decodeUnicodeEscapes((string) $arr[$localeShort]);
                        if (array_key_exists($fallbackLocale, $arr)) return $decodeUnicodeEscapes((string) $arr[$fallbackLocale]);
                        if (array_key_exists($fallbackLocaleShort, $arr)) return $decodeUnicodeEscapes((string) $arr[$fallbackLocaleShort]);
                        $first = reset($arr);
                        return is_null($first) ? '' : $decodeUnicodeEscapes((string) $first);
                    };

                    if (is_array($value)) {
                        return $pickFromArray($value);
                    }

                    if (!is_string($value)) {
                        return '';
                    }

                    $trimmed = trim($value);
                    if ($trimmed === '') {
                        return '';
                    }

                    $decoded = json_decode($trimmed, true);
                    if (is_array($decoded)) {
                        return $pickFromArray($decoded);
                    }

                    if (is_string($decoded)) {
                        $innerDecoded = json_decode(trim($decoded), true);
                        if (is_array($innerDecoded)) {
                            return $pickFromArray($innerDecoded);
                        }
                    }

                    if (str_contains($trimmed, '{') && str_contains($trimmed, '}')) {
                        $start = strpos($trimmed, '{');
                        $end = strrpos($trimmed, '}');
                        $maybeObject = substr($trimmed, $start, $end - $start + 1);
                        $maybeDecoded = json_decode($maybeObject, true);
                        if (is_array($maybeDecoded)) {
                            return $pickFromArray($maybeDecoded);
                        }
                    }

                    // Last-resort: extract {"ar":"..."} via regex (avoids JSON decode issues).
                    $keysToTry = array_values(array_unique([$locale, $localeShort, $fallbackLocale, $fallbackLocaleShort]));
                    foreach ($keysToTry as $key) {
                        $pattern = '/"' . preg_quote((string) $key, '/') . '"\s*:\s*"([^"]*)"/u';
                        if (preg_match($pattern, $trimmed, $m) === 1) {
                            return $decodeUnicodeEscapes((string) $m[1]);
                        }
                    }

                    return $value;
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
