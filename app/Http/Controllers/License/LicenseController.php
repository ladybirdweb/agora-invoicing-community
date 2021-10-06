<?php

namespace App\Http\Controllers\License;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Product\Product;
use App\ThirdPartyApp;
use App\User;

class LicenseController extends Controller
{
    private $api_key_secret;
    private $url;
    private $license;
    private $token;

    public function __construct()
    {
        $model = new ApiKey();
        $this->license = $model->first();

        $this->api_key_secret = $this->license->license_api_secret;
        $this->url = $this->license->license_api_url;

        //To authorize to access license manager 
        $this->client_id = $this->license->license_client_id;
        $this->client_secret = $this->license->license_client_secret;
        $this->grant_type = $this->license->license_grant_type;
    }

      /**
       * Generate a time limited access token to access license manager 
       * */
      private function oauthAuthorization()
      {
        $url = $this->url;
        $data = [
            'client_id'=> $this->client_id,
            'client_secret'=>$this->client_secret,
            'grant_type' => $this->grant_type,
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
    public function addNewProduct($product_name, $product_sku)
    {
        $url = $this->url;
        $key = str_random(16);
        $api_key_secret = $this->api_key_secret;
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $addProduct = $this->postCurl($url.'api/admin/products/add', "api_key_secret=$api_key_secret&product_title=$product_name&product_key=$key&product_sku=$product_sku&product_status=1",$token); 
    }

    /*
   *  Add New User
   */
    public function addNewUser($first_name, $last_name, $email)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $addProduct = $this->postCurl($url.'api/admin/clients/add', "api_key_secret=$api_key_secret&client_fname=$first_name&client_lname=$last_name&client_email=$email&client_status=1",$token);
    }

    /*
   *  Edit Product
   */
    public function editProduct($product_name, $product_sku)
    {
        $productId = $this->searchProductId($product_sku);
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $addProduct = $this->postCurl($url.'api/admin/products/edit', "api_key_secret=$api_key_secret&product_id=$productId&product_title=$product_name&product_sku=$product_sku&product_status=1",$token);
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
            $OauthDetails = $this->oauthAuthorization();
            $token = $OauthDetails->access_token;
            $getProductId = $this->postCurl($url.'api/admin/search', "api_key_secret=$api_key_secret&search_type=product&search_keyword=$product_sku&isLicenseSearchApi=1",$token);
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

    public function deleteProductFromAPL($product)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $productId = $this->searchProductId($product->product_sku);
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $productTitle = $product->name;
        $productSku = $product->sku;
        $delProduct = $this->postCurl($url.'api/admin/products/delete', "api_key_secret=$api_key_secret&product_id=$productId&product_title=$productTitle&product_sku=$productSku&product_status=1&delete_record=1",$token);
    }

    /*
   *  Edit User
   */
    public function editUserInLicensing($first_name, $last_name, $email)
    {
        $userId = $this->searchForUserId($email);
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $addProduct = $this->postCurl($url.'api/admin/clients/edit', "api_key_secret=$api_key_secret&client_id=$userId&client_fname=$first_name&client_lname=$last_name&client_email=$email&client_status=1",$token);
    }

    /*
   *  Search for user id while updating client
   */
    public function searchForUserId($email)
    {
        $userId = '';
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $getUserId = $this->postCurl($url.'api/admin/search', "api_key_secret=$api_key_secret&search_type=client&search_keyword=$email&isLicenseSearchApi=1",$token);

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
        $ipAndDomain = $this->getIpAndDomain($domain);
        $ip = $ipAndDomain['ip'];
        $domain = $ipAndDomain['domain'];
        $requireDomain = $ipAndDomain['requireDomain'];
        $productId = $this->searchProductId($sku);
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $addLicense = $this->postCurl($url.'api/admin/license/add', "api_key_secret=$api_key_secret&product_id=$productId&license_code=$serial_key&license_require_domain=1&license_status=1&license_order_number=$orderNo&license_domain=$domain&license_ip=$ip&license_require_domain=$requireDomain&license_limit=6&license_expire_date=$licenseExpiry&license_updates_date=$updatesExpiry&license_support_date=$supportExpiry&license_disable_ip_verification=0&license_limit=2",$token);

        //return response(['message'=>'its created','data'=> $addLicense]);
    }

    /*
    *  Edit Existing License
    */
    public function updateLicensedDomain($licenseCode, $domain, $productId, $licenseExpiry, $updatesExpiry, $supportExpiry, $orderNo, $license_limit = 2, $requiredomain = 1)
    {
        $l_expiry = '';
        $s_expiry = '';
        $u_expiry = '';
        if (strtotime($licenseExpiry) > 1) {
            $l_expiry = date('Y-m-d', strtotime($licenseExpiry));
        }
        if (strtotime($updatesExpiry) > 1) {
            $u_expiry = date('Y-m-d', strtotime($updatesExpiry));
        }
        if (strtotime($supportExpiry) > 1) {
            $s_expiry = date('Y-m-d', strtotime($supportExpiry));
        }
        $url = $this->url;
        $ipAndDomain = $this->getIpAndDomain($domain);
        $token = $this->token;
        $ip = $ipAndDomain['ip'];
        $domain = $ipAndDomain['domain'];
        $requireDomain = $ipAndDomain['requireDomain'];
        $api_key_secret = $this->api_key_secret;
        $searchLicense = $this->searchLicenseId($licenseCode, $productId);
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $licenseId = $searchLicense['licenseId'];
        $productId = $searchLicense['productId'];
        $licenseCode = $searchLicense['code'];
        $updateLicense = $this->postCurl($url.'api/admin/license/edit', "api_key_secret=$api_key_secret&product_id=$productId&license_code=$licenseCode&license_id=$licenseId&license_order_number=$orderNo&license_require_domain=$requireDomain&license_status=1&license_expire_date=$l_expiry&license_updates_date=$u_expiry&license_support_date=$s_expiry&license_domain=$domain&license_ip=$ip&license_limit=$license_limit",$token);
    }

    /**
     * Get the Ip and domain that is to be entered in License Manager.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-05-11T11:31:07+0530
     *
     * @param string $domain
     *
     * @return array
     */
    protected function getIpAndDomain($domain)
    {
        if ($domain != '') {
            if (ip2long($domain)) {
                return ['ip'=>$domain, 'domain'=>'', 'requireDomain'=>0];
            } else {
                return ['ip'=>'', 'domain'=>$domain, 'requireDomain'=>1];
            }
        } else {
            return ['ip'=>'', 'domain'=>'', 'requireDomain'=>0];
        }
    }

    public function searchLicenseId($licenseCode, $productId)
    {
        $license = '';
        $product = '';
        $code = '';
        $limit = '';
        $ipOrDomain = '';
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $getLicenseId = $this->postCurl($url.'api/admin/search', "api_key_secret=$api_key_secret&search_type=license&search_keyword=$licenseCode&isLicenseSearchApi=1",$token);
        $details = json_decode($getLicenseId);
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {
            foreach ($details->page_message as $detail) {
                if ($detail->product_id == $productId) {
                    $license = $detail->license_id;
                    $product = $detail->product_id;
                    $code = $detail->license_code;
                    $limit = $detail->license_limit;
                    $ipOrDomain = $detail->license_require_domain;
                }
            }
        }

        return ['productId'=>$product, 'code'=>$code, 'licenseId'=>$license, 'allowedInstalltion'=>$ipOrDomain, 'installationLimit'=>$limit];
    }

    //Update the Installation status as Inactive after Licensed Domain Is Chnaged
    public function updateInstalledDomain($licenseCode, $productId)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        
        //Search for the Installation Id
        $searchInstallationId = $this->searchInstallationId($licenseCode);
        $details = json_decode($searchInstallationId);
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
            foreach ($details->page_message as $detail) {
                if ($detail->product_id == $productId) {
                    $installation_id = $detail->installation_id;
                    $installation_ip = $detail->installation_ip;
                    //delete all existing installation
                    $updateInstallation = $this->postCurl($url.'api/admin/installations/edit', "api_key_secret=$api_key_secret&installation_id=$installation_id&installation_ip=$installation_ip&installation_status=0&delete_record=1",$token);
                }
            }
        }
    }

    public function searchInstallationId($licenseCode)
    {
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $getInstallId = $this->postCurl($url.'api/admin/search', "api_key_secret=$api_key_secret&search_type=installation&search_keyword=$licenseCode&isLicenseSearchApi=1",$token);

        return $getInstallId;
    }

    public function searchInstallationPath($licenseCode, $productId)
    {
        $installation_domain = [];
        $installation_ip = [];
        $details = json_decode($this->searchInstallationId($licenseCode));
        
        if ($details->api_error_detected == 0 && is_array($details->page_message)) {
            foreach ($details->page_message as $detail) {
                if ($detail->product_id == $productId) {
                    // $installation_domain[] = "<a href=https://$detail->installation_domain target = '_blank'>  "."$detail->installation_domain</a>".' | '.$detail->installation_ip;
                    $installation_domain[] = $detail->installation_domain.','.$detail->installation_ip;
                    $installation_ip[] = $detail->installation_ip;
                }
            }
        }

        return ['installed_path' => $installation_domain, 'installed_ip' => $installation_ip];
    }

    //Update  Expiration Date After Renewal
    public function updateExpirationDate($licenseCode, $expiryDate, $productId, $domain, $orderNo, $licenseExpiry, $supportExpiry, $license_limit = 2, $requiredomain = 1)
    {
        $url = $this->url;
        $ipAndDomain = $this->getIpAndDomain($domain);
        $ip = $ipAndDomain['ip'];
        $domain = $ipAndDomain['domain'];
        $requireDomain = $ipAndDomain['requireDomain'];

        $api_key_secret = $this->api_key_secret;
       
        $searchLicense = $this->searchLicenseId($licenseCode, $productId);
        $OauthDetails = $this->oauthAuthorization();
        $token = $OauthDetails->access_token;
        $licenseId = $searchLicense['licenseId'];
        $productId = $searchLicense['productId'];
        $code = $searchLicense['code'];
        $updateLicense = $this->postCurl($url.'api/admin/license/edit', "api_key_secret=$api_key_secret&product_id=$productId&license_code=$code&license_id=$licenseId&license_order_number=$orderNo&license_domain=$domain&license_ip=$ip&license_require_domain=$requireDomain&license_status=1&license_expire_date=$licenseExpiry&license_updates_date=$expiryDate&license_support_date=$supportExpiry&license_limit=$license_limit",$token);
    }

    public function getNoOfAllowedInstallation($licenseCode, $productId)
    {
        $api_key_secret = $this->api_key_secret;
        $searchLicense = $this->searchLicenseId($licenseCode, $productId);

        return $searchLicense['installationLimit'];
    }

    public function getInstallPreference($licenseCode, $productId)
    {
        $api_key_secret = $this->api_key_secret;
        $searchLicense = $this->searchLicenseId($licenseCode, $productId);

        return $searchLicense['allowedInstalltion'];
    }
}
