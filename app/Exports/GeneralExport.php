<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class GeneralExport extends StringValueBinder implements FromView, ShouldAutoSize, WithEvents, WithCustomValueBinder
{
    public $items;
    public $view;
    public function __construct($view, $items) {
        $this->items = $items;
        $this->view = $view;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }
    public function view(): View
    {
        return view($this->view, [
            'items' => $this->items
        ]);
    }
}
