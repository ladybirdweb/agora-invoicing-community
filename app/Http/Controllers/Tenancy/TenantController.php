<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Model\Common\FaveoCloud;
use App\Model\Common\Setting;
use App\Model\Common\StatusSetting;
use App\Model\Order\Order;
use App\ThirdPartyApp;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    private $cloud;

    public function __construct(Client $client, FaveoCloud $cloud)
    {
        $this->client = $client;
        $this->cloud = $cloud->first();

        $this->middleware('auth', ['except'=>['verifyThirdPartyToken']]);
    }

    public function viewTenant()
    {
        $cloud = $this->cloud;

        return view('themes.default1.tenant.index', compact('cloud'));
    }

    public function getTenants(Request $request)
    {
        try {
            $response = $this->client->request(
                    'GET',
                    $this->cloud->cloud_central_domain.'/tenants'
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
        } catch (ConnectException|Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }

        // $tenants =
    }

    private function postCurl($post_url, $post_info)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Logic for creating new tenant is handled here.
     */
    public function createTenant(Request $request)
    {
        $this->validate($request,
            [
                'orderNo' => 'required',
                'domain'=>  'required||string',
            ],
            [
                'domain.regex' => 'Special characters are not allowed in domain name',
            ]);
        try {
            $faveoCloud = $request->domain;
            $dns_record = dns_get_record($faveoCloud,DNS_CNAME);
            if(empty($dns_record) || $dns_record[0]['domain']!= $this->cloud->cloud_central_domain){
                throw new Exception('Your Domains DNS CNAME record is not pointing to our cloud!(CNAME record is missing) Please do it to proceed');
            }
            $licCode = Order::where('number', $request->input('orderNo'))->first()->serial_key;
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            if (! $keys->app_key) {//Valdidate if the app key to be sent is valid or not
                throw new Exception('Invalid App key provided. Please contact admin.');
            }
            $token = str_random(32);
            \DB::table('third_party_tokens')->insert(['user_id'=>\Auth::user()->id, 'token'=>$token]);
            $user = \Auth::user()->email;
            $client = new Client([]);
            $data = ['domain' => $request->input('domain'), 'app_key'=>$keys->app_key, 'token'=>$token, 'lic_code'=>$licCode, 'username'=>$user, 'userId'=>\Auth::user()->id, 'timestamp'=>time()];

            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
            $response = $client->request(
                'POST',
                $this->cloud->cloud_central_domain.'/tenants', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
            );

            $response = explode('{', (string) $response->getBody());

            $response = '{'.$response[1];

            $result = json_decode($response);
            if ($result->status == 'fails') {
                return ['status' => 'false', 'message' => $result->message];
            } elseif ($result->status == 'validationFailure') {
                return ['status' => 'validationFailure', 'message' => $result->message];
            } else {
                $url = $this->cloud->cron_server_url.'/croncreate.php';
                $key = $this->cloud->cron_server_key;
                $tenant = $result->tenant;
                $response = $this->postCurl($url, "tenant=$tenant&key=$key");
                $cronResult = json_decode($response)->status;
                $cronFailureMessage = '';
                if ($cronResult == 'fails') {
                    $cronFailureMessage = '<br><br>Cron creation failed. You mail fetching would not work without this. Please contact Faveo team.';
                }

                $userData = $result->message.'.<br> Email:'.' '.$user.'<br>'.'Password:'.' '.$result->password;

                $setting = Setting::find(1);
                $mail = new \App\Http\Controllers\Common\PhpMailController();
                $mail->sendEmail($setting->email, $user, $userData, 'New instance created');

                return ['status' => $result->status, 'message' => $result->message.'.'.$cronFailureMessage];
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
            $data = ['id' => $request->input('id'), 'app_key'=>$keys->app_key, 'deleteTenant'=> true, 'token'=>$token, 'timestamp'=>time()];
            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
            $client = new Client([]);
            $response = $client->request(
                        'DELETE',
                        $this->cloud->cloud_central_domain.'/tenants', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
                    );
            $responseBody = (string) $response->getBody();
            $response = json_decode($responseBody);
            if ($response->status == 'success') {
                $this->deleteCronForTenant($request->input('id'));

                return successResponse($response->message);
            } else {
                return errorResponse($response->message);
            }
        } catch (Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    private function deleteCronForTenant($tenantId)
    {
        $url = $this->cloud->cron_server_url.'/crondelete.php';
        $key = $this->cloud->cron_server_key;
        $response = $this->postCurl($url, "tenant=$tenantId&key=$key");
        $cronResult = json_decode($response)->status;
        if ($cronResult == 'fails') {
            throw new \Exception('Tenant and storage deleted but cron deletion failed. Please contact server team');
        }
    }

    public function saveCloudDetails(Request $request)
    {
        $this->validate($request, [
            'cloud_central_domain'=> 'required',
        ]);

        try {
            $cloud = new FaveoCloud;
            $cloud->updateOrCreate(['id'=>1], ['cloud_central_domain'=>$request->input('cloud_central_domain'), 'cron_server_url'=> $request->input('cron_server_url'),
                'cron_server_key'=> $request->input('cron_server_key'), ]);
            // $cloud->first()->fill($request->all())->save();
            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }
    public function changeDomain(Request $request)
    {
        $this->validate($request, [
            'currentDomain'=> 'required',
            'newDomain'=>'required',
        ]);
        $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
        $token = str_random(32);
        $newDomain = $request->get('newDomain');
        $data = ['currentDomain' => $request->get('currentDomain'), 'newDomain'=>$newDomain, 'app_key'=>$keys->app_key, 'token'=>$token, 'timestamp'=>time()];
        $dns_record = dns_get_record($newDomain, DNS_CNAME);
        if (empty($dns_record) || $dns_record[0]['domain'] != $this->cloud->cloud_central_domain) {
            throw new Exception('Your Domains DNS CNAME record is not pointing to our cloud!(CNAME record is missing) Please do it to proceed');
        }
        $encodedData = http_build_query($data);
        $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
        $client = new Client([]);
        $response = $client->request(
            'POST',
            $this->cloud->cloud_central_domain.'/changeDomain', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
        );

        return response(['message'=> $response]);
    }
    public function DeleteCloudInstanceForClient($orderNumber,$isDelete){
        if($isDelete){
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            $token = str_random(32);
            $order_id=Order::where('number',$orderNumber)->where('client',\Auth::user()->id)->value('id');
            $installation_path = \DB::table('installation_details')->where('order_id',$order_id)->value('installation_path');
            $response = $this->client->request(
                'GET',
                $this->cloud->cloud_central_domain.'/tenants'
            );
            $responseBody = (string) $response->getBody();
            $response = json_decode($responseBody);
            $domainArray = $response->message;
            for($i=0;$i<count($domainArray);$i++){
                if($domainArray[$i]->domain == $installation_path){
                    $data = ['id' => $domainArray[$i]->id, 'app_key'=>$keys->app_key, 'deleteTenant'=> true, 'token'=>$token, 'timestamp'=>time()];
                    $encodedData = http_build_query($data);
                    $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
                    $client = new Client([]);
                    $response = $client->request(
                        'DELETE',
                        $this->cloud->cloud_central_domain.'/tenants', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
                    );
                    $responseBody = (string) $response->getBody();
                    $response = json_decode($responseBody);
                    if ($response->status == 'success') {
                        $this->deleteCronForTenant( $domainArray[$i]->id);
                        $this->reissueCloudLicense($order_id);
                        Order::where('number',$orderNumber)->where('client',\Auth::user()->id)->delete();
                        return redirect()->back()->with('success', $response->message);
                    }
                    else {
                        return redirect()->back()->with('fails', $response->message);
                    }
                }

            }
            return redirect()->back()->with('fails', "Something went wrong, we couldn't delete your cloud instance.(mostly your cloud instance was already deleted)");

        }
    }
    protected function reissueCloudLicense($order_id)
    {
        $order = Order::findorFail($order_id);
        if (\Auth::user()->role != 'admin' && $order->client != \Auth::user()->id) {
            return errorResponse('Cannot remove license installations. Invalid modification of data');
        }
        $order->domain = '';
        $licenseCode = $order->serial_key;
        $order->save();
        $licenseStatus = StatusSetting::pluck('license_status')->first();
        if ($licenseStatus == 1) {
            $licenseExpiry = $order->subscription->ends_at;
            $updatesExpiry = $order->subscription->update_ends_at;
            $supportExpiry = $order->subscription->support_ends_at;
            $cont = new \App\Http\Controllers\License\LicenseController();
            $updateLicensedDomain = $cont->updateLicensedDomain($licenseCode, $order->domain, $order->product, $licenseExpiry, $updatesExpiry, $supportExpiry, $order->number);
            //Now make Installation status as inactive
            $updateInstallStatus = $cont->updateInstalledDomain($licenseCode, $order->product);
        }

        return ['message' => 'success', 'update'=>'License installations removed'];
    }
}
