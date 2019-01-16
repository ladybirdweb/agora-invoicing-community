<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Common\BaseSettingsController;
use App\Http\Controllers\Controller;
use App\Model\Common\StatusSetting;
use App\Model\Order\Order;
use App\Model\Product\Subscription;
use Bugsnag;
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
     * generate serial key and add no of agents in the last 4 digits og the 16 string/digit serial key .
     *
     * @param int $productid
     *
     * @throws \Exception
     *
     * @return string   The Final Serial Key after adding no of agents in the last 4 digits
     */
    public function generateSerialKey(int $productid)
    {
        try {
            $contents = \Cart::getContent();//get All the content from the cart for gretting no of agents;
            foreach ($contents as $content) {
                if($content->id == $productid) {
                   $noOfAgents = $content->attributes->agents; //Get No of Agents for the Product
                }
            }
            $a = strlen($noOfAgents);
            switch ($a) {//Get Last Four digits based on No.Of Agents
                case '1':
                   $lastFour = '000'.$noOfAgents;
                    break;
                   case '2': 
                    $lastFour = '00'.$noOfAgents;
                     break;
                      case '3': 
                    $lastFour = '0'.$noOfAgents;
                     break;
                      case '4': 
                    $lastFour = $noOfAgents;
                     break;
                default:
                    $lastFour = '0000';
                    break;
            }
            $str = strtoupper(str_random(12));
            $licCode = $str.$lastFour;
            return $licCode;
        } catch (\Exception $ex) {
              app('log')->error($ex->getMessage());
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
        $licenseCode = $order->serial_key;
        $order->domain = implode(',', $allowedDomains);
        $order->save();
        $licenseStatus = StatusSetting::pluck('license_status')->first();
        if ($licenseStatus == 1) {
            $expiryDate = $order->subscription->ends_at;
            $cont = new \App\Http\Controllers\License\LicenseController();
            $updateLicensedDomain = $cont->updateLicensedDomain($licenseCode, $order->domain, $order->product, $expiryDate, $order->number);
            //Now make Installation status as inactive
            $updateInstallStatus = $cont->updateInstalledDomain($licenseCode, $order->product);
        }

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
