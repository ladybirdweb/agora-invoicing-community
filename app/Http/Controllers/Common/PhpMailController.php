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
                'table'  => 'jobs',
                'queue'  => 'default',
                'expire' => 60,
            ];

            $queue = new \App\Model\Mailjob\QueueService();
            $active_queue = $queue->where('status', 1)->first();
            if ($active_queue) {
                $short = $active_queue->short_name;
                $fields = new \App\Model\Mailjob\FaveoQueue();
                $field = $fields->where('service_id', $active_queue->id)->pluck('value', 'key')->toArray();
            }

            return (object) ['driver'=> $short, 'config'=>$field];
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
                        $m->cc($address['address'], $address['name']);
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
                'date'       => date('Y-m-d H:i:s'),
                'from'       => $from,
                'to'         => $to,
                'subject'   => $subject,
                'body'       => $data,
                'status'     => 'success',
            ]);

            return 'success';
        } catch (\Exception $ex) {
            \DB::table('email_log')->insert([
                'date'     => date('Y-m-d H:i:s'),
                'from'     => $from,
                'to'       => $to,
                'subject' => $subject,
                'body'     => $data,
                'status'   => 'failed',
            ]);
            if ($ex instanceof \Swift_TransportException) {
                throw new \Exception($ex->getMessage());
            }

            throw new \Exception($ex->getMessage());
        }
    }

    public function setMailConfig($mail)
    {
        switch ($mail->driver) {
            case 'smtp':
                $config = ['host' => $mail->host,
                    'port' => $mail->port,
                    'security' => $mail->encryption,
                    'username' => $mail->email,
                    'password' => $mail->password,
                ];
                if (! $this->commonMailer->setSmtpDriver($config)) {
                    \Log::info('Invaid configuration :- '.$config);

                    return 'invalid mail configuration';
                }

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
                setServiceConfig($mail);
                break;
        }
    }
}
