<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use App\Model\Product\Product;
use App\Model\Order\Order;
use App\User;

class LicenseController extends Controller
{
   public function postCurl($post_url, $post_info)
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
   public function addNewProduct($product_name,$product_sku)
   {
   	$url = "https://license.faveohelpdesk.com/apl_api/api.php";
   	$api_key_secret = "0bs8ArC9Tp1mG6Cg";
   	 $addProduct= $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=products_add&product_title=$product_name&product_sku=$product_sku&product_status=1");
   }

       /*
   *  Add New User
   */
   public function addNewUser($first_name,$last_name,$email)
   {
    $url = "https://license.faveohelpdesk.com/apl_api/api.php";
    $api_key_secret = "0bs8ArC9Tp1mG6Cg";
    $addProduct= $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=clients_add
      &client_fname=$first_name&client_lname=$last_name&client_email=$email&client_status=1");
   }

    /*
   *  Edit Product
   */
   public function editProduct($product_name,$product_sku)
   {
    $productId = $this->searchProductId($product_sku);
   	$url = "https://license.faveohelpdesk.com/apl_api/api.php";
   	$api_key_secret = "0bs8ArC9Tp1mG6Cg";
   	$addProduct= $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=products_edit
      &product_id=$productId&product_title=$product_name&product_sku=$product_sku&product_status=1");
   }

    /*
   *  Search for product id while updating client
   */
    public function searchProductId($product_sku)
    {
      try{
      $productId = '';
      $url = "https://license.faveohelpdesk.com/apl_api/api.php";
      $api_key_secret = "0bs8ArC9Tp1mG6Cg";
      $getProductId= $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
      &search_type=product&search_keyword=$product_sku");
      $details =  json_decode($getProductId);
      if($details->api_error_detected == 0 && is_array($details->page_message)) {//This is not true if Product_sku is updated
        $productId = $details->page_message[0]->product_id;
       }
      
      return $productId;
      }catch (\Exception $ex) {
         $result = [$ex->getMessage()];
         return response()->json(compact('result'), 500);
      }
      
    }

    /*
   *  Edit User
   */
   public function editUserInLicensing($first_name,$last_name,$email)
   {
     $userId = $this->searchForUserId($email);
      $url = "https://license.faveohelpdesk.com/apl_api/api.php";
      $api_key_secret = "0bs8ArC9Tp1mG6Cg";
      $addProduct= $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=clients_edit&client_id=$userId
      &client_fname=$first_name&client_lname=$last_name&client_email=$email&client_status=1");
    }
   
     /*
   *  Search for user id while updating client
   */
    public function searchForUserId($email)
    {
      $userId = '';
       $url = "https://license.faveohelpdesk.com/apl_api/api.php";
       $api_key_secret = "0bs8ArC9Tp1mG6Cg";
      $getUserId= $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=search
      &search_type=client&search_keyword=$email");
      $details =  json_decode($getUserId);
    if($details->api_error_detected == 0 && is_array($details->page_message)) {//This is not true if email is updated
       $userId = $details->page_message[0]->client_id;
    }
      return $userId;
    }
    

   /*
   *  Create New License For User 
   */
   public function createNewLicene($orderid,$product,$user_id,$ends_at )
   {
     $url = "https://license.faveohelpdesk.com/apl_api/api.php";
      $expiry = $ends_at->toDateString();
      $api_key_secret = "0bs8ArC9Tp1mG6Cg";
      $sku = Product::where('id',$product)->first()->product_sku;
      $order = Order::where('id',$orderid)->first();
      $orderNo = $order->number;
      $domain = $order->domain;
      $email = User::where('id',$user_id)->first()->email;
      $userId = $this->searchForUserId($email);
      $productId = $this->searchProductId($sku);
       $addLicense= $this->postCurl($url, "api_key_secret=$api_key_secret&api_function=licenses_add&product_id=$productId&client_id=$userId
      &license_require_domain=1&license_status=1&license_order_number=$orderNo&license_domain=$domain&license_limit=5&license_expire_date=$expiry&license_disable_ip_verification=0");

   }

}
