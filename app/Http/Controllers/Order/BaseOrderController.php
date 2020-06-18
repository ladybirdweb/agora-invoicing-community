<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\License\LicensePermissionsController;
use App\Model\Common\StatusSetting;
use App\Model\Order\Order;
use App\Model\Product\Product;
use App\Plugins\Stripe\Controllers\SettingsController;
use App\Traits\Order\UpdateDates;
use App\User;
use Bugsnag;
use Crypt;

class BaseOrderController extends ExtendedOrderController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    use UpdateDates;

    public function getUrl($model, $status, $subscriptionId)
    {
        $url = '';
        if ($status == 'success') {
            if ($subscriptionId) {
                $url = '<a href='.url('renew/'.$subscriptionId)." 
                class='btn btn-sm btn-success btn-xs'".tooltip('Renew')."<i class='fas fa-credit-card'
                 style='color:white;'> </i></a>";
            }
        }

        return '<p><a href='.url('orders/'.$model->id)." 
        class='btn btn-sm btn-primary btn-xs'".tooltip('View')."<i class='fas fa-eye'
         style='color:white;'> </i></a> $url</p>";
    }

    /**
     * inserting the values to orders table.
     *
     * @param type $invoiceid
     * @param type $order_status
     *
     * @throws \Exception
     *
     * @return string
     */
    public function executeOrder($invoiceid, $order_status = 'executed')
    {
        try {
            $invoice_items = $this->invoice_items->where('invoice_id', $invoiceid)->get();
            $user_id = $this->invoice->find($invoiceid)->user_id;
            if (count($invoice_items) > 0) {
                foreach ($invoice_items as $item) {
                    if ($item) {
                        $items = $this->getIfItemPresent($item, $invoiceid, $user_id, $order_status);
                    }
                }
            }

            return 'success';
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function getIfItemPresent($item, $invoiceid, $user_id, $order_status)
    {
        try {
            $product = $this->product->where('name', $item->product_name)->first()->id;
            $version = $this->product->where('name', $item->product_name)->first()->version;
            if ($version == null) {
                //Get Version from Product Upload Table
                $version = $this->product_upload->where('product_id', $product)->pluck('version')->first();
            }
            $serial_key = $this->generateSerialKey($product, $item->agents); //Send Product Id and Agents to generate Serial Key
            $domain = $item->domain;
            $plan_id = $this->plan($item->id);
            $order = $this->order->create([

                'invoice_id'      => $invoiceid,
                'invoice_item_id' => $item->id,
                'client'          => $user_id,
                'order_status'    => $order_status,
                'serial_key'      => Crypt::encrypt($serial_key),
                'product'         => $product,
                'price_override'  => $item->subtotal,
                'qty'             => $item->quantity,
                'domain'          => $domain,
                'number'          => $this->generateNumber(),
            ]);
            $this->addOrderInvoiceRelation($invoiceid, $order->id);

            if ($plan_id != 0) {
                $this->addSubscription($order->id, $plan_id, $version, $product, $serial_key);
            }

            $this->sendOrderMail($user_id, $order->id, $item->id);

            //Update Subscriber To Mailchimp
            $mailchimpStatus = StatusSetting::pluck('mailchimp_status')->first();
            if ($mailchimpStatus == 1) {
                $this->addtoMailchimp($product, $user_id, $item);
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);
            app('log')->error($ex->getMessage());

            throw new \Exception($ex->getMessage());
        }
    }

    public function addToMailchimp($product, $user_id, $item)
    {
        try {
            $mailchimp = new \App\Http\Controllers\Common\MailChimpController();
            $email = User::where('id', $user_id)->pluck('email')->first();
            if ($item->subtotal > 0) {
                $r = $mailchimp->updateSubscriberForPaidProduct($email, $product);
            } else {
                $r = $mailchimp->updateSubscriberForFreeProduct($email, $product);
            }
        } catch (\Exception $ex) {
            return;
        }
    }

    /**
     * inserting the values to subscription table.
     *
     * @param int    $orderid
     * @param int    $planid
     * @param string $version
     * @param int    $product
     * @param string $serial_key
     *
     * @throws \Exception
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     */
    public function addSubscription($orderid, $planid, $version, $product, $serial_key)
    {
        try {
            $permissions = LicensePermissionsController::getPermissionsForProduct($product);
            if ($version == null) {
                $version = '';
            }
            $days = $this->plan->where('id', $planid)->first()->days;
            $licenseExpiry = $this->getLicenseExpiryDate($permissions['generateLicenseExpiryDate'], $days);
            $updatesExpiry = $this->getUpdatesExpiryDate($permissions['generateUpdatesxpiryDate'], $days);
            $supportExpiry = $this->getSupportExpiryDate($permissions['generateSupportExpiryDate'], $days);
            $user_id = $this->order->find($orderid)->client;
            $this->subscription->create(['user_id'     => $user_id,
                'plan_id' => $planid, 'order_id' => $orderid, 'update_ends_at' =>$updatesExpiry, 'ends_at' => $licenseExpiry, 'support_ends_at'=>$supportExpiry, 'version'=> $version, 'product_id' =>$product, ]);

            $licenseStatus = StatusSetting::pluck('license_status')->first();
            if ($licenseStatus == 1) {
                $cont = new \App\Http\Controllers\License\LicenseController();
                $createNewLicense = $cont->createNewLicene($orderid, $product, $user_id, $licenseExpiry, $updatesExpiry, $supportExpiry, $serial_key);
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);
            app('log')->error($ex->getMessage());

            throw new \Exception('Can not Generate Subscription');
        }
    }

    /**
     *  Get the Expiry Date for License.
     *
     * @param bool $permissions [Whether Permissons for generating License Expiry Date are there or not]
     * @param int  $days        [No of days that would get addeed to the current date ]
     *
     * @return string [The final License Expiry date that is generated]
     */
    protected function getLicenseExpiryDate(bool $permissions, $days)
    {
        $ends_at = '';
        if ($days > 0 && $permissions == 1) {
            $dt = \Carbon\Carbon::now();
            $ends_at = $dt->addDays($days);
        }

        return $ends_at;
    }

    /**
     *  Get the Expiry Date for Updates.
     *
     * @param bool $permissions [Whether Permissons for generating Updates Expiry Date are there or not]
     * @param int  $days        [No of days that would get added to the current date ]
     *
     * @return string [The final Updates Expiry date that is generated]
     */
    protected function getUpdatesExpiryDate(bool $permissions, $days)
    {
        $update_ends_at = '';
        if ($days > 0 && $permissions == 1) {
            $dt = \Carbon\Carbon::now();
            $update_ends_at = $dt->addDays($days);
        }

        return $update_ends_at;
    }

    /**
     *  Get the Expiry Date for Support.
     *
     * @param bool $permissions [Whether Permissons for generating Updates Expiry Date are there or not]
     * @param int  $days        [No of days that would get added to the current date ]
     *
     * @return string [The final Suport Expiry date that is generated]
     */
    protected function getSupportExpiryDate(bool $permissions, $days)
    {
        $support_ends_at = '';
        if ($days > 0 && $permissions == 1) {
            $dt = \Carbon\Carbon::now();
            $support_ends_at = $dt->addDays($days);
        }

        return $support_ends_at;
    }

    public function addOrderInvoiceRelation($invoiceid, $orderid)
    {
        try {
            $relation = new \App\Model\Order\OrderInvoiceRelation();
            $relation->create(['order_id' => $orderid, 'invoice_id' => $invoiceid]);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function sendOrderMail($userid, $orderid, $itemid)
    {
        //order
        $order = $this->order->find($orderid);
        //product
        $product = $this->product($itemid);
        //user
        $productId = Product::where('name', $product)->pluck('id')->first();
        $users = new User();
        $user = $users->find($userid);
        //check in the settings
        $settings = new \App\Model\Common\Setting();
        $setting = $settings->where('id', 1)->first();
        $orders = new Order();
        $order = $orders->where('id', $orderid)->first();
        $invoice = $this->invoice->find($order->invoice_id);
        $number = $invoice->number;
        $downloadurl = '';
        if ($user && $order->order_status == 'Executed') {
            $downloadurl = url('product/'.'download'.'/'.$productId.'/'.$number);
        }
        // $downloadurl = $this->downloadUrl($userid, $orderid,$productId);
        $myaccounturl = url('my-order/'.$orderid);
        $invoiceurl = $this->invoiceUrl($orderid);
        //template
        $mail = $this->getMail($setting, $user, $downloadurl, $invoiceurl, $order, $product, $orderid, $myaccounturl);
    }

    public function getMail($setting, $user, $downloadurl, $invoiceurl, $order, $product, $orderid, $myaccounturl)
    {
        try {
            $templates = new \App\Model\Common\Template();
            $temp_id = $setting->order_mail;
            $template = $templates->where('id', $temp_id)->first();
            $knowledgeBaseUrl = $setting->company_url;
            $from = $setting->email;
            $to = $user->email;
            $adminEmail = $setting->company_email;
            $subject = $template->name;
            $data = $template->data;
            $replace = [
                'name'          => $user->first_name.' '.$user->last_name,
                'serialkeyurl' => $myaccounturl,
                'downloadurl'   => $downloadurl,
                'invoiceurl'    => $invoiceurl,
                'product'       => $product,
                'number'        => $order->number,
                'expiry'        => $this->expiry($orderid),
                'url'           => $this->renew($orderid),
                'knowledge_base'=> $knowledgeBaseUrl,

            ];
            $type = '';
            if ($template) {
                $type_id = $template->type;
                $temp_type = new \App\Model\Common\TemplateType();
                $type = $temp_type->where('id', $type_id)->first()->name;
            }
            $templateController = new \App\Http\Controllers\Common\TemplateController();
            $mail = $templateController->mailing($from, $to, $data, $subject, $replace, $type);
            if ($order->invoice->grand_total) {
                SettingsController::sendPaymentSuccessMailtoAdmin($order->invoice->currency, $order->invoice->grand_total, $user, $product);
            }

            return $mail;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function invoiceUrl($orderid)
    {
        $orders = new Order();
        $order = $orders->where('id', $orderid)->first();
        $invoiceid = $order->invoice_id;
        $url = url('my-invoice/'.$invoiceid);

        return $url;
    }

    /**
     * get the price of a product by id.
     *
     * @param type $product_id
     *
     * @throws \Exception
     *
     * @return type collection
     */
    public function getPrice($product_id)
    {
        try {
            return $this->price->where('product_id', $product_id)->first();
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function downloadUrl($userid, $orderid)
    {
        $orders = new Order();
        $order = $orders->where('id', $orderid)->first();
        $invoice = $this->invoice->find($order->invoice_id);
        $number = $invoice->number;
        $url = url('download/'.$userid.'/'.$number);

        return $url;
    }
}
