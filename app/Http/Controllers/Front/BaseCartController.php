<?php

namespace App\Http\Controllers\Front;

use App\Model\Common\Country;
use App\Model\Payment\Plan;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Product;
use Bugsnag;
use Cart;
use Exception;
use Illuminate\Http\Request;
use Session;

class BaseCartController extends ExtendedBaseCartController
{
    public function getCartCollection($items)
    {
        if ($items == null) {
            $cartCollection = Cart::getContent();
        } else {
            $cartCollection = $items;
        }

        return $cartCollection;
    }

    /**
     * @param type $tax_class_id
     *
     * @throws \Exception
     *
     * @return type
     */
    public function getTaxByPriority($taxClassId)
    {
        try {
            $taxe_relation = Tax::where('tax_classes_id', $taxClassId)->get();

            return $taxe_relation;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('error in get tax priority');
        }
    }

    /**
     *   Get tax value for Same State.
     *
     * @param type $productid
     * @param type $c_gst
     * @param type $s_gst
     *                        return type
     */
    public function getValueForSameState($productid, $c_gst, $s_gst, $taxClassId, $taxes)
    {
        try {
            $value = $taxes->toArray()[0]['active'] ?
                    (TaxProductRelation::where('product_id', $productid)->where('tax_class_id', $taxClassId)->count() ?
                        $c_gst + $s_gst.'%' : 0) : 0;

            return $value;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     *   Get tax value for Other States.
     *
     * @param type $productid
     * @param type $i_gst
     *                        return type
     */
    public function getValueForOtherState($productid, $i_gst, $taxClassId, $taxes)
    {
        $value = '';
        $value = $taxes->toArray()[0]['active'] ? //If the Current Class is active
              (TaxProductRelation::where('product_id', $productid)->where('tax_class_id', $taxClassId)->count() ?
                        $i_gst.'%' : 0) : 0; //IGST

        return $value;
    }

    /**
     *  Get tax value for Union Territory States.
     *
     * @param type $productid
     * @param type $c_gst
     * @param type $ut_gst
     *                        return type
     */
    public function getValueForUnionTerritory($productid, $c_gst, $ut_gst, $taxClassId, $taxes)
    {
        $value = '';
        $value = $taxes->toArray()[0]['active'] ?
             (TaxProductRelation::where('product_id', $productid)
                ->where('tax_class_id', $taxClassId)
                ->count() ? $ut_gst + $c_gst.'%' : 0) : 0;

        return $value;
    }

    public function getValueForOthers($productid, $taxClassId, $taxes)
    {
        $otherRate = 0;
        $status = $taxes->toArray()[0]['active'];
        if ($status && (TaxProductRelation::where('product_id', $productid)
            ->where('tax_class_id', $taxClassId)->count() > 0)) {
            $otherRate = Tax::where('tax_classes_id', $taxClassId)->first()->rate;
        }

        $value = $otherRate.'%';

        return $value;
    }

    /**
     * @param type $id
     *
     * @return array
     */
    public function addCurrencyAttributes($id)
    {
        try {
            $currency = $this->currency();
            $product = $this->product->where('id', $id)->first();
            if ($product) {
                $productCurrency = $this->currency();
                $currency = $this->currency->where('code', $productCurrency)->get()->toArray();
            } else {
                $currency = [];
            }

            return $currency;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     *
     * @return type
     */
    public function postContactUs(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ]);

        $set = new \App\Model\Common\Setting();
        $set = $set->findOrFail(1);

        try {
            $from = $set->email;
            $fromname = $set->company;
            $toname = '';
            $to = 'support@faveohelpdesk.com';
            $data = '';
            $data .= 'Name: '.$request->input('name').'<br/s>';
            $data .= 'Email: '.$request->input('email').'<br/>';
            $data .= 'Message: '.$request->input('message').'<br/>';
            $data .= 'Mobile: '.$request->input('Mobile').'<br/>';

            $subject = 'Faveo billing enquiry';
            $this->templateController->mailing($from, $to, $data, $subject, [], $fromname, $toname);

            return redirect()->back()->with('success', 'Your message was sent successfully. Thanks.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Reduce No. of Agents When Minus button Is Clicked.
     *
     * @param Request $request Get productid , Product quantity ,Price,Currency,Symbol as Request
     *
     * @return success
     */
    public function reduceAgentQty(Request $request)
    {
        $id = $request->input('productid');
        $agtqty = $request->input('agtQty');
        $price = $request->input('actualAgentprice');
        $currency = $request->input('currency');
        $symbol = $request->input('symbol');
        Cart::update($id, [
             'price'    => $price,
           'attributes' => ['agents' => $agtqty, 'currency'=>['currency'=>$currency, 'symbol'=>$symbol]],
        ]);

        return 'success';
    }

    /**
     * Update The Quantity And Price in cart when No of Agents Increasd.
     *
     * @param Request $request Get productid , Product quantity ,Price,Currency,Symbol as Request
     *
     * @return success
     */
    public function updateAgentQty(Request $request)
    {
        $id = $request->input('productid');
        $agtqty = $request->input('agtQty');
        $price = $request->input('actualAgentprice');
        $currency = $request->input('currency');
        $symbol = $request->input('symbol');
        Cart::update($id, [
           'price'      => $price,
           'attributes' => ['agents' =>  $agtqty, 'currency'=>['currency'=>$currency, 'symbol'=>$symbol]],
        ]);

        return 'success';
    }

    /**
     * Reduce The Quantity And Price in cart whenMinus Button is Clicked.
     *
     * @param Request $request Get productid , Product quantity ,Price as Request
     *
     * @return success
     */
    public function reduceProductQty(Request $request)
    {
        $id = $request->input('productid');
        $qty = $request->input('qty');
        $price = $request->input('actualprice');
        Cart::update($id, [
                'quantity' => -1,
                'price'    => $price,
        ]);

        return 'success';
    }

    /**
     * Update The Quantity And Price in cart when No of Products Increasd.
     *
     * @param Request $request Get productid , Product quantity ,Price as Request
     *
     * @return success
     */
    public function updateProductQty(Request $request)
    {
        $id = $request->input('productid');
        $qty = $request->input('qty');
        $price = $request->input('actualprice');
        Cart::update($id, [
                'quantity' => [
                'relative' => false,
                'value'    => $qty,
            ],
           'price'  => $price,
        ]);

        return 'success';
    }

    /**
     * Returns the Collection to be added to cart.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-10T18:14:09+0530
     *
     * @param int $id Product Id
     *
     * @return array $items  Array of items and Tax conditions to the cart
     */
    public function addProduct(int $id)
    {
        try {
            $qty = 1;
            $agents = 0; //Unlmited Agents
            $planid = $this->checkPlanSession() === true ? Session::get('plan') : Plan::where('product', $id)->pluck('id')->first(); //Get Plan id From Session
            $product = Product::find($id);
            $plan = $product->planRelation->find($planid);
            if ($plan) { //If Plan For a Product exists
                $quantity = $plan->planPrice->first()->product_quantity;
                //If Product quantity is null(when show agent in Product Seting Selected),then set quantity as 1;
                $qty = $quantity != null ? $quantity : 1;
                $agtQty = $plan->planPrice->first()->no_of_agents;
                // //If Agent qty is null(when show quantity in Product Setting Selected),then set Agent as 0,ie Unlimited Agents;
                $agents = $agtQty != null ? $agtQty : 0;
            }
            $currency = $this->currency();
            $actualPrice = $this->cost($product->id);
            // $taxConditions = $this->checkTax($id);
            $items = ['id'     => $id, 'name' => $product->name, 'price' => $actualPrice,
                 'quantity'    => $qty, 'attributes' => ['currency' => $currency, 'agents'=> $agents], ];

            return $items;
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * @param type $id
     * @param type $key
     * @param type $value
     */
    public function cartUpdate($id, $key, $value)
    {
        try {
            Cart::update(
                    $id, [
                $key => $value, // new item name
                    ]
            );
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $iso
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function findCountryByGeoip($iso)
    {
        try {
            $country = \App\Model\Common\Country::where('country_code_char2', $iso)->first();
            if ($country) {
                return $country->country_code_char2;
            } else {
                return '';
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $iso
     *
     * @throws \Exception
     *
     * @return array
     */
    public static function findStateByRegionId($iso)
    {
        try {
            $states = \App\Model\Common\State::where('country_code_char2', $iso)
            ->pluck('state_subdivision_name', 'state_subdivision_code')->toArray();

            return $states;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get Currency Status(ON/oFF) on the basis of Country detected by Geoip
     * when User is not Logged In.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-10T01:56:32+0530
     *
     * @return int Currency Status
     */
    public function getStatuswhenNotLoggedin()
    {
        $cont = new \App\Http\Controllers\Front\PageController();
        $location = $cont->getLocation();
        $country = self::findCountryByGeoip($location['iso_code']);
        $currencyStatus = $userCountry->currency->status;

        return $currencyStatus;
    }
}
