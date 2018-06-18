<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Github\GithubApiController;
use App\Http\Requests\User\ProfileRequest;
use App\Model\Common\Timezone;
use App\Model\Github\Github;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Product\Product;
use App\Model\Product\ProductUpload;
use App\Model\Product\Subscription;
use App\User;
use Auth;
use Bugsnag;
use DateTime;
use DateTimeZone;
use Exception;
use GrahamCampbell\Markdown\Facades\Markdown;
use Hash;

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

        $product_upload = new ProductUpload();
        $this->product_upload = $product_upload;

        $product = new Product();
        $this->product = $product;

        $github_controller = new GithubApiController();
        $this->github_api = $github_controller;

        $model = new Github();
        $this->github = $model->firstOrFail();

        $this->client_id = $this->github->client_id;
        $this->client_secret = $this->github->client_secret;
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
            $invoices = Invoice::where('user_id', \Auth::user()->id)
                    ->select('number', 'created_at', 'grand_total', 'id', 'status');

            return \DataTables::of($invoices->get())
                            ->addColumn('number', function ($model) {
                                return $model->number;
                            })
                            ->addColumn('date', function ($model) {

                                // $timezone = Timezone::where('id',Auth::user()->timezone_id)->pluck('location')->first();
                                $date = $model->created_at;

                                return $date;
                                // $myobject->created_at->timezone($this->auth->user()->timezone);
                            })
                            // ->showColumns('created_at')
                            ->addColumn('total', function ($model) {
                                return $model->grand_total;
                            })
                            ->addColumn('Action', function ($model) {
                                $status = $model->status;
                                $payment = '';
                                if ($status == 'Pending' && $model->grand_total > 0) {
                                    $payment = '  <a href='.url('paynow/'.$model->id)." class='btn btn-primary btn-xs'>Pay Now</a>";
                                }

                                return '<p><a href='.url('my-invoice/'.$model->id)." class='btn btn-primary btn-xs'>View</a>".$payment.'</p>';
                            })
                            ->rawColumns(['number', 'created_at', 'total', 'Action'])
                            // ->orderColumns('number', 'created_at', 'total')
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    /**
     * Get list of all the versions from Filesystem.
     *
     * @param type $productid
     * @param type $clientid
     * @param type $invoiceid
     *
     * Get list of all the versions from Filesystem.
     * @param type $productid
     * @param type $clientid
     * @param type $invoiceid
     *
     * @return type
     */
    public function getVersionList($productid, $clientid, $invoiceid)
    {
        try {
            $versions = ProductUpload::where('product_id', $productid)->select('id', 'product_id', 'version', 'title', 'description', 'file', 'created_at')->get();

            return \DataTables::of($versions)
                            ->addColumn('id', function ($versions) {
                                return ucfirst($versions->id);
                            })
                            ->addColumn('version', function ($versions) {
                                return ucfirst($versions->version);
                            })
                            ->addColumn('title', function ($versions) {
                                return ucfirst($versions->title);
                            })
                            ->addColumn('description', function ($versions) {
                                return ucfirst($versions->description);
                            })
                            ->addColumn('file', function ($versions) use ($clientid, $invoiceid, $productid) {
                                // $clientid=\Auth::User()->id;
                                $endDate = Subscription::select('ends_at')->where('product_id', $productid)->first();

                                if ($versions->created_at->toDateTimeString() < $endDate->ends_at->toDateTimeString()) {
                                    return '<p><a href='.url('download/'.$productid.'/'.$clientid.'/'.$invoiceid.'/'.$versions->id)." class='btn btn-sm btn-primary'>Download</a>"
                                            .'&nbsp;

                                   </p>';
                                } else {
                                    return '<button class="btn btn-primary btn-sm disabled tooltip">Download <span class="tooltiptext">Please Renew!!</span></button>';
                                }
                            })
                            ->rawColumns(['version', 'title', 'description', 'file'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    /**
     * Get list of all the versions from Github.
     *
     * @param type $productid
     * @param type $clientid
     * @param type $invoiceid
     */
    public function getGithubVersionList($productid, $clientid, $invoiceid)
    {
        try {
            $products = $this->product::where('id', $productid)->select('name', 'version', 'github_owner', 'github_repository')->get();
            foreach ($products as $product) {
                $owner = $product->github_owner;
                $repo = $product->github_repository;
            }
            $url = "https://api.github.com/repos/$owner/$repo/releases";
            $link = $this->github_api->getCurl1($url);
            $link = $link['body'];
            $link = (array_slice($link, 0, 10, true));

            return \DataTables::of($link)
                            ->addColumn('version', function ($link) {
                                // dd($link['tag_name']);
                                return ucfirst($link['tag_name']);
                            })
                            ->addColumn('name', function ($link) {
                                return ucfirst($link['name']);
                            })
                            ->addColumn('description', function ($link) {
                                $markdown = Markdown::convertToHtml(ucfirst($link['body']));

                                return $markdown;
                            })
                            ->addColumn('file', function ($link) use ($clientid, $invoiceid, $productid) {
                                $order = Order::where('invoice_id', '=', $invoiceid)->first();
                                $order_id = $order->id;
                                $orderEndDate = Subscription::select('ends_at')->where('product_id', $productid)->where('order_id', $order_id)->first();
                                if ($orderEndDate) {
                                    if (strtotime($link['created_at']) < strtotime($orderEndDate->ends_at)) {
                                        $link = $this->github_api->getCurl1($link['zipball_url']);

                                        return '<p><a href='.$link['header']['Location']." class='btn btn-sm btn-primary'><i class='fa fa-download'></i>&nbsp;&nbsp;Download</a>"
                                                .'&nbsp;

                                   </p>';
                                    } else {
                                        return '<button class="btn btn-primary btn-sm disabled tooltip">Download <span class="tooltiptext">Please Renew!!</span></button>';
                                    }
                                } elseif (!$orderEndDate) {
                                    $link = $this->github_api->getCurl1($link['zipball_url']);

                                    return '<p><a href='.$link['header']['Location']." class='btn btn-sm btn-primary'>Download  </a>"
                                            .'&nbsp;

                                   </p>';
                                }
                            })
                            ->rawColumns(['version', 'name', 'description', 'file'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    public function orders()
    {
        try {
            return view('themes.default1.front.clients.order1');
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /*
     * Show all the orders for User
     */

    public function getOrders()
    {
        try {
            $orders = Order:: where('client', \Auth::user()->id);

            return \DataTables::of($orders->get())
                            ->addColumn('id', function ($model) {
                                return $model->id;
                            })
                            ->addColumn('product_name', function ($model) {
                                return $model->product()->first()->name;
                            })
                            ->addColumn('expiry', function ($model) {
                                $tz = \Auth::user()->timezone()->first()->name;
                                $end = '--';
                                if ($model->subscription()->first()) {
                                    if ($end != '0000-00-00 00:00:00' || $end != null) {
                                        $ends = new DateTime($model->subscription()->first()->ends_at);
                                        $tz = \Auth::user()->timezone()->first()->name;
                                        $ends->setTimezone(new DateTimeZone($tz));
                                        $date = $ends->format('M j, Y, g:i a ');
                                        $end = $date;
                                        // dd($end);
                                    }
                                }

                                return $end;
                            })

                            ->addColumn('Action', function ($model) {
                                $sub = $model->subscription()->first();
                                $order = Order::where('id', $model->id)->select('product')->first();
                                $productid = $order->product;
                                $order_cont = new \App\Http\Controllers\Order\OrderController();
                                $status = $order_cont->checkInvoiceStatusByOrderId($model->id);
                                $url = '';
                                if ($status == 'success') {
                                    if ($sub) {
                                        $url = $this->renewPopup($sub->id, $productid);
                                    }
                                    //$url = '<a href=' . url('renew/' . $sub->id) . " class='btn btn-sm btn-primary' title='Renew the order'>Renew</a>";
                                }
                                $productCheck = $model->product()->select('github_owner', 'github_repository')->where('id', $model->product)->first();
                                if (!$productCheck->github_owner == '' && !$productCheck->github_repository == '') {
                                    $listUrl = $this->downloadGithubPopup($model->client, $model->invoice()->first()->id, $productid);
                                } else {
                                    $listUrl = $this->downloadPopup($model->client, $model->invoice()->first()->number, $productid);
                                }

                                return '<a href='.url('my-order/'.$model->id)." class='btn  btn-primary btn-xs' style='margin-right:5px;'><i class='fa fa-eye' title='Details of order'></i> $listUrl $url </a>";
                            })
                            ->rawColumns(['id', 'created_at', 'ends_at', 'product', 'Action'])
                            // ->orderColumns('id', 'created_at', 'ends_at', 'product')
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    public function subscriptions()
    {
        try {
            return view('themes.default1.front.clients.subscription');
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

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
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    public function profile()
    {
        try {
            $user = $this->user->where('id', \Auth::user()->id)->first();
            //dd($user);
            $timezones = new \App\Model\Common\Timezone();
            $timezones = $timezones->pluck('name', 'id')->toArray();
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($user->state);
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($user->country);
            $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();

            return view('themes.default1.front.clients.profile', compact('user', 'timezones', 'state', 'states', 'bussinesses'));
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

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
            $response = ['type' => 'success',  'message' =>'Updated Successfully..'];

            return $response;
            // return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $e) {
            $result = [$ex->getMessage()];

            return response()->json(compact('result'), 500);
            Bugsnag::notifyException($e);
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
                $response = ['type'=>'success', 'message'=>'Password Updated Successfully'];

                return $response;
            } else {
                $response = ['type'=>'error', 'message'=>'Password Not Updated'];
            }
        } catch (\Exception $e) {
            $result = [$e->getMessage()];

            return response()->json(compact('result'), 500);
            Bugsnag::notifyException($e);
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
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getOrder($id)
    {
        try {
            $order = $this->order->findOrFail($id);
            if ($order) {
                $invoice = $order->invoice()->first();
                $items = $order->invoice()->first()->invoiceItem()->get();
                $subscription = '';
                $plan = '';
                if ($order->subscription) {
                    $subscription = $order->subscription;

                    $plan = $subscription->plan()->first();
                }
                $product = $order->product()->first();
                $price = $product->price()->first();
                //dd($price);
                $user = \Auth::user();

                return view('themes.default1.front.clients.show-order', compact('invoice', 'order', 'user', 'plan', 'product', 'subscription'));
            }

            throw new Exception('Sorry! We can not find your order');
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect('/')->with('fails', $ex->getMessage());
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

            $relation = $order->invoiceRelation()->pluck('invoice_id')->toArray();
            $invoices = $this->invoice
                    ->select('number', 'created_at', 'grand_total', 'id', 'status')
                    ->whereIn('id', $relation);
            if ($invoices->get()->count() == 0) {
                $invoices = $order->invoice()
                        ->select('number', 'created_at', 'grand_total', 'id', 'status');
            }

            return \DataTables::of($invoices->get())
                            ->addColumn('number', function ($model) {
                                return $model->number;
                            })
                            ->addColumn('products', function ($model) {
                                $invoice = $this->invoice->find($model->id);
                                $products = $invoice->invoiceItem()->pluck('product_name')->toArray();

                                return ucfirst(implode(',', $products));
                            })
                            ->addColumn('date', function ($model) {
                                $date = date_create($model->created_at);

                                return date_format($date, 'M j, Y, g:i a');
                            })
                            ->addColumn('total', function ($model) {
                                return $model->grand_total;
                            })
                            ->addColumn('status', function ($model) {
                                return ucfirst($model->status);
                            })
                            ->addColumn('action', function ($model) {
                                if (\Auth::user()->role == 'admin') {
                                    $url = '/invoices/show?invoiceid='.$model->id;
                                } else {
                                    $url = 'my-invoice';
                                }

                                return '<a href='.url($url.'/'.$model->id)." class='btn btn-sm btn-primary btn-xs'><i class='fa fa-eye' style='color:white;'> </i>&nbsp;&nbsp;View</a>";
                            })
                            ->rawColumns(['number', 'products', 'date', 'total', 'status', 'action'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPaymentByOrderId($orderid, $userid)
    {
        try {
            // dd($orderid);
            $order = $this->order->where('id', $orderid)->where('client', $userid)->first();
            // dd($order);
            $relation = $order->invoiceRelation()->pluck('invoice_id')->toArray();
            if (count($relation) > 0) {
                $invoices = $relation;
            } else {
                $invoices = $order->invoice()->pluck('id')->toArray();
            }
            $payments = $this->payment->whereIn('invoice_id', $invoices)
                    ->select('id', 'invoice_id', 'user_id', 'amount', 'payment_method', 'payment_status', 'created_at');
            //dd(\Input::all());
            return \DataTables::of($payments->get())
                            ->addColumn('checkbox', function ($model) {
                                if (\Input::get('client') != 'true') {
                                    return "<input type='checkbox' class='payment_checkbox' value=".$model->id.' name=select[] id=check>';
                                }
                            })
                            ->addColumn('number', function ($model) {
                                return $model->invoice()->first()->number;
                            })
                            ->addColumn('amount', 'payment_method', 'payment_status', 'created_at')
                            ->addColumn('total', function ($model) {
                                return $model->grand_total;
                            })
                            ->rawColumns(['checkbox', 'number', 'total', 'payment_method', 'payment_status', 'created_at'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPaymentByOrderIdClient($orderid, $userid)
    {
        try {
            $order = $this->order->where('id', $orderid)->where('client', $userid)->first();
            $relation = $order->invoiceRelation()->pluck('invoice_id')->toArray();
            if (count($relation) > 0) {
                $invoices = $relation;
            } else {
                $invoices = $order->invoice()->pluck('id')->toArray();
            }
            $payments = $this->payment->whereIn('invoice_id', $invoices)
                    ->select('id', 'invoice_id', 'user_id', 'payment_method', 'payment_status', 'created_at', 'amount');
            //dd(\Input::all());
            return \DataTables::of($payments->get())
                            ->addColumn('number', function ($model) {
                                return $model->invoice()->first()->number;
                            })
                              ->addColumn('total', function ($model) {
                                  return $model->amount;
                              })
                               ->addColumn('created_at', function ($model) {
                                   $date1 = new DateTime($model->created_at);
                                   $tz = \Auth::user()->timezone()->first()->name;
                                   $date1->setTimezone(new DateTimeZone($tz));
                                   $date = $date1->format('M j, Y, g:i a');

                                   return $date;
                               })

                            ->addColumn('payment_method', 'payment_status', 'created_at')

                            ->rawColumns(['number', 'total', 'payment_method', 'payment_status', 'created_at'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function renewPopup($id, $productid)
    {
        return view('themes.default1.renew.popup', compact('id', 'productid'));
    }

    public function downloadPopup($clientid, $invoiceid, $productid)
    {
        return view('themes.default1.front.clients.download-list', compact('clientid', 'invoiceid', 'productid'));
    }

    public function downloadGithubPopup($clientid, $invoiceid, $productid)
    {
        return view('themes.default1.front.clients.download-github-list', compact('clientid', 'invoiceid', 'productid'));
    }
}
