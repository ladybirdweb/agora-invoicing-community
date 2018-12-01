<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Common\BaseSettingsController;
use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Product\Subscription;
use Bugsnag;
use Crypt;
use Illuminate\Http\Request;

class ExtendedOrderController extends Controller
{
    public function advanceSearch($order_no = '', $product_id = '', $expiry = '',
        $expiryTill = '', $from = '', $till = '', $domain = '')
    {
        try {
            $join = Order::leftJoin('subscriptions', 'orders.id', '=', 'subscriptions.order_id');
            if ($order_no) {
                $join = $join->where('number', $order_no);
            }
            if ($product_id) {
                $join = $join->where('product', $product_id);
            }
            if ($expiry) {
                $expiryFrom = (new BaseSettingsController())->getDateFormat($expiry);
                $tills = (new BaseSettingsController())->getDateFormat();

                $tillDate = $this->getTillDate($expiryFrom, $expiryTill, $tills);
                $join = $join->whereBetween('subscriptions.ends_at', [$expiryFrom, $tillDate]);
            }

            if ($expiryTill) {
                $exptill = (new BaseSettingsController())->getDateFormat($expiryTill);
                $froms = Subscription::first()->ends_at;
                $fromDate = $this->getFromDate($expiry, $froms);
                $join = $join->whereBetween('subscriptions.ends_at', [$fromDate, $exptill]);
            }
            if ($from) {
                $fromdate = date_create($from);

                $from = date_format($fromdate, 'Y-m-d H:m:i');
                $tills = date('Y-m-d H:m:i');

                $tillDate = $this->getTillDate($from, $till, $tills);
                $join = $join->whereBetween('orders.created_at', [$from, $tillDate]);
            }
            if ($till) {
                $tilldate = date_create($till);
                $till = date_format($tilldate, 'Y-m-d H:m:i');
                $froms = Order::first()->created_at;
                $fromDate = $this->getFromDate($from, $froms);
                $join = $join->whereBetween('orders.created_at', [$fromDate, $till]);
            }
            if ($domain) {
                if (str_finish($domain, '/')) {
                    $domain = substr_replace($domain, '', -1, 0);
                }
                $join = $join->where('domain', 'LIKE', '%'.$domain.'%');
            }
            // dd($join->get());
            $join = $join->orderBy('created_at', 'desc')
        ->select('orders.id', 'orders.created_at', 'client',
            'price_override', 'order_status', 'product', 'number', 'serial_key');

            return $join;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function getTillDate($from, $till, $tills)
    {
        if ($till) {
            $todate = date_create($till);
            $tills = date_format($todate, 'Y-m-d H:m:i');
        }

        return $tills;
    }

    public function getFromDate($from, $froms)
    {
        if ($from) {
            $fromdate = date_create($from);
            $froms = date_format($fromdate, 'Y-m-d H:m:i');
        }

        return $froms;
    }

    /**
     * Create orders.
     *
     * @param Request $request
     *
     * @return type
     */
    public function orderExecute(Request $request)
    {
        try {
            $invoiceid = $request->input('invoiceid');
            $execute = $this->executeOrder($invoiceid);
            if ($execute == 'success') {
                return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
            } else {
                return redirect()->back()->with('fails', \Lang::get('message.not-saved-successfully'));
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * generating serial key if product type is downloadable.
     *
     * @param type $product_type
     *
     * @throws \Exception
     *
     * @return type
     */
    public function generateSerialKey($product_type)
    {
        try {
            // if ($product_type == 2) {
            $str = str_random(16);
            $str = strtoupper($str);
            $str = Crypt::encrypt($str);

            return $str;
            // }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function generateNumber()
    {
        try {
            return rand('10000000', '99999999');
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function changeDomain(Request $request)
    {
        $domain = '';
        $arrayOfDomains = [];
        $allDomains = $request->input('domain');
        $seperateDomains = explode(',', $allDomains); //Bifurcate the domains here

        $allowedDomains = $this->getAllowedDomains($seperateDomains);
        $id = $request->input('id');
        $order = Order::findorFail($id);
        $clientEmail = $order->user->email;
        $order->domain = implode(',', $allowedDomains);
        $order->save();
        $cont = new \App\Http\Controllers\License\LicenseController();
        $updateLicensedDomain = $cont->updateLicensedDomain($clientEmail, $order->domain);
        //Now make Installation status as inactive
        $updateInstallStatus = $cont->updateInstalledDomain($clientEmail);

        return ['message' => 'success', 'update'=>'Licensed Domain Updated'];
    }

    public function getAllowedDomains($seperateDomains)
    {
        $needle = 'www';
        foreach ($seperateDomains as $domain) {
            $isIP = (bool) ip2long($domain);
            if ($isIP == true) {
                $allowedDomains[] = $domain;
            } else {
                $customDomain = (strpos($domain, $needle) !== false) ? str_replace('www.', '', $domain) : 'www.'.$domain;
                $allowedDomains[] = ($domain.','.$customDomain);
            }
        }

        return  $allowedDomains;
    }
}
