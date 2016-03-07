<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Model\Common\Setting;
use App\Model\Common\Template;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Payment;
use App\User;
use Illuminate\Http\Request;
use Input;

class InvoiceController extends Controller
{
    public $invoice;
    public $invoiceItem;
    public $user;
    public $template;
    public $setting;
    public $payment;

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('admin');

        $invoice = new Invoice();
        $this->invoice = $invoice;

        $invoiceItem = new InvoiceItem();
        $this->invoiceItem = $invoiceItem;

        $user = new User();
        $this->user = $user;

        $template = new Template();
        $this->template = $template;

        $seting = new Setting();
        $this->setting = $seting;

        $payment = new Payment();
        $this->payment = $payment;
    }

    public function index()
    {
        try {
            //dd($this->invoice->get());
            return view('themes.default1.invoice.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetInvoices()
    {
        //dd($this->invoice->get());
        return \Datatable::collection($this->invoice->get())
                        ->addColumn('client', function ($model) {
                            $first = $this->user->where('id', $model->user_id)->first()->first_name;
                            $last = $this->user->where('id', $model->user_id)->first()->last_name;
                            $id = $this->user->where('id', $model->user_id)->first()->id;

                            return '<a href='.url('clients/'.$id).'>'.ucfirst($first).' '.ucfirst($last).'</a>';
                        })
                        ->showColumns('number', 'date', 'grand_total')
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('invoices/'.$model->id)." class='btn btn-sm btn-primary'>View</a>";
                        })
                        ->searchColumns('date')
                        ->orderColumns('date')
                        ->make();
    }

    public function show(Request $request)
    {
        try {
            //            $invoiceTemplateId = $this->setting->where('id',1)->first()->invoice_template;
//            $template = $this->template->where('id',$invoiceTemplateId)->first()->data;
            $id = $request->input('invoiceid');
            $invoice = $this->invoice->where('id', $id)->first();
            $invoiceItems = $this->invoiceItem->where('invoice_id', $id)->get();
            $user = $this->user->find($invoice->user_id);
            //dd($user);
//            $number = $invoice->number;
//            $address = $this->user->where('id', $invoice->user_id)->first()->address;
//            $name = $this->user->where('id', $invoice->user_id)->first()->name;
//            $grandtotal = $invoice->grand_total;
//            $products = '';
//            $quantities = '';
//            $taxes= '';
//            $price='';
//            $subtotal='';
//
//            foreach($invoiceItems as $item){
//                $products .='<td>'.$item->product_name.'</td>' ;
//                $quantities.= '<td>'.$item->quantity.'</td>' ;
//                $price.= '<td>'.$item->tax_name.'</td>' ;
//                $taxes.= '<td>'.$item->regular_price.'</td>' ;
//                $subtotal.= '<td>'.$item->subtotal.'</td>' ;
//            }
//
//
//            $array1 = ['{{invoice_number}}', '{{address}}', '{{name}}', '{{products}}', '{{quantities}}', '{{taxes}}', '{{price}}', '{{subtotal}}','{{grandtotal}}'];
//            $array2 = [$number,$address,$name,$products,$quantities,$taxes,$price,$subtotal,$grandtotal];
//
//            $template = str_replace($array1, $array2, $template);
//
            //dd($template);
            //echo $template;

            return view('themes.default1.invoice.show', compact('invoiceItems', 'invoice', 'user'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * not in use case.
     *
     * @param Request $request
     *
     * @return type
     */
    public function generateById(Request $request)
    {
        try {
            $clientid = $request->input('clientid');
            $user = new User();
            $user = $user->where('id', $clientid)->first();

            return view('themes.default1.invoice.generate', compact('user'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Generate invoice.
     *
     * @throws \Exception
     */
    public function GenerateInvoice()
    {
        try {
            $user_id = \Auth::user()->id;
            $number = rand(11111111, 99999999);
            $date = \Carbon\Carbon::now();
            $grand_total = \Cart::getSubTotal();

            $invoice = $this->invoice->create(['user_id' => $user_id, 'number' => $number, 'date' => $date, 'grand_total' => $grand_total]);
            foreach (\Cart::getContent() as $cart) {
                $this->CreateInvoiceItems($invoice->id, $cart);
            }

            return $invoice;
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception('Can not Generate Invoice');
        }
    }

    public function CreateInvoiceItems($invoiceid, $cart)
    {
        try {
            $product_name = $cart->name;
            $regular_price = $cart->price;
            $quantity = $cart->quantity;
            $subtotal = $cart->getPriceSumWithConditions();

            $tax_name = '';
            $tax_percentage = '';

            foreach ($cart->attributes['tax'] as $tax) {
                //dd($tax['name']);
                $tax_name .= $tax['name'].',';
                $tax_percentage .= $tax['rate'].',';
            }

            //dd($tax_name);

            $invoiceItem = $this->invoiceItem->create(['invoice_id' => $invoiceid, 'product_name' => $product_name, 'regular_price' => $regular_price, 'quantity' => $quantity, 'tax_name' => $tax_name, 'tax_percentage' => $tax_percentage, 'subtotal' => $subtotal]);
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception('Can not create Invoice Items');
        }
    }

    public function doPayment($payment_method, $invoiceid, $amount, $parent_id = '', $userid = '', $payment_status = 'pending')
    {
        try {
            if ($userid == '') {
                $userid = \Auth::user()->id;
            }
            $this->payment->create([
                'parent_id'      => $parent_id,
                'invoice_id'     => $invoiceid,
                'user_id'        => $userid,
                'amount'         => $amount,
                'payment_method' => $payment_method,
                'payment_status' => $payment_status,

            ]);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
