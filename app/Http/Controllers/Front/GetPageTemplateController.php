<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class GetPageTemplateController extends Controller
{
    /**
     * Get  Template For Helpdsk Products.
     */
    public function getHelpdeskTemplate($helpdesk_products, $data, $trasform)
    {
        $temp_controller = new \App\Http\Controllers\Common\TemplateController();
        if (count($helpdesk_products) > 0) {
            foreach ($helpdesk_products as $key => $value) {
                $trasform[$value['id']]['price'] = $temp_controller->leastAmount($value['id']);
                $trasform[$value['id']]['name'] = $value['name'];
                $trasform[$value['id']]['feature'] = $value['description'];
                $trasform[$value['id']]['subscription'] = $temp_controller
                ->plans($value['shoping_cart_link'], $value['id']);
                $trasform[$value['id']]['url'] = "<input type='submit' 
                value='Order Now' class='btn btn-primary'></form>";
            }
            $template = $this->transform('cart', $data, $trasform);
        } else {
            $template = '';
        }

        return $template;
    }

    /**
     * Get  Template For Helpdesk VPS Products.
     */
    public function getHelpdeskVpsTemplate($helpdesk_vps_product, $data, $trasform3)
    {
        $temp_controller = new \App\Http\Controllers\Common\TemplateController();
        if (count($helpdesk_vps_product) > 0) {
            foreach ($helpdesk_vps_product as $key => $value) {
                $trasform3[$value['id']]['price'] = $temp_controller->leastAmount($value['id']);
                $trasform3[$value['id']]['name'] = $value['name'];
                $trasform3[$value['id']]['feature'] = $value['description'];
                $trasform3[$value['id']]['subscription'] = $temp_controller
                ->plans($value['shoping_cart_link'], $value['id']);
                $trasform3[$value['id']]['url'] = "<input type='submit' 
                value='Order Now' class='btn btn-primary'></form>";
            }
            $helpdeskVpstemplate = $this->transform('cart', $data, $trasform3);
        } else {
            $helpdeskVpstemplate = '';
        }

        return $helpdeskVpstemplate;
    }

    /**
     * Get  Template For ServiceDesk VPS Products.
     */
    public function getServicedeskVpsTemplate($servicedesk_vps_product, $data, $trasform4)
    {
        $temp_controller = new \App\Http\Controllers\Common\TemplateController();
        if (count($servicedesk_vps_product) > 0) {
            foreach ($servicedesk_vps_product as $key => $value) {
                $trasform4[$value['id']]['price'] = $temp_controller->leastAmount($value['id']);
                $trasform4[$value['id']]['name'] = $value['name'];
                $trasform4[$value['id']]['feature'] = $value['description'];
                $trasform4[$value['id']]['subscription'] = $temp_controller
                ->plans($value['shoping_cart_link'], $value['id']);
                $trasform4[$value['id']]['url'] = "<input type='submit' 
                value='Order Now' class='btn btn-primary'></form>";
            }
            $servicedeskkVpstemplate = $this->transform('cart', $data, $trasform4);
        } else {
            $servicedeskkVpstemplate = '';
        }

        return $servicedeskkVpstemplate;
    }

    /**
     * Get  Template For Service Desk Products.
     */
    public function getServiceDeskdeskTemplate($sevice_desk_products, $data, $trasform1)
    {
        $temp_controller = new \App\Http\Controllers\Common\TemplateController();
        if (count($sevice_desk_products) > 0) {
            foreach ($sevice_desk_products as $key => $value) {
                $trasform1[$value['id']]['price'] = $temp_controller->leastAmount($value['id']);
                $trasform1[$value['id']]['name'] = $value['name'];
                $trasform1[$value['id']]['feature'] = $value['description'];
                $trasform1[$value['id']]['subscription'] = $temp_controller
                ->plans($value['shoping_cart_link'], $value['id']);

                $trasform1[$value['id']]['url'] = "<input type='submit' 
                value='Order Now' class='btn btn-primary'></form>";
            }
            $servicedesk_template = $this->transform('cart', $data, $trasform1);
        } else {
            $servicedesk_template = '';
        }

        return $servicedesk_template;
    }

    /**
     * Get  Template For Services.
     */
    public function getServiceTemplate($service, $data, $trasform2)
    {
        $temp_controller = new \App\Http\Controllers\Common\TemplateController();
        if (count($service) > 0) {
            foreach ($service as $key => $value) {
                $trasform2[$value['id']]['price'] = $temp_controller->leastAmountService($value['id']);
                $trasform2[$value['id']]['name'] = $value['name'];
                $trasform2[$value['id']]['feature'] = $value['description'];
                $trasform2[$value['id']]['subscription'] = $temp_controller
                ->plans($value['shoping_cart_link'], $value['id']);

                $trasform2[$value['id']]['url'] = "<input type='submit'
                 value='Order Now' class='btn btn-primary'></form>";
            }
            $service_template = $this->transform('cart', $data, $trasform2);
        } else {
            $service_template = '';
        }

        return $service_template;
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

    public function result($search, $model)
    {
        try {
            $model = $model->where('name', 'like', '%'.$search.'%')
            ->orWhere('content', 'like', '%'.$search.'%')
            ->paginate(10);

            return $model->setPath('search');
        } catch (\Exception $ex) {
            throw new \Exception('Can not get the search result');
        }
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
