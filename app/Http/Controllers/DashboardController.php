<?php

namespace App\Http\Controllers;

use App\Model\Common\Setting;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Payment\Currency;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Carbon\Carbon;
use DateTime;
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
        $getLast30DaysInstallation = $this->getLast30DaysInstallation();

        $users = $this->getAllUsers();
        $productSoldInLast30Days = $this->getSoldProducts(30);
        $recentOrders = $this->getRecentOrders();
        $subscriptions = $this->getExpiringSubscriptions();

        $expiredSubscriptions = $this->getExpiringSubscriptions(true);

        $invoices = $this->getRecentInvoices();
        $allSoldProducts = $this->getSoldProducts();

        $clientsUsingOldVersion = $this->getClientsUsingOldVersions();

        $startSubscriptionDate = date('Y-m-d');
        $endSubscriptionDate = date('Y-m-d', strtotime('+3 months'));
        $status = $request->input('status');
        $conversionRate = $this->getConversionRate();

        return view('themes.default1.common.dashboard', compact('allowedCurrencies1','allowedCurrencies2',
            'currency1Symbol','currency2Symbol','totalSalesCurrency2', 'totalSalesCurrency1', 'yearlySalesCurrency2',
            'yearlySalesCurrency1', 'monthlySalesCurrency2', 'monthlySalesCurrency1', 'users', 'productSoldInLast30Days'
            ,'recentOrders','subscriptions','expiredSubscriptions', 'invoices', 'allSoldProducts', 'pendingPaymentCurrency2',
            'pendingPaymentCurrency1', 'status', 'startSubscriptionDate', 'endSubscriptionDate', 'clientsUsingOldVersion', 'getLast30DaysInstallation', 'conversionRate'));
    }

    private function getConversionRate()
    {
        $dayUtc = new Carbon('-30 days');
        $rate = 0;
        $now = Carbon::now();
        $allOrders = Order::whereBetween('created_at', [$dayUtc, $now])->count();
        $paidOrders = Order::where('price_override', '>', 0)->whereBetween('created_at', [$dayUtc, $now])->count();
        if ($paidOrders) {
            $rate = ($paidOrders / $allOrders) * 100;
        }

        return ['all_orders'=>$allOrders, 'paid_orders'=>$paidOrders, 'rate'=>$rate];
    }

    public function getLast30DaysInstallation()
    {
        $dayUtc = new Carbon('-30 days');
        $now = Carbon::now()->subDays(1);
        $rate = 0;
        $totalSubscriptionInLast30Days = Subscription::whereBetween('created_at', [$dayUtc, $now])->count();
        $inactiveInstallation = Subscription::whereColumn('created_at', '=', 'updated_at')->whereBetween('created_at', [$dayUtc, $now])->count();
        if ($totalSubscriptionInLast30Days) {
            $rate = (($totalSubscriptionInLast30Days - $inactiveInstallation) / $totalSubscriptionInLast30Days * 100);
        }

        return ['total_subscription'=>$totalSubscriptionInLast30Days, 'inactive_subscription'=>$inactiveInstallation, 'rate'=>$rate];
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

    /**
     * Get the list of previous 20 registered users.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return User::orderBy('created_at', 'desc')->where('active', 1)->where('mobile_verified', 1)
            ->select('id', 'first_name', 'last_name', 'user_name', 'profile_pic', 'email', 'created_at')
            ->take(20)
            ->get();
    }

    /**
     * List of products sold in past $noOfDays days. If no parameter is passed, it will give all products.
     * @param int $noOfDays
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getSoldProducts(int $noOfDays = null)
    {
        // ASSUMING THIS CODE WON"T STAY ALIVE TILL year 3000
        $dateBefore = $noOfDays ? (new Carbon("-$noOfDays days"))->toDateTimeString() : Carbon::now()->startOfMillennium()->toDateTimeString();

        return Order::join('products', 'products.id', '=', 'orders.product')
            ->select(\DB::raw('COUNT(*) as order_count'), 'products.id as product_id',
                'orders.created_at as order_created_at', 'products.image as product_image', 'products.name as product_name')
            ->where('order_status', 'executed')
            ->where('orders.created_at', '>', $dateBefore)
            ->orderBy('order_count', 'desc')
            ->orderBy('orders.created_at', 'desc')
            ->groupBy('products.id')
            ->get()->map(function ($element) {
                $element->product_image = (new Product())->getImageAttribute($element->product_image);
                $element->order_created_at = getTimeInLoggedInUserTimeZone($element->order_created_at);

                return $element;
            });
    }

    /**
     * List of orders of past 30 days.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentOrders()
    {
        $dateBefore = (new Carbon('-30 days'))->toDateTimeString();

        return Order::with('user:id,first_name,last_name,email,user_name')
            ->join('products', 'products.id', '=', 'orders.product')
            ->select('products.id as product_id', 'orders.created_at as order_created_at', 'number as order_number', 'client', 'orders.id as order_id', 'products.name as product_name')
            ->where('orders.created_at', '>', $dateBefore)
            ->where('price_override', '>', 0)
            ->orderBy('orders.id', 'desc')
            ->get()->map(function ($element) {
                $element->order_created_at = getDateHtml($element->order_created_at);
                $element->client_name = $element->user->first_name.' '.$element->user->last_name;
                $element->client_profile_link = \Config('app.url').'/clients/'.$element->user->id;
                unset($element->user);

                return $element;
            });
    }

    /**
     * List of orders expiring in next 30 days.
     * @param bool $past30Days
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getExpiringSubscriptions($past30Days = false)
    {
        $today = Carbon::now()->toDateTimeString();

        $baseQuery = Subscription::with('user:id,first_name,last_name,email,user_name')
            ->join('orders', 'subscriptions.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'orders.product')
            ->select('subscriptions.id','products.id as product_id', 'orders.number as order_number', 'orders.id as order_id',
                'products.name as product_name', 'subscriptions.update_ends_at as subscription_ends_at', 'user_id')
            ->where('price_override', '>', 0)
            ->orderBy('subscription_ends_at', 'asc')
            ->groupBy('subscriptions.id');

        if ($past30Days) {
            $baseQuery->where('update_ends_at', '<', $today)
                ->where('update_ends_at', '>=', (new Carbon('-30 days'))->toDateTimeString());
        } else {
            $baseQuery->where('update_ends_at', '>', $today)
                ->where('update_ends_at', '<=', (new Carbon('+30 days'))->toDateTimeString());
        }

        return $baseQuery->get()->map(function ($element) {
            $element->client_name = $element->user->first_name.' '.$element->user->last_name;
            $element->client_profile_link = \Config('app.url').'/clients/'.$element->user->id;
            $element->order_link = \Config('app.url').'/orders/'.$element->order_id;
            $element->days_difference = date_diff(new DateTime(), new DateTime($element->subscription_ends_at))->format('%a days');
            $element->subscription_ends_at = getDateHtml($element->subscription_ends_at);
            unset($element->user);

            return $element;
        });
    }

    /**
     * List of Invoices of past 30 ays.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentInvoices()
    {
        $dateBefore = (new Carbon('-30 days'))->toDateTimeString();

        return Invoice::with('user:id,first_name,last_name,email,user_name')
            ->leftJoin('currencies', 'invoices.currency', '=', 'currencies.code')
            ->leftJoin('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->select('invoices.id as invoice_id', 'invoices.number as invoice_number', 'invoices.grand_total', 'invoices.status',
                \DB::raw('SUM(payments.amount) as paid'), 'invoices.user_id', 'currencies.code as currency_code')
            ->where('invoices.created_at', '>', $dateBefore)
            ->where('invoices.grand_total', '>', 0)
            ->groupBy('invoices.id')
            ->orderBy('invoices.created_at', 'desc')
            ->get()->map(function ($element) {
                $element->balance = (int) ($element->grand_total - $element->paid);
                $element->status = getStatusLabel($element->status);
                $element->grand_total = currencyFormat((int) $element->grand_total, $element->currency_code);
                $element->paid = currencyFormat((int) $element->paid, $element->currency_code);
                $element->balance = currencyFormat((int) $element->balance, $element->currency_code);
                $element->client_name = $element->user->first_name.' '.$element->user->last_name;
                $element->client_profile_link = \Config('app.url').'/clients/'.$element->user->id;
                unset($element->user);

                return $element;
            });
    }

    /**
     * Gets list of clients who are using older version of the latest release.
     * @return mixed
     * @throws \Exception
     */
    private function getClientsUsingOldVersions()
    {
        $date = new Carbon('-30 days');
        $latestVersion = (string) Subscription::orderBy('version', 'desc')->value('version');

        // query the latest version and query for rest of the versions
        return Order::leftJoin('subscriptions', 'orders.id', '=', 'subscriptions.order_id')
            ->leftJoin('users', 'orders.client', '=', 'users.id')
            ->leftJoin('products', 'orders.product', '=', 'products.id')
            ->where('price_override', '>', 0)
            ->where('subscriptions.updated_at', '>', $date)
            ->where('subscriptions.version', '<', $latestVersion)
            ->where('subscriptions.version', '!=', null)
            ->where('subscriptions.version', '!=', '')
            ->select('orders.id', \DB::raw("concat(first_name, ' ', last_name) as client_name"), 'products.name as product_name',
                'subscriptions.version as product_version', 'client as client_id', 'subscriptions.update_ends_at as subscription_ends_at')
            ->orderBy('subscriptions.version', 'asc')
            ->take(30)->get()->map(function ($element) {
                $element->subscription_ends_at = getDateHtml($element->subscription_ends_at);
                $appUrl = \Config::get('app.url');
                $clientProfileUrl = $appUrl.'/clients/'.$element->client_id;
                $element->client_name = "<a href=$clientProfileUrl>$element->client_name</a>";

                return $element;
            });
    }
}
