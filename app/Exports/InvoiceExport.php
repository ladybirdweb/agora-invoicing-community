<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;


class InvoiceExport implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    protected $selectedColumns;
    protected $invoicesData;
    protected $sheetIndex;

    public function __construct($selectedColumns,$invoicesData, $sheetIndex)
    {
        $this->selectedColumns = $selectedColumns;
        $this->invoicesData = $invoicesData;
        $this->sheetIndex = $sheetIndex;
    }

    public function collection()
    {
        return collect($this->invoicesData);
    }

    public function headings(): array
    {
        $headingsMap = [
            'user_id' => 'Name',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'country' => 'Country',
            'grand_total' => 'Total',
            'number' => 'InvoiceNo',
            'date' => 'Date',
            'status' => 'Status',
        ];

        return array_map(function ($column) use ($headingsMap) {
            return $headingsMap[$column] ?? $column;
        }, $this->selectedColumns);
    }

    public function title(): string
    {
        return 'Sheet ' . $this->sheetIndex;
    }
}

