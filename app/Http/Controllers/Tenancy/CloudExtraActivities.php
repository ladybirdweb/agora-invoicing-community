<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\License\LicenseController;
use App\Model\Common\FaveoCloud;
use App\Model\Order\InstallationDetail;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\ThirdPartyApp;
use App\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CloudExtraActivities extends Controller
{
    public function __construct(Client $client, FaveoCloud $cloud)
    {
        $this->client = $client;
        $this->cloud = $cloud->first();

        $this->middleware('auth', ['except' => ['verifyThirdPartyToken', 'storeTenantTillPurchase']]);
    }
//    public function upgradePlan(Request $request)
//    {
//        $price = $this->getCost($request);
//        $items = $this->addUpgradeProduct($request->plan_id, $price, $request->number_agents);
//        \Cart::add($items); //Add Items To the Cart Collection
//
//        return response()->json(['redirectTo' => env('APP_URL').'/show/cart']);
//    }

//    private function addUpgradeProduct($id, $price, $agents)
//    {
//        try {
//            $qty = 1;
//            $product_id = Plan::where('id', $id)->pluck('product')->first();
//
//            $product = Product::find($product_id);
//            $plan = $product->planRelation->find($id);
//
//            if ($plan) { //If Plan For a Product exists
//                $quantity = $plan->planPrice->first()->product_quantity;
//                //If Product quantity is null(when show agent in Product Seting Selected),then set quantity as 1;
//                $qty = $quantity != null ? $quantity : 1;
//                $currency = userCurrencyAndPrice('', $plan);
//            } else {
//                throw new \Exception('Product cannot be added to cart. No plan exists.');
//            }
//            $items = ['id' => $product_id, 'name' => $product->name, 'price' => $price,
//                'quantity' => $qty, 'attributes' => ['currency' => $currency['currency'], 'symbol' => $currency['symbol'], 'agents' => $agents], 'associatedModel' => $product, ];
//
//            return $items;
//        } catch (\Exception $e) {
//            app('log')->error($e->getMessage());
//            throw new \Exception($e->getMessage());
//        }
//    }

//    public function getCost(Request $request, $id = null)
//    {
//        try {
//            $planid = $request->input('plan_id');
//            $userid = $request->input('order')['client'];
//            $orderId = $request->input('order')['id'];
//            $id = Subscription::where('order_id', $orderId)->value('id');
//            $numberOfAgents = $request->number_agents;
//            $subscription = Subscription::find($id);
//            $licenseDate = \Carbon\Carbon::parse($subscription->ends_at);
//            if (strtotime($licenseDate) < 0) {
//                $licenseDate = '';
//            }
//            $orderDetails = array_first(Order::find($subscription->order()->value('id'))->invoice()->cursor());
//            $plan = array_first(Plan::where('id', $planid)->cursor());
//            $planDetails = userCurrencyAndPrice($userid, Plan::find($plan->id));
//            $domain = InstallationDetail::where('order_id', $orderId)->value('installation_path');
//            $initialAgentCount = ltrim($request->input('order')['serial_key'][12], '0');
//            if (Carbon::now() > $subscription->update_ends_at ||
//                Carbon::now() > $subscription->support_ends_at ||
//                Carbon::now() > $licenseDate) {
//                if ($initialAgentCount > $numberOfAgents) {
//                    if ($this->checktheAgent($numberOfAgents, $domain) == 0) {
//                        return $planDetails['plan']->add_price * $numberOfAgents;
//                    } else {
//                        return errorResponse(trans('message.reduce_your_agents'));
//                    }
//                } else {
//                    return $planDetails['plan']->add_price * $numberOfAgents;
//                }
//            } else {
//                if ($initialAgentCount > $numberOfAgents) {
//                    if ($this->checktheAgent($numberOfAgents, $domain) == 0) {
//                        if ($orderDetails->is_renewed) {
//                            $price = $this->decideThePrice($planDetails['plan']->renew_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days, $numberOfAgents);
//                        } else {
//                            $price = $this->decideThePrice($planDetails['plan']->add_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days, $numberOfAgents);
//                        }
//                    } else {
//                        return errorResponse(trans('message.reduce_your_agents'));
//                    }
//                } else {
//                    if ($orderDetails->is_renewed) {
//                        $price = $this->decideThePrice($planDetails['plan']->renew_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days, $numberOfAgents);
//                    } else {
//                        $price = $this->decideThePrice($planDetails['plan']->add_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days, $numberOfAgents);
//                    }
//                }
//            }
//
//            return $price;
//        } catch (\Exception $ex) {
//            throw new \Exception($ex->getMessage());
//        }
//    }
//
//    private function decideThePrice($planPrice, $grandTotal, $subEndsAt, $plan, $numberOfAgents)
//    {
//        if ($grandTotal == 0) {
//            return $planPrice * $numberOfAgents;
//        }
//        if (strlen((string) $plan) < 3) {
//            $months_remaining = Carbon::now()->diffInDays($subEndsAt);
//            $month = $plan;
//        } else {
//            $months_remaining = Carbon::now()->diffInMonths($subEndsAt);
//            $month = floor($plan / 30);
//        }
//        if ($month - $months_remaining) {
//            $renewPrice = (($planPrice * $numberOfAgents) / $month);
//            $PriceToBeConsidered = $renewPrice * $months_remaining;
//            $price = ($PriceToBeConsidered * $numberOfAgents) - $grandTotal;
//        } else {
//            $price = ($planPrice * $numberOfAgents) - $grandTotal;
//        }
//
//        return $price;
//    }

    private function checktheAgent($numberOfAgents, $domain)
    {
        $client = new Client([]);
        $data = ['number_of_agents' => $numberOfAgents];
        $response = $client->request(
            'POST',
            'https://'.$domain.'/api/agent-check', ['form_params'=>$data]
        );
        $response = explode('{', (string) $response->getBody());

        $response = array_first($response);

        return json_decode($response);
    }

    public function domainCloudAutofill()
    {
        // Fetch the company value from the database
        $company = User::where('id', \Auth::user()->id)->value('company');

        // Convert spaces to underscores
        $company = str_replace(' ', '', $company);

        // Convert uppercase letters to lowercase
        $company = substr(strtolower($company), 0, 28);

        // Output the modified company value
        return response()->json(['data'=> $company]);
    }

    public function orderDomainCloudAutofill(Request $request)
    {
        // Output the modified domain value
        return response()->json(['data'=> InstallationDetail::where('order_id', $request->orderId)->value('installation_path')]);
    }

    public function getUpgradeCost(Request $request)
    {
        try {
            $planid = $request->input('plan');
            $userid = $request->input('user');
            $agents = $request->input('agents');
            $plan = Plan::find($planid);
            $planDetails = userCurrencyAndPrice($userid, $plan);
            $price = $planDetails['plan']->add_price * $agents;

            return $price;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function changeDomain(Request $request)
    {
        try {
            $this->validate($request, [
                'currentDomain' => 'required',
                'newDomain' => 'required',
            ]);
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            $token = str_random(32);
            $newDomain = $request->get('newDomain');
            $currentDomain = $request->get('currentDomain');
            if ($newDomain === $currentDomain) {
                return response(['status' => false, 'message' => trans('message.nothing_changed')]);
            }
            $data = ['currentDomain' => $currentDomain, 'newDomain' => $newDomain, 'app_key' => $keys->app_key, 'token' => $token, 'timestamp' => time()];
            $dns_record = dns_get_record($newDomain, DNS_CNAME);
            if (! strpos($newDomain, 'faveocloud.com')) {
                if (empty($dns_record) || ! in_array('faveocloud.com', array_column($dns_record, 'target'))) {
                    throw new Exception('Your Domains DNS CNAME record is not pointing to our cloud!(CNAME record is missing) Please do it to proceed');
                }
            }
            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
            $client = new Client([]);
            $client->request(
                'POST',
                $this->cloud->cloud_central_domain.'/changeDomain', ['form_params' => $data, 'headers' => ['signature' => $hashedSignature]]
            );
            $this->jobsForCloudDomain($newDomain, $currentDomain);

            return response(['status' => true, 'message' => trans('message.cloud_domain_change')]);
        } catch(\Exception $e) {
            return response(['status' => true, 'message' => trans('message.change_domain_failed')]);
        }
    }

    //No need to worry about performance because of if else,
    // these are just triggers that wait for no response
    private function jobsForCloudDomain($newDomain, $currentDomain)
    {
        $client = new Client([]);
        if (! strpos($currentDomain, 'faveocloud.com')) {
            if (! strpos($newDomain, 'faveocloud.com')) {
                $client->request('GET', env('CLOUD_JOB_URL'), [
                    'auth' => ['clouduser', env('CLOUD_AUTH')],
                    'query' => [
                        'token' => env('CLOUD_OAUTH_TOKEN'),
                        'domain' => $newDomain,
                    ],
                ]);
            } else {
                $client->request('GET', env('CLOUD_JOB_URL_NORMAL'), [
                    'auth' => ['clouduser', env('CLOUD_AUTH')],
                    'query' => [
                        'token' => env('CLOUD_OAUTH_TOKEN'),
                        'domain' => $newDomain,
                    ],
                ]);
            }
            $client->request('GET', env('CLOUD__DELETE_JOB_URL_CUSTOM'), [
                'auth' => ['clouduser', env('CLOUD_AUTH')],
                'query' => [
                    'token' => env('CLOUD_OAUTH_TOKEN'),
                    'domain' => $currentDomain,
                ],
            ]);
        } else {
            //normal
            if (! strpos($newDomain, 'faveocloud.com')) {
                $client->request('GET', env('CLOUD_JOB_URL'), [
                    'auth' => ['clouduser', env('CLOUD_AUTH')],
                    'query' => [
                        'token' => env('CLOUD_OAUTH_TOKEN'),
                        'domain' => $newDomain,
                    ],
                ]);
            } else {
                $client->request('GET', env('CLOUD_JOB_URL_NORMAL'), [
                    'auth' => ['clouduser', env('CLOUD_AUTH')],
                    'query' => [
                        'token' => env('CLOUD_OAUTH_TOKEN'),
                        'domain' => $newDomain,
                    ],
                ]);
            }
            $client->request('GET', env('CLOUD__DELETE_JOB_URL_NORMAL'), [
                'auth' => ['clouduser', env('CLOUD_AUTH')],
                'query' => [
                    'token' => env('CLOUD_OAUTH_TOKEN'),
                    'domain' => $currentDomain,
                ],
            ]);
        }
    }

    public function agentAlteration(Request $request)
    {
        try {
            $newAgents = $request->newAgents;
            $orderId = $request->input('orderId');
            $installation_path = InstallationDetail::where('order_id', $orderId)
                ->value('installation_path');
            $product_id = $request->product_id;
            if (! $this->checktheAgent($newAgents, $installation_path)) {
                return response(['status' => false, 'message' => trans('message.agent_reduce')]);
            }
            $oldLicense = \Crypt::decrypt(Order::where('id', $orderId)->value('license_code'));
            $len = strlen($newAgents);
            switch ($len) {//Get Last Four digits based on No.Of Agents
                case '1':
                    $lastFour = '000'.$newAgents;
                    break;
                case '2':
                    $lastFour = '00'.$newAgents;
                    break;
                case '3':
                    $lastFour = '0'.$newAgents;
                    break;
                case '4':
                    $lastFour = $newAgents;
                    break;
                default:
                    $lastFour = '0000';
            }
            $license_code = substr($oldLicense, 0, -4).$lastFour;
            (new LicenseController())->updateLicense($license_code, $oldLicense);
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            $token = str_random(32);
            $data = ['licenseCode' => $license_code, 'installation_path' => $installation_path, 'product_id' => $product_id, 'app_key' => $keys->app_key, 'token' => $token, 'timestamp' => time()];
            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);

            $client = new Client([]);
            $client->request(
                'POST',
                $this->cloud->cloud_central_domain.'/performAgentUpgradeOrDowngrade', ['form_params' => $data, 'headers' => ['signature' => $hashedSignature]]
            );

            Order::where('id', $orderId)->update(['license_code' => \Crypt::encrypt(substr($license_code, 0, 12).$lastFour)]);

            return response(['status' => true, 'message' => trans('message.agent_updated')]);
        } catch(\Exception $e) {
            Logger::exception($e);

            return response(['status' => false, 'message' => trans('message.wrong_agent')]);
        }
    }

    public function upgradeDowngradeCloud(Request $request)
    {
        try {
            $qty = 1;
            $planId = $request->id;
            $price = $request->price;
            $agents = $request->agents;
            $product_id = Plan::where('id', $planId)->pluck('product')->first();

            $product = Product::find($product_id);
            $plan = $product->planRelation->find($planId);
            $currency = userCurrencyAndPrice('', $plan);

            $items = ['id' => $product_id, 'name' => $product->name, 'price' => $price,
                'quantity' => $qty, 'attributes' => ['currency' => $currency['currency'], 'symbol' => $currency['symbol'], 'agents' => $agents], 'associatedModel' => $product, ];

            \Cart::add($items); //Add Items To the Cart Collection

            return response()->json(['redirectTo' => env('APP_URL').'/show/cart']);
        } catch(\Exception $e) {
            app('log')->error($e->getMessage());

            return response(['status' => false, 'message' => trans('message.wrong_upgrade')]);
        }
    }

    public function storeTenantTillPurchase(Request $request)
    {
        if (! $this->checkDomain($request->input('domain'))) {
            return response(['status' => false, 'message' => trans('message.domain_taken')]);
        }
        (new CartController())->cart($request);

        return response()->json(['redirectTo' => env('APP_URL').'/show/cart']);
    }

    public function checkDomain($domain)
    {
        $client = new Client([]);
        $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
        $data = ['domain' => $domain, 'key' => $keys->app_key ];
        $response = $client->request(
            'POST',
            $this->cloud->cloud_central_domain.'/checkDomain', ['form_params'=>$data]
        );
        $response = explode('{', (string) $response->getBody());

        $response = array_first($response);

        return json_decode($response);
    }
}
