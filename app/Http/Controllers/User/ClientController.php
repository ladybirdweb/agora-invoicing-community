<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\License\LicenseController;
use App\Http\Requests\User\ClientRequest;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Payment\Currency;
use App\Traits\PaymentsAndInvoices;
use App\Model\User\AccountActivate;
use App\User;
use Bugsnag;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Log;

class ClientController extends AdvanceSearchController
{
    use PaymentsAndInvoices;

    public $user;
    public $activate;
    public $product;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin');
        $user = new User();
        $this->user = $user;
        $activate = new AccountActivate();
        $this->activate = $activate;
        $product = new \App\Model\Product\Product();
        $this->product = $product;
        $license = new LicenseController();
        $this->licensing = $license;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        $name = $request->input('name');
        $username = $request->input('username');
        $company = $request->input('company');
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $country = $request->input('country');
        $industry = $request->input('industry');
        $company_type = $request->input('company_type');
        $company_size = $request->input('company_size');
        $role = $request->input('role');
        $position = $request->input('position');
        $reg_from = $request->input('reg_from');
        $reg_till = $request->input('reg_till');

        return view('themes.default1.user.client.index',
            compact('name', 'username', 'company', 'mobile', 'email',
                'country', 'industry', 'company_type', 'company_size', 'role', 'position', 'reg_from', 'reg_till'));
    }

    /**
     * Get Clients for yajra datatable.
     */
    public function getClients(Request $request)
    {
        $name = $request->input('name');
        $username = $request->input('username');
        $company = $request->input('company');
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $country = $request->input('country');
        $industry = $request->input('industry');
        $company_type = $request->input('company_type');
        $company_size = $request->input('company_size');
        $role = $request->input('role');
        $position = $request->input('position');
        $reg_from = $request->input('reg_from');
        $reg_till = $request->input('reg_till');
        $user = $this->advanceSearch($name, $username, $company,
         $mobile, $email, $country, $industry, $company_type, $company_size, $role, $position, $reg_from, $reg_till);

        return\ DataTables::of($user->get())
                         ->addColumn('checkbox', function ($model) {
                             return "<input type='checkbox' class='user_checkbox' 
                            value=".$model->id.' name=select[] id=check>';
                         })
                        ->addColumn('first_name', function ($model) {
                            return '<a href='.url('clients/'.$model->id).'>'
                            .ucfirst($model->first_name).' '.ucfirst($model->last_name).'</a>';
                        })
                         ->addColumn('email', function ($model) {
                             return $model->email;
                         })
                          ->addColumn('created_at', function ($model) {
                              $ends = $model->created_at;
                              if ($ends) {
                                  $date1 = new DateTime($ends);
                                  $tz = \Auth::user()->timezone()->first()->name;
                                  $date1->setTimezone(new DateTimeZone($tz));
                                  $end = $date1->format('M j, Y, g:i a ');
                              }

                              return $end;
                          })
                        // ->showColumns('email', 'created_at')
                        ->addColumn('active', function ($model) {
                            if ($model->active == 1) {
                                $email = "<span class='glyphicon glyphicon-envelope'
                                 style='color:green' title='verified email'></span>";
                            } else {
                                $email = "<span class='glyphicon glyphicon-envelope'
                                 style='color:red' title='unverified email'></span>";
                            }
                            if ($model->mobile_verified == 1) {
                                $mobile = "<span class='glyphicon glyphicon-phone' 
                                style='color:green' title='verified mobile'></span>";
                            } else {
                                $mobile = "<span class='glyphicon glyphicon-phone'
                                 style='color:red' title='unverified mobile'></span>";
                            }

                            return $email.'&nbsp;&nbsp;'.$mobile;
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('clients/'.$model->id.'/edit')
                            ." class='btn btn-sm btn-primary btn-xs'>
                            <i class='fa fa-edit' style='color:white;'> </i>&nbsp;&nbsp;Edit</a>"
                                    .'  <a href='.url('clients/'.$model->id)
                                    ." class='btn btn-sm btn-primary btn-xs'>
                                    <i class='fa fa-eye' style='color:white;'> </i>&nbsp;&nbsp;View</a>";
                            // return 'hhhh';
                        })
                        ->rawColumns(['checkbox', 'first_name', 'email',  'created_at', 'active', 'action'])
                        ->make(true);

        // ->searchColumns('email', 'first_name')
                        // ->orderColumns('email', 'first_name', 'created_at')
                        // ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        $timezones = new \App\Model\Common\Timezone();
        $timezones = $timezones->pluck('name', 'id')->toArray();
        $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();
        $managers = User::where('role', 'admin')->where('position', 'manager')
        ->pluck('first_name', 'id')->toArray();
        $timezonesList = \App\Model\Common\Timezone::get();
        foreach ($timezonesList as $timezone) {
            $location = $timezone->location;
            if ($location) {
                $start = strpos($location, '(');
                $end = strpos($location, ')', $start + 1);
                $length = $end - $start;
                $result = substr($location, $start + 1, $length - 1);
                $display[] = (['id'=>$timezone->id, 'name'=> '('.$result.')'.' '.$timezone->name]);
            }
        }
        $timezones = array_column($display, 'name', 'id');

        return view('themes.default1.user.client.create', compact('timezones', 'bussinesses', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Response
     */
    public function store(ClientRequest $request)
    {
        try {
            $user = $this->user;
            $str = 'demopass';
            $password = \Hash::make($str);
            $user->password = $password;
            $cont = new \App\Http\Controllers\Front\PageController();
            $location = $cont->getLocation();
            $user->ip = $location['ip'];
            $user->fill($request->input())->save();
            $this->sendWelcomeMail($user);
            $mailchimp = new \App\Http\Controllers\Common\MailChimpController();
            $r = $mailchimp->addSubscriber($user->email);

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Swift_TransportException $e) {
            return redirect()->back()->with('warning', 'User has been created successfully
             But email configuration has some problem!');
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function show($id)
    {
        try {
            $invoice = new Invoice();
            $order = new Order();
            $invoices = $invoice->where('user_id', $id)->orderBy('created_at', 'desc')->get();
            $invoiceSum = $this->getTotalInvoice($invoices);
            $amountReceived = $this->getAmountPaid($id);
            $pendingAmount = $invoiceSum - $amountReceived;
            // $pendingAmount = $invoiceSum - $amountReceived;
            // if ($pendingAmount < 0) {
            //     $pendingAmount = 0;
            // }
            $extraAmt = $this->getExtraAmt($id);
            $client = $this->user->where('id', $id)->first();

            // $client = "";
            $currency = $client->currency;
            $orders = $order->where('client', $id)->get();
            $comments = $client->comments()->where('user_id', $client->id)->get();

            return view('themes.default1.user.client.show',
                compact('id', 'client', 'invoices', 'model_popup', 'orders',
                 'payments', 'invoiceSum', 'amountReceived', 'pendingAmount', 'currency', 'extraAmt', 'comments'));
        } catch (\Exception $ex) {
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getOrderDetail($id)
    {
        $client = $this->user->where('id', $id)->first();
        $responseData = [];
        foreach ($client->order()->orderBy('created_at', 'desc')->get() as $order) {
            $date = $order->created_at;
            $productName = $order->product()->first() && $order->product()->first()->name ?
            $order->product()->first()->name : 'Unknown';
            $number = $order->number;
            $price = $order->price_override;
            $status = $order->order_status;
            array_push($responseData, (['date'=> $date, 'productName'=>$productName,
                'number'                      => $number, 'price' =>$price, 'status'=>$status, ]));
        }

        return $responseData;
    }

    //Get Payment Details on Invoice Page
    public function getPaymentDetail($id)
    {
        $client = $this->user->where('id', $id)->first();
        $invoice = new Invoice();
        $invoices = $invoice->where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $extraAmt = $this->getExtraAmtPaid($id);
        $date = '';
        $responseData = [];
        if ($invoices) {
            foreach ($client->payment()->orderBy('created_at', 'desc')->get() as $payment) {
                $number = $payment->invoice()->first() ? $payment->invoice()->first()->number : '--';
                $date = $payment->created_at;
                $date1 = new DateTime($date);
                $tz = \Auth::user()->timezone()->first()->name;
                $date1->setTimezone(new DateTimeZone($tz));
                $date = $date1->format('M j, Y, g:i a ');
                $pay_method = $payment->payment_method;
                if ($payment->invoice_id == 0) {
                    $amount = $extraAmt;
                } else {
                    $amount = $payment->amount;
                }
                $status = ucfirst($payment->payment_status);
                array_push($responseData, (['number'=>$number, 'pay_method'=>$pay_method, 'amount'=>$amount, 'status'=>$status, 'date'=>$date]));
            }
        }

        return $responseData;
    }

    public function getClientDetail($id)
    {
        $client = $this->user->where('id', $id)->first();
        $currency = $client->currency;
        if (array_key_exists('name', \App\Http\Controllers\Front\CartController::getStateByCode($client->state))) {
            $client->state = \App\Http\Controllers\Front\CartController::getStateByCode($client->state)['name'];
        }
        $client->country = ucwords(strtolower(\App\Http\Controllers\Front\CartController::getCountryByCode($client->country)));

        $displayData = (['currency'=>$currency, 'client'=> $client]);

        return $displayData;
    }

    public function getExtraAmt($userId)
    {
        try {

                $amounts = Payment::where('user_id', $userId)->where('invoice_id',0)->select('amt_to_credit')->get();
                 $balance = 0;
                foreach ($amounts as $amount) {
                    if ($amount) {
                        $balance = $balance + $amount->amt_to_credit ;
                    }
                }
            }

            return $balance;
        } catch (\Exception $ex) {
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Get total Amount paid for a particular invoice.
     */
    public function getAmountPaid($userId)
    {
        try {
            $amounts = Payment::where('user_id', $userId)->select('amount', 'amt_to_credit')->get();
            $paidSum = 0;
            foreach ($amounts as $amount) {
                if ($amount) {
                    $paidSum = $paidSum + $amount->amount;
                   // $credit = $paidSum + $amount->amt_to_credit;
                }
            }
            return $paidSum;
        } catch (\Exception $ex) {
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Get total of the Invoices for a User.
     */
    public function getTotalInvoice($invoices)
    {
        $sum = 0;
        foreach ($invoices as $invoice) {
            $sum = $sum + $invoice->grand_total;
        }

        return $sum;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function edit($id)
    {
        try {
            $user = $this->user->where('id', $id)->first();
            $timezonesList = \App\Model\Common\Timezone::get();
            foreach ($timezonesList as $timezone) {
                $location = $timezone->location;
                if ($location) {
                    $start = strpos($location, '(');
                    $end = strpos($location, ')', $start + 1);
                    $length = $end - $start;
                    $result = substr($location, $start + 1, $length - 1);
                    $display[] = (['id'=>$timezone->id, 'name'=> '('.$result.')'.' '.$timezone->name]);
                }
            }
            //for display
            $timezones = array_column($display, 'name', 'id');
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($user->state);
            $managers = User::where('role', 'admin')
            ->where('position', 'manager')
            ->pluck('first_name', 'id')->toArray();
            $selectedCurrency = Currency::where('code', $user->currency)
            ->pluck('name', 'code')->toArray();
            $selectedCompany = \DB::table('company_types')->where('short', $user->company_type)
            ->pluck('name', 'short')->toArray();
            $selectedIndustry = \App\Model\Common\Bussiness::where('short', $user->bussiness)
            ->pluck('name', 'short')->toArray();
            $selectedCompanySize = \DB::table('company_sizes')->where('short', $user->company_size)
            ->pluck('name', 'short')->toArray();
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($user->country);

            $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();

            return view('themes.default1.user.client.edit',
                compact('bussinesses', 'user', 'timezones', 'state',
                    'states', 'managers', 'selectedCurrency', 'selectedCompany',
                     'selectedIndustry', 'selectedCompanySize'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function update($id, Request $request)
    {
        try {
            $user = $this->user->where('id', $id)->first();
            $symbol = Currency::where('code', $request->input('currency'))->pluck('symbol')->first();
            $user->currency_symbol = $symbol;
            $user->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('select');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $user = $this->user->where('id', $id)->first();
                if ($user) {
                    $user->delete();
                } else {
                    echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */
                    \Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                    //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                }
            }
            echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert')
                    .'!</b> './* @scrutinizer ignore-type */
                    \Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
        } else {
            echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '
                    ./* @scrutinizer ignore-type */\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.select-a-row').'
                </div>';
        }
    }

    public function getUsers(Request $request)
    {
        $options = $this->user
//->where('email','LIKE','%'.$s.'%')
                ->select('email AS text', 'id AS value')
                ->get();

        return response()->json(compact('options'));
    }

    public function search(Request $request)
    {
        try {
            $term = trim($request->q);
            if (empty($term)) {
                return \Response::json([]);
            }
            $users = User::where('email', 'LIKE', '%'.$term.'%')
             ->orWhere('first_name', 'LIKE', '%'.$term.'%')
             ->orWhere('last_name', 'LIKE', '%'.$term.'%')
             ->select('id', 'email', 'profile_pic', 'first_name', 'last_name')->get();
            $formatted_tags = [];

            foreach ($users as $user) {
                $formatted_users[] = ['id' => $user->id, 'text' => $user->email, 'profile_pic' => $user->profile_pic,
            'first_name'                   => $user->first_name, 'last_name' => $user->last_name, ];
            }

            return \Response::json($formatted_users);
        } catch (\Exception $e) {
            // returns if try fails with exception meaagse
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function advanceSearch($name = '', $username = '', $company = '',
     $mobile = '', $email = '', $country = '', $industry = '',
      $company_type = '', $company_size = '', $role = '', $position = '', $reg_from = '', $reg_till = '')
    {
        $join = \DB::table('users');
        $join = $this->getNamUserCom($join, $name, $username, $company);
        $join = $this->getMobEmCoun($join, $mobile, $email, $country);
        $join = $this->getInCtCs($join, $industry, $company_type, $company_size);
        $join = $this->getRolPos($join, $role, $position);
        $join = $this->getregFromTill($join, $reg_from, $reg_till);

        $join = $join->orderBy('created_at', 'desc')
        ->select('id', 'first_name', 'last_name', 'email', 'created_at',
         'active', 'mobile_verified', 'role', 'position');

        return $join;
    }

    public function sendWelcomeMail($user)
    {
        $activate_model = new AccountActivate();
        $str = str_random(40);
        $activate = $activate_model->create(['email' => $user->email, 'token' => $str]);
        $token = $activate->token;
        $url = url("activate/$token");
        //check in the settings
        $settings = new \App\Model\Common\Setting();
        $setting = $settings->where('id', 1)->first();
        //template
        $templates = new \App\Model\Common\Template();
        $temp_id = $setting->welcome_mail;
        $template = $templates->where('id', $temp_id)->first();
        $from = $setting->email;
        $to = $user->email;
        $subject = $template->name;
        $data = $template->data;
        $replace = ['name' => $user->first_name.' '.$user->last_name,
        'username'         => $user->email, 'password' => $str, 'url' => $url, ];
        $type = '';
        if ($template) {
            $type_id = $template->type;
            $temp_type = new \App\Model\Common\TemplateType();
            $type = $temp_type->where('id', $type_id)->first()->name;
        }
        //dd($type);
        $templateController = new \App\Http\Controllers\Common\TemplateController();
        $mail = $templateController->mailing($from, $to, $data, $subject, $replace, $type);

        return $mail;
    }
}
