<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Common\BaseSettingsController;
use App\Http\Controllers\Controller;
use App\Model\Common\StatusSetting;
use App\Model\Order\Order;
use App\Model\Product\Subscription;
use Bugsnag;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ExtendedOrderController extends Controller
{
    /**
     * Perform Advance Search for Orders Page.
     *
     * @param Request $request
     * @return array
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-19T01:35:08+0530
     */
    public function advanceSearch(Request $request)
    {
        try {
            $baseQuery = $this->getBaseQueryForOrders();
            $this->orderNum($request->input('order_no'), $baseQuery);
            $this->product($request->input('product_id'), $baseQuery);
            $this->expiry($request->input('expiryTill'), $request->input('expiry'), $baseQuery);
            $this->expiryTill($request->input('expiry'), $request->input('expiryTill'), $baseQuery);
            $this->orderFrom($request->input('till'), $request->input('from'), $baseQuery);
            $this->orderTill($request->input('from'), $request->input('till'), $baseQuery);
            $this->subFrom($request->input('sub_till'), $request->input('sub_from'), $baseQuery);
            $this->subTill($request->input('sub_from'), $request->input('sub_till'), $baseQuery);
            $this->installedNotInstalled($request->input('ins_not_ins'), $baseQuery);
            $this->domain($request->input('domain'), $baseQuery);

            $this->paidOrUnpaid($request->input('p_un'), $baseQuery);
            $this->allActiveInstallations($request->input('act_ins'), $baseQuery);
            $this->allInActiveInstallations($request->input('inact_ins'), $baseQuery);
            $this->allRenewals($request->input('renewal'), $baseQuery);
            $this->getSelectedVersionOrders($baseQuery, $request->input('version_from'), $request->input('version_till'));

            return $baseQuery->orderBy('orders.created_at', 'desc');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Gets base query for orders.
     * @return Builder
     */
    private function getBaseQueryForOrders()
    {
        return Order::leftJoin('subscriptions', 'orders.id', '=', 'subscriptions.order_id')
            ->leftJoin('users', 'orders.client', '=', 'users.id')
            ->leftJoin('products', 'orders.product', '=', 'products.id')
            ->select(
                'orders.id', 'orders.created_at', 'price_override', 'order_status', 'product', 'number', 'serial_key',
                'subscriptions.update_ends_at as subscription_ends_at', 'subscriptions.id as subscription_id', 'subscriptions.version as product_version', 'subscriptions.created_at', 'subscriptions.updated_at',
                'products.name as product_name', \DB::raw("concat(first_name, ' ', last_name) as client_name"), 'client as client_id',
                'users.currency'
            );
    }

    private function installedNotInstalled($installedNotInstalled, $join)
    {
        if ($installedNotInstalled) {
            if ($installedNotInstalled == 'installed') {
                return $join->whereColumn('subscriptions.created_at', '!=', 'subscriptions.updated_at');
            }

            return $join->whereColumn('subscriptions.created_at', '=', 'subscriptions.updated_at');
        }
    }

    /**
     * Searches for order for selected versions.
     *
     * @param $baseQuery
     * @param $versionFrom
     * @param $versionTill
     * @return Builder
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     */
    private function getSelectedVersionOrders($baseQuery, $versionFrom, $versionTill)
    {
        if ($versionFrom) {
            $baseQuery->where('subscriptions.version', '>=', $versionFrom);
        }

        if ($versionTill) {
            $baseQuery->where('subscriptions.version', '<=', $versionTill);
        }

        return $baseQuery;
    }

    /**
     * Searches for Activ Installation.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2020-01-29T17:35:05+0530
     *
     * @param  string $allInstallation
     * @param  App\Model\Order $join The order instance
     *
     * @return $join
     */
    public function allActiveInstallations($allInstallation, $join)
    {
        if ($allInstallation) {
            $dayUtc = new Carbon('-30 days');
            $minus30Day = $dayUtc->toDateTimeString();

            $baseQuery = $join->whereHas('subscription', function ($q) use ($minus30Day) {
                $q->where('updated_at', '>', $minus30Day);
            });

            return $baseQuery->when($allInstallation == 'paid_ins', function ($q) {
                $q->where('price_override', '>', 0);
            })->when($allInstallation == 'unpaid_ins', function ($q) {
                $q->where('price_override', '=', 0);
            });
        }
    }

    /**
     * Searches for InActive Installation.
     *
     * @param  string $allInstallation
     * @param  App\Model\Order $join The order instance
     *
     * @return $join
     */
    public function allInactiveInstallations($allInstallation, $join)
    {
        if ($allInstallation) {
            $dayUtc = new Carbon('-30 days');
            $minus30Day = $dayUtc->toDateTimeString();
            $baseQuery = $join->whereHas('subscription', function ($q) use ($minus30Day) {
                $q->where('updated_at', '<', $minus30Day);
            });

            return $baseQuery->when($allInstallation == 'paid_inactive_ins', function ($q) {
                $q->where('price_override', '>', 0);
            })->when($allInstallation == 'unpaid_inactive_ins', function ($q) {
                $q->where('price_override', '=', 0);
            });
        }
    }

    /**
     * Searches for Renewals.
     *
     * @param  string $allInstallation
     * @param  App\Model\Order $join The order instance
     *
     * @return $join
     */
    protected function allRenewals($allRenewal, $join)
    {
        if ($allRenewal) {
            $dayUtc = new Carbon();
            $now = $dayUtc->toDateTimeString();

            return $join->whereHas('subscription', function ($query) use ($now,$allRenewal) {
                if ($allRenewal == 'expired_subscription') {
                    return $query->where('update_ends_at', '<', $now);
                }

                return $query->where('update_ends_at', '>', $now);
            });
        }
    }

    /**
     * Searches for Paid/Unpaid Products.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2020-01-29T17:35:05+0530
     *
     * @param  string $paidUnpaid
     * @param  App\Model\Order $join The order instance
     *
     * @return $join
     */
    private function paidOrUnpaid($paidUnpaid, $join)
    {
        if ($paidUnpaid) {
            if ($paidUnpaid == 'paid') {
                $join = $join->where('price_override', '>', 0);
            } elseif ($paidUnpaid == 'unpaid') {
                $join = $join->where('price_override', '=', 0);
            }
        }

        return $join;
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
            $join = $join->whereBetween('subscriptions.update_ends_at', [$expiryFrom, $tillDate]);

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
            $join = $join->whereBetween('subscriptions.update_ends_at', [$fromDate, $exptill]);

            return $join;
        }
    }

    /**
     * Searches for Subcription From Date.
     *
     * @param string $expiry The Subcription From Date
     * @param object $join
     *
     * @return Query
     */
    private function subFrom($till, $from, $join)
    {
        if ($from) {
            $fromdate = date_create($from);

            $from = date_format($fromdate, 'Y-m-d H:m:i');
            $tills = date('Y-m-d H:m:i');

            $tillDate = $this->getTillDate($from, $till, $tills);
            $join = $join->whereBetween('subscriptions.created_at', [$from, $tillDate]);

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
    private function subTill($from, $till, $join)
    {
        if ($till) {
            $tilldate = date_create($till);
            $till = date_format($tilldate, 'Y-m-d H:m:i');
            $froms = date_format(Subscription::first()->created_at, 'Y-m-d H:m:i');
            $fromDate = $this->getFromDate($from, $froms);
            $join = $join->whereBetween('subscriptions.created_at', [$fromDate, $till]);

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
            $licenseExpiry = $order->subscription->ends_at;
            $updatesExpiry = $order->subscription->update_ends_at;
            $supportExpiry = $order->subscription->support_ends_at;
            $cont = new \App\Http\Controllers\License\LicenseController();
            $updateLicensedDomain = $cont->updateLicensedDomain($licenseCode, $order->domain, $order->product, $licenseExpiry, $updatesExpiry, $supportExpiry, $order->number);
            //Now make Installation status as inactive
            $updateInstallStatus = $cont->updateInstalledDomain($licenseCode, $order->product);
        }

        return ['message' => 'success', 'update'=>'Licensed Domain Updated'];
    }

    public function reissueLicense(Request $request)
    {
        $order = Order::findorFail($request->input('id'));
        if (\Auth::user()->role != 'admin' && $order->client != \Auth::user()->id) {
            return errorResponse('Cannot reissue license. Invalid modification of data');
        }
        $order->domain = '';
        $licenseCode = $order->serial_key;
        $order->save();
        $licenseStatus = StatusSetting::pluck('license_status')->first();
        if ($licenseStatus == 1) {
            $licenseExpiry = $order->subscription->ends_at;
            $updatesExpiry = $order->subscription->update_ends_at;
            $supportExpiry = $order->subscription->support_ends_at;
            $cont = new \App\Http\Controllers\License\LicenseController();
            $updateLicensedDomain = $cont->updateLicensedDomain($licenseCode, $order->domain, $order->product, $licenseExpiry, $updatesExpiry, $supportExpiry, $order->number);
            //Now make Installation status as inactive
            $updateInstallStatus = $cont->updateInstalledDomain($licenseCode, $order->product);
        }

        return ['message' => 'success', 'update'=>'License Reissued'];
    }

    public function getAllowedDomains($seperateDomains)
    {
        $needle = 'www';
        foreach ($seperateDomains as $domain) {
            $allowedDomains[] = $domain;
        }

        return  $allowedDomains;
    }
}
