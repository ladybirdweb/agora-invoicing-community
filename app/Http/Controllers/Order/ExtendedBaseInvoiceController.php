<?php

namespace App\Http\Controllers\Order;

use Exception;
use Illuminate\Http\Request;
use App\Model\Order\Payment;
use App\Http\Controllers\Controller;

class ExtendedBaseInvoiceController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
        $this->middleware('admin', ['except' => ['pdf']]);
    }

    public function newPayment(Request $request)
    {
    	$clientid= $request->input('clientid');
        return view('themes.default1.invoice.newpayment',compact('clientid'));
    }

    public function postNewPayment($clientid,Request $request)
    {
    	$this->validate($request,[
           'payment_date' =>'required',
           'payment_method'=>'required',
           'amount'=>'required',
    	]);
    	try{
    		$payment = new Payment();
    		$payment->payment_status= 'success';
    		$payment->user_id = $clientid;
    		$payment->invoice_id = '--';
    		$paymentReceived = $payment->fill($request->all())->save();
    		return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
         }catch (Exception $ex){
         	dd($ex);
         return redirect()->back()->with('fails', $ex->getMessage());
      } 
    }
}
