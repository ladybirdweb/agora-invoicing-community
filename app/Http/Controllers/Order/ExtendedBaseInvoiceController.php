<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use Exception;
use Illuminate\Http\Request;

class ExtendedBaseInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except' => ['pdf']]);
    }

    public function newPayment(Request $request)
    {
        try {
            $clientid = $request->input('clientid');
            $invoice = new Invoice();
            $order = new Order();
            $invoices = $invoice->where('user_id', $clientid)->where('status', '=', 'pending')->orderBy('created_at', 'desc')->get();
            $cltCont = new \App\Http\Controllers\User\ClientController();
            $invoiceSum = $cltCont->getTotalInvoice($invoices);
            $amountReceived = $cltCont->getAmountPaid($clientid);
            $pendingAmount = $invoiceSum - $amountReceived;
            $client = $this->user->where('id', $clientid)->first();
            $currency = $client->currency;
            $orders = $order->where('client', $clientid)->get();

            return view('themes.default1.invoice.newpayment', compact('clientid', 'client', 'invoices',  'orders',
                  'invoiceSum', 'amountReceived', 'pendingAmount', 'currency'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postNewPayment($clientid, Request $request)
    {
        $this->validate($request, [
           'payment_date'  => 'required',
           'payment_method'=> 'required',
           'amount'        => 'required',
        ]);

        try {
            $payment = new Payment();
            $payment->payment_status = 'success';
            $payment->user_id = $clientid;
            $payment->invoice_id = '--';
            $paymentReceived = $payment->fill($request->all())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($invoiceid, Request $request)
    {
        $invoice = Invoice::where('id',$invoiceid)->first();
        // $payment = Payment::where()
        return view('themes.default1.invoice.editInvoice',compact('userid','invoiceid','invoice'));
    }

    public function postEdit($invoiceid, Request $request)
    {
       $this->validate($request,[
        'total' => 'required',
        'status'=> 'required', 
        ]);
         try{
        $total = $request->input('total');
        $status = $request->input('status');
        $invoice = Invoice::where('id',$invoiceid)->update(['grand_total'=>$total,'status'=>$status]);
        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
     }catch (\Exception $ex)
     {
       return redirect()->back()->with('fails', $ex->getMessage());
     }
       
    }
}
