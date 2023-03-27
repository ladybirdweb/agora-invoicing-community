<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenancy\TenantController;
use App\Model\Common\FaveoCloud;
use App\Model\Mailjob\ExpiryMailDay;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\Mime\Email;

class PhpMailController extends Controller
{
    protected $commonMailer;

    protected $queueManager;

    public function __construct()
    {
        $this->commonMailer = new CommonMailer();
        $this->queueManager = app('queue');
    }

    public function sendEmail($from, $to, $template_data, $template_name, $replace = [], $type = '', $bcc = [])
    {
        $this->setQueue();
        $job = new \App\Jobs\SendEmail($from, $to, $template_data, $template_name, $replace, $type);
        dispatch($job);
    }

     public function NotifyMail($from, $to, $template_data, $template_name)
     {
         $this->setQueue();
         $job = new \App\Jobs\NotifyMail();
         dispatchNow($job);
     }

    /**
     * set the queue service.
     */
    public function setQueue()
    {
        $this->queueManager->setDefaultDriver($this->getActiveQueue()->driver);
    }

    private function getActiveQueue()
    {
        return persistentCache('queue_configuration', function () {
            $short = 'database';
            $field = [
                'driver' => 'database',
                'table' => 'jobs',
                'queue' => 'default',
                'expire' => 60,
            ];

            $queue = new \App\Model\Mailjob\QueueService();
            $active_queue = $queue->where('status', 1)->first();
            if ($active_queue) {
                $short = $active_queue->short_name;
                $fields = new \App\Model\Mailjob\FaveoQueue();
                $field = $fields->where('service_id', $active_queue->id)->pluck('value', 'key')->toArray();
            }

            return (object) ['driver' => $short, 'config' => $field];
        });
    }

    public function NotifyMailing()
    {
        try {
            $this->mailSendForCloud();

            $this->deleteCloudDetails();
        } catch(\Exception $ex) {
            dd($ex);
            \Log::error($ex->getMessage());
        }
    }

    public function getCloudSubscriptions()
    {
        $day = ExpiryMailDay::value('cloud_days');
        $today = new Carbon('today');
        $sub = Subscription::where('product_id', '117')
                            ->whereDate('update_ends_at', '<', $today)
                            ->whereBetween('update_ends_at', [Carbon::now()->subDays($day)->toDateString(), Carbon::now()->toDateString()])
                            ->get();

        return $sub;
    }

    public function mailSendForCloud()
    {
        $sub_data = $this->getCloudSubscriptions();
        if ($sub_data) {
            foreach ($sub_data as $data) {
                $cron = new CronController();
                $user = \DB::table('users')->find($data->user_id);
                $product = Product::find($data->product_id);
                $order = $cron->getOrderById($data->order_id);
                $invoice = $cron->getInvoiceByOrderId($data->order_id);
                $date = Carbon::parse($data->update_ends_at)->format('d/m/Y');

                //check in the settings
                $settings = new \App\Model\Common\Setting();
                $settings = $settings->where('id', 1)->first();

                $mail = new \App\Http\Controllers\Common\PhpMailController();
                $mailer = $mail->setMailConfig($settings);
                $url = url('my-orders');

                $email = (new Email())
                         ->from($settings->email)
                         ->to($user->email)
                         ->subject('Reminder email')
                         ->html('<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                <td style="width: 30px;">&nbsp;</td>
                                <td style="width: 640px; padding-top: 30px;">
                                <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="https://billing.faveohelpdesk.com/common/images/faveo1.png" alt="Faveo Helpdesk" /></h2>
                                </td>
                                <td style="width: 30px;">&nbsp;</td>
                                </tr>
                                <tr>
                                <td style="width: 30px;">&nbsp;</td>
                                <td style="width: 640px; padding-top: 30px;">
                                <table style="width: 640px;border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear'.' '.$user->first_name.' '.$user->last_name.''.',<br /><br />
                                <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your Faveo Cloud - Free Trail has Expired</h1>
                                </td>
                                <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                </tr>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; padding: 0; width: 560px;" align="left">
                                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">We regret to inform you that your trial product has expired and your access to it has been suspended. We hope you had a positive experience using our product during your trial period and we appreciate your interest in our services.</p></br>
                                <p>If you wish to continue using our product, we encourage you to renew your subscription as soon as possible. Without renewal, your Cloud Instance will be deleted and you will no longer have access to it.</p></br>
                                <p>To renew your subscription please use the below link. Without renewal, your Cloud instance will be deleted and you will no longer have access to it.</p></br>
                                <p>Thankyou For Choosing Faveo!</p>
                                <table style="margin: 25px 0 30px 0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr style="background-color: #f8f8f8;">
                                <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Order ID</th>
                                <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Product</th>
                                <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Expiry Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">'.$order->number.'</td>
                                <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">'.$product->name.'</td>
                                <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">'.$data->update_ends_at.'</td>
                                </tr>
                                </tbody>
                                </table>
                                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to login to your Control Panel and renew your orders.</p>
                                </td>
                                <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                </tr>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="'.$url.'" target="_blank"> Renew Order </a></td>
                                <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                </tr>
                                </tbody>
                                </table>
                                </td>
                                <td style="width: 30px;">&nbsp;</td>
                                </tr>
                                <tr>
                                <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="padding: 20px 0 10px 0; width: 640px;" align="left">
                                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">SALES CONTACT</td>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">TECHNICAL SUPPORT</td>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
                                </tr>
                                <tr>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                                <p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:sales@faveohelpdesk.com">sales@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
                                </td>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                                <p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="https://www.support.faveohelpdesk.com">www.support.faveohelpdesk.com</a></p>
                                </td>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                                <p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:accounts@ladybirdweb.com">accounts@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="https://www.faveohelpdesk.com">www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                </td>
                                <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                </tr>
                                </tbody>
                                </table>
                                <p>&nbsp;</p>');
                $mailer->send($email);
            }
        }
    }

    public function deleteCloudDetails()
    {
        $day = ExpiryMailDay::value('cloud_days');
        $today = new Carbon('today');
        $sub = Subscription::whereNotNull('update_ends_at')
                           ->where('product_id', '117')
                           ->whereDate('update_ends_at', '<', $today)
                           ->whereDate('update_ends_at', $today->subDays($day + 1))
                           ->get();
        if ($sub) {
            foreach ($sub as $data) {
                $cron = new CronController();
                $user = \DB::table('users')->find($data->user_id);
                $product = Product::find($data->product_id);
                $order = $cron->getOrderById($data->order_id);
                $id = \DB::table('installation_details')->where('order_id', $order->id)->value('installation_path');

                //Destroy the tenat
                $destroy = (new TenantController(new Client, new FaveoCloud()))->destroyTenant(new Request(['id' => $id]));

                //Mail Sending

                if ($destroy->status() == 200) {
                    //check in the settings
                    $settings = new \App\Model\Common\Setting();
                    $settings = $settings->where('id', 1)->first();

                    $mail = new \App\Http\Controllers\Common\PhpMailController();
                    $mailer = $mail->setMailConfig($settings);

                    $email = (new Email())
                              ->from($settings->email)
                              ->to($user->email)
                              ->subject('Destroyed email')
                              ->html('<p>Your Free trail product is Expired we deleted your instance</p>');
                    $mailer->send($email);
                }

                return $destroy;
            }
        }
    }

    public function mailing($from, $to, $data, $subject, $replace = [],
     $type = '', $bcc = [], $fromname = '', $toname = '', $cc = [], $attach = [])
    {
        try {
            $transform = [];
            $page_controller = new \App\Http\Controllers\Front\PageController();

            $transform[0] = $replace;
            $data = $page_controller->transform($type, $data, $transform);
            $settings = \App\Model\Common\Setting::find(1);
            $fromname = $settings->company;

            $this->setMailConfig($settings);
            \Mail::send('emails.mail', ['data' => $data], function ($m) use ($from, $to, $subject, $fromname, $toname, $cc, $attach, $bcc) {
                $m->from($from, $fromname);

                $m->to($to, $toname)->subject($subject);
                /* if cc is need  */
                if (! empty($cc)) {
                    foreach ($cc as $address) {
                        $m->cc($address['a ress'], $address['name']);
                    }
                }

                if (! empty($bcc)) {
                    foreach ($bcc as $address) {
                        $m->bcc($address);
                    }
                }

                /*  if attachment is need */
                if (! empty($attach)) {
                    foreach ($attach as $file) {
                        $m->attach($file['path'], $options = []);
                    }
                }
            });
            \DB::table('email_log')->insert([
                'date' => date('Y-m-d H:i:s'),
                'from' => $from,
                'to' => $to,
                'subject' => $subject,
                'body' => $data,
                'status' => 'success',
            ]);

            return 'success';
        } catch (\Exception $ex) {
            \DB::table('email_log')->insert([
                'date' => date('Y-m-d H:i:s'),
                'from' => $from,
                'to' => $to,
                'subject' => $subject,
                'body' => $data,
                'status' => 'failed',
            ]);
            if ($ex instanceof \Swift_TransportException) {
                throw new \Exception($ex->getMessage());
            }

            throw new \Exception($ex->getMessage());
        }
    }

    public function setMailConfig($settings)
    {
        switch ($settings->driver) {
            case 'smtp':

                $config = ['host' => $settings->host,
                    'port' => $settings->port,
                    'security' => $settings->encryption,
                    'username' => $settings->email,
                    'password' => $settings->password,
                ];

                $mail = new \App\Http\Controllers\Common\CommonMailer();
                $mailer = $mail->setSmtpDriver($config);
                if (! $this->commonMailer->setSmtpDriver($config)) {
                    \Log::info('Invaid configuration :- '.$config);

                    return 'invalid mail configuration';
                }

                return $mailer;
                break;

            case 'send_mail':
                $config = [
                    'host' => \Config::get('mail.host'),
                    'port' => \Config::get('mail.port'),
                    'security' => \Config::get('mail.encryption'),
                    'username' => \Config::get('mail.username'),
                    'password' => \Config::get('mail.password'),
                ];
                $this->commonMailer->setSmtpDriver($config);
                break;

            default:
                setServiceConfig($settings);
                break;
        }
    }

    public function mailTemplate($contents, $templatevariables)
    {
        $variables = $this->getVariableValues($contents, $templatevariables);
        $messageBody = $contents;
        foreach ($variables as $v =>$k) {
            $messageBody = str_replace($v, $k, $messageBody);
        }

        return $messageBody;
    }

    public function getVariableValues($contents, $templatevariables)
    {
        $variables = [];
        $name = $this->checkElement('name', $templatevariables);
        $email = $this->checkElement('username', $templatevariables);
        $password = $this->checkElement('password', $templatevariables);
        $url = $this->checkElement('url', $templatevariables);
        $website_url = $this->checkElement('website_url', $templatevariables);
        $number = $this->checkElement('number', $templatevariables);
        $address = $this->checkElement('address', $templatevariables);
        $invoiceurl = $this->checkElement('invoiceurl', $templatevariables);
        $content = $this->checkElement('content', $templatevariables);
        $currency = $this->checkElement('currency', $templatevariables);
        $manager_first_name = $this->checkElement('manager_first_name', $templatevariables);
        $manager_last_name = $this->checkElement('manager_last_name', $templatevariables);
        $manager_email = $this->checkElement('manager_email', $templatevariables);
        $manager_code = $this->checkElement('manager_code', $templatevariables);
        $manager_mobile = $this->checkElement('manager_mobile', $templatevariables);
        $manager_skype = $this->checkElement('manager_skype', $templatevariables);
        $contact_us = $this->checkElement('contact_us', $templatevariables);

        $serialkeyurl = $this->checkElement('serialkeyurl', $templatevariables);
        $downloadurl = $this->checkElement('downloadurl', $templatevariables);
        $invoiceurl = $this->checkElement('invoiceurl', $templatevariables);
        $product = $this->checkElement('product', $templatevariables);
        $number = $this->checkElement('number', $templatevariables);
        $expiry = $this->checkElement('expiry', $templatevariables);
        $url = $this->checkElement('url', $templatevariables);
        $knowledge_base = $this->checkElement('knowledge_base', $templatevariables);

        $variables['{$name}'] = $name;
        $variables['{$username}'] = $email;
        $variables['{$password}'] = $password;
        $variables['{$url}'] = $url;
        $variables['{$website_url}'] = $website_url;
        $variables['{$number}'] = $number;
        $variables['{$address}'] = $address;
        $variables['{$invoiceurl}'] = $invoiceurl;
        $variables['{$content}'] = $content;
        $variables['{$currency}'] = $currency;
        $variables['{$manager_first_name}'] = $manager_first_name;
        $variables['{$manager_last_name}'] = $manager_last_name;
        $variables['{$manager_email}'] = $manager_email;
        $variables['{$manager_code}'] = $manager_code;
        $variables['{$manager_mobile}'] = $manager_mobile;
        $variables['{$manager_skype}'] = $manager_skype;
        $variables['{$contact_us}'] = $contact_us;

        $variables['{$serialkeyurl}'] = $serialkeyurl;
        $variables['{$downloadurl}'] = $downloadurl;
        $variables['{$invoiceurl}'] = $invoiceurl;
        $variables['{$product}'] = $product;
        $variables['{$number}'] = $number;
        $variables['{$expiry}'] = $expiry;
        $variables['{$url}'] = $url;
        $variables['{$knowledge_base}'] = $knowledge_base;

        return $variables;
    }

    public function checkElement($element, $array)
    {
        $value = '';
        if (is_array($array)) {
            if (key_exists($element, $array)) {
                $value = $array[$element];
            }
        }

        return $value;
    }

    public function email_log_success($from, $to, $subject, $body)
    {
        \DB::table('email_log')->insert([
            'date' => date('Y-m-d H:i:s'),
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'status' => 'success',
        ]);
    }

    public function email_log_fail($from, $to, $subject, $body)
    {
        \DB::table('email_log')->insert([
            'date' => date('Y-m-d H:i:s'),
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'status' => 'failed',
        ]);
    }
}
