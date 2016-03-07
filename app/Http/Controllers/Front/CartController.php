<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Common\TemplateController;
use App\Http\Controllers\Controller;
use App\Model\Payment\Currency;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxRules;
use App\Model\Product\Product;
use Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public $templateController;
    public $product;
    public $currency;
    public $taxRules;
    public $addons;
    public $addonRelation;
    public $licence;

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

        $taxRules = new TaxRules();
        $this->taxRules = $taxRules;
    }

    public function ProductList()
    {
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
            if (!array_key_exists($id, Cart::getContent()->toArray())) {
                $items = $this->AddProduct($id);
                Cart::add($items);
            }
            $cartCollection = Cart::getContent();

            return view('themes.default1.front.cart', compact('cartCollection'));
        } catch (\Exception $ex) {
            dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function CheckTax($isTaxApply)
    {
        try {
            $rate1 = 0;
            $rate2 = 0;
            $name1 = 'null';
            $name2 = 'null';
            $ruleEnabled = $this->taxRules->where('id', '1')->first();
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
                    if ($compound == 1) {
                        return ['conditions' => [$taxCondition1, $taxCondition2], 'attributes' => ['tax' => [['name' => $name1, 'rate' => $rate1], ['name' => $name2, 'rate' => $rate2]]]];
                    } else {
                        return ['conditions' => $taxCondition2, 'attributes' => ['tax' => [['name' => $name2, 'rate' => $rate2]]]];
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

    public function AddProduct($id)
    {
        $currency = \Input::get('currency');
        if (!$currency) {
            $currency = 'USD';
        }
        $product = $this->product->where('id', $id)->first();
        if ($product) {
            $productCurrency = $product->price()->where('currency', $currency)->first()->currency;
            $actualPrice = $product->price()->where('currency', $currency)->first()->sales_price;
            $baseConversion = $this->currency->where('code', $productCurrency)->first()->base_conversion;
            $convertedPrice = $actualPrice * $baseConversion;
            //dd($product);
            $productName = $product->name;

            /*
             * Check the Tax is On
             */
            $isTaxApply = $product->tax_apply;

            $taxConditions = $this->CheckTax($isTaxApply);
            //dd($taxConditions);

            /*
             * Check if this product allow multiple qty
             */
            if ($product->multiple_qty == 1) {
                // Allow
            } else {
                $qty = 1;
            }
            $items = ['id' => $id, 'name' => $productName, 'price' => $convertedPrice, 'quantity' => $qty];
            $items = array_merge($items, $taxConditions);

            return $items;
        }
    }

    public function ClearCart()
    {
        Cart::clear();

        return redirect('templates/1');
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
}
