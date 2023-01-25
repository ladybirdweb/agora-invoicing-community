<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Order\BaseOrderController;
use App\Http\Controllers\Tenancy\TenantController;
use App\Model\Common\FaveoCloud;
use App\Model\Common\StatusSetting;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Payment\TaxOption;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Auth;
use Crypt;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Lang;

class FreeTrailController extends Controller
{
    public $orderNo = null;

    public function __construct()
    {
        $this->middleware('auth');

        $this->invoice = new Invoice();

        $this->invoiceItem = new InvoiceItem();

        $this->order = new Order();

        $this->subscription = new Subscription();
    }

    /**
     * Get the auth user id
     * Check the first_time_login is not equal to zero in DB to correspaonding,if its not change into one.
     *
     * @return order
     */
    public function firstLoginAttempt(Request $request)
    {
        try {
            if (! Auth::check()) {
                return redirect('login')->back()->with('fails', \Lang::get('message.free-login'));
            }

            $userId = $request->get('id');
            if (Auth::user()->id == $userId) {
                $userLogin = User::find($userId);
                if ($userLogin->first_time_login != 0) {
                    return errorResponse(Lang::get('message.false'), 400);
                }
                User::where('id', $userId)->update(['first_time_login' => 1]);

                $this->generateFreetrailInvoice();

                $this->createFreetrailInvoiceItems();

                $this->executeFreetrailOrder();

                $isSuccess = (new TenantController(new Client, new FaveoCloud()))->createTenant(new Request(['orderNo' => $this->orderNo, 'domain' => $request->domain]));
                if ($isSuccess['status'] == 'false') {
                    return $isSuccess;
                }
                User::where('id', $userId)->update(['first_time_login' => 1]);

                return $isSuccess;
            }
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            throw new \Exception('Can not Generate Freetrial Cloud instance');
        }
    }

    /**
     * Generate invoice from client panel for free trial.
     *
     * @throws \Exception
     */
    private function generateFreetrailInvoice()
    {
        try {
            $tax_rule = new TaxOption();
            $rule = $tax_rule->findOrFail(1);
            $rounding = $rule->rounding;
            $user_id = \Auth::user()->id;
            $grand_total = $rounding ? round(\Cart::getTotal()) : \Cart::getTotal();
            $number = rand(11111111, 99999999);
            $date = \Carbon\Carbon::now();
            $currency = \Session::has('cart_currency') ? \Session::get('cart_currency') : getCurrencyForClient(\Auth::user()->country);
            $invoice = $this->invoice->create(['user_id' => $user_id, 'number' => $number, 'date' => $date, 'grand_total' => $grand_total, 'status' => 'pending',
                'currency' => $currency, ]);

            return $invoice;
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            throw new \Exception('Can not Generate Invoice');
        }
    }

    /**
     * Generate invoice items for free trial.
     *
     * @throws \Exception
     */
    private function createFreetrailInvoiceItems()
    {
        try {
            $product = Product::with('planRelation')->find('117');
            if ($product) {
                $plan_id = $product->planRelation()->pluck('id');

                $cart = \Cart::getContent();
                $userId = \Auth::user()->id;
                $invoice = $this->invoice->where('user_id', $userId)->first();
                $invoiceid = $invoice->id;
                $invoiceItem = $this->invoiceItem->create([
                    'invoice_id' => $invoiceid,
                    'product_name' => $product->name,
                    'regular_price' => planPrice::where('plan_id', $plan_id)
                        ->where('currency', \Auth::user()->currency)->pluck('add_price'),
                    'quantity' => 1,
                    'tax_name' => 'null',
                    'tax_percentage' => $product->planRelation()->pluck('allow_tax'),
                    'subtotal' => 0,
                    'domain' => '',
                    'plan_id' => 0,
                    'agents' => planPrice::where('plan_id', $plan_id)
                        ->where('currency', \Auth::user()->currency)->pluck('no_of_agents'),
                ]);

                return $invoiceItem;
            } else {
                throw new \Exception('Can not Find the Product');
            }
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            throw new \Exception('Can not Generate Invoice items');
        }
    }

    /**
     * Generate Order from client panel for free trial.
     *
     * @throws \Exception
     */
    private function executeFreetrailOrder()
    {
        try {
            $order_status = 'executed';
            $userId = \Auth::user()->id;
            $invoice = $this->invoice->where('user_id', $userId)->first();
            $invoiceid = $invoice->id;
            $item = $this->invoiceItem->where('invoice_id', $invoiceid)->first();
            $user_id = $this->invoice->find($invoiceid)->user_id;
            $items = $this->getIfFreetrailItemPresent($item, $invoiceid, $user_id, $order_status);

            return 'success';
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            throw new \Exception('Can not Generate order');
        }
    }

    /**
     * Create order for free trial if the invoice items present in the DB.
     *
     * @throws \Exception
     */
    private function getIfFreetrailItemPresent($item, $invoiceid, $user_id, $order_status)
    {
        try {
            $product = Product::where('name', $item->product_name)->value('id');
            $version = Product::where('name', $item->product_name)->first()->version;
            if ($version == null) {
                //Get Version from Product Upload Table
                $version = $this->product_upload->where('product_id', $product)->pluck('version')->first();
            }
            $serial_key = $this->generateFreetrailSerialKey($product, $item->agents); //Send Product Id and Agents to generate Serial Key
            $domain = $item->domain;
            //$plan_id = $this->plan($item->id);
            $plan_id = Plan::where('product', $product)
                ->value('id');

            $order = $this->order->create([

                'invoice_id' => $invoiceid,
                'invoice_item_id' => $item->id,
                'client' => $user_id,
                'order_status' => $order_status,
                'serial_key' => Crypt::encrypt($serial_key),
                'product' => $product,
                'price_override' => $item->subtotal,
                'qty' => $item->quantity,
                'domain' => $domain,
                'number' => $this->generateFreetrailNumber(),
            ]);
            $this->orderNo = $order->number;
            $baseorder = new BaseOrderController();
            $baseorder->addOrderInvoiceRelation($invoiceid, $order->id);

            if ($plan_id) {
                $baseorder->addSubscription($order->id, $plan_id, $version, $product, $serial_key);
            }
            $mailchimpStatus = StatusSetting::pluck('mailchimp_status')->first();
            if ($mailchimpStatus) {
                $baseorder->addtoMailchimp($product, $user_id, $item);
            }
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            throw new \Exception('Can not Generate free trial order');
        }
    }

    /**
     * Generate serial key for free trial.
     *
     * @throws \Exception
     */
    private function generateFreetrailSerialKey(int $productid, $agents)
    {
        try {
            $len = strlen($agents);
            switch ($len) {//Get Last Four digits based on No.Of Agents

                case '1':
                    $lastFour = '000'.$agents;
                    break;
                case '2':
                    $lastFour = '00'.$agents;
                    break;
                case '3':
                    $lastFour = '0'.$agents;
                    break;
                case '4':
                    $lastFour = $agents;
                    break;
                default:
                    $lastFour = '0000';
            }
            $str = strtoupper(str_random(12));
            $licCode = $str.$lastFour;

            return $licCode;
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            throw new \Exception('Can not Generate Free trail serialkey');
        }
    }

    private function generateFreetrailNumber()
    {
        return rand('10000000', '99999999');
    }
}
