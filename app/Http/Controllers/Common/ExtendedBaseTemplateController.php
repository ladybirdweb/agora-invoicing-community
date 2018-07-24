<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Product;
use Bugsnag;

class ExtendedBaseTemplateController extends Controller
{
    public function popup($title, $body, $width = '897', $name = '', $modelid = '', $class = 'null', $trigger = false)
    {
        try {
            if ($modelid == '') {
                $modelid = $title;
            }
            if ($trigger == true) {
                $trigger = "<a href=# class=$class  data-toggle='modal' data-target=#edit".$modelid.'>'.$name.'</a>';
            } else {
                $trigger = '';
            }

            return $trigger."
                        <div class='modal fade' id=edit".$modelid.">
                            <div class='modal-dialog' style='width: ".$width."px;'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'
                                         aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                        <h4 class='modal-title'>".$title."</h4>
                                    </div>
                                    <div class='modal-body'>
                                    ".$body."
                                    </div>
                                    <div class='modal-footer'>
                                        <button type=button id=close class='btn btn-default pull-left' 
                                        data-dismiss=modal>Close</button>
                                        <input type=submit class='btn btn-primary' value="./* @scrutinizer ignore-type */
                                        \Lang::get('message.save').'>
                                    </div>
                                    '.\Form::close().'
                                </div>
                            </div>
                        </div>';
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function calculateTotalcart($rate, $price, $cart, $shop)
    {
        if (($cart == 1 && $shop == 1) || ($cart == 1 && $shop == 0) || ($cart == 0 && $shop == 1)) {
            $tax_amount = $price * ($rate / 100);
            $total = $price + $tax_amount;

            return $total;
        }

        return $price;
    }

    public function calculateSub($rate, $price, $cart, $shop)
    {
        if (($cart == 1 && $shop == 1) || ($cart == 1 && $shop == 0) || ($cart == 0 && $shop == 1)) {
            $total = $price / (($rate / 100) + 1);

            return $total;
        }

        return $price;
    }

    public function checkTax($productid, $price, $cart = 0, $cart1 = 0, $shop = 0)
    {
        try {
            $product = Product::findOrFail($productid);
            $controller = new \App\Http\Controllers\Front\CartController();

            $currency = $controller->currency();
            $tax_relation = TaxProductRelation::where('product_id', $productid)->first();
            if (!$tax_relation) {
                return $this->withoutTaxRelation($productid, $currency);
            }
            $taxes = Tax::where('tax_classes_id', $tax_relation->tax_class_id)->where('active', 1)->orderBy('created_at', 'asc')->get();
            if (count($taxes) == 0) {
                throw new \Exception('No taxes is avalable');
            }
            $tax_amount = $this->getTaxAmount($cart, $taxes, $price, $cart1, $shop);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }
}
