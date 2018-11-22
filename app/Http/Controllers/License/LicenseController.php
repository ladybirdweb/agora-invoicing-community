<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Product\Product;
use App\User;
use App\ApiKey;

class LicenseController extends Controller
{
    public $api_key_secret;
    public $url;
    public $license;
  
    public function __construct()
    {
        $model = new ApiKey;
        $this->license = $model->firstOrFail();

        $this->api_key_secret = $this->license->license_api_secret;
        $this->url = $this->license->license_api_url;
    }


    public function postCurl($post_url, $post_info)
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
    public function addNewProduct($product_name, $product_sku)
    {
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=products_add&product_title=$product_name&product_sku=$product_sku&product_status=1");
    }

    /*
   *  Add New User
   */
    public function addNewUser($first_name, $last_name, $email)
    {
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=clients_add
      &client_fname=$first_name&client_lname=$last_name&client_email=$email&client_status=1");
    }

    /*
   *  Edit Product
   */
    public function editProduct($product_name, $product_sku)
    {
        $productId = $this->searchProductId($product_sku);
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=products_edit
      &product_id=$productId&product_title=$product_name&product_sku=$product_sku&product_status=1");
    }

    /*
   *  Search for product id while updating client
   */
    public function searchProductId($product_sku)
    {
        try {
            $productId = '';
            $url =   $this->url;
            $api_key_secret =  $this->api_key_secret;
            $getProductId = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
      &search_type=product&search_keyword=$product_sku");

            $details = json_decode($getProductId);
            if ($details->api_error_detected == 0 && is_array($details->page_message)) {//This is not true if Product_sku is updated
                $productId = $details->page_message[0]->product_id;
            }

            return $productId;
        } catch (\Exception $ex) {
            $result = [$ex->getMessage()];

            return response()->json(compact('result'), 500);
        }
    }

    /*
   *  Edit User
   */
    public function editUserInLicensing($first_name, $last_name, $email)
    {
        $userId = $this->searchForUserId($email);
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=clients_edit&client_id=$userId
      &client_fname=$first_name&client_lname=$last_name&client_email=$email&client_status=1");
    }

    /*
   *  Search for user id while updating client
   */
    public function searchForUserId($email)
    {
        $userId = '';
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $getUserId = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
      &search_type=client&search_keyword=$email");

        $details = json_decode($getUserId);
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {//This is not true if email is updated
            $userId = $details->page_message[0]->client_id;
        }

        return $userId;
    }

    /*
    *  Create New License For User
    */
    public function createNewLicene($orderid, $product, $user_id, $ends_at)
    {
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $sku = Product::where('id', $product)->first()->product_sku;
        $licenseExpirationCheck = Product::where('id', $product)->first()->perpetual_license;
        $expiry = ($licenseExpirationCheck == 1) ? $ends_at->toDateString() : '';
        $order = Order::where('id', $orderid)->first();
        $orderNo = $order->number;
        $domain = $order->domain;
        $email = User::where('id', $user_id)->first()->email;
        $userId = $this->searchForUserId($email);
        $productId = $this->searchProductId($sku);
        $addLicense = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=licenses_add&product_id=$productId&client_id=$userId
      &license_require_domain=1&license_status=1&license_order_number=$orderNo&license_domain=$domain&license_limit=5&license_expire_date=$expiry&license_disable_ip_verification=0");
    }

    /*
    *  Edit Existing License
    */
    public function updateLicensedDomain($clientEmail, $domain)
    {
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $searchLicense = $this->searchLicenseId($clientEmail);
        $licenseId = $searchLicense['licenseId'];
        $productId = $searchLicense['productId'];
        $userId = $searchLicense['userId'];
        $updateLicense = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=licenses_edit&product_id=$productId&client_id=$userId&license_id=$licenseId&license_require_domain=1&license_status=1&license_domain=$domain");
       
    }

    public function searchLicenseId($email)
    {
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $getLicenseId = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
      &search_type=license&search_keyword=$email");

        $details = json_decode($getLicenseId);
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {
            $licenseId = $details->page_message[0]->license_id;
            $productId = $details->page_message[0]->product_id;
            $userId = $details->page_message[0]->client_id;
        }

        return ['productId'=>$productId, 'userId'=>$userId, 'licenseId'=>$licenseId];
    }

    //Update the Installation status as Inactive after Licensed Domain Is Chnaged
    public function updateInstalledDomain($email)
    {
        $installation_id = '';
        $installation_ip = '';
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        //Search for the Installation Id
        $searchInstallationId = $this->searchInstallationId($email);
        $details = json_decode($searchInstallationId);
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {
            $installation_id = $details->page_message[0]->installation_id;
            $installation_ip = $details->page_message[0]->installation_ip;
        }
        // delete The Existing Installation
        $updateInstallation = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=installations_edit&installation_id=$installation_id&installation_ip=$installation_ip&installation_status=0");
    }

    public function searchInstallationId($email)
    {
        $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $getInstallId = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
      &search_type=installation&search_keyword=$email");

        return $getInstallId;
    }

    //Update Expiration Date After Renewal
    public function updateExpirationDate($clientEmail,$expiryDate)
    {
         $url =   $this->url;
        $api_key_secret =  $this->api_key_secret;
        $searchLicense = $this->searchLicenseId($clientEmail);
        $licenseId = $searchLicense['licenseId'];
        $productId = $searchLicense['productId'];
        $userId = $searchLicense['userId'];
        $updateLicense = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=licenses_edit&product_id=$productId&client_id=$userId&license_id=$licenseId&license_require_domain=1&license_status=1&license_expire_date=$expiryDate");
       
    }
}
