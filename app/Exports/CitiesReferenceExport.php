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
            ->select('id', 'name')
            ->orderBy('id')
            ->get();
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
            ->leftJoin('cities as parent_city', 'cities.parent', '=', 'parent_city.id')
            ->where('cities.parent', '!=', 0)
            ->select('cities.id', 'parent_city.name as city', 'cities.name')
            ->orderBy('cities.id')
            ->get();
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
