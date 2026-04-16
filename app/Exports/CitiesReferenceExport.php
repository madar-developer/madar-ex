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
                return [
                    'id' => $city->id,
                    'name' => $city->getTranslation('name', 'ar'),
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
                return [
                    'id' => $district->id,
                    'city' => $district->Parent ? $district->Parent->getTranslation('name', 'ar') : '',
                    'name' => $district->getTranslation('name', 'ar'),
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
