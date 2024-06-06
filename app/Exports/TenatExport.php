<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\ReportSetting;

class TenatExport implements WithMultipleSheets
{
    protected $selectedColumns;
    protected $tenantsData;

    public function __construct($selectedColumns, $tenantsData)
    {
        $this->selectedColumns = $selectedColumns;
        $this->tenantsData = $tenantsData;
    }

    public function sheets(): array
    {
        $sheets = [];
        $limit = ReportSetting::first()->value('records');
        $chunks = $this->tenantsData->chunk($limit);

        foreach ($chunks as $index => $chunk) {
            $sheets[] = new TenantsSheet($this->selectedColumns, $chunk, $index + 1);
        }
        return $sheets;
    }
}

class TenantsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $selectedColumns;
    protected $tenantsData;
    protected $sheetIndex;

    public function __construct($selectedColumns, $tenantsData, $sheetIndex)
    {
        $this->selectedColumns = $selectedColumns;
        $this->tenantsData = $tenantsData;
        $this->sheetIndex = $sheetIndex;
    }

    public function collection()
    {
        return collect($this->tenantsData);
    }

    public function headings(): array
    {
        $headingsMap = [
            'name' => 'Name',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'Order' => 'Order No',
            'Expiry day' => 'Expiry Day',
            'Deletion day' => 'Deletion Day',
            'plan' => 'Plan',
            'tenats' => 'Tenats',
            'domain' => 'Domain',
            'status' => 'Order Status',
            'db_name' => 'Database Name',
            'db_username' => 'Database Username',
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

