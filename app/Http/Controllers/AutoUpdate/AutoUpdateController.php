<?php

namespace App\Http\Controllers\AutoUpdate;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AutoUpdateController extends Controller
{
    private function postCurl($post_url, $post_info)
    {
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result=curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    
    /*
    *  Add New Product
    */
    public function addNewProduct($product_name, $product_sku)
    {
        $url = 'https://updates.faveohelpdesk.com/aus_api/api.php';
        $api_key_secret = 'oiZJh1G4yw00LPbC';
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=products_add&product_title=$product_name&product_sku=$product_sku&product_key='123456'&product_status=1");
    }
}
