<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Product\Product;
use Cart;
use Illuminate\Http\Request;

class BaseCartController extends Controller
{
    /**
     * Reduce No. of Agents When Minus button Is Clicked.
     *
     * @param Request $request Get productid , Product quantity ,Price,Currency,Symbol as Request
     *
     * @return success
     */
    public function reduceAgentQty(Request $request)
    {
        try {
            $id = $request->input('productid');
            $hasPermissionToModifyAgent = Product::find($id)->can_modify_agent;

            if ($hasPermissionToModifyAgent) {
                $cartValues = $this->getCartValues($id, true);

                Cart::update($id, [
                    'price'      => $cartValues['price'],
                    'attributes' => ['agents' =>  $cartValues['agtqty'], 'currency'=> $cartValues['currency'], 'symbol'=>$cartValues['symbol']],
                ]);
            }

            return successResponse('Cart updated successfully');
        } catch (\Exception $ex) {
            return errorResponse($ex->getMessage());
        }
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
        try {
            $id = $request->input('productid');
            $hasPermissionToModifyAgent = Product::find($id)->can_modify_agent;

            if ($hasPermissionToModifyAgent) {
                $cartValues = $this->getCartValues($id);
                Cart::update($id, [
                    'price'      => $cartValues['price'],
                    'attributes' => ['agents' =>  $cartValues['agtqty'], 'currency'=> $cartValues['currency'], 'symbol'=>$cartValues['symbol']],
                ]);
            }

            return successResponse('Cart updated successfully');
        } catch (\Exception $ex) {
            dd($ex);

            return errorResponse($ex->getMessage());
        }
    }

    private function getCartValues($productId, $canReduceAgent = false)
    {
        $cart = \Cart::get($productId);
        if ($cart) {
            $agtqty = $cart->attributes->agents;
            $price = \Cart::getTotal();
            $currency = $cart->attributes->currency;
            $symbol = $cart->attributes->currency;
        } else {
            throw new \Exception('Product not present in cart.');
        }

        if ($canReduceAgent) {
            $agtqty = $agtqty / 2;
            $price = \Cart::getTotal() / 2;
        } else {
            $agtqty = $agtqty * 2;
            $price = \Cart::getTotal() * 2;
        }

        return ['agtqty'=>$agtqty, 'price'=>$price, 'currency'=>$currency, 'symbol'=>$symbol];
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
        try {
            $id = $request->input('productid');
            $hasPermissionToModifyQuantity = Product::find($id)->can_modify_quantity;
            if ($hasPermissionToModifyQuantity) {
                $cart = \Cart::get($id);
                $qty = $cart->quantity - 1;
                $price = $this->cost($id);
                Cart::update($id, [
                    'quantity' => -1,
                    'price'    => $price,
                ]);
            } else {
                throw new \Exception('Cannot Modify Quantity');
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
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
        try {
            $id = $request->input('productid');
            $hasPermissionToModifyQuantity = Product::find($id)->can_modify_quantity;
            if ($hasPermissionToModifyQuantity) {
                $cart = \Cart::get($id);
                $qty = $cart->quantity + 1;
                $price = $this->cost($id);
                Cart::update($id, [
                    'quantity' => [
                        'relative' => false,
                        'value'    => $qty,
                    ],
                    'price'  => $price,
                ]);
            } else {
                throw new \Exception('Cannot Modify Quantity');
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
