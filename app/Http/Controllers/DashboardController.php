<?php

namespace App\Http\Controllers;

use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Product\Subscription;
use App\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['index']]);
        $this->middleware('admin', ['only' => ['index']]);
    }

    public function index()
    {
        $totalSalesINR = $this->getTotalSalesInInr();
        $totalSalesUSD = $this->getTotalSalesInUsd();
        $yearlySalesINR = $this->getYearlySalesInInr();
        $yearlySalesUSD = $this->getYearlySalesInUsd();
        $monthlySalesINR = $this->getMonthlySalesInInr();
        $monthlySalesUSD = $this->getMonthlySalesInUsd();
        $users = $this->getAllUsers();
        $count_users = User::get()->count();
        $productNameList = array();
        $productSoldlists = $this->recentProductSold();
        
        if (count($productSoldlists) > 0) {
            foreach ($productSoldlists as $productSoldlist) {
                if($productSoldlist && $productSoldlist->name){
                    $productNameList[] = $productSoldlist->name;
                }
                
            }
        }
        $arraylists = array_count_values($productNameList);
        $orders = $this->getRecentOrders();
        $subscriptions = $this->expiringSubscription();
        $invoices = $this->getRecentInvoices();
        $products = $this->totalProductsSold();
        $productName = [];
        if (!empty($products)) {
           
            foreach ($products as $product) {
                 if($product && $product->name){
                    $productName[] = $product->name;
                
                
            }
            }
        }
        $arrayCountList = array_count_values($productName);

        return view('themes.default1.common.dashboard', compact('totalSalesINR', 'totalSalesUSD',
                'yearlySalesINR', 'yearlySalesUSD', 'monthlySalesINR', 'monthlySalesUSD', 'users',

                 'count_users', 'arraylists', 'productSoldlists','orders','subscriptions','invoices',
                 'products', 'arrayCountList'));
    }

    /**
     * Get Total Sales in Indian Currency.
     *
     * @return float
     */
    public function getTotalSalesInInr()
    {
        $total = Invoice::where('currency', 'INR')->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get total sales in US Dollar.
     *
     * @return float
     */
    public function getTotalSalesInUsd()
    {
        $total = Invoice::where('currency', 'USD')->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total yearly sale of present year IN INR.
     *
     * @return type
     */
    public function getYearlySalesInInr()
    {
        $currentYear = date('Y');
        $total = Invoice::whereYear('created_at', '=', $currentYear)->where('currency', 'INR')
                ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total yearly sale of present year in USD.
     *
     * @return type
     */
    public function getYearlySalesInUsd()
    {
        $currentYear = date('Y');
        $total = Invoice::whereYear('created_at', '=', $currentYear)->where('currency', 'USD')
                ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total Monthly sale of present month in Inr.
     *
     * @return type
     */
    public function getMonthlySalesInInr()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $total = Invoice::whereYear('created_at', '=', $currentYear)->whereMonth('created_at', '=', $currentMonth)
                ->where('currency', 'INR')
                ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

    /**
     * Get  Total Monthly sale of present month in Usd.
     *
     * @return type
     */
    public function getMonthlySalesInUsd()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        // dd($currentYear,$currentMonth );
        $total = Invoice::whereYear('created_at', '=', $currentYear)->whereMonth('created_at', '=', $currentMonth)
                ->where('currency', 'USD')
                ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);

        return $grandTotal;
    }

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
        try{
        $dayUtc = new Carbon('-30 days');
        $minus30Day = $dayUtc->toDateTimeString();
        $product = [];
        $orders = Order::where('order_status', 'executed')->where('created_at', '>', $minus30Day)->get();
        foreach ($orders as $order) {
            $product[] = $order->product()->first();
        }

        return $product;
    }catch(Exception $ex )
     {
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
        $subsEnds = Subscription::where('ends_at', '>', $today)->where('ends_at', '<=', $plus30Day)->get();

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
}
