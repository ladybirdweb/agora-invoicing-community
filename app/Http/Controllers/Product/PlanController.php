<?php

namespace App\Http\Controllers\Product;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product\Plan;
use App\Model\Product\Subscription;

class PlanController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
        $plan = new Plan();
        $this->plan = $plan;
        $subscription = new Subscription();
        $this->subscription = $subscription;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return view('themes.default1.product.plan.index');
    }
    
    /**
     * Get plans for chumper datatable
     */
    public function GetPlans() {

        //$user = new User;
        //$user = $this->user->where('role', 'user')->get();
        //dd($user);

        return \Datatable::collection($this->plan->get())
                        ->addColumn('#', function($model) {
                            return "<input type='checkbox' value=" . $model->id . " name=select[] id=check>";
                        })
                        ->showColumns('name', 'subscription','price','expiry')
                        
                        ->addColumn('action', function($model) {
                            return "<a href=" . url('plans/' . $model->id . '/edit') . " class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('name', 'subscription','price','expiry')
                        ->orderColumns('name', 'subscription','price','expiry')
                        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $subscription = $this->subscription->lists('name','id');
        return view('themes.default1.product.plan.create',  compact('subscription'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $this->plan->fill($request->input())->save();
        return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $plan = $this->plan->where('id', $id)->first();
        $subscription = $this->subscription->lists('name','id');
        return view('themes.default1.product.plan.edit', compact('plan','subscription'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request) {
        $plan = $this->plan->where('id', $id)->first();
        $plan->fill($request->input())->save();
        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request) {
        $ids = $request->input('select');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $plan = $this->plan->where('id', $id)->first();
                if ($plan) {
                    $plan->delete();
                } else {
                    echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.success') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.no-record') . "
                </div>";
                    //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                }
            }
            echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.success') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.deleted-successfully') . "
                </div>";
        } else {
            echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.success') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.select-a-row') . "
                </div>";
            //echo \Lang::get('message.select-a-row');
        }
    }

}
