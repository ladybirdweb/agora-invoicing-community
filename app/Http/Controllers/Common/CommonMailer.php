<?php

namespace App\Http\Controllers\Common;

use Exception;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

class CommonMailer
{
    public function setSmtpDriver($config)
    {
        try {
            if (! $config) {
                return false;
            }

            $transport = Transport::fromDsn('smtp://'.$config['username'].':'.$config['password'].'@smtp.gmail.com?verify_peer=0');

            $mailer = new Mailer($transport);

            return $mailer;
        } catch (Exception $e) {
            loging($e->getMessage());

            return $e->getMessage();
        }
    }
}
