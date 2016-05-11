<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Common\TemplateController;
use App\Http\Controllers\Controller;
use App\Model\Payment\Currency;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxOption;
use App\Model\Product\Product;
use Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public $templateController;
    public $product;
    public $currency;
    public $addons;
    public $addonRelation;
    public $licence;
    public $tax_option;

    public function __construct()
    {
        $templateController = new TemplateController();
        $this->templateController = $templateController;

        $product = new Product();
        $this->product = $product;

        $currency = new Currency();
        $this->currency = $currency;

        $tax = new Tax();
        $this->tax = $tax;

        $tax_option = new TaxOption();
        $this->tax_option = $tax_option;
    }

    public function ProductList(Request $request)
    {
        $location = \GeoIP::getLocation();
        //dd($location);

        if ($location['country'] == 'India') {
            $currency = 'INR';
        } else {
            $currency = 'USD';
        }
        \Session::put('currency', $currency);
        if (!\Session::has('currency')) {
            \Session::put('currency', 'INR');
//dd(\Session::get('currency'));
        }

        try {
            return $this->templateController->show(1);
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function Cart(Request $request)
    {
        try {
            $id = $request->input('id');
            //dd($id);
            if (!array_key_exists($id, Cart::getContent()->toArray())) {
                $items = $this->addProduct($id);
                Cart::add($items);
            }

            return redirect('show/cart');
        } catch (\Exception $ex) {
            dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function showCart()
    {
        try {
            $attributes = [];
            $cartCollection = Cart::getContent();
            foreach ($cartCollection as $item) {
                $attributes[] = $item->attributes;
            }

            return view('themes.default1.front.cart', compact('cartCollection', 'attributes'));
        } catch (\Exception $ex) {
            dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function checkTax($productid)
    {
        try {
            $tax_attribute[0] = ['name' => 'null', 'rate' => 0];
            $taxCondition[0] = new \Darryldecode\Cart\CartCondition([
                'name'   => 'null',
                'type'   => 'tax',
                'target' => 'item',
                'value'  => '0%',
            ]);
//dd($tax_attribute);
            $product = $this->product->findOrFail($productid);

            $location = \GeoIP::getLocation();
            $counrty_iso = $location['isoCode'];
            $state_code = $location['isoCode'].'-'.$location['state'];
            $geoip_country = '';
            $geoip_state = '';
            if (\Auth::user()) {
                $geoip_country = \Auth::user()->country;
                $geoip_state = \Auth::user()->state;
            }
            if ($geoip_country == '') {
                $geoip_country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($counrty_iso);
            }
            $geoip_state_array = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
            if ($geoip_state == '') {
                if (array_key_exists('id', $geoip_state_array)) {
                    $geoip_state = $geoip_state_array['id'];
                }
            }

//dd($product);
            if ($this->tax_option->findOrFail(1)->inclusive == 0) {
                $tax_rule = $this->tax_option->findOrFail(1);
                $product1 = $tax_rule->inclusive;
                $shop = $tax_rule->shop_inclusive;
                $cart = $tax_rule->cart_inclusive;
                if ($product->tax()->first()) {
                    $tax_class_id = $product->tax()->first()->tax_class_id;
                    if ($this->tax_option->findOrFail(1)->tax_enable == 1) {
                        if ($product1 == 0) {
                            $taxes = $this->getTaxByPriority($tax_class_id);
                            $rate = 0;
                            foreach ($taxes as $key => $tax) {
                                if ($tax->country == $geoip_country || $tax->state == $geoip_state || ($tax->country == '' && $tax->state == '')) {
                                    if ($tax->compound == 1) {
                                        $tax_attribute[$key] = ['name' => $tax->name, 'rate' => $tax->rate];
                                        $taxCondition[$key] = new \Darryldecode\Cart\CartCondition([
                                            'name'   => $tax->name,
                                            'type'   => 'tax',
                                            'target' => 'item',
                                            'value'  => $tax->rate.'%',
                                        ]);
                                    } else {
                                        $tax_attribute[$key] = ['name' => $tax->name, 'rate' => $tax->rate];
                                        $rate += $tax->rate;
                                        $taxCondition[0] = new \Darryldecode\Cart\CartCondition([
                                            'name'   => 'no compound',
                                            'type'   => 'tax',
                                            'target' => 'item',
                                            'value'  => $rate.'%',
                                        ]);
                                    }
                                }
                            }
                        } else {
                            if ($product->tax()->first()) {
                                $tax_class_id = $product->tax()->first()->tax_class_id;
                                if ($this->tax_option->findOrFail(1)->tax_enable == 1) {
                                    $taxes = $this->getTaxByPriority($tax_class_id);
                                    foreach ($taxes as $key => $tax) {
                                        $tax_attribute[$key] = ['name' => $tax->name, 'rate' => $tax->rate];
                                    }
                                }
                            }
                        }
                    } else {
                        if ($product->tax()->first()) {
                            $tax_class_id = $product->tax()->first()->tax_class_id;
                            if ($this->tax_option->findOrFail(1)->tax_enable == 1) {
                                $taxes = $this->getTaxByPriority($tax_class_id);
                                foreach ($taxes as $key => $tax) {
                                    $tax_attribute[$key] = ['name' => $tax->name, 'rate' => $tax->rate];
                                }
                            }
                        }
                    }
                }
            } else {
                if ($product->tax()->first()) {
                    $tax_class_id = $product->tax()->first()->tax_class_id;
                    if ($this->tax_option->findOrFail(1)->tax_enable == 1) {
                        $taxes = $this->getTaxByPriority($tax_class_id);
                        foreach ($taxes as $key => $tax) {
                            $tax_attribute[$key] = ['name' => $tax->name, 'rate' => $tax->rate];
                        }
                    }
                }
            }
            $currency_attribute = $this->addCurrencyAttributes($productid);

            return ['conditions' => $taxCondition, 'attributes' => ['tax' => $tax_attribute, 'currency' => $currency_attribute]];
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception('Can not check the tax');
        }
    }

    public function checkTaxOld($isTaxApply, $id)
    {
        try {
            $rate1 = 0;
            $rate2 = 0;
            $name1 = 'null';
            $name2 = 'null';

            if ($ruleEnabled) {
                $enabled = $ruleEnabled->status;
                $type = $ruleEnabled->type;
                $compound = $ruleEnabled->compound;
                if ($enabled == 1 && $type == 'exclusive') {
                    if ($isTaxApply == 1) {
                        $tax1 = $this->tax->where('level', 1)->first();
                        $tax2 = $this->tax->where('level', 2)->first();
                        if ($tax1) {
                            $name1 = $tax1->name;
                            $rate1 = $tax1->rate;
                            $taxCondition1 = new \Darryldecode\Cart\CartCondition([
                                'name'   => $name1,
                                'type'   => 'tax',
                                'target' => 'item',
                                'value'  => $rate1.'%',
                            ]);
                        } else {
                            $taxCondition1 = new \Darryldecode\Cart\CartCondition([
                                'name'   => $name1,
                                'type'   => 'tax',
                                'target' => 'item',
                                'value'  => $rate1,
                            ]);
                        }
                        if ($tax2) {
                            $name2 = $tax2->name;
                            $rate2 = $tax2->rate;
                            $taxCondition2 = new \Darryldecode\Cart\CartCondition([
                                'name'   => $name2,
                                'type'   => 'tax',
                                'target' => 'item',
                                'value'  => $rate2.'%',
                            ]);
                        } else {
                            $taxCondition2 = new \Darryldecode\Cart\CartCondition([
                                'name'   => $name2,
                                'type'   => 'tax',
                                'target' => 'item',
                                'value'  => $rate2,
                            ]);
                        }
                    } else {
                        $taxCondition1 = new \Darryldecode\Cart\CartCondition([
                            'name'   => $name1,
                            'type'   => 'tax',
                            'target' => 'item',
                            'value'  => $rate1,
                        ]);
                        $taxCondition2 = new \Darryldecode\Cart\CartCondition([
                            'name'   => $name2,
                            'type'   => 'tax',
                            'target' => 'item',
                            'value'  => $rate2,
                        ]);
                    }
                    $currency_attribute = $this->addCurrencyAttributes($id);
//dd($currency_attribute);
                    if ($compound == 1) {
                        return ['conditions' => [$taxCondition1, $taxCondition2], 'attributes' => ['tax' => [['name' => $name1, 'rate' => $rate1], ['name' => $name2, 'rate' => $rate2]], 'currency' => $currency_attribute]];
                    } else {
                        return ['conditions' => $taxCondition2, 'attributes' => ['tax' => [['name' => $name2, 'rate' => $rate2]], 'currency' => $currency_attribute]];
                    }
                }
            }
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception('Can not check the tax');
        }
    }

    public function CartRemove(Request $request)
    {
        $id = $request->input('id');
//dd($id);
        Cart::remove($id);

        return 'success';
    }

    public function ReduseQty(Request $request)
    {
        $id = $request->input('id');
        Cart::update($id, [
            'quantity' => -1, // so if the current product has a quantity of 4, it will subtract 1 and will result to 3
        ]);
//dd(Cart::getContent());
        return 'success';
    }

    public function IncreaseQty(Request $request)
    {
        $id = $request->input('id');
        Cart::update($id, [
            'quantity' => +1, // so if the current product has a quantity of 4, it will add 1 and will result to 5
        ]);
//dd(Cart::getContent());
        return 'success';
    }

    public function AddAddons($id)
    {
        $addon = $this->addons->where('id', $id)->first();

        $isTaxApply = $addon->tax_addon;

        $taxConditions = $this->CheckTax($isTaxApply);

        $items = ['id' => 'addon'.$addon->id, 'name' => $addon->name, 'price' => $addon->selling_price, 'quantity' => 1];
        $items = array_merge($items, $taxConditions);

//dd($items);

        return $items;
    }

    public function GetProductAddons($productId)
    {
        $addons = [];
        if ($this->addonRelation->where('product_id', $productId)->count() > 0) {
            $addid = $this->addonRelation->where('product_id', $productId)->pluck('addon_id')->toArray();
            $addons = $this->addons->whereIn('id', $addid)->get();
        }

        return $addons;
    }

    public function addProduct($id)
    {
        $location = \GeoIP::getLocation();
        //dd($location);

        if ($location['country'] == 'India') {
            $currency = 'INR';
        } else {
            $currency = 'USD';
        }
        \Session::put('currency', $currency);
        if (!\Session::has('currency')) {
            \Session::put('currency', 'INR');
//dd(\Session::get('currency'));
        }
        $currency = \Session::get('currency');
        //dd($currency);
//        if (!$currency) {
//            $currency = 'USD';
//        }
        $product = $this->product->where('id', $id)->first();

        if ($product) {
            $productCurrency = $product->price()->where('currency', $currency)->first()->currency;
            $actualPrice = $product->price()->where('currency', $currency)->first()->sales_price;
            if (!$actualPrice) {
                $actualPrice = $product->price()->where('currency', $currency)->first()->price;
            }
            $currency = $this->currency->where('code', $productCurrency)->get()->toArray();

            $productName = $product->name;

            /*
             * Check the Tax is On
             */
            $isTaxApply = $product->tax_apply;

            $taxConditions = $this->checkTax($id);
//dd($taxConditions);

            /*
             * Check if this product allow multiple qty
             */
            if ($product->multiple_qty == 1) {
                // Allow
            } else {
                $qty = 1;
            }
            $items = ['id' => $id, 'name' => $productName, 'price' => $actualPrice, 'quantity' => $qty, 'attributes' => ['currency' => [[$currency]]]];
            $items = array_merge($items, $taxConditions);
            //dd($items);
            return $items;
        }
    }

    public function ClearCart()
    {
        foreach (Cart::getContent() as $item) {
            if (\Session::has('domain'.$item->id)) {
                \Session::forget('domain'.$item->id);
            }
        }
        Cart::clear();

        return redirect('show/cart')->with('warning', 'Your cart is empty! ');
    }

    public function LicenceCart($id)
    {
        try {
            $licence = $this->licence->where('id', $id)->first();

            $isTaxApply = 0;

            $taxConditions = $this->CheckTax($isTaxApply);

            $items = ['id' => $licence->id, 'name' => $licence->name, 'price' => $licence->price, 'quantity' => 1, 'attributes' => ['number_of_sla' => $licence->number_of_sla]];
            $items = array_merge($items, $taxConditions);
            Cart::clear();
            Cart::add($items);

            return view('themes.default1.front.cart', compact('cartCollection'));
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception('Problem while adding licence to cart');
        }
    }

    public function cartUpdate($id, $key, $value)
    {
        try {
            Cart::update($id, [
                $key => $value, // new item name
                    ]
            );
        } catch (\Exception $ex) {
        }
    }

    public function addCurrencyAttributes($id)
    {
        try {
            $currency = \Session::get('currency');
            $product = $this->product->where('id', $id)->first();
//dd($product);
            if ($product) {
                $productCurrency = $product->price()->where('currency', $currency)->first()->currency;
                $currency = $this->currency->where('code', $productCurrency)->get()->toArray();
            } else {
                $currency = [];
            }

            return $currency;
        } catch (\Exception $ex) {
        }
    }

    public function addCouponUpdate()
    {
        try {
            $code = \Input::get('coupon');
//dd($code);
            $cart = Cart::getContent();
            foreach ($cart as $item) {
                $id = $item->id;
            }
            $promo_controller = new \App\Http\Controllers\Payment\PromotionController();
            $result = $promo_controller->checkCode($code, $id);
//dd($result);
            if ($result == 'success') {
                return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
            }
        } catch (\Exception $ex) {
            dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getTaxByPriority($tax_class_id)
    {
        try {
            $taxe_relation = $this->tax->where('tax_classes_id', $tax_class_id)->groupBy('level')->get();

            return $taxe_relation;
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception('error in get tax priority');
        }
    }

    public static function rounding($price)
    {
        try {
            $tax_rule = new \App\Model\Payment\TaxOption();
            $rule = $tax_rule->findOrFail(1);
            $rounding = $rule->rounding;
            if ($rounding == 1) {
                return round($price);
            } else {
                return $price;
            }
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception('error in get tax priority');
        }
    }

    public function contactUs()
    {
        try {
            return view('themes.default1.front.contact');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

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
            $to = 'support@ladybirdweb.com';
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

    public function addCartBySlug($slug)
    {
        try {
            if ($slug == 'helpdesk-with-kb-pro-edition') {
                $id = 8;
            }
            if ($slug == 'helpdesk-and-kb-community') {
                $id = 7;
            }

            return redirect("pricing?id=$id");
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public static function findCountryByGeoip($iso)
    {
        try {
            $country = \App\Model\Common\Country::where('country_code_char2', $iso)->first();
            if ($country) {
                return $country->country_code_char2;
            } else {
                return 'US';
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

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

    public static function findStateByRegionId($iso)
    {
        try {
            if ($iso) {
                $states = \App\Model\Common\State::where('country_code_char2', $iso)->lists('state_subdivision_name', 'state_subdivision_code')->toArray();
            } else {
                $states = [];
            }

            return $states;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

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

    public static function getStateByCode($code)
    {
        try {
            if (is_int($code)) {
                return [];
            }
            if ($code) {
                $subregion = \App\Model\Common\State::where('state_subdivision_code', $code)->first();
                if (!$subregion) {
                    return [];
                }

                return ['id' => $subregion->state_subdivision_code, 'name' => $subregion->state_subdivision_name];
            } else {
                return [];
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public static function calculateTax($productid, $currency, $cart = 1, $cart1 = 0, $shop = 0)
    {
        try {
            $template_controller = new TemplateController();
            $result = $template_controller->checkTax($productid, $currency, $cart, $cart1, $shop);
            $result = self::rounding($result);

            return $result;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public static function taxValue($rate, $price)
    {
        try {
            $tax = $price / (($rate / 100) + 1);
            $result = $price - $tax;
            $result = self::rounding($result);

            return $result;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
