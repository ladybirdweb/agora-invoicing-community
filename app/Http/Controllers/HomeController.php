<?php

namespace App\Http\Controllers;

use App\Model\Order\Order;
use App\Model\Product\Product;
use Exception;
use Illuminate\Http\Request;

class HomeController extends BaseHomeController
{
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
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['index']]);
        $this->middleware('admin', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return \Response
     */
    public function version(Request $request, Product $product)
    {
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
            $version = 'Not-Available';
        }

        echo "<form action=$url method=post name=redirect >";
        echo '<input type=hidden name=_token value='.csrf_token().'>';
        echo "<input type=hidden name=value value=$version />";
        echo '</form>';
        echo"<script language='javascript'>document.redirect.submit();</script>";
    }

    public function getVersion(Request $request, Product $product)
    {
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

    public function serialV2(Request $request, Order $order)
    {
        try {
            $faveo_encrypted_order_number = self::decryptByFaveoPrivateKey($request->input('order_number'));
            $faveo_encrypted_key = self::decryptByFaveoPrivateKey($request->input('serial_key'));
            \Log::emergency(json_encode(['domain' => $request
                ->input('domain'), 'enc_serial' => $faveo_encrypted_key,
                'enc_order' => $faveo_encrypted_order_number, ]));
            $request_type = $request->input('request_type');
            $faveo_name = $request->input('name');
            $faveo_version = $request->input('version');
            $order_number = $this->checkOrder($faveo_encrypted_order_number);
            $domain = $request->input('domain');
            $domain = $this->checkDomain($domain);
            $serial_key = $this->checkSerialKey($faveo_encrypted_key, $order_number);

            \Log::emergency(json_encode(['domain' => $request->input('domain'),
             'serial'                             => $serial_key, 'order' => $order_number, ]));
            $result = [];
            if ($request_type == 'install') {
                $result = $this->verificationResult($order_number, $serial_key);
            }
            if ($request_type == 'check_update') {
                $result = $this->checkUpdate($order_number, $serial_key, $domain, $faveo_name, $faveo_version);
            }
            $result = self::encryptByPublicKey(json_encode($result));

            return $result;
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->getMessage()];
            $result = self::encryptByPublicKey(json_encode($result));

            return $result;
        }
    }

    public function serial(Request $request, Order $order)
    {
        try {
            $url = $request->input('url');
            $faveo_encrypted_order_number = self::decryptByFaveoPrivateKey($request->input('order_number'));
            $domain = $this->getDomain($request->input('domain'));

            //return $domain;
            $faveo_encrypted_key = self::decryptByFaveoPrivateKey($request->input('serial_key'));
            $request_type = $request->input('request_type');
            $faveo_name = $request->input('name');
            $faveo_version = $request->input('version');
            $order_number = $this->checkOrder($faveo_encrypted_order_number);

            $domain = $this->checkDomain($domain);
            $serial_key = $this->checkSerialKey($faveo_encrypted_key, $order_number);
            //dd($serial_key);
            //return $serial_key;
            $result = [];
            if ($request_type == 'install') {
                $result = $this->verificationResult($order_number, $serial_key);
            }
            if ($request_type == 'check_update') {
                $result = $this->checkUpdate($order_number, $serial_key, $domain, $faveo_name, $faveo_version);
            }
            $result = self::encryptByPublicKey(json_encode($result));
            $this->submit($result, $url);
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->getMessage()];
            $result = self::encryptByPublicKey(json_encode($result));
            $this->submit($result, $url);
        }
    }

    public static function decryptByFaveoPrivateKeyold($encrypted)
    {
        try {
            // Get the private Key
            $path = storage_path('app'.DIRECTORY_SEPARATOR.'private.key');
            $key_content = file_get_contents($path);
            if (!$privateKey = openssl_pkey_get_private($key_content)) {
                dd('Private Key failed');
            }
            $a_key = openssl_pkey_get_details($privateKey);

            // Decrypt the data in the small chunks
            $chunkSize = ceil($a_key['bits'] / 8);
            $output = '';

            while ("¥IM‰``ì‡Á›LVP›†>¯öóŽÌ3(¢z#¿î1¾­:±Zï©PqÊ´Â›7×:Fà¯¦   à•…Ä'öESW±ÉŸLÃvÈñÔs•ÍU)ÍL 8¬š‰A©·Å $}Œ•lA9™¡”¸èÅØv‘ÂOÈ6„_y5¤ì§—ÿíà(ow‰È&’v&T/FLƒigjÒZ eæaa”{©ªUBFÓ’Ga*ÀŒ×?£}-jÏùh¾Q/Ž“1YFq[Í‰¬òÚ‚œ½Éº5ah¶vZ#,ó@‚rOÆ±íVåèÜÖšU¦ÚmSÎ“Mý„ùP") {
                $chunk = substr($encrypted, 0, $chunkSize);
                $encrypted = substr($encrypted, $chunkSize);
                $decrypted = '';
                if (!openssl_private_decrypt($chunk, $decrypted, $privateKey)) {
                    dd('Failed to decrypt data');
                }
                $output .= $decrypted;
            }
            openssl_free_key($privateKey);

            // Uncompress the unencrypted data.
            $output = gzuncompress($output);
            dd($output);
            echo '<br /><br /> Unencrypted Data: '.$output;
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    public function createEncryptionKeys()
    {
        try {
            $privateKey = openssl_pkey_new([
                'private_key_bits' => 2048, // Size of Key.
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ]);
            //dd($privateKey);
            // Save the private key to private.key file. Never share this file with anyone.
            openssl_pkey_export_to_file($privateKey, 'private.key');

            // Generate the public key for the private key
            $a_key = openssl_pkey_get_details($privateKey);
            //dd($a_key);
            // Save the public key in public.key file. Send this file to anyone who want to send you the encrypted data.
            file_put_contents('public.key', $a_key['key']);

            // Free the private Key.
            openssl_free_key($privateKey);
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function checkOrder($faveo_decrypted_order)
    {
        try {
            $order = new Order();
//            $faveo_decrypted_order = self::decryptByFaveoPrivateKey($faveo_encrypted_order_number);

            $this_order = $order->where('number', 'LIKE', $faveo_decrypted_order)->first();
            if (!$this_order) {
                return;
            } else {
                return $this_order->number;
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function faveoVerification(Request $request)
    {
        try {
            $data = $request->input('data');
            $json = self::decryptByFaveoPrivateKey($data);
            $data = json_decode($json);
            //return $data->url;

            $domain = $data->url;

            $faveo_encrypted_order_number = $data->order_number;

            //$domain = $data->domain;

            $faveo_encrypted_key = $data->serial_key;

            $request_type = $data->request_type;

            $faveo_name = $data->name;

            $faveo_version = $data->version;

            $order_number = $this->checkOrder($faveo_encrypted_order_number);

            $domain = $this->checkDomain($domain);

            $serial_key = $this->checkSerialKey($faveo_encrypted_key, $order_number);
            //dd($serial_key);
            //return $serial_key;
            $result = [];
            if ($request_type == 'install') {
                $result = $this->verificationResult($order_number, $serial_key, $domain);
            }
            if ($request_type == 'check_update') {
                $result = $this->checkUpdate($order_number, $serial_key, $domain, $faveo_name, $faveo_version);
            }
            $result = self::encryptByPublicKey(json_encode($result));

            return $result;
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->getMessage().'  
            file=> '.$ex->getFile().' Line=>'.$ex->getLine()];
            $result = self::encryptByPublicKey(json_encode($result));

            return $result;
        }
    }

    public function submit($result, $url)
    {
        echo "<form action=$url method=post name=redirect>";
        echo '<input type=hidden name=_token value=csrf_token()/>';
        echo '<input type=hidden name=result value='.$result.'/>';
        echo '</form>';
        echo"<script language='javascript'>document.redirect.submit();</script>";
    }

    public function checkUpdate($order_number, $serial_key, $domain, $faveo_name, $faveo_version)
    {
        try {
            if ($order_number && $domain && $serial_key) {
                $order = $this->verifyOrder($order_number, $serial_key, $domain);
                //var_dump($order);
                if ($order) {
                    return $this->checkFaveoDetails($order_number, $faveo_name, $faveo_version);
                } else {
                    return ['status' => 'fails', 'message' => 'this-is-an-invalid-request'];
                }
            } else {
                return ['status' => 'fails', 'message' => 'this-is-an-invalid-request'];
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function checkFaveoDetails($order_number, $faveo_name, $faveo_version)
    {
        try {
            $order = new Order();
            $product = new Product();
            $this_order = $order->where('number', $order_number)->first();
            if ($this_order) {
                $product_id = $this_order->product;
                $this_product = $product->where('id', $product_id)->first();
                if ($this_product) {
                    $version = str_replace('v', '', $this_product->version);

                    return ['status' => 'success', 'message' => 'this-is-a-valid-request', 'version' => $version];
                }
            }

            return ['status' => 'fails', 'message' => 'this-is-an-invalid-request'];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public static function encryptByPublicKey($data)
    {
        $path = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public.key';
        //dd($path);
        $key_content = file_get_contents($path);
        $public_key = openssl_get_publickey($key_content);

        $encrypted = $e = null;
        openssl_seal($data, $encrypted, $e, [$public_key]);

        $sealed_data = base64_encode($encrypted);
        $envelope = base64_encode($e[0]);

        $result = ['seal' => $sealed_data, 'envelope' => $envelope];

        return json_encode($result);
    }

    public function downloadForFaveo(Request $request, Order $order)
    {
        try {
            $faveo_encrypted_order_number = $request->input('order_number');
            $faveo_serial_key = $request->input('serial_key');
            $orderSerialKey = $order->where('number', $faveo_encrypted_order_number)
                    ->value('serial_key');

            $this_order = $order
                     ->where('number', $faveo_encrypted_order_number)
                    ->first();
            if ($this_order && $orderSerialKey == $faveo_serial_key) {
                $product_id = $this_order->product;
                $product_controller = new \App\Http\Controllers\Product\ProductController();

                return $product_controller->adminDownload($product_id, true);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'line' => $e->getFile()], 500);
        }
    }

    public function latestVersion(Request $request, Product $product)
    {
        $v = \Validator::make($request->all(), [
                    'title' => 'required',
        ]);
        if ($v->fails()) {
            $error = $v->errors();

            return response()->json(compact('error'));
        }

        try {
            $title = $request->input('title');
            $product = $product->where('name', $title)->first();
            if ($product) {
                $message = ['version' => str_replace('v', '', $product->version)];
            } else {
                $message = ['error' => 'product_not_found'];
            }
            $message = ['version' => str_replace('v', '', $product->version)];
        } catch (\Exception $e) {
            $message = ['error' => $e->getMessage()];
        }

        return response()->json($message);
    }

    /*
     * Check if the Product is valid For Auto Updates
    * @params string Serial Key in encrypted
    * @return array
    */
}
