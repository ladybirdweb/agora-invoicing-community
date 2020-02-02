<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Front\CartController;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Payment\Currency;
use App\Model\Payment\Promotion;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxOption;
use App\User;
use Bugsnag;

class TaxRatesAndCodeExpiryController extends BaseInvoiceController
{
    /**
     * Get tax when enabled.
     */
    public function getTaxWhenEnable($productid, $taxs, $userid)
    {
        $rate = $this->getRate($productid, $taxs, $userid);
        $taxs = ([$rate['taxs']['0']['name'], $rate['taxs']['0']['rate']]);

        return $taxs;
    }

    /**
     * GeT Total Rate.
     */
    public function getTotalRate($taxClassId, $productid, $taxs)
    {
        $cartController = new CartController();
        $taxs = $cartController->getTaxByPriority($taxClassId);
        $value = $cartController->getValueForOthers($productid, $taxClassId, $taxs);
        if ($value == 0) {
            $status = 0;
        }
        $rate = $value;

        return ['rate'=>$rate, 'taxes'=>$taxs];
    }

    /**
     * Get Grandtotal.
     **/
    public function getGrandTotal($code, $total, $cost, $productid, $currency)
    {
        $grand_total = $total;
        if ($code) {
            $grand_total = $this->checkCode($code, $productid, $currency);
        } else {
            if ($total != 0) {
                $grand_total = $total;
            }
        }

        return $grand_total;
    }

    /**
     * Get Message on Invoice Generation.
     **/
    public function getMessage($items, $user_id)
    {
        if ($items) {
            // $this->sendmailClientAgent($user_id, $items->invoice_id);
            $result = ['success' => \Lang::get('message.invoice-generated-successfully')];
        } else {
            $result = ['fails' => \Lang::get('message.can-not-generate-invoice')];
        }

        return $result;
    }

    /**
     * get Subtotal.
     */
    public function getSubtotal($user_currency, $cart)
    {
        if ($user_currency == 'INR') {
            $subtotal = \App\Http\Controllers\Front\CartController::rounding($cart->getPriceSumWithConditions());
        } else {
            $subtotal = \App\Http\Controllers\Front\CartController::rounding($cart->getPriceSumWithConditions());
        }

        return $subtotal;
    }

    public function calculateTotal($rate, $total)
    {
        try {
            $total = intval($total);
            $rates = explode(',', $rate);
            $rule = new TaxOption();
            $rule = $rule->findOrFail(1);
            if ($rule->inclusive == 0) {
                foreach ($rates as $rate1) {
                    if ($rate1 != '') {
                        $rateTotal = str_replace('%', '', $rate1);
                        $total += $total * ($rateTotal / 100);
                    }
                }
            }

            return intval(round($total));
        } catch (\Exception $ex) {
            app('log')->warning($ex->getMessage());
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function checkExecution($invoiceid)
    {
        try {
            $response = false;
            $invoice = Invoice::find($invoiceid);

            $order = Order::where('invoice_id', $invoiceid);
            $order_invoice_relation = $invoice->orderRelation()->first();

            if ($order_invoice_relation) {
                $response = true;
            } elseif ($order->get()->count() > 0) {
                $response = true;
            }

            return $response;
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function invoiceContent($invoiceid)
    {
        $invoice = $this->invoice->find($invoiceid);
        $items = $invoice->invoiceItem()->get();
        $content = '';
        if ($items->count() > 0) {
            foreach ($items as $item) {
                $content .= '<tr>'.
                        '<td style="border-bottom: 1px solid#ccc; color: #333; 
                        font-family: Arial,sans-serif; font-size: 14px; line-height: 20px;
                         padding: 15px 8px;" valign="top">'.$invoice->number.'</td>'.
                        '<td style="border-bottom: 1px solid#ccc; color: #333; 
                        font-family: Arial,sans-serif; font-size: 14px; line-height: 20px;
                         padding: 15px 8px;" valign="top">'.$item->product_name.'</td>'.
                        '<td style="border-bottom: 1px solid#ccc; color: #333; 
                        font-family: Arial,sans-serif; font-size: 14px; line-height: 20px;
                         padding: 15px 8px;" valign="top">'.$this->currency($invoiceid).' '
                         .$item->subtotal.'</td>'.
                        '</tr>';
            }
        }

        return $content;
    }

    public function currency($invoiceid)
    {
        $invoice = Invoice::find($invoiceid);
        $currency_code = $invoice->currency;
        $cur = ' ';
        if ($invoice->grand_total == 0) {
            return $cur;
        }
        $currency = Currency::where('code', $currency_code)->first();
        if ($currency) {
            $cur = $currency->symbol;
            if (!$cur) {
                $cur = $currency->code;
            }
        }

        return $cur;
    }

    public function sendInvoiceMail($userid, $number, $total, $invoiceid)
    {

        //user
        $users = new User();
        $user = $users->find($userid);
        //check in the settings
        $settings = new \App\Model\Common\Setting();
        $setting = $settings->where('id', 1)->first();
        $invoiceurl = $this->invoiceUrl($invoiceid);
        //template
        $templates = new \App\Model\Common\Template();
        $temp_id = $setting->invoice;
        $template = $templates->where('id', $temp_id)->first();
        $from = $setting->email;
        $to = $user->email;
        $subject = $template->name;
        $data = $template->data;
        $replace = [
            'name'       => $user->first_name.' '.$user->last_name,
            'number'     => $number,
            'address'    => $user->address,
            'invoiceurl' => $invoiceurl,
            'content'    => $this->invoiceContent($invoiceid),
            'currency'   => $this->currency($invoiceid),
        ];
        $type = '';
        if ($template) {
            $type_id = $template->type;
            $temp_type = new \App\Model\Common\TemplateType();
            $type = $temp_type->where('id', $type_id)->first()->name;
        }
        //dd($type);
        $templateController = new \App\Http\Controllers\Common\TemplateController();
        $mail = $templateController->mailing($from, $to, $data, $subject, $replace, $type);

        return $mail;
    }

    public function invoiceUrl($invoiceid)
    {
        $url = url('my-invoice/'.$invoiceid);

        return $url;
    }

    public function paymentDeleleById($id)
    {
        try {
            $invoice_no = '';
            $payment = Payment::find($id);
            if ($payment) {
                $invoice_id = $payment->invoice_id;
                $invoice = Invoice::find($invoice_id);
                if ($invoice) {
                    $invoice_no = $invoice->number;
                }
                $payment->delete();
            } else {
                return redirect()->back()->with('fails', 'Can not delete');
            }

            return redirect()->back()->with('success', "Payment for invoice no: $invoice_no has Deleted Successfully");
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function paymentEditById($id)
    {
        try {
            $cltCont = new \App\Http\Controllers\User\ClientController();
            $payment = Payment::find($id);
            $clientid = $payment->user_id;
            $invoice = new Invoice();
            $order = new Order();
            $invoices = $invoice->where('user_id', $clientid)->where('status', '=', 'pending')
            ->orderBy('created_at', 'desc')->get();
            $cltCont = new \App\Http\Controllers\User\ClientController();
            $invoiceSum = $cltCont->getTotalInvoice($invoices);
            $amountReceived = $cltCont->getExtraAmt($clientid);
            $pendingAmount = $invoiceSum - $amountReceived;
            $client = $this->user->where('id', $clientid)->first();
            $currency = $client->currency;
            $symbol = Currency::where('code', $currency)->pluck('symbol')->first();
            $orders = $order->where('client', $clientid)->get();

            return view('themes.default1.invoice.editPayment',
                compact('amountReceived','clientid', 'client', 'invoices',  'orders',
                  'invoiceSum', 'amountReceived', 'pendingAmount', 'currency', 'symbol'));
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function getPromotionDetails($code)
    {
        $promo = Promotion::where('code', $code)->first();
        //check promotion code is valid
        if (!$promo) {
            throw new \Exception(\Lang::get('message.no-such-code'));
        }
        $relation = $promo->relation()->get();
        //check the relation between code and product
        if (count($relation) == 0) {
            throw new \Exception(\Lang::get('message.no-product-related-to-this-code'));
        }
        //check the usess
        $cont = new \App\Http\Controllers\Payment\PromotionController();
        $uses = $cont->checkNumberOfUses($code);
        //dd($uses);
        if ($uses != 'success') {
            throw new \Exception(\Lang::get('message.usage-of-code-completed'));
        }
        //check for the expiry date
        $expiry = $this->checkExpiry($code);
        if ($expiry != 'success') {
            throw new \Exception(\Lang::get('message.usage-of-code-expired'));
        }

        return $promo;
    }
}
