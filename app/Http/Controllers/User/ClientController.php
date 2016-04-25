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

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $user = new User();
        $this->user = $user;
        $activate = new AccountActivate();
        $this->activate = $activate;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('themes.default1.user.client.index');
    }

    /**
     * Get Clients for chumper datatable.
     */
    public function GetClients()
    {

        //$user = new User;
        $user = $this->user->select('id', 'first_name', 'last_name', 'email', 'created_at', 'active')->orderBy('created_at', 'desc');
        //dd($user);

        return \Datatable::query($user)
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->addColumn('first_name', function ($model) {
                            return '<a href='.url('clients/'.$model->id).'>'.ucfirst($model->first_name).' '.ucfirst($model->last_name).'</a>';
                        })

                        ->showColumns('email', 'created_at')

                        ->addColumn('active', function ($model) {
                            if ($model->active == 1) {
                                return "<span style='color:green'>Activated</span>";
                            } else {
                                return "<span style='color:red'>Not activated</span>";
                            }
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('clients/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('email', 'first_name')
                        ->orderColumns('email', 'first_name', 'created_at')
                        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $timezones = new \App\Model\Common\Timezone();
        $timezones = $timezones->lists('name', 'id')->toArray();

        return view('themes.default1.user.client.create', compact('timezones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ClientRequest $request)
    {
        $user = $this->user;
        $str = str_random(6);
        $password = \Hash::make($str);
        $user->password = $password;
        $user->fill($request->input())->save();
        $token = str_random(40);
        $this->activate->create(['email' => $user->email, 'token' => $token]);

        \Mail::send('emails.welcome', ['token' => $token, 'email' => $user->email, 'pass' => $str], function ($message) use ($user) {
            $message->to($user->email, $user->first_name)->subject('Welcome!');
        });

        return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
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
            $invoices = $invoice->where('user_id', $id)->get();
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
            $timezones = $timezones->lists('name', 'id')->toArray();

            $state = \App\Http\Controllers\Front\CartController::getStateByCode($user->state);

            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($user->country);

            return view('themes.default1.user.client.edit', compact('user', 'timezones', 'state', 'states'));
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
}
