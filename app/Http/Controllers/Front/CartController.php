<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Common\TemplateController;
use App\Model\Common\Country;
use App\Model\Common\Setting;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxByState;
use App\Model\Payment\TaxOption;
use App\Model\Product\Product;
use App\Traits\TaxCalculation;
use Bugsnag;
use Cart;
use Darryldecode\Cart\CartCondition;
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
            if (! property_exists($id, Cart::getContent())) {
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
            foreach ($cartCollection as $item) {
                $cart_currency = $item->attributes->currency;
                \Session::put('currency', $cart_currency);
                if (\Auth::user()) {//If User is Logged in and his currency changes after logging in then remove his previous order from cart
                    $currency = \Auth::user()->currency;
                    if ($cart_currency != $currency) {
                        $id = $item->id;
                        Cart::session(\Auth::user()->id)->remove($id);
                        $items = $this->addProduct($id);
                        Cart::add($items);
                    }
                }
            }

            return view('themes.default1.front.cart', compact('cartCollection'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function cartRemove(Request $request)
    {
        $id = $request->input('id');
        Cart::remove($id);
        Cart::removeConditionsByType('tax');
        Cart::clearItemConditions($id);

        return 'success';
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
            $mail = new \App\Http\Controllers\Common\PhpMailController();
            if (emailSendingStatus()) {
                $mail->sendEmail($from, $to, $data, $subject, [], $fromname, $toname);
            }

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
            if (! \Auth::user()) {//When user is not logged in
                $location = getLocation();
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
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get Cost For a particular Plan.
     *
     * @param int $productid
     * @param int $userid
     * @param int $planid
     *
     * @throws \Exception
     *
     * @return int
     */
    public function planCost($productid, $userid, $planid = '')
    {
        try {
            $cost = 0;
            $months = 0;
            $currency = $this->currency($userid);
            if (! $planid) {//When Product Is Added from Cart
                $planid = Plan::where('product', $productid)->pluck('id')->first();
            } elseif ($this->checkPlanSession() === true && ! $planid) {
                $planid = Session::get('plan');
            }
            $plan = Plan::where('id', $planid)->where('product', $productid)->first();
            if ($plan) { //Get the Total Plan Cost if the Plan Exists For a Product
                $months = 1;
                $currency = $this->currency($userid);
                $product = Product::find($productid);
                $days = $plan->periods->pluck('days')->first();
                $price = ($product->planRelation->find($planid)->planPrice->where('currency', $currency['currency'])->first()->add_price);
                if ($days) { //If Period Is defined for a Particular Plan ie no. of Days Generated
                    $months = $days >= '365' ? $days / 30 / 12 : $days / 30;
                }
                $finalPrice = str_replace(',', '', $price);
                $cost = round($months) * $finalPrice;
            } else {
                throw new \Exception('Product cannot be added to cart. No such plan exists.');
            }

            return $cost;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public static function updateFinalPrice(Request $request)
    {
        $value = $request->input('processing_fee').'%';
        $updateValue = new CartCondition([
            'name'   => 'Processing fee',
            'type'   => 'fee',
            'target' => 'total',
            'value'  => $value,
        ]);
        \Cart::condition($updateValue);
    }

    /**
     * @return type
     */
    public function addCouponUpdate(Request $request)
    {
        try {
            $code = \Request::get('coupon');
            $promo_controller = new \App\Http\Controllers\Payment\PromotionController();
            $result = $promo_controller->checkCode($code);
            if ($result == 'success') {
                return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
            }

            return redirect()->back();
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
