<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Model\Common\Setting;
use App\Model\Order\Order;
use App\ThirdPartyApp;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->url = 'http://faveo.helpdesk';
        $this->middleware('auth', ['except'=>['createTenant', 'verifyThirdPartyToken']]);
    }

    public function viewTenant()
    {
        return view('themes.default1.tenant.index');
    }

    public function getTenants(Request $request)
    {
        $client = new Client([]);
        $response = $client->request(
                    'GET',
                    $this->url.'/tenants',
                );
        $responseBody = (string) $response->getBody();
        $response = json_decode($responseBody);

        return \DataTables::of($response->message)

         ->addColumn('tenants', function ($model) {
             return $model->id;
         })
         ->addColumn('domain', function ($model) {
             return $model->domain;
         })
         ->addColumn('db_name', function ($model) {
             return $model->database_name;
         })
         ->addColumn('db_username', function ($model) {
             return $model->database_user_name;
         })
         ->addColumn('action', function ($model) {
             return "<p><button data-toggle='modal' 
             data-id=".$model->id." data-name= '' onclick=deleteTenant('".$model->id."') id='delten".$model->id."'
             class='btn btn-sm btn-danger btn-xs delTenant'".tooltip('Delete')."<i class='fa fa-trash'
             style='color:white;'> </i></button>&nbsp;</p>";
         })
         ->rawColumns(['tenants', 'domain', 'db_name', 'db_username', 'action'])
         ->make(true);

        // $tenants =
    }

    /**
     * Logic for creating new tenant is handled here.
     */
    public function createTenant(Request $request)
    {
        $this->validate($request,
                [
                    'orderNo' => 'required',
                    'domain'=>  'required',
                ]);
        try {
            $faveoCloud = '.faveocloud.com';
            $licCode = Order::where('number', $request->input('orderNo'))->first()->serial_key;
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            if (! $keys->app_key) {//Valdidate if the app key to be sent is valid or not
                throw new Exception('Invalid App key provided. Please contact admin.');
            }
            $token = str_random(32);
            \DB::table('third_party_tokens')->insert(['user_id'=>\Auth::user()->id, 'token'=>$token]);
            $user = \Auth::user()->email;
            $client = new Client([]);
            $data = ['domain' => $request->input('domain').$faveoCloud, 'app_key'=>$keys->app_key, 'token'=>$token, 'lic_code'=>$licCode, 'username'=>$user, 'userId'=>\Auth::user()->id, 'timestamp'=>time()];

            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
            $response = $client->request(
                    'POST',
                    $this->url.'/tenants', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
                );
            $response = explode('{', (string) $response->getBody());
            $response = '{'.$response[1];

            $result = json_decode($response);

            if ($result->status == 'fails') {
                return ['status' => 'false', 'message' => $result->message];
            } elseif ($result->status == 'validationFailure') {
                return ['status' => 'validationFailure', 'message' => $result->message];
            } else {
                $userData = 'Your instance has been created successfully. <br> Email:'.' '.$user.'<br>'.'Password:'.$result->password;

                $setting = Setting::find(1);
                $mail = new \App\Http\Controllers\Common\PhpMailController();
                $mail->sendEmail($setting->email, $user, $userData, 'New instance created');

                return ['status' => 'true', 'message' => $result->message];
            }
        } catch (Exception $e) {
            return ['status' => 'false', 'message' => $e->getMessage()];
        }
    }

    public function verifyThirdPartyToken(Request $request)
    {
        try {
            $token = $request->input('token');
            $userId = $request->input('userId');
            $faveoToken = \DB::table('third_party_tokens')->where('user_id', $userId)->value('token');
            if ($faveoToken && $token == $faveoToken) {
                \DB::table('third_party_tokens')->where('user_id', $userId)->delete();
                //delete third party token here
                $response = ['status' => 'success', 'message' => 'Valid token'];
            } else {
                $response = ['status' => 'fails', 'message' => 'Invalid token'];
            }

            return $response;
        } catch (Exception $e) {
            $error = ['status' => 'fails', 'message' => $e->getMessage()];

            return $error;
        }
    }

    public function destroyTenant(Request $request)
    {
        try {
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            $token = str_random(32);
            $data = ['id' => $request->input('id'), 'app_key'=>$keys->app_key, 'token'=>$token, 'timestamp'=>time()];
            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
            $client = new Client([]);
            $response = $client->request(
                        'DELETE',
                        $this->url.'/tenants', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
                    );
            $responseBody = (string) $response->getBody();
            $response = json_decode($responseBody);
            if ($response->status == 'success') {
                return successResponse($response->message);
            } else {
                return errorResponse($response->message);
            }
        } catch (Exception $e) {
            return errorResponse($e->getMessage());
        }
    }
}
