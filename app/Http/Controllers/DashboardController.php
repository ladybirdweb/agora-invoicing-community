<?php

namespace App\Http\Controllers;

use App\Model\Common\Setting;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Payment\Currency;
use App\Model\Product\Subscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['index']]);
        $this->middleware('admin', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $allowedCurrencies1 = Setting::find(1)->value('default_currency');
        $currency1Symbol = Setting::find(1)->value('default_symbol');
        $allowedCurrencies2 = Currency::where('dashboard_currency', 1)->pluck('code')->first();
        $currency2Symbol = Currency::where('dashboard_currency', 1)->pluck('symbol')->first();
        $totalSalesCurrency1 = $this->getTotalSalesInCur1($allowedCurrencies1);
        $totalSalesCurrency2 = $this->getTotalSalesInCur2($allowedCurrencies2);
        $yearlySalesCurrency2 = $this->getYearlySalesCur2($allowedCurrencies2);
        $yearlySalesCurrency1 = $this->getYearlySalesCur1($allowedCurrencies1);
        $monthlySalesCurrency2 = $this->getMonthlySalesCur2($allowedCurrencies2);
        $monthlySalesCurrency1 = $this->getMonthlySalesInCur1($allowedCurrencies1);
        $pendingPaymentCurrency2 = $this->getPendingPaymentsCur2($allowedCurrencies2);
        $pendingPaymentCurrency1 = $this->getPendingPaymentsCur1($allowedCurrencies1);
        $users = $this->getAllUsers();
        $count_users = User::get()->count();
        $productNameList = [];
        $productSoldlists = $this->recentProductSold();
        if (count($productSoldlists) > 0) {
            $productNameList = $this->getProductNameList($productSoldlists);
        }

        $arraylists = array_count_values($productNameList);
        $orders = $this->getRecentOrders();
        $subscriptions = $this->expiringSubscription();
        $invoices = $this->getRecentInvoices();
        $products = $this->totalProductsSold();
        $productName = [];
        if (!empty($products)) {
            foreach ($products as $product) {
                if ($product && $product->name) {
                    $productName[] = $product->name;
                }
            }
        }
        $arrayCountList = array_count_values($productName);
        $startSubscriptionDate = date('Y-m-d');
        $endSubscriptionDate = date('Y-m-d', strtotime('+3 months'));
        $status = $request->input('status');

        return view('themes.default1.common.dashboard', compact('allowedCurrencies1','allowedCurrencies2','currency1Symbol','currency2Symbol','totalSalesCurrency2', 'totalSalesCurrency1', 'yearlySalesCurrency2', 'yearlySalesCurrency1', 'monthlySalesCurrency2', 'monthlySalesCurrency1', 'users','count_users', 'arraylists', 'productSoldlists','orders','subscriptions','invoices', 'products', 'arrayCountList', 'pendingPaymentCurrency2', 'pendingPaymentCurrency1', 'status','startSubscriptionDate',
                 'endSubscriptionDate'));
    }

    /**
     * Get Total Sales in Allowed Dashboard Currency.
     *
     * @return float
     */
    public function getTotalSalesInCur2($allowedCurrencies2)
    {
        $total = Invoice::where('currency', $allowedCurrencies2)
        ->where('status', '=', 'success')
        ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get total sales in Default Currency.
     *
     * @return float
     */
    public function getTotalSalesInCur1($allowedCurrencies1)
    {
        $total = Invoice::where('currency', $allowedCurrencies1)
        ->where('status', '=', 'success')
        ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total yearly sale of present year IN Allowed Dashboard Currency.
     *
     * @return type
     */
    public function getYearlySalesCur2($allowedCurrencies2)
    {
        $currentYear = date('Y');
        $total = Invoice::whereYear('created_at', '=', $currentYear)
        ->where('status', '=', 'success')
        ->where('currency', $allowedCurrencies2)
        ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total yearly sale of present year in USD.
     *
     * @return type
     */
    public function getYearlySalesCur1($allowedCurrencies1)
    {
        $currentYear = date('Y');
        $total = Invoice::whereYear('created_at', '=', $currentYear)
        ->where('status', '=', 'success')
        ->where('currency', $allowedCurrencies1)
        ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total Monthly sale of present month in Allowed Dashboard Currency.
     *
     * @return type
     */
    public function getMonthlySalesCur2($allowedCurrencies2)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $total = Invoice::whereYear('created_at', '=', $currentYear)->whereMonth('created_at', '=', $currentMonth)
                ->where('currency', $allowedCurrencies2)
                ->where('status', '=', 'success')
                ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total Monthly sale of present month in System Default Currency.
     *
     * @return type
     */
    public function getMonthlySalesInCur1($allowedCurrencies1)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        // dd($currentYear,$currentMonth );
        $total = Invoice::whereYear('created_at', '=', $currentYear)->whereMonth('created_at', '=', $currentMonth)
                ->where('currency', $allowedCurrencies1)
                 ->where('status', '=', 'success')
                ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total Pending Payment Inr.
     *
     * @return type
     */
    public function getPendingPaymentsCur2($allowedCurrencies2)
    {
        $total = Invoice::where('currency', $allowedCurrencies2)
        ->where('status', '=', 'pending')
        ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total Pending Payment Inr.
     *
     * @return type
     */
    public function getPendingPaymentsCur1($allowedCurrencies1)
    {
        $total = Invoice::where('currency', $allowedCurrencies1)
        ->where('status', '=', 'pending')
        ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    // getPendingPaymentsInInr

    /**
     * Get the list of previous 20 registered users.
     *
     * @return type
     */
    public function getAllUsers()
    {
        $allUsers = User::orderBy('created_at', 'desc')->where('active', 1)->where('mobile_verified', 1)
              ->take(20)
              ->get()
              ->toArray();

        return $allUsers;
    }

    /**
     * List of products sold in past 30 days.
     *
     * @return type
     */
    public function recentProductSold()
    {
        try {
            $dayUtc = new Carbon('-30 days');
            $minus30Day = $dayUtc->toDateTimeString();
            $product = [];
            $orders = Order::where('order_status', 'executed')->where('created_at', '>', $minus30Day)->get();
            foreach ($orders as $order) {
                $product[] = $order->product()->first();
            }

            return $product;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * List of orders of past 30 days.
     */
    public function getRecentOrders()
    {
        $dayUtc = new Carbon('-30 days');
        $minus30Day = $dayUtc->toDateTimeString();
        $recentOrders = Order::where('created_at', '>', $minus30Day)->orderBy('created_at', 'desc')
                 ->where('price_override', '>', 0)->get();

        return $recentOrders;
    }

    /**
     * List of Invoices of past 30 ays.
     */
    public function getRecentInvoices()
    {
        $dayUtc = new Carbon('-30 days');
        $minus30Day = $dayUtc->toDateTimeString();
        $recentInvoice = Invoice::where('created_at', '>', $minus30Day)->orderBy('created_at', 'desc')
                        ->where('grand_total', '>', 0)->get();

        return $recentInvoice;
    }

    /**
     * List of orders expiring in next 30 days.
     */
    public function expiringSubscription()
    {
        $dayUtc = new Carbon('+30 days');
        $today = Carbon::now()->toDateTimeString();
        $plus30Day = $dayUtc->toDateTimeString();
        $subsEnds = Subscription::where('update_ends_at', '>', $today)->where('update_ends_at', '<=', $plus30Day)->whereHas('order', function($query) {
                    $query->where('price_override', '>', 0);
                })
        ->orderBy('update_ends_at', 'ASC')->get();

        return $subsEnds;
    }

    /**
     * List of the products sold.
     */
    public function totalProductsSold()
    {
        $product = [];
        $orders = Order::where('order_status', 'executed')->get();
        foreach ($orders as $order) {
            $product[] = $order->product()->first();
        }

        return $product;
    }

    public function getProductNameList($productSoldlists)
    {
        try {
            $productNameList = [];
            foreach ($productSoldlists as $productSoldlist) {
                if ($productSoldlist && $productSoldlist->name) {
                    $productNameList[] = $productSoldlist->name;
                }
            }

            return $productNameList;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
