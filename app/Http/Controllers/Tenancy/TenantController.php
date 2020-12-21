<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\ThirdPartyApp;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->url = 'http://faveo.helpdesk';
    }

    /**
     * Complete logic for creating new tenant is handled here.
     */
    public function createTenant(Request $request)
    {
        $this->validate($request,
                [
                    'orderNo' => 'required',
                    'domain'=>  'required',
                ]);
        try {
            $licCode = Order::where('number', $request->input('orderNo'))->first()->serial_key;
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            if (! $keys->app_key) {//Valdidate if the app key to be sent is valid or not
                throw new Exception('Invalid App key provided. Please contact admin.');
            }
            $token = str_random(32);
            \DB::table('third_party_tokens')->insert(['user_id'=>\Auth::user()->id, 'token'=>$token]);
            $user = \Auth::user()->email;
            $client = new Client([]);
            $data = ['domain' => $request->input('domain'), 'app_key'=>$keys->app_key, 'token'=>$token, 'lic_code'=>$licCode, 'username'=>$user, 'userId'=>\Auth::user()->id, 'timestamp'=>Carbon::now()->timestamp];

            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);

            $response = $client->request(
                    'POST',
                    'http://faveo.helpdesk/tenants', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
                );
            var_dump($response);
            exit();
            // dd(json_decode($response->getBody()->getContents()));
            // dd($response);
            // json_decode($response->getBody()->getContents()
            return successResponse('Created successfully');
        } catch (Exception $e) {
            // var_dump($e);
            // die();
            dd($e);

            return errorResponse($e->getMessage());
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
                return successResponse('Valid token');
            } else {
                return errorResponse('Invalid Token');
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
