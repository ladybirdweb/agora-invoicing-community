<?php

namespace App\Http\Controllers\Common;

use App\Email_log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenancy\TenantController;
use App\Model\Common\FaveoCloud;
use App\Model\Mailjob\ExpiryMailDay;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\Payment_log;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

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
            $this->notifyFreeTrail();
            //Notification for After expiry
            $this->mailSendForCloud();
            //Delete cloud
            $this->deleteCloudDetails();
        } catch(\Exception $ex) {
            \Log::error($ex->getMessage());
        }
    }

    public function notifyFreeTrail()
    {
        try {
            $expiredDate = now()->subDays(7);
            $today = new Carbon('today');
            $sub = Subscription::whereIn('product_id', [117, 119])->where('update_ends_at', '<', $expiredDate)->get();

            foreach ($sub as $data) {
                $cron = new CronController();
                $user = \DB::table('users')->find($data->user_id);
                $product = Product::find($data->product_id);
                $order = $cron->getOrderById($data->order_id);
                if ($order) {
                    $invoice = $cron->getInvoiceByOrderId($data->order_id);
                    $date = Carbon::parse($data->update_ends_at)->format('d/m/Y');

                    //check in the settings
                    $settings = new \App\Model\Common\Setting();
                    $setting = $settings::find(1);

                    //template
                    $template = new \App\Model\Common\Template();
                    $temp_id = $settings->where('id', 1)->value('Free_trail_gonna_expired');
                    $template = $template->where('id', $temp_id)->first();

                    $mail = new \App\Http\Controllers\Common\PhpMailController();
                    $type = '';
                    $replace = ['name' => $user->first_name.' '.$user->last_name,
                        'product' => $product->name,
                        'number' => $order->number,
                        'expiry' => date('j M Y', strtotime($data->update_ends_at)),
                        'url' => url('my-orders'), ];
                    if ($template) {
                        $type_id = $template->type;
                        $temp_type = new \App\Model\Common\TemplateType();
                        $type = $temp_type->where('id', $type_id)->first()->name;
                    }
                    $mail->SendEmail($setting->email, $user->email, $template->data, $template->name, $replace, $type);
                }
            }
        } catch(\Exception $ex) {
            \Log::error($ex->getMessage());
        }
    }

    public function getCloudSubscriptions()
    {
        $day = ExpiryMailDay::value('cloud_days');
        $today = new Carbon('today');
        $sub = Subscription::whereIn('product_id', [117, 119])
                            ->whereDate('update_ends_at', '<', $today)
                            ->whereBetween('update_ends_at', [Carbon::now()->subDays($day)->toDateString(), Carbon::now()->toDateString()])
                            ->get();

        return $sub;
    }

    public function mailSendForCloud()
    {
        try {
            $sub_data = $this->getCloudSubscriptions();
            if ($sub_data) {
                foreach ($sub_data as $data) {
                    $cron = new CronController();
                    $user = \DB::table('users')->find($data->user_id);
                    $product = Product::find($data->product_id);
                    $order = $cron->getOrderById($data->order_id);
                    if ($order) {
                        $invoice = $cron->getInvoiceByOrderId($data->order_id);
                        $date = Carbon::parse($data->update_ends_at)->format('d/m/Y');

                        //check in the settings
                        $settings = new \App\Model\Common\Setting();
                        $setting = $settings::find(1);

                        //template
                        $template = new \App\Model\Common\Template();
                        $temp_id = $settings->where('id', 1)->value('free_trail_expired');
                        $template = $template->where('id', $temp_id)->first();

                        $mail = new \App\Http\Controllers\Common\PhpMailController();
                        $type = '';
                        $replace = ['name' => $user->first_name.' '.$user->last_name,
                            'product' => $product->name,
                            'number' => $order->number,
                            'expiry' => date('j M Y', strtotime($data->update_ends_at)),
                            'url' => url('my-orders'), ];
                        if ($template) {
                            $type_id = $template->type;
                            $temp_type = new \App\Model\Common\TemplateType();
                            $type = $temp_type->where('id', $type_id)->first()->name;
                        }
                        $mail->SendEmail($setting->email, $to, $template->data, $template->name, $replace, $type);
                    }
                }
            }
        } catch(\Exception $ex) {
            \Log::error($ex->getMessage());
        }
    }

    public function deleteCloudDetails()
    {
        try {
            $contact = getContactData();
            $day = ExpiryMailDay::value('cloud_days');
            $today = new Carbon('today');
            $sub = Subscription::whereNotNull('update_ends_at')
        ->whereIn('product_id', [117, 119])
        ->where(function ($query) use ($today, $day) {
            $query->whereDate('update_ends_at', '<', $today)
                ->orWhereDate('update_ends_at', $today->subDays($day + 1));
        })
        ->get();

            foreach ($sub as $data) {
                $cron = new CronController();
                $user = \DB::table('users')->find($data->user_id);
                $product = Product::find($data->product_id);
                $order = $cron->getOrderById($data->order_id);

                if (empty($order)) {
                    continue;
                }
                $id = \DB::table('installation_details')->where('order_id', $order->id)->value('installation_path');

                if (is_null($id) || $id == 'cloud.fratergroup.in') {
                    $order->delete();
                } else {
                    //Destroy the tenat
                    $destroy = (new TenantController(new Client, new FaveoCloud()))->destroyTenant(new Request(['id' => $id]));

                    //Mail Sending

                    if ($destroy->status() == 200) {
                        //check in the settings
                        $settings = new \App\Model\Common\Setting();
                        $setting = $settings::find(1);

                        //template
                        $template = new \App\Model\Common\Template();
                        $temp_id = \DB::table('template_types')->where('name', 'cloud_deleted')->value('id');
                        $template = $template->where('id', $temp_id)->first();

                        $mail = new \App\Http\Controllers\Common\PhpMailController();
                        $type = '';
                        $replace = ['name' => $user->first_name.' '.$user->last_name,
                            'product' => $product->name,
                            'number' => $order->number,
                            'expiry' => date('j M Y', strtotime($data->update_ends_at)),
                            'contact' => $contact['contact'],
                            'logo' => $contact['logo'],
                        ];
                        if ($template) {
                            $type_id = $template->type;
                            $temp_type = new \App\Model\Common\TemplateType();
                            $type = $temp_type->where('id', $type_id)->first()->name;
                        }
                        $mail->SendEmail($setting->email, $user->email, $template->data, $template->name, $replace, $type);
                        $order->delete();
                    }
                }
            }
        } catch(\Exception $e) {
            \Log::error($ex->getMessage());
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
            $fromname = $settings->from_name;

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
        $total = $this->checkElement('total', $templatevariables);
        $exceptionMessage = $this->checkElement('exception', $templatevariables);
        $message = $this->checkElement('message', $templatevariables);
        $deletionDate = $this->checkElement('deletionDate', $templatevariables);
        $product_type = $this->checkElement('product_type', $templatevariables);
        $contact = $this->checkElement('contact', $templatevariables);
        $logo = $this->checkElement('logo', $templatevariables);
        $orderHeading = $this->checkElement('orderHeading', $templatevariables);
        $renewPrice = $this->checkElement('renewPrice', $templatevariables);
        $future_expiry = $this->checkElement('future_expiry', $templatevariables);
        $title = $this->checkElement('title', $templatevariables);
        $company_email = $this->checkElement('company_email', $templatevariables);

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
        $variables['{$total}'] = $total;
        $variables['{$exception}'] = $exceptionMessage;
        $variables['{$message}'] = $message;
        $variables['{$deletionDate}'] = $deletionDate;
        $variables['{$product_type}'] = $product_type;
        $variables['{$contact}'] = $contact;
        $variables['{$logo}'] = $logo;
        $variables['{$orderHeading}'] = $orderHeading;
        $variables['{$renewPrice}'] = $renewPrice;
        $variables['{$future_expiry}'] = $future_expiry;
        $variables['{$title}'] = $title;
        $variables['{$company_email}'] = $company_email;

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
        Email_log::insert([
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
        Email_log::insert([
            'date' => date('Y-m-d H:i:s'),
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'status' => 'failed',
        ]);
    }

    public function payment_log($from, $method, $status, $order, $exception = null)
    {
        $data = [
            'date' => date('Y-m-d H:i:s'),
            'from' => $from,
            'status' => $status,
            'payment_method' => $method,
            'order' => $order,
        ];

        if ($exception !== null) {
            $data['exception'] = $exception;
        }

        Payment_log::insert($data);
    }
}
