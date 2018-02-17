<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ClientRequest;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\User\AccountActivate;
use App\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
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
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
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

        $count_users = $this->user->get()->count();
        $pro_editions = $this->soldEdition('Faveo Helpdesk Pro');
        $community = $this->soldEdition('faveo helpdesk community');
        $product_count = $this->productCount();

        return view('themes.default1.user.client.index', compact('name', 'username', 'company', 'mobile', 'email', 'country', 'count_users', 'pro_editions', 'community', 'product_count', 'industry', 'company_type', 'company_size'));
    }

    /**
     * Get Clients for chumper datatable.
     */
    public function GetClients(Request $request)
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

        $user = $this->advanceSearch($name, $username, $company, $mobile, $email, $country, $industry, $company_type, $company_size);

        return\ DataTables::of($user->get())

                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->addColumn('first_name', function ($model) {
                            return '<a href='.url('clients/'.$model->id).'>'.ucfirst($model->first_name).' '.ucfirst($model->last_name).'</a>';
                        })
                         ->addColumn('email', function ($model) {
                             return $model->email;
                         })
                          ->addColumn('created_at', function ($model) {
                              $ends = $model->created_at;
                              if ($ends) {
                                  $date = date_create($ends);
                                  $end = date_format($date, 'l, F j, Y H:m A');
                              }

                              return $end;

                              //   return $model->created_at;
                          })
                        // ->showColumns('email', 'created_at')
                        ->addColumn('active', function ($model) {
                            if ($model->active == 1) {
                                $email = "<span class='glyphicon glyphicon-envelope' style='color:green' title='verified email'></span>";
                            } else {
                                $email = "<span class='glyphicon glyphicon-envelope' style='color:red' title='unverified email'></span>";
                            }
                            if ($model->mobile_verified == 1) {
                                $mobile = "<span class='glyphicon glyphicon-phone' style='color:green' title='verified mobile'></span>";
                            } else {
                                $mobile = "<span class='glyphicon glyphicon-phone' style='color:red' title='unverified mobile'></span>";
                            }

                            return $email.'&nbsp;&nbsp;'.$mobile;
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('clients/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>"
                                    .'  <a href='.url('clients/'.$model->id)." class='btn btn-sm btn-primary'>View</a>";
                            // return 'hhhh';
                        })
                        ->rawColumns(['first_name', 'email',  'created_at', 'active', 'action'])
                        ->make(true);

        // ->searchColumns('email', 'first_name')
                        // ->orderColumns('email', 'first_name', 'created_at')
                        // ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $timezones = new \App\Model\Common\Timezone();
        $timezones = $timezones->pluck('name', 'id')->toArray();
        $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();
        $managers = User::where('role', 'admin')->where('position', 'manager')->pluck('first_name', 'id')->toArray();

        return view('themes.default1.user.client.create', compact('timezones', 'bussinesses', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ClientRequest $request)
    {
        try {
            $user = $this->user;
            $str = str_random(6);
            $password = \Hash::make($str);
            $user->password = $password;
            $user->fill($request->input())->save();
            $this->sendWelcomeMail($user);

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Swift_TransportException $e) {
            return redirect()->back()->with('warning', 'User has created successfully But email configuration has some problem!');
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            $invoice = new Invoice();
            $order = new Order();
            $invoices = $invoice->where('user_id', $id)->orderBy('created_at', 'desc')->get();
            $client = $this->user->where('id', $id)->first();
            $orders = $order->where('client', $id)->get();
            //dd($client);

            return view('themes.default1.user.client.show', compact('client', 'invoices', 'model_popup', 'orders'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $user = $this->user->where('id', $id)->first();
            $timezones = new \App\Model\Common\Timezone();
            $timezones = $timezones->pluck('name', 'id')->toArray();

            $state = \App\Http\Controllers\Front\CartController::getStateByCode($user->state);
            $managers = User::where('role', 'admin')->where('position', 'manager')->pluck('first_name', 'id')->toArray();

            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($user->country);

            $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();

            return view('themes.default1.user.client.edit', compact('bussinesses', 'user', 'timezones', 'state', 'states', 'managers'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, ClientRequest $request)
    {
        $user = $this->user->where('id', $id)->first();
        $user->fill($request->input())->save();

        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
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
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.no-record').'
                </div>';
                    //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                }
            }
            echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.deleted-successfully').'
                </div>';
        } else {
            echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.select-a-row').'
                </div>';
            //echo \Lang::get('message.select-a-row');
        }
    }

    public function getUsers(Request $request)
    {
        //dd($request->all());
        //$s = $request->input('mask');
        $options = $this->user
//->where('email','LIKE','%'.$s.'%')
                ->select('email AS text', 'id AS value')
                ->get();

        return response()->json(compact('options'));
    }

    public function advanceSearch($name = '', $username = '', $company = '', $mobile = '', $email = '', $country = '', $industry = '', $company_type = '', $company_size = '')
    {
        $join = $this->user;

        if ($name) {
            $join = $join->where('first_name', 'LIKE', '%'.$name.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$name.'%');
        }
        if ($username) {
            $join = $join->where('user_name', 'LIKE', '%'.$username.'%');
        }
        if ($company) {
            $join = $join->where('company', 'LIKE', '%'.$company.'%');
        }
        if ($mobile) {
            $join = $join->where('mobile', $mobile);
        }
        if ($email) {
            $join = $join->where('email', 'LIKE', '%'.$email.'%');
        }
        if ($country) {
            $join = $join->where('country', $country);
        }
        if ($industry) {
            $join = $join->where('bussiness', $industry);
        }
        if ($company_type) {
            $join = $join->where('company_type', $company_type);
        }
        if ($company_size) {
            $join = $join->where('company_size', $company_size);
        }

        $join = $join->orderBy('created_at', 'desc')->select('id', 'first_name', 'last_name', 'email', 'created_at', 'active', 'mobile_verified');

        return $join;
    }

    public function soldEdition($name)
    {
        $invoice = new \App\Model\Order\InvoiceItem();
        $product_in_invoice = $invoice->where('product_name', $name)->distinct()->pluck('invoice_id');
        $order = new Order();
        $orders = $order->whereIn('invoice_id', $product_in_invoice)->get()->count();

        return $orders;
    }

    public function productCount()
    {
        $products = $this->product->get()->count();

        return $products;
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
        $replace = ['name' => $user->first_name.' '.$user->last_name, 'username' => $user->email, 'password' => $str, 'url' => $url];
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
