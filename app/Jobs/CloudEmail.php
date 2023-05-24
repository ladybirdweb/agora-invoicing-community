<?php

namespace App\Jobs;

use App\Model\Common\Setting;
use App\Model\Mailjob\CloudEmail as cloudemailsend;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class CloudEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
       //
    }

    /**
     * This job will evaluate if the custom domain for cloud is up and running and then notify the client.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $settings = Setting::find(1);
            $mail = new \App\Http\Controllers\Common\PhpMailController();
            $mailer = $mail->setMailConfig($settings);
            $clouds = cloudemailsend::all();

            foreach ($clouds as $cloud) {
                if ($this->checkTheAvailabilityOfCustomDomain($cloud->domain, $cloud->counter, $cloud->user)) {
                    $userData = $cloud->result_message.'.<br> Email:'.' '.$cloud->user.'<br>'.'Password:'.' '.$cloud->result_password;
                    $email = (new Email())
                        ->from($settings->email)
                        ->to($cloud->user)
                        ->subject('New instance created')
                        ->html($cloud->result_message.'.<br> Email:'.' '.$cloud->user.'<br>'.'Password:'.' '.$cloud->result_password);

                    $mailer->send($email);

                    $mail->email_log_success($settings->email, $cloud->user, 'New instance created', $cloud->result_message.'.<br> Email:'.' '.$cloud->user.'<br>'.'Password:'.' '.$cloud->result_password);

//                    $mail = new \App\Http\Controllers\Common\PhpMailController();
//
//                    $mail->sendEmail($settings->email, $cloud->user, $userData, 'New instance created');

                    cloudemailsend::where('domain', $cloud->domain)->delete();
                }
            }
        } catch(\Exception $e) {
            $this->googleChat($e->getMessage());
        }
    }

    private function checkTheAvailabilityOfCustomDomain($domain, $counter, $user)
    {
        $client = new Client();
        try {
            $response = $client->get('https://'.$domain);
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 300) {
                $this->prepareMessages($domain, $counter,$user, true);

                return true;
            }
        } catch (\Exception $e) {
            $this->prepareMessages($domain, $counter,$user);
            // The domain is not reachable or the SSL certificate is invalid.
            return false;
        }
        $this->prepareMessages($domain, $counter,$user);

        return false;
    }

    private function googleChat($text)
    {
        $url = env('GOOGLE_CHAT');
        $message = [
            'text' => $text,
        ];
        $message_headers = [
            'Content-Type' => 'application/json; charset=UTF-8',
        ];
        $client = new Client();
        $client->post($url, [
            'headers' => $message_headers,
            'body' => json_encode($message),
        ]);
    }

    private function prepareMessages($domain, $counter,$user, $success = false)
    {
        if ($success) {
            $this->googleChat('Hello, It has come to my notice that this domain has been created successfully Domain name:'.$domain.' and this is their email: '.$user."\u{2705}\u{2705}\u{2705}");
        } else {
            cloudemailsend::where('domain', $domain)->increment('counter');
            if ($counter == 5) {
                $this->googleChat('Hello, It has come to my notice that this domain has not been created successfully Domain name:'.$domain.' and this is their email: '.$user.'&#10060;'."\u{2716}\u{2716}\u{2716}");
            }
        }
    }
}
