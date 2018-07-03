<?php

namespace App\Http\Controllers;

use App\Model\Order\Invoice;
use App\User;
use App\Model\Order\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;


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
        $productSoldlists = $this->recentProductSold();
        foreach ($productSoldlists as $productSoldlist) {
          $productNameList[] = $productSoldlist->name;
          }
          $arraylists=array_count_values($productNameList);
         
		return view('themes.default1.common.dashboard',compact('totalSalesINR','totalSalesUSD','yearlySalesINR'
                ,'yearlySalesUSD','monthlySalesINR','monthlySalesUSD','users','count_users','arraylists','productSoldlists'));
     }


    /**
     * Get Total Sales in Indian Currency.
     *
     * @return float
     */
    public function getTotalSalesInInr()
    {
        $invoice = new Invoice();
        $total = $invoice->where('currency', 'INR')->pluck('grand_total')->all();
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
        $invoice = new Invoice();
        $total = $invoice->where('currency', 'USD')->pluck('grand_total')->all();
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
        $invoice = new Invoice();
        $currentYear = date('Y');
        $total = $invoice::whereYear('created_at', '=', $currentYear)->where('currency', 'INR')
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
        $invoice = new Invoice();
        $currentYear = date('Y');
        $total = $invoice::whereYear('created_at', '!=', $currentYear)->where('currency', 'USD')
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
        $invoice = new Invoice();
        $currentMonth = date('m');
        $currentYear = date('Y');
        $total = $invoice::whereYear('created_at', '=', $currentYear)->whereMonth('created_at', '=', $currentMonth)
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
        $invoice = new Invoice();
        $currentMonth = date('m');
        $currentYear = date('Y');
        // dd($currentYear,$currentMonth );
        $total = $invoice::whereYear('created_at', '=', $currentYear)->whereMonth('created_at', '=', $currentMonth)
                ->where('currency', 'USD')
                ->pluck('grand_total')->all();
        $grandTotal = array_sum($total);
        return $grandTotal;
    }


     /**
      * Get the list of previous 8 registered users
      * @return type
      */
     public function getAllUsers()
     {
      $user = new User();
      $allUsers = $user->orderBy('created_at','desc')->where('active',1)->where('mobile_verified',1)
              ->take(8)
              ->get()
              ->toArray();
       return $allUsers; 
 
     }


     /**
      * List of products sold in past 30 days
      * @return type
      */
     public function recentProductSold()
     {
 	     $dayUtc = new Carbon('-30 days');
 	     $minus30Day = $dayUtc->toDateTimeString();
     	 $orders = Order::where('order_status','executed')->where('created_at' ,'>', $minus30Day)->get();
     	 foreach ($orders as $order) {
         	$product[] = $order->product()->first();
         }
          return $product;

     }

}
