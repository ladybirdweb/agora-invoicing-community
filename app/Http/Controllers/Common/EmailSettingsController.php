<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

use App\Model\Common\Setting;
use Illuminate\Http\Request;

class EmailSettingsController extends Controller
{
    protected $emailConfig;

    protected function checkSConnection(Setting $emailConfig)
    {
        try {
            $this->emailConfig = $emailConfig;
        } catch (Exception $e) {
            $this->error = $e;

            return false;
        }
    }

    public function settingsEmail(Setting $settings)
    {
        try {
            $set = $settings->find(1);

            return view('themes.default1.common.setting.email', compact('set'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettingsEmail(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required',
            'password'  => 'required',
            'driver'    => 'required',
        ]);

        if ($request->input('driver') == 'smtp') {
            $this->validate($request, [
                'port'      => 'required',
                'encryption'=> 'required',
                'host'      => 'required',
            ]);
        }


        try {
            $emailSettings = $request->all();
            $this->emailConfig = Setting::first();

            $this->emailConfig->fill($emailSettings);
            if (! $this->checkSendConnection($this->emailConfig)) {
                return errorResponse($this->errorhandler());
            }
            $this->emailConfig->save();

            return successResponse('Email Settings saved successfully');
        } catch (\Exception $ex) {
            return errorResponse($ex->getMessage());
        }
    }

    /**
     * takes care of exception handling in this class.
     * NOTE: to make errors user friendly, more and more cases has to be added to it.
     * @return string   returns formatted message
     */
    private function errorhandler()
    {
        $message = method_exists($this->error, 'getMessage') ? $this->error->getMessage() : $this->error;

        return $message;
    }

    /**
     * checks send connection based on the mail driver.
     * @param Emails $emailConfig emailConfig object
     * @return bool
     */
    protected function checkSendConnection(Setting $emailConfig)
    {
        try {
            if (! $emailConfig->driver) {
                throw new \Exception('sending protocol must be provided');
            }

            $this->emailConfig = $emailConfig;

            //if sending protocol is mail, no connection check is required
            if ($this->emailConfig->driver == 'mail') {
                return $this->checkMailConnection();
            }

            //set outgoing mail configuation to the passed one
            setServiceConfig($this->emailConfig);

            if ($this->emailConfig->driver == 'smtp') {
                return $this->checkSMTPConnection();
            }

        } catch (Exception $e) {
            $this->error = $e;

            return false;
        }
    }


    /**
     * checks if php's mail function is enabled on current server.
     * @return bool  true if enabled else false
     */
    private function checkMailConnection()
    {
        if (function_exists('mail')) {
            return true;
        }
        $this->error = 'PHP Mail function is disabled on your server';

        return false;
    }

    private function checkSMTPConnection()
    {
        try {
            $https = [];
            $https['ssl']['verify_peer'] = false;
            $https['ssl']['verify_peer_name'] = false;

            $transport = new \Swift_SmtpTransport(\Config::get('mail.host'), \Config::get('mail.port'), \Config::get('mail.security'));
            $transport->setUsername(\Config::get('mail.username'));
            $transport->setPassword(\Config::get('mail.password'));
            $transport->setStreamOptions($https);
            $mailer = new \Swift_Mailer($transport);
            $mailer->getTransport()->start();

            return true;
        } catch (\Swift_TransportException $e) {
            $this->error = $e;

            return false;
        } catch (\Exception $e) {
            $this->error = $e;

            return false;
        }
    }
}
