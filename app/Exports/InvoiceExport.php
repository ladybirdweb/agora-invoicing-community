<?php

namespace App\Exports;

use App\ReportSetting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoiceExport implements WithMultipleSheets
{
    protected $selectedColumns;
    protected $invoicesData;

    public function __construct($selectedColumns, $invoicesData)
    {
        $this->selectedColumns = $selectedColumns;
        $this->invoicesData = $invoicesData;
    }

    public function sheets(): array
    {
        $sheets = [];
        $limit = ReportSetting::first()->value('records');
        $chunks = $this->invoicesData->chunk($limit);
        foreach ($chunks as $index => $chunk) {
            $sheets[] = new InvoiceSheet($this->selectedColumns, $chunk, $index + 1);
        }

        return $sheets;
    }
}

class InvoiceSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $selectedColumns;
    protected $invoicesData;
    protected $sheetIndex;

    public function __construct($selectedColumns, $invoicesData, $sheetIndex)
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
        return 'Sheet '.$this->sheetIndex;
    }
}
