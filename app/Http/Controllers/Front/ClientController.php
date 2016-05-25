<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Product\Subscription;
use App\User;
use Exception;

class ClientController extends Controller
{
    public $user;
    public $invoice;
    public $order;
    public $subscription;
    public $payment;

    public function __construct()
    {
        $this->middleware('auth');

        $user = new User();
        $this->user = $user;

        $invoice = new Invoice();
        $this->invoice = $invoice;

        $order = new Order();
        $this->order = $order;

        $subscription = new Subscription();
        $this->subscription = $subscription;

        $payment = new Payment();
        $this->payment = $payment;
    }

    public function invoices()
    {
        try {
            return view('themes.default1.front.clients.invoice');
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getInvoices()
    {
        try {
            $invoices = $this->invoice
                    ->where('user_id', \Auth::user()->id)
                    ->select('number', 'created_at', 'grand_total', 'id', 'status');

            return \Datatable::query($invoices)
                        ->addColumn('number', function ($model) {
                            return $model->number;
                        })
                        ->showColumns('created_at')
                        ->addColumn('total', function ($model) {
                            return $model->grand_total;
                        })
                        ->addColumn('action', function ($model) {
                            $status = $model->status;
                            $payment = '';
                            if ($status == 'Pending') {
                                $payment = '  <a href='.url('paynow/'.$model->id)." class='btn btn-sm btn-primary'>Pay Now</a>";
                            }

                            return '<p><a href='.url('my-invoice/'.$model->id)." class='btn btn-sm btn-primary'>View</a>".$payment.'</p>';
                        })
                        ->searchColumns('number', 'created_at', 'total')
                        ->orderColumns('number', 'created_at', 'total')
                        ->make();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function orders()
    {
        try {
            return view('themes.default1.front.clients.order1');
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getOrders()
    {
        //        try{
           $orders = $this->order
                   ->where('client', \Auth::user()->id);
                   //->select('id','product','created_at')

            return \Datatable::query($orders)
                        ->addColumn('id', function ($model) {
                            return $model->id;
                        })

                        ->addColumn('product', function ($model) {
                            return $model->product()->first()->name;
                        })
                        ->showColumns('created_at')
                        ->addColumn('ends_at', function ($model) {
                            $end = $model->subscription()->first()->ends_at;
                            if ($end == '0000-00-00 00:00:00' || $end == null) {
                                $end = '--';
                            }

                            return $end;
                        })
                        ->addColumn('action', function ($model) {
                            //dd($model);
                            return '<a href='.url('my-order/'.$model->id)." class='btn btn-sm btn-primary'>Details</a>"
                                    .'  <a href='.url('my-invoice/'.$model->invoice()->first()->id)." class='btn btn-sm btn-primary'>Invoice</a>"
                                    .'   <a href='.url('download/'.$model->client.'/'.$model->invoice()->first()->number)." class='btn btn-sm btn-primary'>Download</a>";
                        })
                        ->searchColumns('id', 'created_at', 'ends_at', 'product')
                        ->orderColumns('id', 'created_at', 'ends_at', 'product')
                        ->make();
//        } catch (Exception $ex) {
//            echo $ex->getMessage();
//        }
    }

    public function subscriptions()
    {
        try {
            return view('themes.default1.front.clients.subscription');
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getSubscriptions()
    {
        try {
            $subscriptions = $this->subscription->where('user_id', \Auth::user()->id)->get();

            return \Datatable::collection($subscriptions)
                        ->addColumn('id', function ($model) {
                            return $model->id;
                        })
                        ->showColumns('created_at')

                        ->addColumn('ends_at', function ($model) {
                            return $model->subscription()->first()->ends_at;
                        })
                        ->searchColumns('id', 'created_at', 'ends_at')
                        ->orderColumns('created_at', 'ends_at')
                        ->make();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function profile()
    {
        try {
            $user = $this->user->where('id', \Auth::user()->id)->first();
            //dd($user);
            $timezones = new \App\Model\Common\Timezone();
            $timezones = $timezones->lists('name', 'id')->toArray();
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($user->state);
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($user->country);

            return view('themes.default1.front.clients.profile', compact('user', 'timezones', 'state', 'states'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postProfile(ProfileRequest $request)
    {
        try {
            $user = \Auth::user();
            if ($request->hasFile('profile_pic')) {
                $name = \Input::file('profile_pic')->getClientOriginalName();
                $destinationPath = 'dist/app/users';
                $fileName = rand(0000, 9999).'.'.$name;
                \Input::file('profile_pic')->move($destinationPath, $fileName);
                $user->profile_pic = $fileName;
            }
            $user->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function postPassword(ProfileRequest $request)
    {
        try {
            $user = \Auth::user();
            $oldpassword = $request->input('old_password');
            $currentpassword = $user->getAuthPassword();
            $newpassword = $request->input('new_password');
            if (\Hash::check($oldpassword, $currentpassword)) {
                $user->password = Hash::make($newpassword);
                $user->save();

                return redirect()->back()->with('success1', \Lang::get('message.updated-successfully'));
            } else {
                return redirect()->back()->with('fails1', \Lang::get('message.not-updated'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function getInvoice($id)
    {
        try {
            $invoice = $this->invoice->findOrFail($id);
            $items = $invoice->invoiceItem()->get();
            $user = \Auth::user();

            return view('themes.default1.front.clients.show-invoice', compact('invoice', 'items', 'user'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getOrder($id)
    {
        try {
            $order = $this->order->findOrFail($id);
            $invoice = $order->invoice()->first();
            $items = $order->invoice()->first()->invoiceItem()->get();
            $subscription = $order->subscription()->first();
            $plan = $subscription->plan()->first();
            $product = $order->product()->first();
            $price = $product->price()->first();
           //dd($price);
           $user = \Auth::user();

            return view('themes.default1.front.clients.show-order', compact('invoice', 'order', 'user', 'plan', 'product', 'subscription'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getSubscription($id)
    {
        try {
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getInvoicesByOrderId($orderid, $userid)
    {
        try {
            $order = $this->order->where('id', $orderid)->where('client', $userid)->first();
            $invoices = $order->invoice()
                    ->select('number', 'created_at', 'grand_total', 'id');

            return \Datatable::query($invoices)
                        ->addColumn('number', function ($model) {
                            return $model->number;
                        })
                        ->addColumn('invoice_item', function ($model) {
                            $products = $model->invoiceItem()->lists('product_name')->toArray();

                            return ucfirst(implode(',', $products));
                        })
                        ->showColumns('created_at')
                        ->addColumn('total', function ($model) {
                            return $model->grand_total;
                        })
                        ->addColumn('action', function ($model) {
                            if (\Auth::user()->role == 'admin') {
                                $url = '/invoices/show?invoiceid='.$model->id;
                            } else {
                                $url = 'my-invoice';
                            }

                            return '<a href='.url($url.'/'.$model->id)." class='btn btn-sm btn-primary'>View</a>";

                        })
                        ->searchColumns('number', 'created_at', 'grand_total')
                        ->orderColumns('number', 'created_at', 'grand_total')
                        ->make();
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPaymentByOrderId($orderid, $userid)
    {
        try {
            $order = $this->order->where('id', $orderid)->where('client', $userid)->first();
            $invoices = $order->invoice()->lists('id')->toArray();
            $payments = $this->payment->whereIn('invoice_id', $invoices)
                    ->select('id', 'invoice_id', 'user_id', 'amount', 'payment_method', 'payment_status', 'created_at');

            return \Datatable::query($payments)
                        ->addColumn('number', function ($model) {
                            return $model->invoice()->first()->number;
                        })
                        ->showColumns('amount', 'payment_method', 'payment_status')
                        ->addColumn('total', function ($model) {
                            return $model->grand_total;
                        })

                        ->searchColumns('amount', 'payment_method', 'payment_status')
                        ->orderColumns('amount', 'payment_method', 'payment_status')
                        ->make();
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
