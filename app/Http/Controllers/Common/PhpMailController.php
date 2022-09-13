<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

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
            'from' => $setting->email,
            'to' => $user['email'],
            'subject' => $template->name,
            'body' => $template->data,
            'status' => 'failed',
        ]);
    }
}
