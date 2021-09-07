<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocalizedLicenseRequest;
use App\Model\Order\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class LocalizedLicenseController extends Controller
{
    /**
     * Downloads the license file.
     * */
    public function downloadFile(Request $request)
    {
        $orderNo = $request->get('orderNo');
        $fileName = 'faveo-license-{'.$orderNo.'}.txt';
        $filePath = storage_path('app/public/'.$fileName);

        return response()->download($filePath);
    }

    /**
     * Downloads the license file through admin.
     * */
    public function downloadFileAdmin($fileName)
    {
        $filePath = storage_path('app/public/'.$fileName);

        return response()->download($filePath);
    }

    /**
     * Downloads the private key for the license.
     * */
    public function downloadPrivate($orderNo)
    {
        $fileName = storage_path('app/public/privateKey-'.$orderNo.'.txt');

        return response()->download($fileName);
    }

    /**
     * Downloads the private key for the license through admin panel.
     * */
    public function downloadPrivateKeyAdmin($fileName)
    {
        $value = explode('}', $fileName);
        $orderNo = substr($value[0], 15);
        $fileName = storage_path('app/public/privateKey-'.$orderNo.'.txt');

        return response()->download($fileName);
    }

    /**
     * Chooses which license mode is applicable File/Database.
     * */
    public function choose(Request $request)
    {
        $chose = $request->input('choose');
        $orderNo = $request->input('orderNo');
        if ($chose == 1) {
            $encrypt = new EncryptDecryptController();
            $encrypt->generateKeys($orderNo);
            Order::where('number', $orderNo)->update(['license_mode' => 'File']);

            return response()->json(['success'=>'Status change successfully.']); //return redirect()->back()->with('success', Lang::get('Private and Public Keys generated for this order number: '.$orderNo));
        } else {
            Order::where('number', $orderNo)->update(['license_mode' => 'Database']);
            Storage::disk('public')->delete('publicKey-'.$orderNo.'.txt');
            Storage::disk('public')->delete('privateKey-'.$orderNo.'.txt');
            Storage::disk('public')->delete('faveo-license-{'.$orderNo.'}.txt');

            return response()->json(['success'=>'Status change successfully.']); //return redirect()->back()->with('success',Lang::get('Reverted back to database license mode' .$orderNo));
        }
    }

    /**
     * Stores the license file after the client has entered a domain and downloads the license.
     * */
    public function storeFile(LocalizedLicenseRequest $request)
    {
        $licenseVal = strtotime($request->input('expiry')) > 1 ? $request->input('expiry') : '--';
        $updateVal = strtotime($request->input('updates')) > 1 ? $request->input('updates') : '--';
        $supportVal = strtotime($request->input('support_expiry')) > 1 ? $request->input('support_expiry') : '--';

        if ($licenseVal == '--') {
            $licenseExpiry = $licenseVal;
        } else {
            $licenseExpiry = Carbon::parse($licenseVal)->format('Y-m-d');
        }
        if ($updateVal == '--') {
            $updatesExpiry = $updateVal;
        } else {
            $updatesExpiry = Carbon::parse($updateVal)->format('Y-m-d');
        }
        if ($supportVal == '--') {
            $supportExpiry = $supportVal;
        } else {
            $supportExpiry = Carbon::parse($supportVal)->format('Y-m-d');
        }

        $domain = $request->input('domain');
        $licenseCode = $request->input('code');
        $orderNo = $request->input('orderNo');

        $userData = '<root_url>'.$domain.'</root_url><license_code>'.$licenseCode.'</license_code><license_expiry>'.$licenseExpiry.'</license_expiry><updates_expiry>'.$updatesExpiry.'</updates_expiry><support_expiry>'.$supportExpiry.'</support_expiry>';

        $encrypt = new EncryptDecryptController();
        $encryptData = $encrypt->encrypt($userData, $orderNo);

        $fileName = 'faveo-license-{'.$orderNo.'}.txt';
        Storage::disk('public')->put($fileName, $encryptData);

        $link = $this->tempOrderLink($orderNo);

        return Redirect::to($link);
    }

    /**
     * Generates a temporary link to download the license file with a time constraint.
     * */
    public function tempOrderLink($orderNo)
    {
        $url = URL::temporarySignedRoute('event.rsvp', now()->addSeconds(30), [
            'orderNo'=>$orderNo,
        ]);

        return $url;
    }

    /**
     * Edits the license details without showing the pre-existing license data.
     * */
    /*public function fileEdit(Request $request,$fileName)
    {
      $value = explode("}",$fileName);
      $orderNo = substr($value[0], 15);
      $fileName = "faveo-license-{".$orderNo."}.txt";
      dd($orderNo,$fileName);
      extract($this->getLicenseData($fileName,$orderNo));

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
       $fwrite=@fwrite($handle,"<root_url>$root_url</root_url><license_code>$license_code</license_code><license_expiry>$license_expiry</license_expiry><updates_expiry>$updates_expiry</updates_expiry><support_expiry>$support_expiry</support_expiry>");
          if ($fwrite===false) //updating file failed
           {
            echo "Update was not performed";
            exit();
            }
       $encrypt = new EncryptDecryptController();
       $data=$encrypt->encrypt($fileName,$orderNo);
       Storage::disk('public')->put($fileName,$data);
       @fclose($handle);
       return redirect()->back()->with('success', Lang::get('License data is updated'.$orderNo));
    }*/

    /**
     * Deletes the license file.
     * */
    public function deleteFile($fileName)
    {
        Storage::disk('public')->delete($fileName);

        return redirect()->back()->with('success', Lang::get('License File is deleted '.$fileName));
    }

    //return an array with license data
    private function getLicenseData($fileName, $orderNo)
    {
        $settings_row = [];
        $settings_row = $this->parseLicenseFile($fileName, $orderNo);

        return $settings_row;
    }

    //parse license file and make an array with license data
    private function parseLicenseFile($fileName, $orderNo)
    {
        $license_data_array = [];
        $stored = Storage::disk('public')->path($fileName);
        if (@is_readable($stored)) {
            $decrypt = new EncryptDecryptController();
            $contents = $decrypt->decrypt($orderNo);
            Storage::disk('public')->put($fileName, $contents);
            $stored = Storage::disk('public')->path($fileName);
            $file_content = file_get_contents($stored);
            preg_match_all("/<([a-z_]+)>(.*?)<\/([a-z_]+)>/", $file_content, $matches, PREG_SET_ORDER);
            if (! empty($matches)) {
                foreach ($matches as $value) {
                    if (! empty($value[1]) && $value[1] == $value[3]) {
                        $license_data_array[$value[1]] = $value[2];
                    }
                }
            }
        }

        return $license_data_array;
    }
}
