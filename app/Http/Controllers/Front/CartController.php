<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Common\TemplateController;
use App\Model\Common\Setting;
use App\Model\Payment\Currency;
use App\Model\Payment\PlanPrice;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxByState;
use App\Model\Payment\TaxClass;
use App\Model\Payment\TaxOption;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Product;
use Bugsnag;
use Cart;
use Exception;
use Illuminate\Http\Request;
use Session;

class CartController extends BaseCartController
{
    public $templateController;
    public $product;
    public $currency;
    public $addons;
    public $addonRelation;
    public $licence;
    public $tax_option;
    public $tax_by_state;
    public $setting;

    public function __construct()
    {
        $templateController = new TemplateController();
        $this->templateController = $templateController;

        $product = new Product();
        $this->product = $product;

        $plan_price = new PlanPrice();
        $this->$plan_price = $plan_price;

        $currency = new Currency();
        $this->currency = $currency;

        $tax = new Tax();
        $this->tax = $tax;

        $setting = new Setting();
        $this->setting = $setting;

        $tax_option = new TaxOption();
        $this->tax_option = $tax_option;

        $tax_by_state = new TaxByState();
        $this->tax_by_state = new $tax_by_state();

        // $this->middleware('Inatall');
        // $this->middleware('admin');
    }

    public function productList(Request $request)
    {
        try {
            $cont = new \App\Http\Controllers\Front\GetPageTemplateController();
            $location = $cont->getLocation();
        } catch (\Exception $ex) {
            $location = false;
            $error = $ex->getMessage();
        }

        $country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($location['countryCode']);
        $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($location['countryCode']);
        $states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
        $state_code = $location['countryCode'].'-'.$location['region'];
        $state = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
        $mobile_code = \App\Http\Controllers\Front\CartController::getMobileCodeByIso($location['countryCode']);
        $currency = $cont->getCurrency($location);

        \Session::put('currency', $currency);
        if (!\Session::has('currency')) {
            \Session::put('currency', 'INR');
        }

        try {
            $page_controller = new PageController();

            return $page_controller->cart();
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function cart(Request $request)
    {
        try {
            $plan = '';

            if ($request->has('subscription')) {
                $plan = $request->get('subscription');

                Session::put('plan', $plan);
            }
            $id = $request->input('id');

            if (!array_key_exists($id, Cart::getContent())) {
                $items = $this->addProduct($id);

                Cart::add($items);
            }

            return redirect('show/cart');
        } catch (\Exception $ex) {
            // dd($ex);
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function showCart()
    {
        try {
            $currency = 'INR';
            $cart_currency = 'INR';
            $attributes = [];
            $cartCollection = Cart::getContent();
            foreach ($cartCollection as $item) {
                $attributes[] = $item->attributes;
                $cart_currency = $attributes[0]['currency'];
                $currency = $attributes[0]['currency'];
                if (\Auth::user()) {
                    $cart_currency = $attributes[0]['currency'];
                    $user_currency = \Auth::user()->currency;
                    $user_country = \Auth::user()->country;
                    $user_state = \Auth::user()->state;
                    $currency = 'INR';
                    if ($user_currency == 1 || $user_currency == 'USD') {
                        $currency = 'USD';
                    }
                    if ($cart_currency != $currency) {
                        $id = $item->id;
                        Cart::remove($id);
                        $items = $this->addProduct($id);

                        Cart::add($items);
                        //
                    }
                }
            }
            // if ($cart_currency != $currency) {
            //     return redirect('show/cart');
            // }
            //dd(Cart::getContent());

            return view('themes.default1.front.cart', compact('cartCollection', 'attributes'));
        } catch (\Exception $ex) {
            //dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function checkTax($productid, $user_state = '', $user_country = '')
    {
        try {
            $taxCondition = [];
            $tax_attribute = [];
            $tax_attribute[0] = ['name' => 'null', 'rate' => 0, 'tax_enable' =>0];
            $taxCondition[0] = new \Darryldecode\Cart\CartCondition([
                'name'   => 'null',
                'type'   => 'tax',
                'target' => 'item',
                'value'  => '0%',
            ]);
            $cont = new \App\Http\Controllers\Front\GetPageTemplateController();
            $location = $cont->getLocation();

            $country = $this->findCountryByGeoip($location['countryCode']);
            $states = $this->findStateByRegionId($location['countryCode']);
            $states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
            $state_code = $location['countryCode'].'-'.$location['region'];
            $state = $this->getStateByCode($state_code);
            $mobile_code = $this->getMobileCodeByIso($location['countryCode']);
            $country_iso = $location['countryCode'];

            $geoip_state = $this->getGeoipState($state_code, $user_state);
            $geoip_country = $this->getGeoipCountry($country_iso, $user_country);

            if ($this->tax_option->findOrFail(1)->inclusive == 0) {
                $tax_rule = $this->tax_option->findOrFail(1);
                $shop = $tax_rule->shop_inclusive;
                $cart = $tax_rule->cart_inclusive;
                $tax_enable = $this->tax_option->findOrFail(1)->tax_enable;
                //Check the state of user for calculating GST(cgst,igst,utgst,sgst)
                $user_state = TaxByState::where('state_code', $geoip_state)->first();
                $origin_state = $this->setting->first()->state; //Get the State of origin
                $tax_class_id = TaxProductRelation::where('product_id', $productid)->pluck('tax_class_id')->toArray();

                if ($tax_class_id) {//If the product is allowed for tax (Check in tax_product relation table)
                   if ($tax_enable == 1) {//If GST is Enabled

                       $state_code = '';
                       $c_gst = '';
                       $s_gst = '';
                       $i_gst = '';
                       $ut_gst = '';
                       $value = '';
                       $rate = '';
                       $status = 1;

                       if ($user_state != '') {//Get the CGST,SGST,IGST,STATE_CODE of the user
                           $c_gst = $user_state->c_gst;
                           $s_gst = $user_state->s_gst;
                           $i_gst = $user_state->i_gst;
                           $ut_gst = $user_state->ut_gst;
                           $state_code = $user_state->state_code;

                           if ($state_code == $origin_state) {//If user and origin state are same
                               $rateForSameState = $this->getTaxWhenIndianSameState($user_state,
                                $origin_state, $productid, $c_gst, $s_gst, $state_code, $status);

                               $taxes = $rateForSameState['taxes'];
                               $status = $rateForSameState['status'];
                               $value = $rateForSameState['value'];
                           } elseif ($state_code != $origin_state && $ut_gst == 'NULL') {//If user is from other state
                               $rateForOtherState = $this->getTaxWhenIndianOtherState($user_state,
                                $origin_state, $productid, $i_gst, $state_code, $status);
                               $taxes = $rateForOtherState['taxes'];
                               $status = $rateForOtherState['status'];
                               $value = $rateForOtherState['value'];
                           } elseif ($state_code != $origin_state && $ut_gst != 'NULL') {//if user from Union Territory
                               $rateForUnionTerritory = $this->getTaxWhenUnionTerritory($user_state,
                                $origin_state, $productid, $c_gst, $ut_gst, $state_code, $status);
                               $taxes = $rateForUnionTerritory['taxes'];
                               $status = $rateForUnionTerritory['status'];
                               $value = $rateForUnionTerritory['value'];
                           }
                       } else {//If user from other Country
                           $taxClassId = Tax::where('state', $geoip_state)
                           ->orWhere('country', $geoip_country)
                           ->pluck('tax_classes_id')->first();
                           if ($taxClassId) { //if state equals the user State or country equals user country
                               $taxForSpecificCountry = $this->getTaxForSpecificCountry($taxClassId,
                                $productid, $status);
                               $taxes = $taxForSpecificCountry['taxes'];
                               $status = $taxForSpecificCountry['status'];
                               $value = $taxForSpecificCountry['value'];
                               $rate = $taxForSpecificCountry['value'];
                           } else {//if Tax is selected for Any Country Any State
                               $taxClassId = Tax::where('country', '')
                               ->where('state', 'Any State')
                               ->pluck('tax_classes_id')->first();
                               if ($taxClassId) {
                                   $taxForAnyCountry = $this->getTaxForAnyCountry($taxClassId, $productid, $status);
                                   $taxes = $taxForAnyCountry['taxes'];
                                   $status = $taxForAnyCountry['status'];
                                   $value = $taxForAnyCountry['value'];
                                   $rate = $taxForAnyCountry['value'];
                               } else {
                                   $taxes = [0];
                               }
                           }
                       }
                       foreach ($taxes as $key => $tax) {

                                    //All the da a attribute that is sent to the checkout Page if tax_compound=0
                           if ($taxes[0]) {
                               $tax_attribute[$key] = ['name' => $tax->name, 'c_gst'=>$c_gst,
                               's_gst'                        => $s_gst, 'i_gst'=>$i_gst, 'ut_gst'=>$ut_gst,
                                'state'                       => $state_code, 'origin_state'=>$origin_state,
                                 'tax_enable'                 => $tax_enable, 'rate'=>$value, 'status'=>$status, ];

                               $taxCondition[0] = new \Darryldecode\Cart\CartCondition([

                                            'name'   => 'no compound',
                                            'type'   => 'tax',
                                            'target' => 'item',
                                            'value'  => $value,
                                          ]);
                           } else {
                               $tax_attribute[0] = ['name' => 'null', 'rate' => 0, 'tax_enable' =>0];
                               $taxCondition[0] = new \Darryldecode\Cart\CartCondition([
                                           'name'   => 'null',
                                           'type'   => 'tax',
                                           'target' => 'item',
                                           'value'  => '0%',
                                         ]);
                           }
                       }
                   } elseif ($tax_enable == 0) {//If Tax enable is 0
                       $status = 1;
                       if ($this->tax_option->findOrFail(1)->tax_enable == 0) {
                           $taxClassId = Tax::where('country', '')
                           ->where('state', 'Any State')
                           ->pluck('tax_classes_id')->first(); //In case of India when
                           // other tax is available and tax is not enabled
                           if ($taxClassId) {
                               $taxes = $this->getTaxByPriority($taxClassId);
                               $value = $this->getValueForOthers($productid, $taxClassId, $taxes);
                               if ($value == 0) {
                                   $status = 0;
                               }
                               $rate = $value;
                               foreach ($taxes as $key => $tax) {
                                   $tax_attribute[$key] = ['name' => $tax->name, 'rate' => $value, 'tax_enable'=>0, 'status' => $status];
                                   $taxCondition[$key] = new \Darryldecode\Cart\CartCondition([

                                            'name'   => $tax->name,
                                            'type'   => 'tax',
                                            'target' => 'item',
                                            'value'  => $value,
                                        ]);
                               }
                           } else {//In case of other country
                               //when tax is available and tax is not enabled
                               //(Applicable when Global Tax class for any country and state is not there)
                               $taxClassId = Tax::where('state', $geoip_state)
                               ->orWhere('country', $geoip_country)
                               ->pluck('tax_classes_id')->first();
                               if ($taxClassId) { //if state equals the user State
                                   $taxes = $this->getTaxByPriority($taxClassId);
                                   $value = $this->getValueForOthers($productid, $taxClassId, $taxes);
                                   if ($value == '') {
                                       $status = 0;
                                   }
                                   $rate = $value;
                               }
                               foreach ($taxes as $key => $tax) {
                                   $tax_attribute[$key] = ['name' => $tax->name, 'rate' => $value, 'tax_enable'=>0, 'status' => $status];
                                   $taxCondition[$key] = new \Darryldecode\Cart\CartCondition([

                                            'name'   => $tax->name,
                                            'type'   => 'tax',
                                            'target' => 'item',
                                            'value'  => $value,
                                        ]);
                               }
                           }
                       }
                   }
                } else {
                    $tax_attribute[0] = ['name' => 'null', 'rate' => 0, 'tax_enable' =>0];
                    $taxCondition[0] = new \Darryldecode\Cart\CartCondition([
                'name'   => 'null',
                'type'   => 'tax',
                'target' => 'item',
                'value'  => '0%',
            ]);
                }
            }

            $currency_attribute = $this->addCurrencyAttributes($productid);

            return ['conditions' => $taxCondition,
            'attributes'         => ['tax' => $tax_attribute,
            'currency'                     => $currency_attribute, ], ];
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('Can not check the tax');
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
            $value = '';
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
              ->where('tax_class_id', $taxClassId)->count() ? $ut_gst + $c_gst.'%' : 0) : 0;

        return $value;
    }

    public function otherRate($productid)
    {
        // $taxClassOther = TaxClass::where('name', 'Others')->pluck('id')->toArray();
        // $otherRate = TaxProductRelation::where('product_id', $productid)->where('tax_class_id', $taxClassOther)->count() ? Tax::where('tax_classes_id', $taxClassOther)->first()->rate : '';
        $otherRate = '';

        return $otherRate;
    }

    public function getValueForOthers($productid, $taxClassId, $taxes)
    {
        $otherRate = 0;
        $status = $taxes->toArray()[0]['active'];
        if ($status && (TaxProductRelation::where('product_id', $productid)
          ->where('tax_class_id', $taxClassId)->count() > 0)) {
            $otherRate = Tax::where('tax_classes_id', $taxClassId)->first()->rate;
        }

        // $value= $taxes->toArray()[0]['active'] ?
        //  (TaxProductRelation::where('product_id', $productid)->where('tax_class_id', $taxClassId)->count() != 0) ?
        //  $otherRate = Tax::where('tax_classes_id', $taxClassId)->first()->rate;

        $value = $otherRate.'%';

        return $value;
    }

    public function cartRemove(Request $request)
    {
        $id = $request->input('id');
        Cart::remove($id);

        return 'success';
    }

    public function reduseQty(Request $request)
    {
        $id = $request->input('id');
        Cart::update($id, [
            'quantity' => -1, // so if the current product has a quantity of 4, it will subtract 1 and will result to 3
        ]);

        return 'success';
    }

    public function updateQty(Request $request)
    {
        $id = $request->input('productid');
        $qty = $request->input('qty');
        Cart::update($id, [
            'quantity' => [
                'relative' => false,
                'value'    => $qty,
            ],
        ]);

        return 'success';
    }

    /**
     * @return type
     */
    public function clearCart()
    {
        foreach (Cart::getContent() as $item) {
            if (\Session::has('domain'.$item->id)) {
                \Session::forget('domain'.$item->id);
            }
        }
        $this->removePlanSession();
        $renew_control = new \App\Http\Controllers\Order\RenewController();
        $renew_control->removeSession();
        Cart::clear();

        return redirect('show/cart');
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
        }
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
        }
    }

    /**
     * @return type
     */
    public function addCouponUpdate()
    {
        try {
            $code = \Input::get('coupon');
            $cart = Cart::getContent();
            foreach ($cart as $item) {
                $id = $item->id;
            }
            $promo_controller = new \App\Http\Controllers\Payment\PromotionController();
            $result = $promo_controller->checkCode($code, $id);
            if ($result == 'success') {
                return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
            }

            return redirect()->back();
        } catch (\Exception $ex) {
            dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
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
            $taxe_relation = $this->tax->where('tax_classes_id', $taxClassId)->get();

            return $taxe_relation;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('error in get tax priority');
        }
    }

    /**
     * @param type $price
     *
     * @throws \Exception
     *
     * @return type
     */
    public static function rounding($price)
    {
        try {
            $tax_rule = new \App\Model\Payment\TaxOption();
            $rule = $tax_rule->findOrFail(1);
            $rounding = $rule->rounding;
            if ($rounding == 1) {
                // $price = str_replace(',', '', $price);

                return round($price);
            } else {
                return $price;
            }
        } catch (\Exception $ex) {
            dd($ex);
            Bugsnag::notifyException($ex);
            // throw new \Exception('error in get tax priority');
        }
    }

    /**
     * @return type
     */
    public function contactUs()
    {
        try {
            return view('themes.default1.front.contact');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
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
            $this->templateController->Mailing($from, $to, $data, $subject, [], $fromname, $toname);
            //$this->templateController->Mailing($from, $to, $data, $subject);
            return redirect()->back()->with('success', 'Your message was sent successfully. Thanks.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * @param type $code
     *
     * @throws \Exception
     *
     * @return type
     */
    public static function getCountryByCode($code)
    {
        try {
            $country = \App\Model\Common\Country::where('country_code_char2', $code)->first();
            if ($country) {
                return $country->country_name;
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $name
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function getTimezoneByName($name)
    {
        try {
            if ($name) {
                $timezone = \App\Model\Common\Timezone::where('name', $name)->first();
                if ($timezone) {
                    $timezone = $timezone->id;
                } else {
                    $timezone = '114';
                }
            } else {
                $timezone = '114';
            }

            return $timezone;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $code
     *
     * @throws \Exception
     *
     * @return type
     */
    public static function getStateByCode($code)
    {
        try {
            $result = ['id' => '', 'name' => ''];
            if ($code) {
                $subregion = \App\Model\Common\State::where('state_subdivision_code', $code)->first();
                if ($subregion) {
                    $result = ['id' => $subregion->state_subdivision_code,
                     'name'         => $subregion->state_subdivision_name, ];
                }
            }

            return $result;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $id
     *
     * @throws \Exception
     *
     * @return type
     */
    public static function getStateNameById($id)
    {
        try {
            $name = '';
            $subregion = \App\Model\Common\State::where('state_subdivision_id', $id)->first();
            if ($subregion) {
                $name = $subregion->state_subdivision_name;
            }

            return $name;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $productid
     * @param type $price
     * @param type $cart
     * @param type $cart1
     * @param type $shop
     *
     * @return type
     */
    public static function calculateTax($productid, $price, $cart = 1, $cart1 = 0, $shop = 0)
    {
        try {
            $template_controller = new TemplateController();
            $result = $template_controller->checkTax($productid, $price, $cart, $cart1, $shop);

            $result = self::rounding($result);

            return $result;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * @param type $rate
     * @param type $price
     *
     * @return type
     */
    public static function taxValue($rate, $price)
    {
        try {
            $tax = $price * ($rate / 100);
            $result = $tax;

            $result = self::rounding($result);

            return $result;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * @return type
     */
    public static function addons()
    {
        try {
            $items = Cart::getContent();
            $cart_productids = [];
            if (count($items) > 0) {
                foreach ($items as $key => $item) {
                    $cart_productids[] = $key;
                }
            }
            $_this = new self();
            $products = $_this->products($cart_productids);

            return $products;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * @param type $ids
     *
     * @throws \Exception
     *
     * @return type
     */
    public function products($ids)
    {
        $parents_string = [];
        $parent = [];
        $productid = [];

        try {
            $parents = $this->product
                    ->whereNotNull('parent')
                    ->where('parent', '!=', 0)
                    ->where('category', 'addon')
                    ->pluck('parent', 'id')
                    ->toArray();
            foreach ($parents as $key => $parent) {
                if (is_array($parent)) {
                    $parent = implode(',', $parent);
                }
                $parents_string[$key] = $parent;
            }
            if (count($parents_string) > 0) {
                foreach ($parents_string as $key => $value) {
                    if (strpos($value, ',') !== false) {
                        $value = explode(',', $value);
                    }
                    $parent[$key] = $value;
                }
            }

            foreach ($parent as $key => $id) {
                if (in_array($id, $ids)) {
                    $productid[] = $key;
                }
                if (is_array($id)) {
                    foreach ($id as $i) {
                        if (in_array($i, $ids)) {
                            $productid[] = $key;
                        }
                    }
                }
            }
            $parent_products = $this->getProductById($productid);

            return $parent_products;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $ids
     *
     * @throws \Exception
     *
     * @return type
     */
    public function getProductById($ids)
    {
        try {
            $products = [];
            if (count($ids) > 0) {
                $products = $this->product
                        ->whereIn('id', $ids)
                        ->get();
            }

            return $products;
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $iso
     *
     * @throws \Exception
     *
     * @return type
     */
    public static function getMobileCodeByIso($iso)
    {
        try {
            $code = '';
            if ($iso != '') {
                $mobile = \DB::table('mobile')->where('iso', $iso)->first();
                if ($mobile) {
                    $code = $mobile->phonecode;
                }
            }

            return $code;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $userid
     *
     * @throws \Exception
     *
     * @return string
     */
    public function currency($userid = '')
    {
        try {
            $currency = 'INR';
            if ($this->checkCurrencySession() == true) {
                $currency = Session::get('currency');
            }

            if (\Auth::user()) {
                $currency = \Auth::user()->currency;
                if ($currency == 'USD' || $currency == '1') {
                    $currency = 'USD';
                }
            }
            if ($userid != '') {
                $user = new \App\User();
                $currency = $user->find($userid)->currency;
                if ($currency == 'USD' || $currency == '1') {
                    $currency = 'USD';
                } else {
                    $currency = 'INR';
                }
            }
            // dd($currency);
            return $currency;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $productid
     * @param type $userid
     * @param type $planid
     *
     * @return type
     */
    public function cost($productid, $userid = '', $planid = '')
    {
        try {
            $cost = $this->planCost($productid, $userid, $planid);
            if ($cost == 0) {
                $cost = $this->productCost($productid, $userid);
            }

            return self::rounding($cost);
        } catch (\Exception $ex) {
            // throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $productid
     * @param type $userid
     *
     * @throws \Exception
     *
     * @return type
     */
    public function productCost($productid, $userid = '')
    {
        try {
            $sales = 0;
            $currency = $this->currency($userid);
            $product = $this->product->find($productid);
            $price = $product->price()->where('currency', $currency)->first();
            if ($price) {
                $sales = $price->sales_price;
                if ($sales == 0) {
                    $sales = $price->price;
                }
            }
            //}

            return $sales;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $productid
     * @param type $userid
     * @param type $planid
     *
     * @throws \Exception
     *
     * @return type
     */
    public function planCost($productid, $userid, $planid = '')
    {
        try {
            $cost = 0;
            $subscription = $this->allowSubscription($productid);
            if ($this->checkPlanSession() == true) {
                $planid = Session::get('plan');
            }

            if ($subscription == true) {
                $plan = new \App\Model\Payment\Plan();
                $plan = $plan->where('id', $planid)->where('product', $productid)->first();

                if ($plan) {
                    $currency = $this->currency($userid);
                    $price = $plan->planPrice()
                                    ->where('currency', $currency)
                                    ->first()
                            ->add_price;
                    $days = $plan->days;
                    $months = $days / 30 / 12;
                    $cost = round($months) * $price;
                }
            }

            return $cost;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function checkCurrencySession()
    {
        try {
            if (Session::has('currency')) {
                return true;
            }

            return false;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $productid
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function allowSubscription($productid)
    {
        try {
            $reponse = false;
            $product = $this->product->find($productid);
            if ($product) {
                if ($product->subscription == 1) {
                    $reponse = true;
                }
            }

            return $reponse;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
