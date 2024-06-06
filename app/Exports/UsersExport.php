<?php

namespace App\Exports;

use App\ReportSetting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class UsersExport implements WithMultipleSheets
{
    protected $selectedColumns;
    protected $usersData;

    public function __construct($selectedColumns, $usersData)
    {
        $this->selectedColumns = $selectedColumns;
        $this->usersData = $usersData;
    }

    public function sheets(): array
    {
        $sheets = [];
        $limit = ReportSetting::first()->value('records');
        $chunks = $this->usersData->chunk($limit);

        foreach ($chunks as $index => $chunk) {
            $sheets[] = new UsersSheet($this->selectedColumns, $chunk, $index + 1);
        }

        return $sheets;
    }
}

class UsersSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $selectedColumns;
    protected $usersData;
    protected $sheetIndex;

    public function __construct($selectedColumns, $usersData, $sheetIndex)
    {
        $this->selectedColumns = $selectedColumns;
        $this->usersData = $usersData;
        $this->sheetIndex = $sheetIndex;
    }

    public function collection()
    {
        return collect($this->usersData);
    }

    public function headings(): array
    {
        $headingsMap = [
            'name' => 'Name',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'country' => 'Country',
            'created_at' => 'Registered on',
            'active' => 'Email status',
            'mobile_verified' => 'Mobile status',
            'is_2fa_enabled' => '2FA status',

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
