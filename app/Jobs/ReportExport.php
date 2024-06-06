<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReportExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportType;
    protected $selectedColumns;
    protected $searchParams;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct($reportType, $selectedColumns, $searchParams, $email)
    {
        $this->reportType = $reportType;
        $this->selectedColumns = $selectedColumns;
        $this->searchParams = $searchParams;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        switch ($this->reportType) {
            case 'users':
                $exportJob = new ExportUsersJob($this->selectedColumns, $this->searchParams, $this->email);
                break;
            case 'invoices':
                $exportJob = new ExportInvoicesJob($this->selectedColumns, $this->searchParams, $this->email);
                break;
            case 'orders':
                $exportJob = new ExportOrdersJob($this->selectedColumns, $this->searchParams, $this->email);
                break;
            case 'tenats':
                $exportJob = new ExportTenatsJob($this->selectedColumns, $this->searchParams, $this->email);
                break;
            default:
                return;
        }

        dispatch($exportJob);
    }
}
