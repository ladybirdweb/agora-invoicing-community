<?php

namespace App\Http\Controllers\AutoUpdate;

use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\Model\Product\UpgradeSettings;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanUpgradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function storeUpgrade(Request $request)
    {
        $upgraded = $request->upgradeOrDowngrade;
        $oldProduct = $request->oldProduct;
        try {
            foreach ($upgraded as $upgrade) {
                UpgradeSettings::updateOrCreate(['product_id' => $oldProduct, 'upgrade_product_id' => $upgrade]);
            }
            UpgradeSettings::where('product_id', $oldProduct)->whereNotIn('upgrade_product_id', $upgraded)->delete();

            return redirect()->back()->with('success', trans('message.yes_its_done'));
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function getCost(Request $request, $id = null)
    {
        try {
            $planid = $request->input('plan');
            $userid = $request->input('user');
            if (is_null($id)) {
                $id = $request->input('subscription');
            }
            $subscription = Subscription::find($id);
            $licenseDate = \Carbon\Carbon::parse($subscription->ends_at);
            if (strtotime($licenseDate) < 0) {
                $licenseDate = '';
            }
            $orderDetails = array_first(Order::find($subscription->order()->value('id'))->invoice()->cursor());
            $plan = array_first(Plan::where('product', $planid)->cursor());
            $planDetails = userCurrencyAndPrice($userid, Plan::find($plan->id));
            if (Carbon::now() > $subscription->update_ends_at ||
                Carbon::now() > $subscription->support_ends_at ||
                Carbon::now() > $licenseDate) {
                return $planDetails['plan']->add_price;
            } else {
                if ($orderDetails->is_renewed) {
                    $price = $this->decideThePrice($planDetails['plan']->renew_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days);
                } else {
                    $price = $this->decideThePrice($planDetails['plan']->add_price, $orderDetails->grand_total, $subscription->update_ends_at, $plan->days);
                }
            }

            return $price;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    private function decideThePrice($planPrice, $grandTotal, $subEndsAt, $plan)
    {
        $months_remaining = (Carbon::now())->diffInMonths($subEndsAt);
        $month = floor(($plan / 30));
        if ($month - $months_remaining) {
            $renewPrice = (($planPrice) / $month);
            $PriceToBeConsidered = $renewPrice * $months_remaining;
            $price = $PriceToBeConsidered - $grandTotal;
        } else {
            $price = $planPrice - $grandTotal;
        }

        return $price;
    }

    public function upgradeForm($id)
    {
        try {
            $sub = (new Subscription())->find($id);
            $userid = $sub->user_id;
            if (User::onlyTrashed()->find($userid)) {//If User is soft deleted for this order
                throw new \Exception('The user for this order is suspended from the system. Restore the user to upgrade.');
            }
            $productid = $sub->product_id;
            $plans = (new Plan())->pluck('name', 'id')->toArray();

            return view('themes.default1.upgrade.upgrade', compact('id', 'productid', 'plans', 'userid'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function upgradeByClient($id, Request $request)
    {
        $price = $this->getCost($request, $id);

        $items = $this->addUpgradeProduct($request->plan, $price);
        \Cart::add($items); //Add Items To the Cart Collection

        return redirect('show/cart');
    }

    private function addUpgradeProduct(int $id, $price)
    {
        try {
            $qty = 1;
            $agents = 0; //Unlmited Agents
            $planid = Plan::where('product', $id)->pluck('id')->first();

            $product = Product::find($id);
            $plan = $product->planRelation->find($planid);

            if ($plan) { //If Plan For a Product exists
                $quantity = $plan->planPrice->first()->product_quantity;
                //If Product quantity is null(when show agent in Product Seting Selected),then set quantity as 1;
                $qty = $quantity != null ? $quantity : 1;
                $agtQty = $plan->planPrice->first()->no_of_agents;
                // //If Agent qty is null(when show quantity in Product Setting Selected),then set Agent as 0,ie Unlimited Agents;
                $agents = $agtQty != null ? $agtQty : 0;
                $currency = userCurrencyAndPrice('', $plan);
            } else {
                throw new \Exception('Product cannot be added to cart. No plan exists.');
            }
            $items = ['id' => $id, 'name' => $product->name, 'price' => $price,
                'quantity' => $qty, 'attributes' => ['currency' => $currency['currency'], 'symbol' => $currency['symbol'], 'agents' => $agents], 'associatedModel' => $product, ];

            return $items;
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
