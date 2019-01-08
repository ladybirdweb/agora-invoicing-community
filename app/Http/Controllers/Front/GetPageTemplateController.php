<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class GetPageTemplateController extends Controller
{
    /**
     * Get  Template For Helpdsk Products.
     */
    public function getTemplateOne($helpdesk_products, $data, $trasform)
    {
        $temp_controller = new \App\Http\Controllers\Common\TemplateController();
        if (count($helpdesk_products) > 0) {
            foreach ($helpdesk_products as $key => $value) {
                // $trasform[$value['id']]['group']
                $trasform[$value['id']]['price'] = $temp_controller->leastAmount($value['id']);
                $trasform[$value['id']]['name'] = $value['name'];
                $trasform[$value['id']]['feature'] = $value['description'];
                $trasform[$value['id']]['subscription'] = $temp_controller
                ->plans($value['shoping_cart_link'], $value['id']);
                $trasform[$value['id']]['url'] = "<input type='submit' 
                value='Order Now' class='btn btn-dark btn-modern btn-outline py-2 px-4'></form>";
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
    public function getTemplateTwo($helpdesk_vps_product, $data, $trasform1)
    {
        $temp_controller = new \App\Http\Controllers\Common\TemplateController();
        if (count($helpdesk_vps_product) > 0) {
            foreach ($helpdesk_vps_product as $key => $value) {
                $trasform1[$value['id']]['price'] = $temp_controller->leastAmount($value['id']);
                $trasform1[$value['id']]['name'] = $value['name'];
                $trasform1[$value['id']]['feature'] = $value['description'];
                $trasform1[$value['id']]['subscription'] = $temp_controller
                ->plans($value['shoping_cart_link'], $value['id']);
                $trasform1[$value['id']]['url'] = "<input type='submit' 
                value='Order Now' class='btn btn-primary'></form>";
            }
            $helpdeskVpstemplate = $this->transform('cart', $data, $trasform1);
        } else {
            $helpdeskVpstemplate = '';
        }

        return $helpdeskVpstemplate;
    }

    /**
     * Get  Template For ServiceDesk VPS Products.
     */
    public function getTemplateThree($servicedesk_vps_product, $data, $trasform2)
    {
        $temp_controller = new \App\Http\Controllers\Common\TemplateController();
        if (count($servicedesk_vps_product) > 0) {
            foreach ($servicedesk_vps_product as $key => $value) {
                $trasform2[$value['id']]['price'] = $temp_controller->leastAmount($value['id']);
                $trasform2[$value['id']]['name'] = $value['name'];
                $trasform2[$value['id']]['feature'] = $value['description'];
                $trasform2[$value['id']]['subscription'] = $temp_controller
                ->plans($value['shoping_cart_link'], $value['id']);
                $trasform2[$value['id']]['url'] = "<input type='submit' 
                value='Order Now' class='btn btn-primary'></form>";
            }
            $servicedeskkVpstemplate = $this->transform('cart', $data, $trasform2);
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

    /**
     * Get  Template For Service Desk Products.
     */
    // public function getTemplate($products, $data, $trasformmew)
    // {
    //     $temp_controller = new \App\Http\Controllers\Common\TemplateController();
    //     if (count($products) > 0) {
    //         foreach ($products as $key => $value) {
    //             $trasformmew[$value['id']]['price'] = $temp_controller->leastAmount($value['id']);
    //             $trasformmew[$value['id']]['name'] = $value['name'];
    //             $trasformmew[$value['id']]['feature'] = $value['description'];
    //             $trasformmew[$value['id']]['subscription'] = $temp_controller
    //             ->plans($value['shoping_cart_link'], $value['id']);

    //             $trasformmew[$value['id']]['url'] = "<input type='submit'
    //             value='Order Now' class='btn btn-primary'></form>";
    //         }
    //         $newtemplate = $this->transform('cart', $data, $trasformmew);
    //     } else {
    //         $newtemplate = '';
    //     }

    //     return $newtemplate;
    // }

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
