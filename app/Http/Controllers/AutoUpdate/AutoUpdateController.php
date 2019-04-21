<?php

namespace App\Http\Controllers\AutoUpdate;

use App\ApiKey;
use App\Http\Controllers\Controller;

class AutoUpdateController extends Controller
{
    private $api_key_secret;
    private $url;
    private $update;

    public function __construct()
    {
        $model = new ApiKey();
        $this->update = $model->firstOrFail();

        $this->api_key_secret = $this->update->update_api_secret;
        $this->url = $this->update->update_api_url;
    }

    private function postCurl($post_url, $post_info)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /*
    *  Add New Product
    */
    public function addNewProductToAUS($product_name, $product_sku)
    {
        $url = $this->url;
        $key = str_random(16);
        $api_key_secret = $this->api_key_secret;
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=products_add&product_title=$product_name&product_sku=$product_sku&product_key=$key&product_status=1");
    }

    /*
    *  Add New Version
    */
    public function addNewVersion($product_id, $version_number, $upgrade_zip_file, $version_status)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $addNewVersion = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=versions_add&product_id=$product_id&version_number=$version_number&version_upgrade_file=$upgrade_zip_file&version_status=$version_status&product_status=1");
    }

    /*
    *  Edit Version
    */
    public function editVersion($version_number, $product_sku)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $searchLicense = $this->searchVersion($version_number, $product_sku);
        $versionId = $searchLicense['version_id'];
        $productId = $searchLicense['product_id'];
        $addNewVersion = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=versions_edit&product_id=productId&version_id=$versionId&version_number=$version_number&version_status=1");
    }

    /*
    *  Search Version
    */
    public function searchVersion($version_number, $product_sku)
    {
        $versionId = '';
        $productId = '';
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $getVersion = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
        &search_type=version&search_keyword=$product_sku");
        $details = json_decode($getVersion);
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {
            foreach ($details->page_message as $detail) {
                if ($detail->version_number == $version_number) {
                    $versionId = $detail->version_id;
                    $productId = $detail->product_id;
                }
            }
        }

        return ['version_id'=>$versionId, 'product_id'=>$productId];
    }
}
