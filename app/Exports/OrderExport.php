<?php

namespace App\Exports;

use App\ReportSetting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;


class OrderExport implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;
    protected $selectedColumns;
    protected $ordersData;
    protected $sheetIndex;

    public function __construct($selectedColumns,$ordersData, $sheetIndex)
    {
        $this->selectedColumns = $selectedColumns;
        $this->ordersData = $ordersData;
        $this->sheetIndex = $sheetIndex;
    }


    public function collection()
    {
        return collect($this->ordersData);
    }

    public function headings(): array
    {
        $headingsMap = [
            'client' => 'Name',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'number' => 'Order No',
            'product_name' => 'Product Name',
            'plan_name' => 'Plan Name',
            'version' => 'Version',
            'agents' => 'Agents',
            'order_status' => 'Status',
            'status' => 'Order Status',
            'order_date' => 'Created At',
            'update_ends_at' => 'Expiry At',
        ];

        return array_map(function ($column) use ($headingsMap) {
            return $headingsMap[$column] ?? $column;
        }, $this->selectedColumns);
    }

    public function title(): string
    {
        return 'Orders';
    }
}
