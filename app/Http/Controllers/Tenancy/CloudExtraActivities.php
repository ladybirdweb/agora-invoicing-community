<?php

namespace App\Http\Controllers\Tenancy;


use App\Http\Controllers\Controller;
use App\Model\Order\InstallationDetail;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CloudExtraActivities extends Controller
{
    public function upgradePlan(Request $request){

        $price = $this->getCost($request);
        $items = $this->addUpgradeProduct($request->plan_id, $price,$request->number_agents);
        \Cart::add($items);//Add Items To the Cart Collection
        return response()->json(['redirectTo' => env('APP_URL').'/show/cart']);
    }

    private function addUpgradeProduct($id, $price, $agents)
    {
        try {
            $qty = 1;
            $product_id = Plan::where('id', $id)->pluck('product')->first();

            $product = Product::find($product_id);
            $plan = $product->planRelation->find($id);

            if ($plan) { //If Plan For a Product exists
                $quantity = $plan->planPrice->first()->product_quantity;
                //If Product quantity is null(when show agent in Product Seting Selected),then set quantity as 1;
                $qty = $quantity != null ? $quantity : 1;
                $currency = userCurrencyAndPrice('', $plan);
            } else {
                throw new \Exception('Product cannot be added to cart. No plan exists.');
            }
            $items = ['id' => $product_id, 'name' => $product->name, 'price' => $price,
                'quantity' => $qty, 'attributes' => ['currency' => $currency['currency'], 'symbol' => $currency['symbol'], 'agents' => $agents], 'associatedModel' => $product, ];

            return $items;
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function getCost(Request $request, $id = null)
    {
        try {
            $planid = $request->input('plan_id');
            $userid = $request->input('order')['client'];
            $orderId=$request->input('order')['id'];
            $id=Subscription::where('order_id',$orderId)->value('id');
            $numberOfAgents = $request->number_agents;
            $subscription = Subscription::find($id);
            $licenseDate = \Carbon\Carbon::parse($subscription->ends_at);
            if (strtotime($licenseDate) < 0) {
                $licenseDate = '';
            }
            $orderDetails = array_first(Order::find($subscription->order()->value('id'))->invoice()->cursor());
            $plan = array_first(Plan::where('id', $planid)->cursor());
            $planDetails = userCurrencyAndPrice($userid, Plan::find($plan->id));
            $domain = InstallationDetail::where('order_id',$orderId)->value('installation_path');
            $initialAgentCount=ltrim($request->input('order')['serial_key'][12],'0');
            if (Carbon::now() > $subscription->update_ends_at ||
                Carbon::now() > $subscription->support_ends_at ||
                Carbon::now() > $licenseDate) {
                if($initialAgentCount > $numberOfAgents){
                    if ($this->checktheAgent($numberOfAgents,$domain)==0){
                        return $planDetails['plan']->add_price * $numberOfAgents;
                    }
                    else{
                        return errorResponse(trans('message.reduce_your_agents'));
                    }
                }
                else{
                    return $planDetails['plan']->add_price * $numberOfAgents;
                }
            } else {
                if($initialAgentCount > $numberOfAgents){
                    if ($this->checktheAgent($numberOfAgents,$domain)==0){
                        if ($orderDetails->is_renewed) {
                            $price = $this->decideThePrice($planDetails['plan']->renew_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days, $numberOfAgents);
                        } else {
                            $price = $this->decideThePrice($planDetails['plan']->add_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days, $numberOfAgents);
                        }
                    }
                    else{
                        return errorResponse(trans('message.reduce_your_agents'));
                    }
                }
                else {
                    if ($orderDetails->is_renewed) {
                        $price = $this->decideThePrice($planDetails['plan']->renew_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days, $numberOfAgents);
                    } else {
                        $price = $this->decideThePrice($planDetails['plan']->add_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days, $numberOfAgents);
                    }
                }
            }

            return $price;
        } catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    private function decideThePrice($planPrice, $grandTotal, $subEndsAt, $plan, $numberOfAgents)
    {
        if($grandTotal==0){
            return $planPrice * $numberOfAgents;
        }
        if (strlen((string)$plan)<3) {
            $months_remaining = (Carbon::now())->diffInDays($subEndsAt);
            $month = $plan;
        }
        else{
            $months_remaining = (Carbon::now())->diffInMonths($subEndsAt);
            $month = floor(($plan / 30));
        }
        if ($month - $months_remaining) {
            $renewPrice = (($planPrice * $numberOfAgents) / $month);
            $PriceToBeConsidered = $renewPrice * $months_remaining;
            $price = ($PriceToBeConsidered * $numberOfAgents) - $grandTotal;
        } else {
            $price = ($planPrice * $numberOfAgents) - $grandTotal;
        }
        return $price;
    }

    private function checktheAgent($numberOfAgents,$domain){
        $client = new Client([]);
        $data = ['number_of_agents' => $numberOfAgents];
        $response = $client->request(
            'POST',
            'https://'.$domain.'/api/agent-check', ['form_params'=>$data]
        );
        $response = explode('{', (string) $response->getBody());

        $response = '{'.$response[1];

        return json_decode($response);
    }
}