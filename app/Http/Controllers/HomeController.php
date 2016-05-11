<?php

namespace App\Http\Controllers;

use App\Model\Product\Product;
use App\Model\Order\Order;
use Illuminate\Http\Request;
use Exception;

class HomeController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Home Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders your application's "dashboard" for users that
      | are authenticated. Of course, you are free to change or remove the
      | controller as you wish. It is just here to get your app started!
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth', ['only' => ['index']]);
        $this->middleware('admin', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        return view('themes.default1.layouts.master');
    }

    public function version(Request $request, Product $product) {

        $url = $request->input('response_url');

        $title = $request->input('title');
        //dd($title);
        $id = $request->input('id');
        if ($id) {
            $product = $product->where('id', $id)->first();
        } else {
            $product = $product->where('name', $title)->first();
        }


        if ($product) {
            $version = str_replace('v', '', $product->version);
        } else {
            $version = "Not-Available";
        }


        echo "<form action=$url method=post name=redirect >";
        echo "<input type=hidden name=_token value=" . csrf_token() . ">";
        echo "<input type=hidden name=value value=$version />";
        echo "</form>";
        echo"<script language='javascript'>document.redirect.submit();</script>";
    }

    public function getVersion(Request $request, Product $product) {
        $this->validate($request, [
            'title' => 'required',
        ]);
        $title = $request->input('title');
        $product = $product->where('name', $title)->first();
        if ($product) {
            $version = $product->version;
        } else {
            return 0;
        }
        return str_replace('v', '', $product->version);
    }

    public function versionTest() {
        $s = "eyJpdiI6ImFIVDByR29vVzNpcEExM2UyNDVaWXc9PSIsInZhbHVlIjoiODNJS0MxWXFyVEtrYjhZYXFmUFlvOTJYY09NUHhGYTZBemN2eFMzckZCST0iLCJtYWMiOiI2MDdmZTU5YmRjMjQxOWRlZjE3ODUyMWI0OTk5NDM5ZmQxMWE5ZTUyNzQ3YTMyOGQyYmRmNGVkYWQyNDM5ZTNkIn0=";
        dd(decrypt($s));
        $url = "http://localhost/billings/agorainvoicing/agorainvoicing/public/version";
        $response = "http://localhost/billings/agorainvoicing/agorainvoicing/public/version-result";
        $name = "faveo helpdesk community";
        echo "<form action=$url method=post name=redirect >";
        echo "<input type=hidden name=_token value=csrf_token() />";
        echo "<input type=hidden name=response_url value=$response />";
        echo "<input type=hidden name=title value='faveo helpdesk community' />";
        echo "</form>";
        echo"<script language='javascript'>document.redirect.submit();</script>";
    }

    public function versionResult(Request $request) {
        dd($request->all());
    }

    public function serial(Request $request, Order $order) {

        $ul = $request->input('url');
        $url = str_replace("serial", "CheckSerial", $ul);
        $domain = $request->input('domain');
        $first = $request->input('first');
        $second = $request->input('second');
        $third = $request->input('third');
        $forth = $request->input('forth');
        $serial = $first . $second . $third . $forth;
        //dd($serial);
        $order_no = $request->input('order_no');
        $order = $order->where('number', $order_no)->first();
        if ($order) {
            if ($domain === $order->domain) {

                $key = $order->serial_key;
                if ($key === $serial) {

                    $id1 = 'true';
                    echo "<form action=$url/$id1 method=post name=redirect>";
                    echo "<input type=hidden name=_token value=csrf_token()/>";
                    echo "</form>";
                    echo"<script language='javascript'>document.redirect.submit();</script>";
                } else {
                    $id = 'false1';
                    echo "<form action=$url/$id method=post name=redirect>";
                    echo "<input type=hidden name=_token value=csrf_token()/>";
                    echo "</form>";
                    echo"<script language='javascript'>document.redirect.submit();</script>";
                }
            } else {
                $id = 'false3';
                echo "<form action=$url/$id method=post name=redirect>";
                echo "<input type=hidden name=_token value=csrf_token()/>";
                echo "</form>";
                echo"<script language='javascript'>document.redirect.submit();</script>";
            }
        } else {
            $id = 'false2';
            echo "<form action=$url/$id method=post name=redirect>";
            echo "<input type=hidden name=_token value=csrf_token()/>";
            echo "</form>";
            echo"<script language='javascript'>document.redirect.submit();</script>";
        }
    }

    public static function decryptByFaveoPrivateKey($encrypted) {
        try {
            // Get the private Key
            $path = storage_path('app/faveo-private.key');
            $key_content = file_get_contents($path);
            if (!$privateKey = openssl_pkey_get_private($key_content)) {
                throw new \Exception('Private Key failed');
            }
            $a_key = openssl_pkey_get_details($privateKey);

            // Decrypt the data in the small chunks
            $chunkSize = ceil($a_key['bits'] / 8);
            $output = '';

            while ($encrypted) {
                $chunk = substr($encrypted, 0, $chunkSize);
                $encrypted = substr($encrypted, $chunkSize);
                $decrypted = '';
                if (!openssl_private_decrypt($chunk, $decrypted, $privateKey)) {
                    die('Failed to decrypt data');
                }
                $output .= $decrypted;
            }
            openssl_free_key($privateKey);

            // Uncompress the unencrypted data.
            $output = gzuncompress($output);

            return $output;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function getEncryptedData(Request $request) {
        $enc = $request->input('en');
        $result = self::decryptByFaveoPrivateKey($enc);
        return response()->json($result);
    }

    public function createEncryptionKeys() {
        try {
            $privateKey = openssl_pkey_new(array(
                'private_key_bits' => 2048, // Size of Key.
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ));
            //dd($privateKey);
            // Save the private key to private.key file. Never share this file with anyone.
            openssl_pkey_export_to_file($privateKey, 'faveo-private-new.key');

            // Generate the public key for the private key
            $a_key = openssl_pkey_get_details($privateKey);
            //dd($a_key);
            // Save the public key in public.key file. Send this file to anyone who want to send you the encrypted data.
            file_put_contents('faveo-public-new.key', $a_key['key']);

            // Free the private Key.
            openssl_free_key($privateKey);
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function checkSerialKey($faveo_encrypted_key,$order_number) {
        try {
            $order = new Order();
            $faveo_decrypted_key = self::decryptByFaveoPrivateKey($faveo_encrypted_key);
            $this_order = $order->where('number', $order_number)->first();
            if (!$this_order) {
                return null;
            } else {
               if($this_order->serial_key == $faveo_decrypted_key){
                   return $this_order->serial_key;
               }
            }
            return null;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function checkOrder($faveo_encrypted_order_number) {
        try {
            $order = new Order();
            $faveo_decrypted_order = self::decryptByFaveoPrivateKey($faveo_encrypted_order_number);
            $this_order = $order->where('number', $faveo_decrypted_order)->first();
            if (!$this_order) {
                return null;
            } else {
                return $this_order->number;
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function checkDomain($request_url) {
        try {
//            echo $request_url;
//            exit();
            $order = new Order();
            $this_order = $order->where('domain', $request_url)->first();
            if (!$this_order) {
                return null;
            } else {
                return $this_order->domain;
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function verifyOrder($order_number, $serial_key, $domain) {
        try {
            
            $order = new Order();
            $this_order = $order
                    ->where('number', $order_number)
                   // ->where('serial_key', $serial_key)
                    ->where('domain', $domain)
                    ->first();
            return $this_order;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function faveoVerification(Request $request) {
        try {
            $url = $request->input('url');
            $faveo_encrypted_order_number = $request->input('order_number');
            $faveo_encrypted_key = $request->input('serial_key');
            $request_type = $request->input('request_type');
            $faveo_name = $request->input('name');
            $faveo_version = $request->input('version');
            $order_number = $this->checkOrder($faveo_encrypted_order_number);
            $domain = $this->checkDomain($url);
            $serial_key = $this->checkSerialKey($faveo_encrypted_key,$order_number);
            //return $serial_key;
            $result = [];
            if ($request_type == 'install') {
                $result = $this->verificationResult($order_number, $serial_key, $domain);
            }
            if ($request_type == 'check_update') {
                $result = $this->checkUpdate($order_number, $serial_key, $domain, $faveo_name, $faveo_version);
            }
           
            return response()->json($result);
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->getMessage()];
            return response()->json($result);
        }
    }

    public function verificationResult($order_number, $serial_key, $domain) {
        try {

            if ($order_number && $domain && $serial_key) {
                $order = $this->verifyOrder($order_number, $serial_key, $domain);
                if ($order) {
                    return ['status' => 'success', 'message' => 'This is a valid request'];
                }
            } else {
                return ['status' => 'fails', 'message' => 'This is an invalid request'];
            }
            
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function checkUpdate($order_number, $serial_key, $domain, $faveo_name, $faveo_version) {
        try {
            if ($order_number && $domain && $serial_key) {
                $order = $this->verifyOrder($order_number, $serial_key, $domain);
                //var_dump($order);
                if ($order) {
                    return $this->checkFaveoDetails($order_number, $faveo_name, $faveo_version);
                }
            } else {
                return ['status' => 'fails', 'message' => 'This is an invalid request'];
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function checkFaveoDetails($order_number, $faveo_name, $faveo_version) {
        try {
            $order = new Order();
            $product = new Product();
            $this_order = $order->where('number', $order_number)->first();
            if ($this_order) {
                $product_id = $this_order->product;
                if($product_id){
                    $this_product = $product->where('id',$product_id)->first();
                    if($this_product){
                        $version = str_replace('v', '', $this_product->version);
                        return ['status' => 'success', 'message' => 'This is a valid request','version'=>$version];
                    }
                }
            }
            return ['status' => 'fails', 'message' => 'This is an invalid request'];
            
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

}
