<?php

namespace App\Http\Controllers\Common;

use App\Model\Common\Template;
use App\Model\Mailjob\ExpiryMailDay;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Product\Subscription;
use App\User;
use App\ApiKey;
use App\Plugins\Stripe\Model\StripePayment;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Carbon\Carbon;
use App\Auto_renewal;
use Razorpay\Api\Api;

class CronController extends BaseCronController
{
    protected $subscription;

    protected $order;

    protected $user;

    protected $template;

    protected $invoice;

    public function __construct()
    {
        $subscription = new Subscription();
        $this->sub = $subscription;

        $order = new Order();
        $this->order = $order;

        $user = new User();
        $this->user = $user;

        $template = new Template();
        $this->template = $template;

        $invoice = new Invoice();
        $this->invoice = $invoice;
    }

    public function getExpiredInfoByOrderId($orderid)
    {
        $yesterday = new Carbon('today');
        $sub = $this->sub
                ->where('order_id', $orderid)
                ->where('update_ends_at', '!=', '0000-00-00 00:00:00')
                ->whereNotNull('update_ends_at')
                ->where('update_ends_at', '<', $yesterday)
                ->first();

        return $sub;
    }

    public function getAllDaysExpiryUsers($day)
    {
        $sub = $this->getAllDaysExpiryInfo($day);
        //dd($sub->get());
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $value;
            }
        }

        return $users;
    }

    public function get15DaysExpiryUsers()
    {
        $sub = $this->get15DaysExpiryInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $value;
            }
        }

        return $users;
    }

    public function getOneDayExpiryUsers()
    {
        $sub = $this->getOneDayExpiryInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $value;
            }
        }

        return $users;
    }

    public function getOnDayExpiryUsers()
    {
        $sub = $this->getOnDayExpiryInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $value;
            }
        }

        return $users;
    }

    public function getExpiredUsers()
    {
        $sub = $this->getExpiredInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $value;
            }
        }

        return $users;
    }

    public function get30DaysOrders()
    {
        $users = [];
        $users = $this->get30DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }

        return $users;
    }

    public function get15DaysOrders()
    {
        $users = [];
        $users = $this->get15DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }

        return $users;
    }

    public function get1DaysOrders()
    {
        $users = [];
        $users = $this->getOneDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }

        return $users;
    }

    public function get0DaysOrders()
    {
        $users = [];
        $users = $this->getOnDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }

        return $users;
    }

    public function getPlus1Orders()
    {
        $users = [];
        $users = $this->getExpiredUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }

        return $users;
    }

    public function get15DaysSubscription()
    {
        $users = [];
        $users = $this->get15DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }

        return $users;
    }

    public function get1DaysSubscription()
    {
        $users = [];
        $users = $this->getOneDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }

        return $users;
    }

    public function get0DaysSubscription()
    {
        $users = [];
        $users = $this->getOnDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }

        return $users;
    }

    public function getPlus1Subscription()
    {
        $users = [];
        $users = $this->getExpiredUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }

        return $users;
    }

    public function getUsers()
    {
        $users = [];
        if (count($this->get30DaysUsers())) {
            array_push($users, $this->get30DaysUsers());
        }
        if (count($this->get15DaysUsers())) {
            array_push($users, $this->get15DaysUsers());
        }
        if (count($this->get1DaysUsers())) {
            array_push($users, $this->get1DaysUsers());
        }
        if (count($this->get0DaysUsers())) {
            array_push($users, $this->get0DaysUsers());
        }
        if (count($this->getPlus1Users())) {
            array_push($users, $this->getPlus1Users());
        }

        return $users;
    }

    public function eachSubscription()
    {
        $allDays = ExpiryMailDay::pluck('days')->toArray();
        $sub = $this->getSubscriptions($allDays);
        foreach ($sub as $value) {
            $userid = $value->user_id;
            $user = $this->getUserById($userid);
            $end = $value->update_ends_at;
            $order = $this->getOrderById($value->order_id);
            $invoice = $this->getInvoiceByOrderId($value->order_id);
            $item = $this->getInvoiceItemByInvoiceId($invoice->id);
            $product = $item->product_name;
            if (emailSendingStatus()) {
                $this->mail($user, $end, $product, $order, $value->id);
            }
        }
    }
    public function autoRenewal()
    {
        $subscriptions_detail = $this->getOnDayExpiryInfo()->get();
        foreach($subscriptions_detail as $subscription)
        {
            $userid = $subscription->user_id;
            $end = $subscription->update_ends_at;
            $order = $this->getOrderById($subscription->order_id);
            $invoice = $this->getInvoiceByOrderId($subscription->order_id);
            $item = $this->getInvoiceItemByInvoiceId($invoice->id);
            $product = $item->product_name;
            $currency = $invoice->currency;

            //Card details
            $usercard_details = Auto_renewal::where('user_id',$userid)->where('invoice_number',$invoice->number)->first();


            $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
            $stripe = Stripe::make($stripeSecretKey);


            $amount = $usercard_details->amount;

            $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
            $stripe = Stripe::make($stripeSecretKey);
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $usercard_details->card_no,
                    'exp_month' => $usercard_details->exp_month,
                    'exp_year' => $usercard_details->exp_year,
                    'cvc' => $usercard_details->cvv,
                ],
            ]);
            // if (! isset($token['id'])) {
            //     \Session::put('error', 'The Stripe Token was not generated correctly');

            //     return redirect()->route('stripform');
            // }
            $user = \DB::table('users')->where('id',$userid)->first();
            $customer = $stripe->customers()->create([
                'name' => $user->first_name.' '.$user->last_name,
                'email' => $user->email,
                'address' => [
                    'line1' => $user->address,
                    'postal_code' => $user->zip,
                    'city' => $user->town,
                    'state' => $user->state,
                    'country' => $user->country,
                ],
            ]);
            $stripeCustomerId = $customer['id'];
            $currency = strtolower($invoice->currency);
            $card = $stripe->cards()->create($stripeCustomerId, $token['id']);
            $charge = $stripe->charges()->create([
                'customer' => $customer['id'],
                'currency' => $currency,
                'amount' => $amount,
                'description' => 'Add in wallet',
            ]);
            if ($charge['status'] == 'succeeded') {
                //Change order Status as Success if payment is Successful
                $stateCode = $user->state;
                // $cont = new \App\Http\Controllers\RazorpayController();
                $state = $this->getState($user,$stateCode);
                $currency = \DB::table('currencies')->where('code', $currency)->pluck('symbol')->first();

                $control = new \App\Http\Controllers\Order\RenewController();
                //After Regular Payment
                if ($control->checkRenew() === false) {
                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                    $checkout_controller->checkoutAction($invoice);

                    $view = $cont->getViewMessageAfterPayment($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                } else {
                    //Afer Renew
                    $control->successRenew($invoice);
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice);
                    if ($invoice->grand_total && emailSendingStatus()) {
                        $this->sendPaymentSuccessMailtoAdmin($invoice->currency, $invoice->grand_total, \Auth::user(), $invoice->invoiceItem()->first()->product_name);
                    }
                    $view = $cont->getViewMessageAfterRenew($invoice, $state, $currency);
                    dd($view);
                    $status = $view['status'];
                    $message = $view['message'];
                }
                \Session::forget('items');
                \Session::forget('code');
                \Session::forget('codevalue');
                \Session::forget('totalToBePaid');
                \Session::forget('invoice');
                \Session::forget('cart_currency');
                \Cart::removeCartCondition('Processing fee');

                return redirect('checkout')->with($status, $message);
            } else {
                return redirect('checkout')->with('fails', 'Your Payment was declined. Please try making payment with other gateway');
            }


            // $key_id = 'rzp_test_CB4oJRv7dNBBC8';
            // $secret = 'Mc39d5kxbFYecEbWniJER5E0';
            // $api = new Api($key_id, $secret);
            // $plan = $api->plan->create(array('period' => 'yearly', 'interval' => 1, 'item' => array('name' => $product, 'description' => 'Description for the yearly 1 plan', 'amount' => $price, 'currency' => $currency),'notes'=> array('key1'=> 'value3','key2'=> 'value2')));
            // $planid = $plan->id;
            // $mytime = Carbon::now();
            // $demo = $api->subscription->create(array('plan_id' => $planid, 'customer_notify' => 1,'quantity'=>1, 'total_count' => 1, 'start_at' => $mytime, 'addons' => array(array('item' => array('name' => $product, 'amount' => $price, 'currency' => $currency))),'notes'=> array('key1'=> 'value3','key2'=> 'value2')));
        }

        }

    public function getState($user,$stateCode)
    {
        if ($user->country != 'IN') {
            $state = State::where('state_subdivision_code', $stateCode)->pluck('state_subdivision_name')->first();
        } else {
            $state = TaxByState::where('state_code', $user->state)->pluck('state')->first();
        }

        return $state;
    }
}
