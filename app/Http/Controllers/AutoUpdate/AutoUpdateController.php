<?php

namespace App\Http\Controllers\AutoUpdate;

use App\ApiKey;
use App\ThirdPartyApp;
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
  

      private function oauthAuthorization()
      {
        $url = $this->url;
        $data = [
            'client_id'=>3,
            'client_secret'=>'al2kUNnATgwPYuxZTRYGQrOAQjeEjr5TUDOrTwgI',
            'grant_type' => 'client_credentials',
        ];
        $response = $this->postCurl($url."oauth/token",$data);
        $response=json_decode($response);
        return $response;
    }
     

    private function postCurl($post_url, $post_info,$token=null)
    {
       if(!empty($token))
       {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BEARER);
        curl_setopt($ch,CURLOPT_XOAUTH2_BEARER,$token);
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
    else{
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
    }

    /*
    *  Add New Product
    */
    /*public function addNewProductToAUS($product_name, $product_sku)
    {
        $url = $this->url;
        $key = str_random(16);
        $token = $this->token;
        $api_key_secret = $this->api_key_secret;
        $addProduct = $this->postCurl($url."api/admin/products/add", "token=$token&api_key_secret=$api_key_secret&product_title=$product_name&product_sku=$product_sku&product_key=$key&product_status=1");
    }*/

    /*
    *  Add New Version
    */

    public function addNewVersion($product_id, $version_number, $upgrade_zip_file, $version_status)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $addNewVersion = $this->postCurl($url."api/admin/versions/add", "api_key_secret=$api_key_secret&product_id=$product_id&version_number=$version_number&version_upgrade_file=$upgrade_zip_file&version_status=$version_status&product_status=1",$token);
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
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $addNewVersion = $this->postCurl($url."api/admin/versions/edit", "api_key_secret=$api_key_secret&product_id=productId&version_id=$versionId&version_number=$version_number&version_status=1",$token);
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
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $getVersion = $this->postCurl($url.'api/admin/search', "api_key_secret=$api_key_secret&search_type=version&search_keyword=$product_sku",$token);
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
