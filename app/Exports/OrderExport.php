<?php

namespace App\Exports;

use App\Order\Order;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\ReportSetting;

class OrderExport implements WithMultipleSheets
{
   protected $selectedColumns;
   protected $ordersData;

     public function __construct($selectedColumns, $ordersData)
    {
        $this->selectedColumns = $selectedColumns;
        $this->ordersData = $ordersData;
    }

     public function sheets(): array
    {
        $sheets = [];
        $limit = ReportSetting::first()->value('records');
        $chunks = $this->ordersData->chunk($limit);

        foreach ($chunks as $index => $chunk) {
            $sheets[] = new OrderSheet($this->selectedColumns, $chunk, $index + 1);
        }

        return $sheets;
    }
}
class OrderSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $selectedColumns;
    protected $ordersData;
    protected $sheetIndex;

    public function __construct($selectedColumns, $ordersData, $sheetIndex)
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
            'product_name' => 'product Name',
            'plan_name' => 'Plan Name',
            'version' => 'version',
            'agents' => 'Agents',
            'order_status' => 'Status',
            'status' => 'Order status',
             'order_date' => 'Created At',
             'update_ends_at' => 'Expiry At',

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
