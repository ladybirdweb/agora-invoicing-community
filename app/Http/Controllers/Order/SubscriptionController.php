<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Product\Subscription;
use App\User;

class SubscriptionController extends Controller
{
    public $subscription;
    public $user;
    public $plan;
    public $order;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $subscription = new Subscription();
        $this->subscription = $subscription;

        $user = new User();
        $this->user = $user;

        $plan = new Plan();
        $this->plan = $plan;

        $order = new Order();
        $this->order = $order;
    }

    public function index()
    {
        try {
            return view('themes.default1.subscription.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getSubscription()
    {
        //dd($this->invoice->get());
        return \Datatable::collection($this->subscription->get())

                        ->addColumn('user_id', function ($model) {
                            $user = $model->user()->first();
                            $first = $user->first_name;
                            $last = $user->last_name;
                            $id = $user->id;

                            return '<a href='.url('clients/'.$id).'>'.ucfirst($first).' '.ucfirst($last).'</a>';
                        })
                        ->addColumn('plan_id', function ($model) {
                            $name = $this->plan->where('id', $model->plan_id)->first()->name;

                            return $name;
                        })
                        ->addColumn('order_id', function ($model) {
                            $name = $this->order->where('id', $model->order_id)->first()->id;

                            return $name;
                        })
                        ->showColumns('ends_at')
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('subscriptions/'.$model->id)." 
                            class='btn btn-sm btn-primary'>View</a>";
                        })
                        ->searchColumns('ends_at')
                        ->orderColumns('ends_at')
                        ->make();
    }

    public function show($id)
    {
    }
}
