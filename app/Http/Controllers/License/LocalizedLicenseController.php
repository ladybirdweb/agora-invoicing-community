<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;

class LocalizedLicenseController extends Controller
{
     public function downloadFile(Request $request)
    {
          $orderNo=$request->get('orderNo');
          $fileName = "faveo-license-{".$orderNo."}.txt";
          $file = Storage::disk('public')->get($fileName);
          return (new Response($file,200))
            ->header('Content-Type', 'text/html');
    }
   

    public function choose(Request $request)
    {
        $chose = $request->get('choose');
        if ($chose == 'FILE')
        {
            $this->storeFile();
        }
        else{         
             exit();
        }
    }   

    public function storeFile(Request $request)
    {  
        $licenseExpiry = $request->get('expiry');
        $updatesExpiry = $request->get('update');
        $supportExpiry = $request ->get('support_expiry');
        $orderNo = $request->get('orderNo');
        $domain = $request->get('domain');     
        $userData =  "<root_url>".$domain."</root_url><license_expiry>".$licenseExpiry."</license_expiry><updates_expiry>".$updatesExpiry."</updates_expiry><support_expiry>".$supportExpiry."</support_expiry>";
        $encrypt = new EncryptDecryptController();
        $encryptData = $encrypt->encrypt($userData);
        $fileName ='faveo-license-{'.$orderNo.'}.txt';
        //$file=Storage::disk('public')->path($fileName);
        Storage::disk('public')->put($fileName,$encryptData);      
    }

    public function tempOrderLink(Request $request)
    {

     $orderNo=$request->get('orderNo');
     $url=URL::temporarySignedRoute('event.rsvp', now()->addSeconds(30), [
             'orderNo'=>$orderNo,            
           ]);
     return $url;
    }

    public function showAllFiles()
    {
   
       $filesArray=Storage::disk('public')->files();
       dd(Storage::allFiles('public'),$filesArray);
       return $filesArray;
    }

  public function fileEdit(Request $request)
  {
    $orderNo = $request->get('orderNo');
    $fileName = "faveo-license-{".$orderNo."}.txt";
    extract($this->getLicenseData($fileName));

    if(!is_null($request->get('root_url')))
    {
      $root_url = $request->get('root_url');
    }
    if(!is_null($request->get('license_expiry')))
    {
      $license_expiry = $request->get('license_expiry');
    }
    if(!is_null($request->get('updates_expiry')))
    {
      $updates_expiry = $request->get('updates_expiry');
    }
    if(!is_null($request->get('support_expiry')))
    {
      $support_expiry = $request->get('support_expiry');
    }

    $stored=Storage::disk('public')->path($fileName);
    $handle=@fopen($stored, "w+");
     $fwrite=@fwrite($handle,"<root_url>$root_url</root_url><license_expiry>$license_expiry</license_expiry><updates_expiry>$updates_expiry</updates_expiry><support_expiry>$support_expiry</support_expiry>");
        if ($fwrite===false) //updating file failed
         {
          echo "Update was not performed";
          exit();
          }
        @fclose($handle);
  
  }

  public function deleteFile(Request $request)
  {
    $orderNo=$request->get('orderNo');
    $fileName = "faveo-license-{".$orderNo."}.txt";
    Storage::disk('public')->delete($fileName);
  }


//return an array with license data
private function getLicenseData($fileName)
    {
    $settings_row=array();
    $settings_row=$this->parseLicenseFile($fileName);
    return $settings_row;
    }

//parse license file and make an array with license data
private function parseLicenseFile($fileName)
    {
    $license_data_array=array();
    $stored = Storage::disk('public')->path($fileName);
    if (@is_readable($stored))
        {
        $file_content=file_get_contents($stored);
        preg_match_all("/<([a-z_]+)>(.*?)<\/([a-z_]+)>/", $file_content, $matches, PREG_SET_ORDER);
        if (!empty($matches))
            {
            foreach ($matches as $value)
                {
                if (!empty($value[1]) && $value[1]==$value[3])
                    {
                    $license_data_array[$value[1]]=$value[2];
                    }
                }
            }
        }
    return $license_data_array;
    }

}
