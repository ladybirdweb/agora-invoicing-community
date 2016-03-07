<?php

namespace App\Plugins\Twilio\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Twilio\Model\Twilio;

require base_path('vendor/twilio/Services/Twilio.php');

use Services_Twilio;
use Services_Twilio_TinyHttp;

class ProcessController extends Controller
{
    public $set;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $set = new Twilio();
        $this->set = $set->where('id', '1')->first();
    }

    public function Process($request)
    {
        try {
            $account_sid = $request->input('account_sid');
            $auth_token = $request->input('auth_token');
            if ($request->input('from')) {
                $from = $request->input('from');
            } else {
                $from = $this->set->from;
            }
            $to = $request->input('to');
            $body = $request->input('body');

            $http = new Services_Twilio_TinyHttp(
                    'https://api.twilio.com', ['curlopts' => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ]]
            );

            $client = new Services_Twilio($account_sid, $auth_token, '2010-04-01', $http);

            $client->account->messages->create([
                'To'   => $to,
                'From' => $from,
                'Body' => $body,
            ]);
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception('Message can not send');
        }
    }
}
