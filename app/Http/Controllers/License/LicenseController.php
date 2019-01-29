<?php

namespace App\Http\Controllers\License;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Product\Product;
use App\User;

class LicenseController extends Controller
{
    private $api_key_secret;
    private $url;
    private $license;

    public function __construct()
    {
        $model = new ApiKey();
        $this->license = $model->firstOrFail();

        $this->api_key_secret = $this->license->license_api_secret;
        $this->url = $this->license->license_api_url;
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
    public function addNewProduct($product_name, $product_sku)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=products_add&product_title=$product_name&product_sku=$product_sku&product_status=1");
    }

    /*
   *  Add New User
   */
    public function addNewUser($first_name, $last_name, $email)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=clients_add
      &client_fname=$first_name&client_lname=$last_name&client_email=$email&client_status=1");
    }

    /*
   *  Edit Product
   */
    public function editProduct($product_name, $product_sku)
    {
        $productId = $this->searchProductId($product_sku);
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
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
            $url = $this->url;
            $api_key_secret = $this->api_key_secret;
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
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $addProduct = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=clients_edit&client_id=$userId
      &client_fname=$first_name&client_lname=$last_name&client_email=$email&client_status=1");
    }

    /*
   *  Search for user id while updating client
   */
    public function searchForUserId($email)
    {
        $userId = '';
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
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
    public function createNewLicene($orderid, $product, $user_id,
        $licenseExpiry, $updatesExpiry, $supportExpiry, $serial_key)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $sku = Product::where('id', $product)->first()->product_sku;
        $licenseExpiry = ($licenseExpiry != '') ? $licenseExpiry->toDateString() : '';
        $updatesExpiry = ($updatesExpiry != '') ? $updatesExpiry->toDateString() : '';
        $supportExpiry = ($supportExpiry != '') ? $supportExpiry->toDateString() : '';
        $order = Order::where('id', $orderid)->first();
        $orderNo = $order->number;
        $domain = $order->domain;
        $productId = $this->searchProductId($sku);
        $addLicense = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=licenses_add&product_id=$productId&license_code=$serial_key&license_require_domain=1&license_status=1&license_order_number=$orderNo&license_domain=$domain&license_limit=2&license_expire_date=$licenseExpiry&license_updates_date=$updatesExpiry&license_support_date=$supportExpiry&license_disable_ip_verification=0");
    }

    /*
    *  Edit Existing License
    */
    public function updateLicensedDomain($licenseCode, $domain, $productId, $expiryDate, $orderNo)
    {
        if ($expiryDate) {
            $expiryDate = $expiryDate->toDateString();
        }
        $url = $this->url;
        $isIP = (bool) ip2long($domain);
        if ($isIP == true) {
            $ip = $domain;
            $domain = '';
        } else {
            $domain = $domain;
            $ip = '';
        }
        $api_key_secret = $this->api_key_secret;
        $searchLicense = $this->searchLicenseId($licenseCode, $productId);
        $licenseId = $searchLicense['licenseId'];
        $productId = $searchLicense['productId'];
        $licenseCode = $searchLicense['code'];
        $updateLicense = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=licenses_edit&product_id=$productId&license_code=$licenseCode&license_id=$licenseId&license_order_number=$orderNo&license_require_domain=1&license_status=1&license_expire_date=$expiryDate&license_domain=$domain&license_ip=$ip");
    }

    public function searchLicenseId($licenseCode, $productId)
    {
        $license = '';
        $product = '';
        $code = '';
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $getLicenseId = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
        &search_type=license&search_keyword=$licenseCode");
        $details = json_decode($getLicenseId);
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {
            foreach ($details->page_message as $detail) {
                if ($detail->product_id == $productId) {
                    $license = $detail->license_id;
                    $product = $detail->product_id;
                    $code = $detail->license_code;
                }
            }
        }

        return ['productId'=>$product, 'code'=>$code, 'licenseId'=>$license];
    }

    //Update the Installation status as Inactive after Licensed Domain Is Chnaged
    public function updateInstalledDomain($licenseCode, $productId)
    {
        $installation_id = '';
        $installation_ip = '';
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        //Search for the Installation Id
        $searchInstallationId = $this->searchInstallationId($licenseCode);
        $details = json_decode($searchInstallationId);
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {
            foreach ($details->page_message as $detail) {
                if ($detail->product_id == $productId) {
                    $installation_id = $detail->installation_id;
                    $installation_ip = $detail->installation_ip;
                }
            }
        }
        // deactivate The Existing Installation
        $updateInstallation = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=installations_edit&installation_id=$installation_id&installation_ip=$installation_ip&installation_status=0");
    }

    public function searchInstallationId($licenseCode)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $getInstallId = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
      &search_type=installation&search_keyword=$licenseCode");

        return $getInstallId;
    }

    //Update  Expiration Date After Renewal
    public function updateExpirationDate($licenseCode, $expiryDate, $productId, $domain, $orderNo, $licenseExpiry, $supportExpiry)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $searchLicense = $this->searchLicenseId($licenseCode, $productId);
        $licenseId = $searchLicense['licenseId'];
        $productId = $searchLicense['productId'];
        $code = $searchLicense['code'];
        $updateLicense = $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=licenses_edit&product_id=$productId&license_code=$code&license_id=$licenseId&license_order_number=$orderNo&license_domain=$domain&license_require_domain=1&license_status=1&license_expire_date=$licenseExpiry&license_updates_date=$expiryDate&license_support_date=$supportExpiry");
    }
}
