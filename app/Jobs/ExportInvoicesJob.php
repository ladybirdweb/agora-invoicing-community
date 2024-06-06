<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Exports\InvoiceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\ExportDetail;
use App\User;
use App\Traits\CoupCodeAndInvoiceSearch;
use Illuminate\Http\Request;


class ExportInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CoupCodeAndInvoiceSearch;

    protected $selectedColumns;
    protected $searchParams;
    protected $email;

    /**
     * Create a new job instance.
     */
   public function __construct($selectedColumns, $searchParams, $email)
    {
        $this->selectedColumns = $selectedColumns;
        $this->searchParams = $searchParams;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
      public function handle()
    {
        // Similar logic to export users but for invoices
        $this->selectedColumns = array_filter($this->selectedColumns, function($column) {
            return !in_array($column, ['checkbox', 'action']);
        });
        $request = new Request();
        $request->merge($this->searchParams);
        $name = $request->input('name');
        $invoice_no = $request->input('invoice_no');
        $status = $request->input('status');
        $currency = $request->input('currency_id');
        $from = $request->input('from');
        $till = $request->input('till');
        $invoices = $this->advanceSearch($name, $invoice_no, $currency, $status, $from, $till);

         // Apply filtering based on search parameters
        // foreach ($this->searchParams as $key => $value) {
        //     if ($value !== null && $value !== '') {
        //         switch ($key) {
        //             case 'from':
        //                 $invoices->whereDate('date', '>=', date('Y-m-d', strtotime($value)));
        //                 break;
        //             case 'till':
        //                 $invoices->whereDate('date', '<=', date('Y-m-d', strtotime($value)));
        //                 break;
        //             case 'invoice_no':
        //                 $invoices->where('number', $value);
        //                 break;
        //             case 'status':
        //                 $invoices->where('status', $value);
        //                 break;
        //             case 'currency_id':
        //                 $invoices->where('currency', $value);
        //                 break;
        //                  $invoices->where($key, $value);
        //                 break;
        //         }
        //     }
        // }
        
        $invoices->orderBy('date', 'desc');

        $filteredInvoices = $invoices->get()->map(function ($invoice) {
            $invoiceData = [];
            foreach ($this->selectedColumns as $column) {
                switch ($column) {
                    case 'user_id':
                        $user = $invoice->user;
                        $invoiceData['name'] = $user ? $user->first_name . ' ' . $user->last_name : null;
                        break;
                    case 'email':
                        $invoiceData['email'] = $user ? $user->email : null;
                        break;
                    case 'mobile':
                        $invoiceData['mobile'] = $user ? '+' . $user->mobile_code . ' ' . $user->mobile : null;
                        break;
                    case 'country':
                        $invoiceData['country'] = $user ? $user->country : null;
                        break;
                    case 'grand_total':
                        $invoiceData['total'] = currencyFormat($invoice->grand_total, $code = $invoice->currency);
                        break;
                    case 'product':
                        $item = InvoiceItem::where('invoice_id', $invoice->id)->first();
                        $invoiceData['product'] = $item ? $item->product_name : null;
                        break;
                    case 'date':
                        $invoiceData['date'] = \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d');
                        break;
                    case 'status':
                        $invoiceData['status'] = $this->getStatus($invoice->status);;
                        break;
                    default:
                        $invoiceData[$column] = $invoice->$column;
                }
            }
            return $invoiceData;
        });


        $invoicesData = $filteredInvoices;

        $export = new InvoiceExport($this->selectedColumns, $invoicesData);
        $id = User::where('email', $this->email)->value('id');
        $user = User::find($id);
        $timestamp = now()->format('Ymd_His');
        $fileName = 'invoices_' . $id . '_' . $timestamp . '.xlsx';
        $filePath = storage_path('app/public/export/' . $fileName);
        Excel::store($export, 'public/export/' . $fileName);

        $exportDetail = ExportDetail::create([
            'user_id' => $id,
            'file_path' => storage_path('app/public/export/' . $fileName),
            'file' => $fileName,
            'name' => 'invoices',
        ]);

        $settings = new \App\Model\Common\Setting();
        $setting = $settings::find(1);
        $from = $setting->email;
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $downloadLink = route('download.exported.file', ['id' => $exportDetail->id]);
        $emailContent = 'Hello ' . $user->first_name . ' ' . $user->last_name . ',' .
            '<br><br>Invoice report is successfully generated and ready for download.' .
            '<br><br>Download link: <a href="' . $downloadLink . '">' . $downloadLink . '</a>' .
            '<br><br>Please note this link will be expired in 6 hours.' .
            '<br><br>Kind regards,<br>' . $user->first_name;

        $mail->SendEmail($from, $this->email, $emailContent, 'Invoice report available for download');
    }
    public function getStatus($status) {
    switch ($status) {
        case 'Pending':
            return 'unpaid';
        case 'Success':
            return 'paid';
        case 'Renewed':
            return 'renewed';
        default:
            return 'partially paid';
    }
}

}
