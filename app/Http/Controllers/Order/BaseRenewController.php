<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use Exception;
use Illuminate\Http\Request;

class BaseRenewController extends Controller
{
    public function invoiceBySubscriptionId($id, $planid, $cost)
    {
        try {
            $sub = Subscription::find($id);
            $order_id = $sub->order_id;
           
            return $this->getInvoiceByOrderId($order_id, $planid, $cost);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function getInvoiceByOrderId($orderid, $planid, $cost)
    {
        try {
            $order = Order::find($orderid);
            $invoice_item_id = $order->invoice_item_id;
            $invoice_id = $order->invoice_id;
            $invoice = Invoice::find($invoice_id);
            if ($invoice_item_id == 0) {
                $invoice_item_id = $invoice->invoiceItem()->first()->id;
            }
            $item = InvoiceItem::find($invoice_item_id);
            $product = $this->getProductByName($item->product_name);
            //dd($product);
            $user = $this->getUserById($order->client);
            if (!$user) {
                throw new Exception('User has removed from database');
            }
            if (!$product) {
                throw new Exception('Product has removed from database');
            }

            return $this->generateInvoice($product, $user, $orderid, $planid, $cost, $code = '');
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function getProductByName($name)
    {
        try {
            $product = Product::where('name', $name)->first();
            if ($product) {
                return $product;
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function getCost(Request $request)
    {
        try {
            $planid = $request->input('plan');
            $userid = $request->input('user');

            return $this->planCost($planid, $userid);
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function planCost($planid, $userid)
    {
        try {
            $currency = $this->getUserCurrencyById($userid);
            $plan = Plan::find($planid);
            $price = $plan->planPrice()->where('currency', $currency)->first()->renew_price;

            return $price;
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function generateInvoice($product, $user, $orderid, $planid, $cost, $code = '')
    {
        try {
            $controller = new InvoiceController();
            $currency = \Auth::user()->currency;
            if ($code != '') {
                $product_cost = $controller->checkCode($code, $product->id, $currency);
            }
            if ($cost != '') {
                $product_cost = $this->planCost($planid, $user->id);
            }
            $cost = $this->tax($product, $product_cost, $user->id);
            $currency = $this->getUserCurrencyById($user->id);
            $number = rand(11111111, 99999999);
            $date = \Carbon\Carbon::now();
            $invoice = Invoice::create([
                'user_id'     => $user->id,
                'number'      => $number,
                'date'        => $date,
                'grand_total' => $cost,
                'currency'    => $currency,
                'status'      => 'pending',
            ]);
            $this->createOrderInvoiceRelation($orderid, $invoice->id);
            $items = $controller->createInvoiceItemsByAdmin($invoice->id, $product->id,
             $code, $product_cost, $currency, $qty = 1);

            return $items;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
