<?php

namespace App\Http\Controllers\Common;

use App\ApiKey;
use App\Auto_renewal;
use App\Http\Controllers\License\LicensePermissionsController;
use App\Model\Common\Setting;
use App\Model\Common\StatusSetting;
use App\Model\Common\Template;
use App\Model\Common\StatusSetting;
use App\Model\Common\Template;
use App\Model\Mailjob\ExpiryMailDay;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Symfony\Component\Mime\Email;

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
        $status = StatusSetting::value('expiry_mail');
        if ($status == 1) {
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
    }

    public function autoRenewalExpiryNotify()
    {
        $status = StatusSetting::value('subs_expirymail');
        if ($status == 1) {
            $Days = ExpiryMailDay::pluck('autorenewal_days')->toArray();

            $cron = new AutorenewalCronController();
            $Autosub = $cron->getAutoSubscriptions($Days);
            foreach ($Autosub as $value) {
                $userid = $value->user_id;
                $user = $this->getUserById($userid);
                $end = $value->update_ends_at;
                $order = $this->getOrderById($value->order_id);
                $invoice = $this->getInvoiceByOrderId($value->order_id);
                $item = $this->getInvoiceItemByInvoiceId($invoice->id);
                $product = $item->product_name;
                if (emailSendingStatus()) {
                    $this->Auto_renewalMail($user, $end, $product, $order, $value->id);
                }
            }
        }
    }

    public function postRenewalNotify()
    {
        $status = StatusSetting::value('post_expirymail');
        if ($status == 1) {
            $periods = ExpiryMailDay::pluck('postexpiry_days')->toArray();
            $cron = new AutorenewalCronController();
            $postSub = $cron->getPostSubscriptions($periods);
            foreach ($postSub as $value) {
                $userid = $value->user_id;
                $user = $this->getUserById($userid);
                $end = $value->update_ends_at;
                $order = $this->getOrderById($value->order_id);
                $invoice = $this->getInvoiceByOrderId($value->order_id);
                $item = $this->getInvoiceItemByInvoiceId($invoice->id);
                $product = $item->product_name;
                if (emailSendingStatus()) {
                    $this->Expiredsub_Mail($user, $end, $product, $order, $value->id);
                }
            }
        }
    }

    public function getOnDayExpiryInfoSubs()
    {
        $yesterday = new Carbon('yesterday');
        $tomorrow = new Carbon('tomorrow');
        $sub = Subscription::whereNotNull('update_ends_at')
            ->where('is_subscribed', 1)
            ->whereBetween('update_ends_at', [$yesterday, $tomorrow]);

        return $sub;
    }

    public function autoRenewal()
    {
        try {
            $subscriptions_detail = $this->getOnDayExpiryInfoSubs()->get();
            foreach ($subscriptions_detail as $subscription) {
                $status = $subscription->is_subscribed;
                if ($status == '1') {
                    $userid = $subscription->user_id;
                    $end = $subscription->update_ends_at;
                    $order = $this->getOrderById($subscription->order_id);
                    $invoice = $this->getInvoiceByOrderId($subscription->order_id);
                    $item = $this->getInvoiceItemByInvoiceId($invoice->id);
                    // $product = $item->product_name;
                    $product_details = Product::where('name', $item->product_name)->first();
                    $plan = Plan::where('product', $product_details->id)->first('days');
                    $currency = $invoice->currency;

                    $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
                    $stripe = new \Stripe\StripeClient($stripeSecretKey);

                    $user = \DB::table('users')->where('id', $userid)->first();
                    $customer_id = Auto_renewal::where('user_id', $userid)->value('customer_id');

                    $this->razorpay_payment($plan->days, $product_details->name, $invoice, $currency, $subscription, $user);

                    //create product
                    $product = $stripe->products->create([
                        'name' => $product_details->name,
                    ]);
                    $product_id = $product['id'];

                    //define product price and recurring interval

                    $price = $stripe->prices->create([
                        'unit_amount' => $invoice->grand_total,
                        'currency' => $currency,
                        'recurring' => ['interval' => 'day', 'interval_count' => $plan->days],
                        'product' => $product_id,
                    ]);
                    $price_id = $price['id'];

                    //CREATE SUBSCRIPTION

                    $stripe_subscription = $stripe->subscriptions->create([
                        'customer' => $customer_id,
                        'items' => [
                            ['price' => $price_id],
                        ],
                    ]);
                    if ($stripe_subscription['status'] == 'active') {
                        //Afer Renew
                        Subscription::where('id', $subscription->id)->update(['subscribe_id' => $stripe_subscription['id']]);
                        $this->successRenew($invoice, $subscription);
                        $this->postRazorpayPayment($invoice, $payment_method = 'stripe');
                        if ($invoice->grand_total && emailSendingStatus()) {
                            $this->sendPaymentSuccessMailtoAdmin($invoice->currency, $invoice->grand_total, $user, $invoice->invoiceItem()->first()->product_name);
                        }
                    }
                }
            }
        } catch (\Cartalyst\Stripe\Exception\ApiLimitExceededException|\Cartalyst\Stripe\Exception\BadRequestException|\Cartalyst\Stripe\Exception\MissingParameterException|\Cartalyst\Stripe\Exception\NotFoundException|\Cartalyst\Stripe\Exception\ServerErrorException|\Cartalyst\Stripe\Exception\StripeException|\Cartalyst\Stripe\Exception\UnauthorizedException $e) {
            throw new \Exception($ex->getMessage());
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            if (emailSendingStatus()) {
                $this->sendFailedPaymenttoAdmin($invoice->grand_total, $e->getMessage(), $user);
            }
            \Session::put('amount', $amount);
            \Session::put('error', $e->getMessage());

            return redirect()->route('checkout');
        } catch (\Exception $e) {
            $this->razorpay_payment($plan->days, $product_details->name, $invoice, $currency, $subscription, $user);
        }
    }

    public function razorpay_payment($days, $product_name, $invoice, $currency, $subscription, $user)
    {
        try {
            $status = $subscription->is_subscribed;
            if ($status == '1') {
                $key_id = ApiKey::pluck('rzp_key')->first();
                $secret = ApiKey::pluck('rzp_secret')->first();
                $amount = $invoice->grand_total;
                $update_end = $subscription->update_ends_at;
                $api = new Api($key_id, $secret);
                $rzp_plan = $api->plan->create(['period' => 'monthly',
                    'interval' => round((int) $days / 30),
                    'item' => [
                        'name' => $product_name,
                        'amount' => round((int) $invoice->grand_total),
                        'currency' => $currency, ],

                ]
            );

                $rzp_subscription = $api->subscription->create([
                    'plan_id' => $rzp_plan['id'],
                    'customer_notify' => 1,
                    'quantity'=>1,
                    'total_count' => 1,
                    'start_at' =>  Carbon::parse($update_end)->timestamp,
                    'addons' => [[
                        'item' => [
                            'name' => 'Delivery charges',
                            'amount' => $amount,
                            'currency' => $currency, ], ]],
                ]);

                //Afer Renew
                if ($rzp_subscription['status'] == 'active') {
                    Subscription::where('id', $subscription->id)->update(['subscribe_id' => $rzp_subscription['id']]);
                    $this->successRenew($invoice, $subscription);
                    $this->postRazorpayPayment($invoice, $payment_method = 'Razorpay');
                    if ($invoice->grand_total && emailSendingStatus()) {
                        $this->sendPaymentSuccessMailtoAdmin($invoice->currency, $invoice->grand_total, $user, $invoice->invoiceItem()->first()->product_name);
                    }
                }
            }
        } catch (\Exception $e) {
            dd($e);
            if (emailSendingStatus()) {
                $this->sendFailedPaymenttoAdmin($invoice->grand_total, $e->getMessage(), $user);
            }
        }
    }

    public static function sendFailedPaymenttoAdmin($amount, $exceptionMessage, $user)
    {
        dd($exceptionMessage);
        $setting = Setting::find(1);
        $paymentFailData = 'Payment for'.' '.'of'.' '.$user->currency.' '.$amount.' '.'failed by'.' '.$user->first_name.' '.$user->last_name.' '.'. User Email:'.' '.$user->email.'Reason:'.$exceptionMessage;

        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $mail->sendEmail($setting->email, $setting->company_email, $paymentFailData, 'Payment failed ');

        $set = new \App\Model\Common\Setting();
        $set = $set->findOrFail(1);
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $mailer = $mail->setMailConfig($set);

        $email = (new Email())
               ->from($set->email)
               ->to($user->email)
               ->subject('Payment failed ')
               ->html('<p>Dear'.' '.$user->first_name.' '.$user->last_name.','.'<br/><br/>'.'Payment for'.' '.$amount.'</b>'.' '.'is failed</p><br/>Reason:'.$exceptionMessage.','.'Please feel free to head over to our '.'<b>'.'Customer Support'.'</b>'.' '.'team'.'.');
        $mailer->send($email);
        $mail->email_log_fail($set->email, $user->email, 'Payment failed for subscription', $exceptionMessage);
    }

    public static function sendPaymentSuccessMailtoAdmin($currency, $total, $user, $productName)
    {
        $set = new \App\Model\Common\Setting();
        $set = $set->findOrFail(1);
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $mailer = $mail->setMailConfig($set);

        $email = (new Email())
               ->from($set->email)
               ->to($user->email)
               ->subject('Payment Successful')
               ->html('<p>Dear'.' '.$user->first_name.' '.$user->last_name.','.'<br/><br/>'.'Your Subscription for'.' '.'<b>'.$productName.'</b>'.' '.'of'.' '.$currency.' '.'<b> '.$total.'</b>'.' '.'successfully Renewed</p><br/>If you need more clarification about your payment status or have questions'.','.'Please feel free to head over to our '.'<b>'.'Customer Support'.'</b>'.' '.'team'.'.');
        $mailer->send($email);
        $mail->email_log_success($set->email, $user->email, 'Payment Successful for subscription', 'Your Subscription for'.' '.'<b>'.$productName.'</b>'.' '.'of'.' '.$currency.' '.'<b> '.$total.'</b>'.' '.'successfully Renewed');
    }

    public function successRenew($invoice, $subscription)
    {
        try {
            $invoice->processing_fee = $invoice->processing_fee;
            $invoice->status = 'success';
            $invoice->save();
            $id = $subscription->id;
            $planid = $subscription->plan_id;
            $plan = $this->plan->find($planid);
            $days = $plan->days;
            $sub = $this->sub->find($id);
            $permissions = LicensePermissionsController::getPermissionsForProduct($sub->product_id);
            $licenseExpiry = $this->getExpiryDate($permissions['generateLicenseExpiryDate'], $sub, $days);
            $updatesExpiry = $this->getUpdatesExpiryDate($permissions['generateUpdatesxpiryDate'], $sub, $days);
            $supportExpiry = $this->getSupportExpiryDate($permissions['generateSupportExpiryDate'], $sub, $days);
            $sub->ends_at = $licenseExpiry;
            $sub->update_ends_at = $updatesExpiry;
            $sub->support_ends_at = $supportExpiry;
            if (Order::where('id', $sub->order_id)->value('license_mode') == 'File') {
                Order::where('id', $sub->order_id)->update(['is_downloadable' => 0]);
            } else {
                $licenseStatus = StatusSetting::pluck('license_status')->first();
                if ($licenseStatus == 1) {
                    $this->editDateInAPL($sub, $updatesExpiry, $licenseExpiry, $supportExpiry);
                }
            }
            $this->removeSession();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    //Update License Expiry Date
    public function getExpiryDate($permissions, $sub, $days)
    {
        $expiry_date = '';
        if ($days > 0 && $permissions == 1) {
            $date = \Carbon\Carbon::parse($sub->ends_at);
            $expiry_date = $date->addDays($days);
        }

        return $expiry_date;
    }

    //Update Updates Expiry Date
    public function getUpdatesExpiryDate($permissions, $sub, $days)
    {
        $expiry_date = '';
        if ($days > 0 && $permissions == 1) {
            $date = \Carbon\Carbon::parse($sub->update_ends_at);
            $expiry_date = $date->addDays($days);
        }

        return $expiry_date;
    }

    //Update Support Expiry Date
    public function getSupportExpiryDate($permissions, $sub, $days)
    {
        $expiry_date = '';
        if ($days > 0 && $permissions == 1) {
            $date = \Carbon\Carbon::parse($sub->support_ends_at);
            $expiry_date = $date->addDays($days);
        }

        return $expiry_date;
    }

    public function editDateInAPL($sub, $updatesExpiry, $licenseExpiry, $supportExpiry)
    {
        $productId = $sub->product_id;
        $domain = $sub->order->domain;
        $orderNo = $sub->order->number;
        $licenseCode = $sub->order->serial_key;
        $expiryDate = $updatesExpiry ? Carbon::parse($updatesExpiry)->format('Y-m-d') : '';
        $licenseExpiry = $licenseExpiry ? Carbon::parse($licenseExpiry)->format('Y-m-d') : '';
        $supportExpiry = $supportExpiry ? Carbon::parse($supportExpiry)->format('Y-m-d') : '';
        $noOfAllowedInstallation = '';
        $getInstallPreference = '';
        $cont = new \App\Http\Controllers\License\LicenseController();
        $noOfAllowedInstallation = $cont->getNoOfAllowedInstallation($licenseCode, $productId);
        $getInstallPreference = $cont->getInstallPreference($licenseCode, $productId);
        $updateLicensedDomain = $cont->updateExpirationDate($licenseCode, $expiryDate, $productId, $domain, $orderNo, $licenseExpiry, $supportExpiry, $noOfAllowedInstallation, $getInstallPreference);
    }

    public function removeSession()
    {
        \Session::forget('subscription_id');
        \Session::forget('plan_id');
        \Session::forget('invoiceid');
    }

    public function postRazorpayPayment($invoice, $payment_method)
    {
        try {
            $amount = $invoice->grand_total;

            $payment_status = 'success';
            $payment_date = \Carbon\Carbon::now()->toDateTimeString();

            $invoice = Invoice::find($invoice->id);
            $processingFee = '';
            foreach (\Cart::getConditionsByType('fee') as $processFee) {
                $processingFee = $processFee->getValue();
            }
            $invoice_status = 'pending';

            $payment = $this->payment->create([
                'invoice_id' => $invoice->id,
                'user_id' => $invoice->user_id,
                'amount' => $amount,
                'payment_method' => $payment_method,
                'payment_status' => $payment_status,
                'created_at' => $payment_date,
            ]);
            $all_payments = $this->payment
        ->where('invoice_id', $invoice->id)
        ->where('payment_status', 'success')
        ->pluck('amount')->toArray();
            $total_paid = array_sum($all_payments);
            if ($total_paid >= $invoice->grand_total) {
                $invoice_status = 'success';
            }
            if ($invoice) {
                $sessionValue = $this->getCodeFromSession();
                $code = $sessionValue['code'];
                $codevalue = $sessionValue['codevalue'];
                $invoice->discount = $codevalue;
                $invoice->coupon_code = $code;
                $invoice->processing_fee = $processingFee;
                $invoice->status = $invoice_status;
            }

            return $payment;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    protected function getCodeFromSession()
    {
        $code = '';
        $codevalue = '';
        if (\Session::has('code')) {//If coupon code is applied get it here from Session
            $code = \Session::get('code');
            $codevalue = \Session::get('codevalue');
        }

        return ['code' => $code, 'codevalue' => $codevalue];
    }
}
