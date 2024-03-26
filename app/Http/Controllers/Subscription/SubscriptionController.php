<?php

namespace App\Http\Controllers\Subscription;

use App\ApiKey;
use App\Auto_renewal;
use App\Http\Controllers\Common\CronController;
use App\Http\Controllers\ConcretePostSubscriptionHandleController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Order\BaseRenewController;
use App\Http\Controllers\RazorpayController;
use App\Model\Common\Template;
use App\Model\Common\TemplateType;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\Plugins\Stripe\Controllers\SettingsController;
use App\User;
use Carbon\Carbon;
use DateTime;
use Razorpay\Api\Api;

class SubscriptionController extends Controller
{
    protected $subscription;

    protected $order;

    protected $user;

    protected $template;

    protected $invoice;

    protected $PostSubscriptionHandle;

    public function __construct(ConcretePostSubscriptionHandleController $PostSubscriptionHandle)
    {
        $subscription = new Subscription();
        $this->sub = $subscription;

        $plan = new Plan();
        $this->plan = $plan;

        $order = new Order();
        $this->order = $order;

        $user = new User();
        $this->user = $user;

        $template = new Template();
        $this->template = $template;

        $invoice = new Invoice();
        $this->invoice = $invoice;

        $payment = new Payment();
        $this->payment = $payment;

        $stripeController = new SettingsController();
        $this->stripeController = $stripeController;

        $this->PostSubscriptionHandle = $PostSubscriptionHandle;
    }

    public function getOnDayExpiryInfoSubs()
    {
        $yesterday = new Carbon('yesterday');
        $tomorrow = new Carbon('tomorrow');
        $daybefore = new Carbon('-1 days');
        $today = new Carbon('today');
        $sub = Subscription::whereNotNull('update_ends_at')
            ->where('is_subscribed', 1)
            ->whereBetween('update_ends_at', [$yesterday, $tomorrow])
            ->orwhereBetween('support_ends_at', [$yesterday, $tomorrow])
            ->orwhereBetween('update_ends_at', [$daybefore, $today]);

        return $sub;
    }

    public function getCreatedSubscription()
    {
        $today = now()->startOfDay();
        $twoDaysAgo = now()->subDays(2)->startOfDay();
        $sub = Subscription::where(function ($query) {
            $query->Where('rzp_subscription', '2')
                ->orWhere('autoRenew_status', '2');
        })
       ->whereBetween('update_ends_at', [$twoDaysAgo, $today]);

        return $sub;
    }

    public function autoRenewal()
    {
        ini_set('memory_limit', '-1');
        try {
            $createdSubscription = $this->getCreatedSubscription()->get();
            foreach ($createdSubscription as $sub) {
                $this->checkSubscriptionStatus($sub);
            }
            //Retrieve expired subscription details
            $subscriptions_detail = $this->getOnDayExpiryInfoSubs()->get();
            foreach ($subscriptions_detail as $subscription) {
                $userid = $subscription->user_id;
                $end = $subscription->update_ends_at;
                $cronController = new CronController();
                $order = $cronController->getOrderById($subscription->order_id);
                $oldinvoice = $cronController->getInvoiceByOrderId($subscription->order_id);
                $item = $cronController->getInvoiceItemByInvoiceId($oldinvoice->id);
                $product_details = Product::where('name', $item->product_name)->first();

                $plan = Plan::where('product', $product_details->id)->first('days');
                $oldcurrency = $oldinvoice->currency;

                $user = \DB::table('users')->where('id', $userid)->first();
                $stripe_payment_details = Auto_renewal::where('user_id', $userid)->where('order_id', $subscription->order_id)->where('payment_method', 'stripe')->latest()->first(['customer_id', 'payment_intent_id']);
                $planid = Plan::where('product', $product_details->id)->value('id');

                $subscription = Subscription::where('id', $subscription->id)->first();
                $productType = Product::find($subscription->product_id);
                $price = PlanPrice::where('plan_id', $subscription->plan_id)->where('currency', $oldcurrency)->value('renew_price');
                $cost = in_array($subscription->product_id, cloudPopupProducts()) ? $this->getPriceforCloud($order, $price) : PlanPrice::where('plan_id', $planid)->where('currency', $oldcurrency)->value('renew_price');

                if ($this->shouldCancelSubscription($product_details, $price)) {
                    $subscription->update(['is_subscribed' => 0]);
                }

                //Do not create invoices for invoices that are already unpaid
                if ($subscription->autoRenew_status != '2' && $subscription->rzp_subscription != '2') {
                    $renewController = new BaseRenewController();
                    $invoice = $renewController->generateInvoice($product_details, $user, $order->id, $plan->id, $cost, $code = '', $item->agents, $oldcurrency);
                    $cost = Invoice::where('id', $invoice->invoice_id)->value('grand_total');
                    $currency = Invoice::where('id', $invoice->invoice_id)->value('currency');
                    $unit_cost = $this->PostSubscriptionHandle->calculateUnitCost($currency, $cost);
                }

                //Check the invoice status for active subscription
                $this->validateInvoiceForActiveSubscriptionStatus($subscription, $invoice, $currency, $cost, $user, $order, $product_details);

                //Create subscription status enabled users
                $this->createSubscriptionsForEnabledUsers($stripe_payment_details, $product_details, $unit_cost, $currency, $plan, $subscription, $invoice, $order, $user, $cost, $end);
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function shouldCancelSubscription($productDetails, $price)
    {
        return $productDetails->type == '4' && $price == '0';
    }

    public function mailSendToActiveStripeSubscription($product_details, $unit_cost, $currency, $plan, $url, $user)
    {
        $contact = getContactData();
        //check in the settings
        $settings = new \App\Model\Common\Setting();
        $setting = $settings::find(1);

        $mail = new \App\Http\Controllers\Common\PhpMailController();
        //template
        $templates = new \App\Model\Common\Template();
        $temp_id = TemplateType::where('name', 'stripe_subscription_authentication')->value('id');

        $template = $templates->where('type', $temp_id)->first();
        $today = new DateTime();
        $today->modify('+1 day');
        $expiry_date = $today->format('d M Y');

        $replace = [
            'name' => ucfirst($user->first_name).' '.ucfirst($user->last_name),
            'product' => $product_details->name,
            'total' => currencyFormat($unit_cost, $code = $currency),
            'contact' => $contact['contact'],
            'logo' => $contact['logo'],
            'expiry_date' => $expiry_date,
            'reply_email' => $setting->company_email,
            'application_title' => $setting->title,
            'company_title' => $setting->company,
            'url' => $url,
        ];

        $type = '';
        if ($template) {
            $type_id = $template->type;
            $temp_type = new \App\Model\Common\TemplateType();
            $type = $temp_type->where('id', $type_id)->first()->name;
        }
        $mail->SendEmail($setting->email, $user->email, $template->data, $template->name, $replace, $type);
    }

    public function checkSubscriptionStatus($subscription)
    {
        try {
            if ($subscription) {
                $product_name = Product::where('id', $subscription->product_id)->value('name');
                $invoiceid = \DB::table('order_invoice_relations')->where('order_id', $subscription->order_id)->latest()->value('invoice_id');
                $invoiceItem = \DB::table('invoice_items')->where('invoice_id', $invoiceid)->where('product_name', $product_name)->first();
                $invoice = Invoice::where('id', $invoiceItem->invoice_id)->where('status', 'pending')->first();
                $order = Order::where('id', $subscription->order_id)->first();
                $cost = $invoice->grand_total;
                $user = User::find($subscription->user_id);
                //Check Razorpay subscription status
                if ($subscription->subscribe_id && $subscription->rzp_subscription == '2') {
                    $key_id = ApiKey::pluck('rzp_key')->first();
                    $secret = ApiKey::pluck('rzp_secret')->first();
                    $api = new Api($key_id, $secret);
                    $subscriptionStatus = $api->subscription->fetch($subscription->subscribe_id);

                    //Authenticated means subscription enableed by maunal payment so we are updating the suscription
                    if ($subscriptionStatus->status == 'authenticated') {
                        $subscription = Subscription::find($subscription->id);
                        $subscription->rzp_subscription = '3';
                        $subscription->save();

                        if ($invoice) {
                            $sub = $this->PostSubscriptionHandle->successRenew($invoiceItem, $subscription, $payment_method = 'Razorpay', $invoice->currency);
                            $this->PostSubscriptionHandle->postRazorpayPayment($invoiceItem, $payment_method = 'Razorpay');
                            $this->PostSubscriptionHandle->sendPaymentSuccessMail($sub, $invoice->currency, $cost, $user, $product_name, $order->number);
                            $this->PostSubscriptionHandle->PaymentSuccessMailtoAdmin($invoice, $cost, $user, $product_name, $template = null, $order, $payment = 'razorpay');
                        }
                    } else {
                        Subscription::where('id', $subscription->id)->update(['rzp_subscription' => '1']);
                    }
                } elseif ($subscription->subscribe_id && $subscription->autoRenew_status == '2') {
                    $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
                    $stripe = new \Stripe\StripeClient($stripeSecretKey);
                    \Stripe\Stripe::setApiKey($stripeSecretKey);
                    $subscriptionStatus = \Stripe\Subscription::retrieve($subscription->subscribe_id);
                    if ($subscriptionStatus->status == 'active') {
                        Subscription::where('id', $subscription->id)->update(['autoRenew_status' => '3']);
                        $sub = $this->PostSubscriptionHandle->successRenew($invoice, $subscription, $payment_method = 'stripe', $invoice->currency);
                        $pay = $this->PostSubscriptionHandle->postRazorpayPayment($invoice, $payment_method = 'stripe');
                        if ($cost && emailSendingStatus()) {
                            $this->PostSubscriptionHandle->sendPaymentSuccessMail($sub, $invoice->currency, $cost, $user, $product_name, $order->number);
                            $this->PostSubscriptionHandle->PaymentSuccessMailtoAdmin($invoice, $cost, $user, $product_name, $template = null, $order, $payment = 'stripe');
                        }
                    } else {
                        Subscription::where('id', $subscription->id)->update(['autoRenew_status' => '1']);
                    }
                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPriceforCloud($order, $price)
    {
        $numberofAgents = (int) ltrim(substr($order->serial_key, -4), '0');
        $finalPrice = $numberofAgents * $price;

        return $finalPrice;
    }

    private function validateInvoiceForActiveSubscriptionStatus($subscription, $invoice, $currency, $cost, $user, $order, $product_details)
    {
        if ($subscription->is_subscribed != '1') {
            return;
        }

        if ($subscription->is_subscribed == '1' && $subscription->autoRenew_status == '3') {
            $this->processStripeSubscription($subscription, $invoice, $currency, $cost, $user, $order, $product_details);
        } elseif ($subscription->is_subscribed == '1' && $subscription->rzp_subscription == '3') {
            $this->processRazorpaySubscription($subscription, $invoice, $currency, $cost, $user, $order, $product_details);
        }
    }

    private function processStripeSubscription($subscription, $invoice, $currency, $cost, $user, $order, $product_details)
    {
        $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
        $stripe = new \Stripe\StripeClient($stripeSecretKey);
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        $subscriptionStatus = \Stripe\Subscription::retrieve($subscription->subscribe_id);
        $latestInvoiceId = $subscriptionStatus->latest_invoice;
        $latestInvoice = \Stripe\Invoice::retrieve($latestInvoiceId);

        if ($latestInvoice->status != 'paid') {
            return;
        }

        $createdDate = Carbon::createFromTimestamp($latestInvoice->created)->startOfDay();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        if (! $createdDate->eq($today) && ! $createdDate->eq($yesterday)) {
            return;
        }

        $sub = $this->PostSubscriptionHandle->successRenew($invoice, $subscription, 'stripe', $currency);
        $this->PostSubscriptionHandle->postRazorpayPayment($invoice, 'stripe');

        if ($cost && emailSendingStatus()) {
            $this->PostSubscriptionHandle->sendPaymentSuccessMail($sub, $currency, $cost, $user, $product_details->name, $order->number);
            $this->PostSubscriptionHandle->PaymentSuccessMailtoAdmin($invoice, $cost, $user, $product_details->name, null, $order, 'stripe');
        }
    }

    private function processRazorpaySubscription($subscription, $invoice, $currency, $cost, $user, $order, $product_details)
    {
        $key_id = ApiKey::pluck('rzp_key')->first();
        $secret = ApiKey::pluck('rzp_secret')->first();
        $api = new Api($key_id, $secret);
        $subscriptionStatus = $api->subscription->fetch($subscription->subscribe_id);

        if ($subscriptionStatus->status != 'active') {
            return;
        }

        $invoices = $api->invoice->all(['subscription_id' => $subscription->subscribe_id]);
        $recentInvoice = null;
        $product_name = Product::where('id', $subscription->product_id)->value('name');
        $invoiceid = \DB::table('order_invoice_relations')->where('order_id', $subscription->order_id)->latest()->value('invoice_id');
        $invoiceItem = \DB::table('invoice_items')->where('invoice_id', $invoiceid)->where('product_name', $product_name)->first();
        $invoice = Invoice::where('id', $invoiceItem->invoice_id)->where('status', 'pending')->first();
        $order = Order::where('id', $subscription->order_id)->first();

        foreach ($invoices->items as $invoice) {
            if ($invoice->status === 'paid') {
                $recentInvoice = $invoice;
                break;
            }
        }

        if ($recentInvoice) {
            $this->PostSubscriptionHandle->successRenew($invoiceItem, $subscription, 'Razorpay', $invoice->currency);
            $this->PostSubscriptionHandle->postRazorpayPayment($invoiceItem, 'Razorpay');
            $this->PostSubscriptionHandle->sendPaymentSuccessMail($subscription->id, $currency, $cost, $user, $product_name, $order->number);
            $this->PostSubscriptionHandle->PaymentSuccessMailtoAdmin($invoice, $cost, $user, $product_name, null, $order, 'razorpay');
        }
    }

    public function createSubscriptionsForEnabledUsers($stripe_payment_details, $product_details, $unit_cost, $currency, $plan, $subscription, $invoice, $order, $user, $cost, $end)
    {
        if ($subscription->is_subscribed == '1') {
            if ($subscription->autoRenew_status == '1') {
                $this->handleStripeSubscription($stripe_payment_details, $product_details, $unit_cost, $currency, $plan, $subscription, $invoice, $order, $user, $cost);
            } elseif ($subscription->rzp_subscription == '1') {
                $this->handleRazorpaySubscription($unit_cost, $plan, $product_details, $invoice, $currency, $subscription, $user, $order, $end);
            }
        }
    }

    private function handleStripeSubscription($stripe_payment_details, $product_details, $unit_cost, $currency, $plan, $subscription, $invoice, $order, $user, $cost)
    {
        $stripeController = new SettingsController();
        $stripeResponse = $stripeController->handleStripeAutoPay($stripe_payment_details, $product_details, $unit_cost, $currency, $plan);

        if ($stripeResponse->status == 'active') {
            $this->updateSubscriptionAndSendEmails($stripeResponse, $invoice, $subscription, $cost, $user, $product_details, $order, 'stripe', $currency);
        } elseif ($stripeResponse->status == 'incomplete') {
            $this->handleIncompleteStripePayment($stripeResponse, $invoice, $subscription, $currency, $plan, $product_details, $cost, $user);
        }
    }

    private function handleIncompleteStripePayment($stripeResponse, $invoice, $subscription, $currency, $plan, $product_details, $cost, $user)
    {
        $latestInvoiceId = $stripeResponse->latest_invoice;
        $latestInvoice = \Stripe\Invoice::retrieve($latestInvoiceId);
        $url = $latestInvoice->hosted_invoice_url;

        if ($url) {
            $this->mailSendToActiveStripeSubscription($product_details, $cost, $currency, $plan, $url, $user);
            Subscription::where('id', $subscription->id)->update(['subscribe_id' => $stripeResponse->id, 'autoRenew_status' => '2']);
        }
    }

    private function handleRazorpaySubscription($unit_cost, $plan, $product_details, $invoice, $currency, $subscription, $user, $order, $end)
    {
        $razorpayController = new RazorpayController();
        $rzpResponse = $razorpayController->handleRzpAutoPay($unit_cost, $plan->days, $product_details->name, $invoice, $currency, $subscription, $user, $order, $end, $product_details);

        if ($rzpResponse->status == 'created') {
            Subscription::where('id', $subscription->id)->update(['subscribe_id' => $rzpResponse->id, 'rzp_subscription' => '2']);
        }
    }

    private function updateSubscriptionAndSendEmails($response, $invoice, $subscription, $cost, $user, $product_details, $order, $payment_method, $currency)
    {
        Subscription::where('id', $subscription->id)->update(['subscribe_id' => $response->id, 'autoRenew_status' => '3']);
        $sub = $this->PostSubscriptionHandle->successRenew($invoice, $subscription, $payment_method, $currency);
        $this->PostSubscriptionHandle->postRazorpayPayment($invoice, $payment_method);

        if ($cost && emailSendingStatus()) {
            $this->PostSubscriptionHandle->sendPaymentSuccessMail($sub, $currency, $cost, $user, $product_details->name, $order->number);
            $this->PostSubscriptionHandle->PaymentSuccessMailtoAdmin($invoice, $cost, $user, $product_details->name, $template = null, $order, $payment_method);
        }
    }
}
