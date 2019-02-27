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

    /**
     * generate Invoice and Invoice Item after Increasing the subscription date from Admin Panel.
     *
     * @param int $orderid The Order ID
     * @param int $planid  The Plan Id related t the Subscription
     * @param int $cost    The Renew cost for for the Paln
     */
    public function getInvoiceByOrderId(int $orderid, int $planid, $cost)
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
            $product = $this->getProductByName($item->product_name, $order);
            $user = $this->getUserById($order->client);
            if (!$user) {
                throw new Exception('User has removed from database');
            }
            if (!$product) {
                throw new Exception('Product has removed from database');
            }

            return $this->generateInvoice($product, $user, $orderid, $planid, $cost, $code = '', $item->agents);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function getProductByName($name, $order = '')
    {
        try {
            $product = Product::where('name', $name)->first();
            if ($product) {
                return $product;
            } else {
                $product = Product::where('id', $order->product)->first();

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

    public function generateInvoice($product, $user, $orderid, $planid, $cost, $code = '', $agents = '')
    {
        try {
            $controller = new InvoiceController();
            $currency = $user->currency;
            if ($code != '') {
                $product_cost = $controller->checkCode($code, $product->id, $currency);
            }
            $renewalPrice = $cost; //Get Renewal Price before calculating tax over it to save as regular price of product
            $cost = $this->tax($product, $renewalPrice, $user->id);
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
             $code, $renewalPrice, $currency, $qty = 1, $agents);

            return $items;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
