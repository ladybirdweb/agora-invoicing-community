<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use Illuminate\Http\Request;

class EncryptDecryptController extends Controller
{
    
    public $pubkey = '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA52mwBXB/1pjK7sEcLfo8
VS1Pin8htxGQM3ReMvDwWAEGPLWELQ2HkuI/jcs8oVkwRenFEvcTG3+NYPKjCdlr
iX/IW5fFknFFwM/5xZJHzoTHiQzs2ExAJ+18Rr3rUNDD2Y98GOY03ahBszKRERZP
TBnbVsYirxdI7gsUgEtOQ1F6b0Yf6T2sjanKhxrk0RhOEh5yL1K4K32FMt0KrDNC
nMLaLWWdoRlkxyIxpaHXI+Uc0Ah22W0RAFsC26paWgf0p2VMebKPbIkIrVE4Ul2e
+GrzizXMiDfcp4p1ztdG0Iq5AUtif9fZyMDft7IYcrzj7OcOJIPrsgO6X9/14ZSx
tFZQ6xCrYCKgqfP1z6taqLle9toIdSNIoH1B0hn4MSo2Ag/jDYvmqXIg0sTpYqkz
zjnjlyCv0dfe41RtVAHTdGk0J9jfvxZTz244vyvcpXtqv2Gt8HkrqZ0HIWXFtU1A
5TQq4NfyqtpGJ99k2w+5/t9dTVmDqzlAyJo4iOiiM2okk8dhz5HL4soltzuZheOd
Dgwx6reQ5/C833BRAJr1I6y9eKMS48AMOmcLnpI3MccPBxuBUeGHbNpL8jPSit/w
l2BoyojFSvhO6rZL8ODtU1ClcOvtYrjuXUcPNvzf7XnmG62bgzXKw0jsv/rb4/kw
d3bmpAONQ0Rq0BETHBWENvMCAwEAAQ==
-----END PUBLIC KEY-----';
    public $privkey ='-----BEGIN PRIVATE KEY-----
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
BnxxCqPONCnLTGZQ/mYwWPHRrDwxjII=
-----END PRIVATE KEY-----';


    public function encrypt($data)
    {
        
        //$data = $request->get('data');
        if (openssl_public_encrypt($data, $encrypted,$this->pubkey,OPENSSL_PKCS1_PADDING)){
            $data = base64_encode($encrypted);          
        }
        else{
            throw new Exception('Unable to encrypt data. Perhaps it is bigger than the key size?');
        }
        return $data;
    }


    public function decrypt(Request $request)
    {
        $data = $request->get('data');
        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey,OPENSSL_PKCS1_PADDING)){
            $data = $decrypted;      
        }
        else{
            $data = '';
        }
        return response(['message'=>'Decrypted','data'=>$data]);
    }


   public function generate()
   {
     $config = array(  
     "digest_alg" => "sha512",  
     "private_key_bits" => 4096,  
     "private_key_type" => OPENSSL_KEYTYPE_RSA,  
     ); 
     // Create the keypair  
     $pair=openssl_pkey_new($config); 
     // Get private key  
     openssl_pkey_export($pair, $privatekey);  
     // Get public key  
     $publickey=openssl_pkey_get_details($pair)["key"];  

     echo "====PKCS1 RSA Key in Non Encrypted Format ====\n";  
     var_dump($privatekey);  
     echo "====PKCS1 RSA Key in Encrypted Format====\n ";  

     // Get private key in Encrypted Format  
     openssl_pkey_export($pair, $privatekey,"myverystrongpassword" );  
     // Get public key  
     $publickey=openssl_pkey_get_details($pair);  
     $publickey=$publickey["key"];  
     var_dump($privatekey);  
     echo "RSA Public Key \n ";  
     var_dump($publickey);
   }
}
