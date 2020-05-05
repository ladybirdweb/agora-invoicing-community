<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Common\TemplateController;
use App\Http\Controllers\Controller;
use App\Model\Product\Product;
use Bugsnag;

class GetPageTemplateController extends Controller
{
    /**
     * Get  Template For Products.
     * @param $helpdesk_products
     * @param $data
     * @param $trasform
     * @return string
     */
    public function getTemplateOne($helpdesk_products, $data, $trasform)
    {
        $template = '';
        $temp_controller = new TemplateController();
        if (count($helpdesk_products) > 0) {
            foreach ($helpdesk_products as $product) {
                //Store all the values in $trasform variable for shortcodes to read from
                $trasform[$product['id']]['price'] = $temp_controller->leastAmount($product['id']);
                $trasform[$product['id']]['price-description'] = self::getPriceDescription($product['id']);
                $trasform[$product['id']]['name'] = $product['name'];
                $trasform[$product['id']]['feature'] = $product['description'];
                $form = \Form::open(['method' => 'get', 'url' => $product['shoping_cart_link']]);
                $form .= \Form::select(
                    'subscription', ['Plans' => [
                        $trasform[$product['id']]['price'].' '.$trasform[$product['id']]['price-description']]
                    ], null
                );
                $form .= \Form::hidden('id', $product['id']);
                $trasform[$product['id']]['subscription'] = $form;
                $trasform[$product['id']]['url'] = "<input type='submit' 
                value='Order Now' class='btn btn-dark btn-modern btn-outline py-2 px-4'></form>";
            }
            $template = $this->transform('cart', $data, $trasform);
        }
        return $template;
    }

    /**
     * Get Price Description(eg: Per Year,Per Month ,One-Time) for a Product.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-09T00:20:09+0530
     *
     * @param int $productid Id of the Product
     *
     * @return string $priceDescription        The Description of the Price
     */
    public function getPriceDescription(int $productid)
    {
        try {
            $plan = Product::find($productid)->plan();
            $description = $plan ? $plan->planPrice->first() : '';
            $priceDescription = $description ? $description->price_description : '';

            return  $priceDescription;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex->getMessage());
            app('log')->error($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function checkConfigKey($config, $transform)
    {
        $result = [];
        if ($config) {
            foreach ($config as $key => $value) {
                if (array_key_exists($key, $transform)) {
                    $result[$value] = $transform[$key];
                }
            }
        }

        return $result;
    }

    public function keyArray($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = $key;
        }

        return $result;
    }

    public function valueArray($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = $value;
        }

        return $result;
    }
}
