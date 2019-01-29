<?php

namespace App\Traits\Order;

use App\Http\Controllers\License\LicensePermissionsController;
use App\Model\Common\StatusSetting;
use App\Model\Order\Order;
use App\Model\Product\Subscription;
use Bugsnag;
use Carbon\Carbon;
use Illuminate\Http\Request;

////////////////////////////////////////////////////////////////////////////
////////////// TRAIT FOR UPDATING DATES FOR ORDER/INVOICE //////////////////
////////////////////////////////////////////////////////////////////////////

trait UpdateDates
{
    /*
    Edit Updates Expiry Date In aDmin panel
     */
    public function editUpdateExpiry(Request $request)
    {
        $this->validate($request, [
         'date' => 'required',
        ]);

        try {
            $productId = Subscription::where('order_id', $request->input('orderid'))->pluck('product_id')->first();
            $licenseSupportExpiry = Subscription::where('order_id', $request->input('orderid'))
            ->select('ends_at', 'support_ends_at')->first();
            $permissions = LicensePermissionsController::getPermissionsForProduct($productId);
            if ($permissions['generateUpdatesxpiryDate'] == 1) {
                $newDate = $request->input('date');
                $date = \DateTime::createFromFormat('d/m/Y', $newDate);
                $date = $date->format('Y-m-d H:i:s');
                Subscription::where('order_id', $request->input('orderid'))->update(['update_ends_at'=>$date]);
                $checkUpdateStatus = StatusSetting::first()->pluck('license_status')->first();
                if ($checkUpdateStatus == 1) {
                    $this->editUpdateDateInAPL($request->input('orderid'), $date, $licenseSupportExpiry);
                }
            }

            return ['message'=>'success', 'update'=>'Updates Expiry Date Updated Successfully'];
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex->getMessage());
            $result = [$ex->getMessage()];

            return response()->json(compact('result'), 500);
        }
    }

    //Update Updates Expry in Licensing
    public function editUpdateDateInAPL($orderId, $expiryDate, $licenseSupportExpiry)
    {
        $order = Order::find($orderId);
        $licenseExpiry = $licenseSupportExpiry->ends_at ? Carbon::parse($licenseSupportExpiry->ends_at)->format('Y-m-d') : '';
        $supportExpiry = $licenseSupportExpiry->support_ends_at ? Carbon::parse($licenseSupportExpiry->support_ends_at)->format('Y-m-d') : '';
        $expiryDate = $expiryDate ? Carbon::parse($expiryDate)->format('Y-m-d') : '';
        $cont = new \App\Http\Controllers\License\LicenseController();
        $updateLicensedDomain = $cont->updateExpirationDate($order->serial_key, $expiryDate, $order->product, $order->domain, $order->number, $licenseExpiry, $supportExpiry);
    }

    /*
    Edit License Expiry Date In aDmin panel
     */
    public function editLicenseExpiry(Request $request)
    {
        $this->validate($request, [
         'date' => 'required',
        ]);

        try {
            $productId = Subscription::where('order_id', $request->input('orderid'))->pluck('product_id')->first();
            $updatesSupportExpiry = Subscription::where('order_id', $request->input('orderid'))
            ->select('update_ends_at', 'support_ends_at')->first();
            $permissions = LicensePermissionsController::getPermissionsForProduct($productId);
            if ($permissions['generateLicenseExpiryDate'] == 1) {
                $newDate = $request->input('date');
                $date = \DateTime::createFromFormat('d/m/Y', $newDate);
                $date = $date->format('Y-m-d H:i:s');
                Subscription::where('order_id', $request->input('orderid'))->update(['ends_at'=>$date]);
                $checkUpdateStatus = StatusSetting::first()->pluck('license_status')->first();
                if ($checkUpdateStatus == 1) {
                    $this->editLicenseDateInAPL($request->input('orderid'), $date, $updatesSupportExpiry);
                }
            }

            return ['message'=>'success', 'update'=>'License Expiry Date Updated Successfully'];
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex->getMessage());
            $result = [$ex->getMessage()];

            return response()->json(compact('result'), 500);
        }
    }

    //Update License Expiry in Licensing
    public function editLicenseDateInAPL($orderId, $date, $updatesSupportExpiry)
    {
        $order = Order::find($orderId);
        $expiryDate = Carbon::parse($updatesSupportExpiry->update_ends_at)->format('Y-m-d');
        $supportExpiry = Carbon::parse($updatesSupportExpiry->support_ends_at)->format('Y-m-d');
        $licenseExpiry = Carbon::parse($date)->format('Y-m-d');
        $cont = new \App\Http\Controllers\License\LicenseController();
        $updateLicensedDomain = $cont->updateExpirationDate($order->serial_key, $expiryDate, $order->product, $order->domain, $order->number, $licenseExpiry, $supportExpiry);
    }

    /*
    Edit Support Expiry Date In aDmin panel
     */
    public function editSupportExpiry(Request $request)
    {
        $this->validate($request, [
         'date' => 'required',
        ]);

        try {
            $productId = Subscription::where('order_id', $request->input('orderid'))->pluck('product_id')->first();
            $updatesLicenseExpiry = Subscription::where('order_id', $request->input('orderid'))
            ->select('update_ends_at', 'ends_at')->first();
            $permissions = LicensePermissionsController::getPermissionsForProduct($productId);
            if ($permissions['generateSupportExpiryDate'] == 1) {
                $newDate = $request->input('date');
                $date = \DateTime::createFromFormat('d/m/Y', $newDate);
                $date = $date->format('Y-m-d H:i:s');
                Subscription::where('order_id', $request->input('orderid'))->update(['support_ends_at'=>$date]);
                $checkUpdateStatus = StatusSetting::first()->pluck('license_status')->first();
                if ($checkUpdateStatus == 1) {
                    $this->editSupportDateInAPL($request->input('orderid'), $date, $updatesLicenseExpiry);
                }
            }

            return ['message'=>'success', 'update'=>'Support Expiry Date Updated Successfully'];
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex->getMessage());
            $result = [$ex->getMessage()];

            return response()->json(compact('result'), 500);
        }
    }

    //Update Support Expiry in Licensing
    public function editSupportDateInAPL($orderId, $date, $updatesLicenseExpiry)
    {
        $order = Order::find($orderId);
        $expiryDate = Carbon::parse($updatesLicenseExpiry->update_ends_at)->format('Y-m-d');
        $licenseExpiry = Carbon::parse($updatesLicenseExpiry->ends_at)->format('Y-m-d');
        $supportExpiry = Carbon::parse($date)->format('Y-m-d');
        $cont = new \App\Http\Controllers\License\LicenseController();
        $updateLicensedDomain = $cont->updateExpirationDate($order->serial_key, $expiryDate, $order->product, $order->domain, $order->number, $licenseExpiry, $supportExpiry);
    }
}
