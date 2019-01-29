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
    /**
     * Perform Advance Search for Orders Page.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-19T01:35:08+0530
     *
     * @param string $order_no
     * @param string $product_id
     * @param string $expiry
     * @param string $expiryTill
     * @param string $from
     * @param string $till
     * @param string $domain
     *
     * @return array
     */
    public function advanceSearch(
        $order_no = '',
        $product_id = '',
        $expiry = '',
        $expiryTill = '',
        $from = '',
        $till = '',
        $domain = ''
    ) {
        try {
            $join = Order::leftJoin('subscriptions', 'orders.id', '=', 'subscriptions.order_id');
            ($this->orderNum($order_no, $join));
            $this->product($product_id, $join);
            $this->expiry($expiryTill, $expiry, $join);
            $this->expiryTill($expiry, $expiryTill, $join);
            $this->orderFrom($till, $from, $join);
            $this->orderTill($from, $till, $join);
            $this->domain($domain, $join);

            $join = $join->orderBy('created_at', 'desc')
           ->select(
               'orders.id',
               'orders.created_at',
               'client',
            'price_override',
               'order_status',
               'product',
               'number',
               'serial_key'
           );

            return $join;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    /**
     * Searches for Order No.
     *
     * @param int             $order_no The Order NO to be searched
     * @param App\Model\Order $join     The Order instance
     *
     * @return $join
     */
    private function orderNum($order_no, $join)
    {
        if ($order_no) {
            $join = $join->where('number', $order_no);

            return $join;
        }
    }

    /**
     * Searches for Product.
     *
     * @param int             $order_no The Order NO to be searched
     * @param App\Model\Order $join     The Order instance
     *
     * @return $join
     */
    private function product($product_id, $join)
    {
        if ($product_id) {
            $join = $join->where('product', $product_id);

            return $join;
        }
    }

    /**
     * Searches for Expiry From.
     *
     * @param string $expiry The Expiry From Date
     * @param object $join
     *
     * @return Query
     */
    private function expiry($expiryTill, $expiry, $join)
    {
        if ($expiry) {
            $expiryFrom = (new BaseSettingsController())->getDateFormat($expiry);
            $tills = (new BaseSettingsController())->getDateFormat();

            $tillDate = $this->getTillDate($expiryFrom, $expiryTill, $tills);
            $join = $join->whereBetween('subscriptions.ends_at', [$expiryFrom, $tillDate]);

            return $join;
        }
    }

    /**
     * Searches for Expiry Till.
     *
     * @param string $expiry The Expiry Till Date
     * @param object $join
     *
     * @return Query
     */
    private function expiryTill($expiry, $expiryTill, $join)
    {
        if ($expiryTill) {
            $exptill = (new BaseSettingsController())->getDateFormat($expiryTill);
            $froms = Subscription::first()->ends_at;
            $fromDate = $this->getFromDate($expiry, $froms);
            $join = $join->whereBetween('subscriptions.ends_at', [$fromDate, $exptill]);

            return $join;
        }
    }

    /**
     * Searches for Order From Date.
     *
     * @param string $expiry The Order From Date
     * @param object $join
     *
     * @return Query
     */
    public function orderFrom($till, $from, $join)
    {
        if ($from) {
            $fromdate = date_create($from);

            $from = date_format($fromdate, 'Y-m-d H:m:i');
            $tills = date('Y-m-d H:m:i');

            $tillDate = $this->getTillDate($from, $till, $tills);
            $join = $join->whereBetween('orders.created_at', [$from, $tillDate]);

            return $join;
        }
    }

    /**
     * Searches for Order Till Date.
     *
     * @param string $expiry The Order Till Date
     * @param object $join
     *
     * @return Query
     */
    public function orderTill($from, $till, $join)
    {
        if ($till) {
            $tilldate = date_create($till);
            $till = date_format($tilldate, 'Y-m-d H:m:i');
            $froms = Order::first()->created_at;
            $fromDate = $this->getFromDate($from, $froms);
            $join = $join->whereBetween('orders.created_at', [$fromDate, $till]);

            return $join;
        }
    }

    /**
     * Searches for Domain.
     *
     * @param string $domain domaiin
     * @param object $join
     *
     * @return Query
     */
    public function domain($domain, $join)
    {
        if ($domain) {
            if (str_finish($domain, '/')) {
                $domain = substr_replace($domain, '', -1, 0);
            }
            $join = $join->where('domain', 'LIKE', '%'.$domain.'%');

            return $join;
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
     * @param int $agents    No Of Agents
     *
     * @throws \Exception
     *
     * @return string The Final Serial Key after adding no of agents in the last 4 digits
     */
    public function generateSerialKey(int $productid, $agents)
    {
        try {
            $len = strlen($agents);
            switch ($len) {//Get Last Four digits based on No.Of Agents

                case '1':
                   $lastFour = '000'.$agents;
                    break;
                   case '2':

                    $lastFour = '00'.$agents;
                     break;
                      case '3':
                    $lastFour = '0'.$agents;
                     break;
                      case '4':
                    $lastFour = $agents;

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
