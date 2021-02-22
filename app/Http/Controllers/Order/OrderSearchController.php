<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Product\ProductUpload;
use App\Model\Product\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class OrderSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Perform Advance Search for Orders Page.
     *
     * @param Request $request
     * @return array
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-19T01:35:08+0530
     */
    public function advanceOrderSearch(Request $request)
    {
        try {
            $baseQuery = $this->getBaseQueryForOrders();
            $this->orderNum($request->input('order_no'), $baseQuery);
            $this->product($request->input('product_id'), $baseQuery);
            $this->orderFrom($request->input('till'), $request->input('from'), $baseQuery);
            $this->orderTill($request->input('from'), $request->input('till'), $baseQuery);
            $this->domain($request->input('domain'), $baseQuery);
            $this->allInstallations($request->input('act_ins'), $baseQuery);
            $this->allRenewals($request->input('renewal'), $baseQuery);
            $this->getSelectedVersionOrders($baseQuery, $request->input('version'), $request->input('product_id'));

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
                'subscriptions.update_ends_at as subscription_ends_at', 'subscriptions.id as subscription_id', 'subscriptions.version as product_version', 'subscriptions.updated_at as subscription_updated_at', 'subscriptions.created_at as subscription_created_at',
                'products.name as product_name', \DB::raw("concat(first_name, ' ', last_name) as client_name"), 'client as client_id',
            );
    }

    public function getProductVersions(Request $request, $productId)
    {
        try {
            $id = $productId;
            if (($productId !== 'paid') && ($productId !== 'unpaid')) {
                $allVersions = Subscription::where('product_id', $productId)->where('product_id', '!=', 0)->where('version', '!=', '')->whereNotNull('version')
                ->orderBy('version', 'desc')->groupBy('version')->get();
                // $states = \App\Model\Common\State::where('country_code_char2', $id)
                // ->orderBy('state_subdivision_name', 'asc')->get();
                if ($request->select_id != '') {
                    echo '<option name="version" value='.$request->select_id.'>'.$request->select_id.'</option>';
                } else {
                    echo '<option value=""> Choose</option>';
                }

                echo '<option value="Outdated">Outdated</option>';
                foreach ($allVersions as $version) {
                    echo '<option value='.$version->version.'>'.$version->version.'</option>';
                }
            } else {
                echo '<option value=""> Choose</option><option value="Latest"> Latest</option><option value="Outdated"> Outdated</option>';
            }
        } catch (\Exception $ex) {
            echo "<option value=''>Problem while loading</option>";

            return redirect()->back()->with('fails', $ex->getMessage());
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
    private function getSelectedVersionOrders($baseQuery, $version, $productId)
    {
        if ($version) {
            if ($productId == 'paid') {
                $latestVersion = ProductUpload::orderBy('version', 'desc')->value('version');
                if ($version == 'Latest') {
                    $baseQuery->where('subscriptions.version', '=', $latestVersion);
                } elseif ($version == 'Outdated') {
                    $baseQuery->where('subscriptions.version', '<', $latestVersion);
                }
            } elseif ($productId == 'unpaid') {
                $latestVersion = ProductUpload::orderBy('version', 'desc')->value('version');
                if ($version == 'Latest') {
                    $baseQuery->where('subscriptions.version', '=', $latestVersion);
                } elseif ($version == 'Outdated') {
                    $baseQuery->where('subscriptions.version', '<', $latestVersion);
                }
            } elseif ($version == 'Outdated') {
                $latestVersion = Subscription::where('product_id', $productId)->orderBy('version', 'desc')->value('version');

                $baseQuery->where('subscriptions.version', '<', $latestVersion);
            } else {
                $baseQuery->where('subscriptions.version', '=', $version);
            }
        }

        return $baseQuery;
    }

    /**
     * Searches for Installation.
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
    public function allInstallations($allInstallation, $join)
    {
        if ($allInstallation) {
            $dayUtc = new Carbon('-30 days');
            $minus30Day = $dayUtc->toDateTimeString();
            if ($allInstallation == 'installed') {
                return $join->whereColumn('subscriptions.created_at', '!=', 'subscriptions.updated_at');
            } elseif ($allInstallation == 'not_installed') {
                return $join->whereColumn('subscriptions.created_at', '=', 'subscriptions.updated_at');
            } elseif ($allInstallation == 'paid_inactive_ins') {
                $baseQuery = $join->whereHas('subscription', function ($q) use ($minus30Day) {
                    $q->where('subscriptions.updated_at', '<', $minus30Day);
                });

                return $baseQuery;
            } elseif ($allInstallation == 'paid_ins') {
                $baseQuery = $join->whereHas('subscription', function ($q) use ($minus30Day) {
                    $q->whereColumn('subscriptions.created_at', '!=', 'subscriptions.updated_at')->where('subscriptions.updated_at', '>', $minus30Day);
                });
            }

            return $baseQuery;
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

            return $join->whereHas('subscription', function ($query) use ($now, $allRenewal) {
                if ($allRenewal == 'expired_subscription') {
                    return $query->where('update_ends_at', '<', $now);
                }

                return $query->where('update_ends_at', '>', $now);
            });
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
            if ($product_id == 'paid') {
                $join = $join->where('price_override', '>', 0);
            } elseif ($product_id == 'unpaid') {
                $join = $join->where('price_override', '=', 0);
            } else {
                $join = $join->where('product', $product_id);
            }
        }

        return $join;
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
}
