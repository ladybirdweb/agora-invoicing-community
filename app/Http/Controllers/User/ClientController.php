<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\License\LicenseController;
use App\Http\Requests\User\ClientRequest;
use App\Model\Common\Country;
use App\Model\Common\StatusSetting;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Payment\Currency;
use App\Model\User\AccountActivate;
use App\Traits\PaymentsAndInvoices;
use App\User;
use Bugsnag;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

class ClientController extends AdvanceSearchController
{
    use PaymentsAndInvoices;

    public $user;
    public $activate;
    public $product;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
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
        $clientForSalesMan = $request->input('sales_man');
        $clientForAccMan = $request->input('actmanager');
        $clientForSalesMan = $request->input('salesmanager');
        return view(
            'themes.default1.user.client.index',
            compact(
                'name',
                'username',
                'company',
                'mobile',
                'email',
                'country',
                'industry',
                'company_type',
                'company_size',
                'role',
                'position',
                'reg_from',
                'reg_till',
                'clientForSalesMan',
                'clientForAccMan'
            )
        );
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
        $acc_manager = $request->input('actmanager');
        $sales_manager = $request->input('salesmanager');
        $user = $this->advanceSearch(
            $name,
            $username,
            $company,
            $mobile,
            $email,
            $country,
            $industry,
            $company_type,
            $company_size,
            $role,
            $position,
            $reg_from,
            $reg_till,
            $acc_manager,
            $sales_manager
        );
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
        $accountManager = User::where('role', 'admin')->where('position', 'account_manager')
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

        return view('themes.default1.user.client.create', compact('timezones', 'bussinesses', 'managers', 'accountManager'));
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
            if ($request->input('mobile_code') == '') {
                $country = new Country();
                $mobile_code = $country->where('country_code_char2', $request->input('country'))->pluck('phonecode')->first();
            } else {
                $mobile_code = str_replace('+', '', $request->input('mobile_code'));
            }
            $cont = new \App\Http\Controllers\Front\PageController();
            $currency_symbol = Currency::where('code', $request->input('currency'))->pluck('symbol')->first();
            $location = $cont->getLocation();
            $user->user_name = $request->input('user_name');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->password = $password;
            $user->company = $request->input('company');
            $user->bussiness = $request->input('bussiness');
            $user->active = $request->input('active');
            $user->mobile_verified = $request->input('mobile_verified');
            $user->role = $request->input('role');
            $user->position = $request->input('position');
            $user->company_type = $request->input('company_type');
            $user->company_size = $request->input('company_size');
            $user->address = $request->input('address');
            $user->town = $request->input('town');
            $user->country = $request->input('country');
            $user->state = $request->input('state');
            $user->zip = $request->input('zip');
            $user->timezone_id = $request->input('timezone_id');
            $user->currency = $request->input('currency');
            $user->mobile_code = $mobile_code;
            $user->mobile = $request->input('mobile');
            $user->skype = $request->input('skype');
            $user->manager = $request->input('manager');
            $user->account_manager = $request->input('account_manager');
            $user->currency_symbol = $currency_symbol;
            $user->ip = $location['ip'];

            $user->save();
            $this->sendWelcomeMail($user);
            $mailchimpStatus = StatusSetting::first()->value('mailchimp_status');
            if ($mailchimpStatus == 1) {
                $mailchimp = new \App\Http\Controllers\Common\MailChimpController();
                $r = $mailchimp->addSubscriber($user->email);
            }

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
            $is2faEnabled = $client->is_2fa_enabled;
            // $client = "";
            $currency = $client->currency;
            $orders = $order->where('client', $id)->get();
            $comments = $client->comments()->where('user_id', $client->id)->get();

            return view(
                'themes.default1.user.client.show',
                compact('id','client','invoices','orders','invoiceSum','amountReceived','pendingAmount','currency','extraAmt','comments',
                    'is2faEnabled')
            );
        } catch (\Exception $ex) {
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
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
            $acc_managers = User::where('role', 'admin')
            ->where('position', 'account_manager')
            ->pluck('first_name', 'id')->toArray();
            $selectedCurrency = Currency::where('code', $user->currency)
            ->pluck('name', 'code')->toArray();
            $selectedCompany = \DB::table('company_types')->where('name', $user->company_type)
            ->pluck('name', 'short')->toArray();
            $selectedIndustry = \App\Model\Common\Bussiness::where('name', $user->bussiness)
            ->pluck('name', 'short')->toArray();
            $selectedCompanySize = \DB::table('company_sizes')->where('short', $user->company_size)
            ->pluck('name', 'short')->toArray();
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($user->country);

            $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();

            return view(
                'themes.default1.user.client.edit',
                compact(
                    'bussinesses',
                    'user',
                    'timezones',
                    'state',
                    'states',
                    'managers',
                    'selectedCurrency',
                    'selectedCompany',
                    'selectedIndustry',
                    'selectedCompanySize',
                    'acc_managers'
                )
            );
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
            // \Session::put('test', 1000);
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
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $user = $this->user->where('id', $id)->first();
                    //Check if this admin  is account manager and is assigned as account manager to other clients
                    $isAccountManager = User::where('account_manager', $id)->get();
                    $isSalesManager = User::where('manager', $id)->get();
                    if (count($isSalesManager) > 0) {
                        throw new \Exception('Admin'.' '.$user->first_name.' '.$user->last_name.' '.'cannot be deleted as he/she is existing sales manager for certain clients. Please replace Sales Manager from settings and then try deleting.');
                    }
                    if (count($isAccountManager) > 0) {
                        throw new \Exception('Admin'.' '.$user->first_name.' '.$user->last_name.' '.'cannot be deleted as he/she is existing account manager for certain clients. Please replace Account Manager from settings and then try deleting.');
                    }
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
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
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
