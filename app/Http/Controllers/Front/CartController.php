<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Common\TemplateController;
use App\Model\Common\Country;
use App\Model\Common\Setting;
use App\Model\Payment\Currency;
use App\Model\Payment\PlanPrice;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxByState;
use App\Model\Payment\TaxOption;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Product;
use App\Traits\TaxCalculation;
use Bugsnag;
use Cart;
use Illuminate\Http\Request;
use Session;

class CartController extends BaseCartController
{
    use TaxCalculation;

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
    }

    // public function productList(Request $request)
    // {
    //     $cont = new \App\Http\Controllers\Front\PageController();
    //     $location = $cont->getLocation();
    //     $country = $this->findCountryByGeoip($location['iso_code']);
    //     $states = $this->findStateByRegionId($location['iso_code']);
    //     $states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
    //     $state_code = $location['iso_code'].'-'.$location['state'];
    //     $state = $this->getStateByCode($state_code);
    //     $mobile_code = $this->getMobileCodeByIso($location['iso_code']);
    //     $currency = $this->currency();

    //     \Session::put('currency', $currency);
    //     if (!\Session::has('currency')) {
    //         \Session::put('currency', 'INR');
    //     }

    //     try {
    //         $page_controller = new PageController();

    //         return $page_controller->cart();
    //     } catch (\Exception $ex) {
    //         return redirect()->back()->with('fails', $ex->getMessage());
    //     }
    // }

    /*
     * The first request to the cart Page comes here
     * Get Plan id and Product id as Request
     *
     * @param  int  $plan   Planid;
     * @param  int  $id     Productid;
     */
    public function cart(Request $request)
    {
        try {
            $plan = '';
            if ($request->has('subscription')) {//put he Plan id sent into session variable
                $plan = $request->get('subscription');
                Session::put('plan', $plan);
            }
            $id = $request->input('id');
            if (!array_key_exists($id, Cart::getContent())) {
                $items = $this->addProduct($id);
                \Cart::add($items); //Add Items To the Cart Collection
            }

            return redirect('show/cart');
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /*
     * Show the cart with all the Cart Attributes and Cart Collections
     * Link: https://github.com/darryldecode/laravelshoppingcart
     */
    public function showCart()
    {
        try {
            $cartCollection = Cart::getContent();
            $attributes = [];
            foreach ($cartCollection as $item) {
                $attributes[] = $item->attributes;
                $cart_currency = $attributes[0]['currency']['currency'];
                \Session::put('currency', $cart_currency);
                if (\Auth::user()) {//If User is Loggen in and his currency changes after logginng in then remove his previous order from cart
                    $currency = \Auth::user()->currency;
                    if ($cart_currency != $currency) {
                        $id = $item->id;
                        Cart::session(\Auth::user()->id)->remove($id);
                        $items = $this->addProduct($id);
                        Cart::add($items);
                    }
                }
            }

            return view('themes.default1.front.cart', compact('cartCollection', 'attributes'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex->getMessage());

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
            $cont = new \App\Http\Controllers\Front\PageController();
            $location = $cont->getLocation();
            $country = $this->findCountryByGeoip($location['iso_code']); //Get country by geopip
            $states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
            $state_code = $location['iso_code'].'-'.$location['state'];
            $state = $this->getStateByCode($state_code); //match the geoip state with billing table state.
            $geoip_state = $this->getGeoipState($state_code, $user_state);
            $geoip_country = $this->getGeoipCountry($location['iso_code'], $user_country);

            if ($this->tax_option->findOrFail(1)->inclusive == 0) {
                $tax_enable = $this->tax_option->findOrFail(1)->tax_enable;
                //Check the state of user for calculating GST(cgst,igst,utgst,sgst)
                $user_state = TaxByState::where('state_code', $geoip_state)->first();
                $origin_state = $this->setting->first()->state; //Get the State of origin
                $tax_class_id = TaxProductRelation::where('product_id', $productid)->pluck('tax_class_id')->toArray();
                if (count($tax_class_id) > 0) {//If the product is allowed for tax (Check in tax_product relation table)
                    if ($tax_enable == 1) {//If GST is Enabled

                         $details = $this->getDetailsWhenUserStateIsIndian(
                             $user_state,
                             $origin_state,
                             $productid,
                             $geoip_state,
                             $geoip_country
                         );
                        $c_gst = $details['cgst'];
                        $s_gst = $details['sgst'];
                        $i_gst = $details['igst'];
                        $ut_gst = $details['utgst'];

                        $state_code = $details['statecode'];

                        $status = $details['status'];
                        $taxes = $details['taxes'];
                        $status = $details['status'];
                        $value = $details['value'];
                        $rate = $details['rate'];
                        foreach ($taxes as $key => $tax) {
                            //All the da a attribute that is sent to the checkout Page if tax_compound=0
                            if ($taxes[0]) {
                                $tax_attribute[$key] = ['name' => $tax->name, 'c_gst'=>$c_gst,

                               's_gst'         => $s_gst, 'i_gst'=>$i_gst, 'ut_gst'=>$ut_gst,
                                'state'        => $state_code, 'origin_state'=>$origin_state,
                                 'tax_enable'  => $tax_enable, 'rate'=>$value, 'status'=>$status, ];

                                $taxCondition[0] = new \Darryldecode\Cart\CartCondition([
                                            'name'   => 'no compound', 'type'   => 'tax',
                                            'target' => 'item', 'value'  => $value,
                                          ]);
                            } else {
                                $tax_attribute[0] = ['name' => 'null', 'rate' => 0, 'tax_enable' =>0];
                                $taxCondition[0] = new \Darryldecode\Cart\CartCondition([
                                           'name'   => 'null', 'type'   => 'tax',
                                           'target' => 'item', 'value'  => '0%',
                                           'rate'   => 0, 'tax_enable' =>0,
                                         ]);
                            }
                        }
                    } elseif ($tax_enable == 0) {//If Tax enable is 0 and other tax is available

                        $details = $this->whenOtherTaxAvailableAndTaxNotEnable($tax_class_id, $productid, $geoip_state, $geoip_country);

                        $taxes = $details['taxes'];
                        $value = $details['value'];
                        $status = $details['status'];
                        foreach ($taxes as $key => $tax) {
                            $tax_attribute[$key] = ['name' => $tax->name,
                            'rate'                         => $value, 'tax_enable'=>0, 'status' => $status, ];
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

            return ['conditions' => $taxCondition, 'tax_attributes'=>  $tax_attribute];
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('Can not check the tax');
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
            $to = $set->company_email;
            $data = '';
            $data .= 'Name: '.strip_tags($request->input('name')).'<br/>';
            $data .= 'Email: '.strip_tags($request->input('email')).'<br/>';
            $data .= 'Message: '.strip_tags($request->input('message')).'<br/>';
            $data .= 'Mobile: '.strip_tags($request->input('country_code').$request->input('Mobile')).'<br/>';
            $subject = 'Faveo billing enquiry';
            $this->templateController->mailing($from, $to, $data, $subject, [], $fromname, $toname);
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
                return $country->nicename;
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
            $timezone = \App\Model\Common\Timezone::where('name', $name)->first();
            if ($timezone) {
                $timezone = $timezone->id;
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

            $subregion = \App\Model\Common\State::where('state_subdivision_code', $code)->first();
            if ($subregion) {
                $result = ['id' => $subregion->state_subdivision_code,
                 'name'         => $subregion->state_subdivision_name, ];
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
     * @param type $userid
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function currency($userid = '')
    {
        try {
            $currency = Setting::find(1)->default_currency;
            $currency_symbol = Setting::find(1)->default_symbol;
            if (!\Auth::user()) {//When user is not logged in
                $cont = new \App\Http\Controllers\Front\PageController();
                $location = $cont->getLocation();
                $country = self::findCountryByGeoip($location['iso_code']);
                $userCountry = Country::where('country_code_char2', $country)->first();
                $currencyStatus = $userCountry->currency->status;
                if ($currencyStatus == 1) {
                    $currency = $userCountry->currency->code;
                    $currency_symbol = $userCountry->currency->symbol;
                }
            }
            if (\Auth::user()) {
                $currency = \Auth::user()->currency;
                $currency_symbol = \Auth::user()->currency_symbol;
            }
            if ($userid != '') {//For Admin Panel Clients
                $currencyAndSymbol = self::getCurrency($userid);
                $currency = $currencyAndSymbol['currency'];
                $currency_symbol = $currencyAndSymbol['symbol'];
            }

            return ['currency'=>$currency, 'symbol'=>$currency_symbol];
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /*
    * Get Currency And Symbol For Admin Panel Clients
    */
    public static function getCurrency($userid)
    {
        $user = new \App\User();
        $currency = $user->find($userid)->currency;
        $symbol = $user->find($userid)->currency_symbol;

        return ['currency'=>$currency, 'symbol'=>$symbol];
    }

    /**
     * @param int $productid
     * @param int $userid
     * @param int $planid
     *
     * @return string
     */
    public function cost($productid, $userid = '', $planid = '')
    {
        try {
            $cost = $this->planCost($productid, $userid, $planid);

            return $cost;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex->getMessage());
            app('log')->error($ex->getMessage());
        }
    }
}
