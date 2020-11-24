<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Common\TemplateController;
use App\Model\Common\Setting;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxByState;
use App\Model\Payment\TaxOption;
use App\Model\Product\Product;
use Cart;
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
                $subscription = $request->get('subscription');
                Session::put('plan_id', $subscription);
            }
            $id = $request->input('id');

            if (! property_exists($id, Cart::getContent())) {
                $items = $this->addProduct($id);
                \Cart::add($items); //Add Items To the Cart Collection
            }

            return redirect('show/cart');
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
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
            if (\Session::has('plan_id')) { //If a plan is selected from dropdown in pricing page, this is true
                $planid = \Session::get('plan_id');
            } else {
                $planid = Plan::where('product', $id)->pluck('id')->first();
            }
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
                $this->checkProductsHaveSimilarCurrency($currency['currency']);
            } else {
                throw new \Exception('Product cannot be added to cart. No plan exists.');
            }
            $actualPrice = $this->cost($product->id, $planid);
            $items = ['id'     => $id, 'name' => $product->name, 'price' => $actualPrice,
                'quantity'    => $qty, 'attributes' => ['currency' => $currency['currency'], 'symbol'=>$currency['symbol'], 'agents'=> $agents], 'associatedModel' => $product, ];

            return $items;
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * If multiple products are being added to cart, this method checks all the products have similar currency.
     * @param  string $currency Currency of the product to be added to cart
     */
    private function checkProductsHaveSimilarCurrency($currency)
    {
        $carts = \Cart::getContent();
        foreach ($carts as $cart) {
            if ($cart->attributes['currency'] != $currency) {
                throw new \Exception('All products added to the cart should have similar currency');
            }
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
            }

            return view('themes.default1.front.cart', compact('cartCollection'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function cartRemove(Request $request)
    {
        $id = $request->input('id');
        Cart::remove($id);
        Cart::removeConditionsByType('tax');
        Cart::removeConditionsByType('coupon');
        Cart::clearItemConditions($id);

        return 'success';
    }

    public function clearCart()
    {
        foreach (Cart::getContent() as $item) {
            Cart::remove($item->id);
            Cart::clearItemConditions($item->id);
            if (\Session::has('domain'.$item->id)) {
                \Session::forget('domain'.$item->id);
            }
        }

        if (Session::has('plan')) {
            Session::forget('plan');
        }
        $renew_control = new \App\Http\Controllers\Order\RenewController();
        $renew_control->removeSession();
        Cart::clearCartConditions();
        Cart::clear();

        return redirect('show/cart');
    }

    /**
     * @param int $productid
     * @param int $userid
     * @param int $planid
     *
     * @return string
     */
    public function cost($productid, $planid = '', $userid = '')
    {
        try {
            $cost = $this->planCost($productid, $userid, $planid);

            return $cost;
        } catch (\Exception $ex) {
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
    public function planCost($productid, $userid = '', $planid = '')
    {
        try {
            $cost = 0;
            $months = 0;
            if (! $planid) {//When Product Is Added from Cart
                $planid = Plan::where('product', $productid)->pluck('id')->first();
            } elseif (checkPlanSession() && ! $planid) {
                $planid = Session::get('plan_id');
            }
            $plan = Plan::where('id', $planid)->where('product', $productid)->first();
            if ($plan) { //Get the Total Plan Cost if the Plan Exists For a Product
                $currency = userCurrencyAndPrice($userid, $plan);
                $months = 1;
                $product = Product::find($productid);
                $days = $plan->periods->pluck('days')->first();
                $price = $currency['plan']->add_price;
                if ($days) { //If Period Is defined for a Particular Plan ie no. of Days Generated
                    $months = $days >= '365' ? $days / 30 / 12 : $days / 30;
                }
                $finalPrice = str_replace(',', '', $price);
                $cost = round($months) * $finalPrice;
            } else {
                throw new \Exception('Product cannot be added to cart. No plan exists.');
            }

            return $cost;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
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
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
