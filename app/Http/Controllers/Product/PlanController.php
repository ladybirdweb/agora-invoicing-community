<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\License\LicensePermissionsController;
use App\Model\Common\Setting;
use App\Model\Payment\Currency;
use App\Model\Payment\Period;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use Bugsnag;
use Illuminate\Http\Request;

class PlanController extends ExtendedPlanController
{
    protected $currency;
    protected $price;
    protected $period;
    protected $product;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin');
        $plan = new Plan();
        $this->plan = $plan;
        $subscription = new Subscription();
        $this->subscription = $subscription;
        $currency = new Currency();
        $this->currency = $currency;
        $price = new PlanPrice();
        $this->price = $price;
        $period = new Period();
        $this->period = $period;
        $product = new Product();
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Response
     */
    public function index()
    {
        $currency = $this->currency->where('status', '1')->pluck('name', 'code')->toArray();
        $periods = $this->period->pluck('name', 'days')->toArray();
        $products = $this->product->pluck('name', 'id')->toArray();

        return view('themes.default1.product.plan.index', compact('currency', 'periods', 'products'));
    }

    /**
     * Get plans for chumper datatable.
     */
    public function getPlans()
    {
        $new_plan = Plan::select('id', 'name', 'days', 'product')->get();
        $defaultCurrency = Setting::where('id', 1)->value('default_currency');

        return\ DataTables::of($new_plan)
                        ->addColumn('checkbox', function ($model) {
                            return "<input type='checkbox' class='plan_checkbox' 
                            value=".$model->id.' name=select[] id=check>';
                        })
                        ->addColumn('name', function ($model) {
                            return ucfirst($model->name);
                        })
                        ->addColumn('days', function ($model) {
                            if ($model->days != '') {
                                $months = $model->days / 30;

                                return round($months);
                            }

                            return 'Not Available';
                        })
                        ->addColumn('product', function ($model) {
                            $productid = $model->product;
                            $product = $this->product->where('id', $productid)->first();
                            $response = '';
                            if ($product) {
                                $response = $product->name;
                            }

                            return ucfirst($response);
                        })
                         ->addColumn('price', function ($model) use ($defaultCurrency) {
                             $price = PlanPrice::where('plan_id', $model->id)->where('currency', $defaultCurrency)
                            ->pluck('add_price')->first();
                             if ($price != null) {
                                 return $price;
                             } else {
                                 return 'Not Available';
                             }
                         })
                         ->addColumn('currency', function ($model) use ($defaultCurrency) {
                             if ($defaultCurrency && $defaultCurrency != null) {
                                 return $defaultCurrency;
                             } else {
                                 return 'Not Available';
                             }
                         })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('plans/'.$model->id.'/edit')." 
                            class='btn btn-sm btn-primary btn-xs'><i class='fa fa-edit' 
                            style='color:white;'> </i>&nbsp;&nbsp;Edit</a>";
                        })
                        ->rawColumns(['checkbox', 'name', 'days', 'product', 'price', 'currency', 'action'])
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        $currency = $this->currency->where('status', 1)->pluck('name', 'code')->toArray();
        $periods = $this->period->pluck('name', 'days')->toArray();
        $products = $this->product->pluck('name', 'id')->toArray();

        return view('themes.default1.product.plan.create', compact('currency', 'periods', 'products'));
    }

    /**
     * Store the Plans Details While Plan Creation.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-08T13:32:57+0530
     *
     * @param Request $request Plan Form Details
     *
     * @return [type] Saves Plan
     */
    public function store(Request $request)
    {
        $permissions = LicensePermissionsController::getPermissionsForProduct($request->input('product'));
        $subs = $permissions['generateUpdatesxpiryDate'] != 0 || $permissions['generateLicenseExpiryDate'] != 0
           || $permissions['generateSupportExpiryDate'] != 0 ? 1 : 0;
        $days_rule = $subs == 1 ? 'required|' : 'sometimes|';

        $this->validate($request, [
            'name'             => 'required',
            'days'             => $days_rule.'numeric',
            'add_price.*'      => 'required',
            'product'          => 'required',
            'product_quantity' => 'required_without:no_of_agents|integer|min:1',
            'no_of_agents'     => 'required_without:product_quantity|integer|min:0',
        ]);
        $product_quantity = $request->input('product_quantity');
        $no_of_agents = $request->input('no_of_agents');
        $this->plan->fill($request->input())->save();
        if ($request->input('days') != '') {
            $period = Period::where('days', $request->input('days'))->first()->id;
            $this->plan->periods()->attach($period);
        }

        $add_prices = $request->input('add_price');
        $renew_prices = $request->input('renew_price');
        $product = $request->input('product');
        $priceDescription = $request->input('price_description');

        if (count($add_prices) > 0) {
            foreach ($add_prices as $key => $price) {
                $renew_price = '';
                if (array_key_exists($key, $renew_prices)) {
                    $renew_price = $renew_prices[$key];
                }
                $this->price->create([
                    'plan_id'           => $this->plan->id,
                    'currency'          => $key,
                    'add_price'         => $price,
                    'renew_price'       => $renew_price,
                    'price_description' => $priceDescription,
                    'product_quantity'  => $product_quantity,
                    'no_of_agents'      => $no_of_agents,
                ]);
            }
        }

        return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function edit($id)
    {
        $plan = $this->plan->where('id', $id)->first();
        $currency = $this->currency->where('status', '1')->pluck('name', 'code')->toArray();
        $add_price = $this->price->where('plan_id', $id)->pluck('add_price', 'currency')->toArray();
        $renew_price = $this->price->where('plan_id', $id)->pluck('renew_price', 'currency')->toArray();
        $periods = $this->period->pluck('name', 'days')->toArray();
        $products = $this->product->pluck('name', 'id')->toArray();
        $priceDescription = $plan->planPrice->first()->price_description;
        $productQunatity = $plan->planPrice->first()->product_quantity;
        $agentQuantity = $plan->planPrice->first()->no_of_agents;
        foreach ($products as $key => $product) {
            $selectedProduct = $this->product->where('id', $plan->product)
          ->pluck('name', 'id', 'subscription')->toArray();
        }
        $selectedPeriods = $this->period->where('days', $plan->days)
       ->pluck('name', 'days')->toArray();

        return view(
            'themes.default1.product.plan.edit',
            compact(
                'plan',
                'currency',
                'add_price',
                'renew_price',
                'periods',
                'products',
                'selectedPeriods',
                'selectedProduct',
                'priceDescription',
                'productQunatity',
                'agentQuantity'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function update($id, Request $request)
    {
        $permissions = LicensePermissionsController::getPermissionsForProduct($request->input('product'));
        $subs = $permissions['generateUpdatesxpiryDate'] != 0 || $permissions['generateLicenseExpiryDate'] != 0
           || $permissions['generateSupportExpiryDate'] != 0 ? 1 : 0;
        $days_rule = $subs == 1 ? 'required|' : 'sometimes|';

        $this->validate($request, [
            'name'                => 'required',
            'add_price.*'         => 'required',
            'product'             => 'required',
              'days'              => $days_rule.'numeric',
               'product_quantity' => 'required_without:no_of_agents|integer|min:0',
            'no_of_agents'        => 'required_without:product_quantity|integer|min:0',
        ]);
        $product_quantity = $request->input('product_quantity');
        $no_of_agents = $request->input('no_of_agents');
        $priceDescription = $request->input('price_description');
        $plan = $this->plan->where('id', $id)->first();
        $plan->fill($request->input())->save();
        $add_prices = $request->input('add_price');
        $renew_prices = $request->input('renew_price');
        $product = $request->input('product');
        $period = $request->input('days');

        if (count($add_prices) > 0) {
            $price = $this->price->where('plan_id', $id)->get();

            if (count($price) > 0) {
                foreach ($price as $delete) {
                    $delete->delete();
                }
            }
            foreach ($add_prices as $key => $price) {
                $renew_price = '';
                if (array_key_exists($key, $renew_prices)) {
                    $renew_price = $renew_prices[$key];
                }
                $this->price->create([
                    'plan_id'           => $plan->id,
                    'currency'          => $key,
                    'add_price'         => $price,
                    'renew_price'       => $renew_price,
                    'price_description' => $priceDescription,
                    'product_quantity'  => $product_quantity,
                    'no_of_agents'      => $no_of_agents,
                ]);
            }
        }

        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('select');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $plan = $this->plan->where('id', $id)->first();
                if ($plan) {
                    $plan->delete();
                } else {
                    echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                    //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                }
            }
            echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
        } else {
            echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */ \Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */ \Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.select-a-row').'
                </div>';
            //echo \Lang::get('message.select-a-row');
        }
    }

    /**
     * Whether to show Periods when Product Selected
     * Whether to show Product Quantity or No of Agents when Product Is Selected.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-08T12:30:09+0530
     *
     * @param Request $request Receive Product Id as Paramater
     *
     * @return json Returns Boolean value FOR Whether Periods/Agents Enabled for Product
     */
    public function checkSubscription(Request $request)
    {
        try {
            $product_id = $request->input('product_id');
            $permissions = LicensePermissionsController::getPermissionsForProduct($product_id);
            $checkSubscription = $permissions['generateUpdatesxpiryDate'] != 0 || $permissions['generateLicenseExpiryDate'] != 0
           || $permissions['generateSupportExpiryDate'] != 0 ? 1 : 0;
            $product = Product::find($product_id);
            $checkIfAgentEnabled = ($product->show_agent == 1) ? 1 : 0;
            $result = ['subscription'=> $checkSubscription, 'agentEnable'=>$checkIfAgentEnabled];

            return response()->json($result);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);
            $result = ['subscription' => $ex->getMessage()];

            return response()->json($result);
        }
    }
}
