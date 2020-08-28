<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\License\LicensePermissionsController;
use App\Http\Requests\User\ProfileRequest;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Product\Product;
use Bugsnag;
use Exception;
use Hash;

class BaseClientController extends Controller
{
    /**
     * Get the version list popup for the Product.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-06
     *
     * @param Order $orders    Order For the Client
     * @param int   $productid Product id for the Order
     *
     * @return array Show Modal Popup if Condition Satisfies
     */
    public function getPopup($query, int $productid)
    {
        $listUrl = '';
        $permissions = LicensePermissionsController::getPermissionsForProduct($productid);
        if ($permissions['downloadPermission'] == 1) { //If the Product has doownlaod permission
            if ($query->github_owner && $query->github_repository) {
                $listUrl = $this->downloadGithubPopup($query->client, $orders->invoice_id, $productid);
            } else {
                $listUrl = $this->downloadPopup($query->client, $query->invoice_number, $productid);
            }
        }

        return $listUrl;
    }

    public function downloadPopup($clientid, $invoiceid, $productid)
    {
        return view('themes.default1.front.clients.download-list',
            compact('clientid', 'invoiceid', 'productid'));
    }

    public function downloadGithubPopup($clientid, $invoiceid, $productid)
    {
        return view('themes.default1.front.clients.download-github-list',
            compact('clientid', 'invoiceid', 'productid'));
    }

    public function renewPopup($id, $productid)
    {
        return view('themes.default1.renew.popup', compact('id', 'productid'));
    }

    public function getActionButton($countExpiry, $countVersions, $link, $orderEndDate, $productid)
    {
        $downloadPermission = LicensePermissionsController::getPermissionsForProduct($productid);
        if ($downloadPermission['allowDownloadTillExpiry'] == 1) {
            if (strtotime($link['created_at']) < strtotime($orderEndDate->update_ends_at)) {
                $githubApi = new \App\Http\Controllers\Github\GithubApiController();

                $link = $githubApi->getCurl1($link['zipball_url']);

                return '<p><a href='.$link['header']['Location']." 
            class='btn btn-sm btn-primary'><i class='fa fa-download'>
            </i>&nbsp;&nbsp;Download</a>".'&nbsp;

      </p>';
            } else {
                return '<button class="btn btn-primary btn-sm disabled tooltip">
            Download <span class="tooltiptext">Please Renew!!</span></button>';
            }
        } elseif ($downloadPermission['allowDownloadTillExpiry'] == 0) {
            if ($countExpiry == $countVersions) {
                $githubApi = new \App\Http\Controllers\Github\GithubApiController();
                $link = $githubApi->getCurl1($link['zipball_url']);

                return '<p><a href='.$link['header']['Location']." 
            class='btn btn-sm btn-primary'><i class='fa fa-download'>
            </i>&nbsp;&nbsp;Download</a>".'&nbsp;

      </p>';
            } else {
                return '<button class="btn btn-primary btn-sm disabled tooltip">
            Download <span class="tooltiptext">Please Renew!!</span></button>';
            }
        }
    }

    /**
     * Update Profile.
     */
    public function postProfile(ProfileRequest $request)
    {
        try {
            $user = \Auth::user();
            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $name = \Request::file('profile_pic')->getClientOriginalName();
                $destinationPath = public_path('common/images/users');
                $fileName = rand(0000, 9999).'.'.$name;
                $file->move($destinationPath, $fileName);
                $user->profile_pic = $fileName;
            }
            $user->first_name = strip_tags($request->input('first_name'));
            $user->last_name = strip_tags($request->input('last_name'));
            $user->email = strip_tags($request->input('email'));
            $user->company = strip_tags($request->input('company'));
            $user->mobile_code = strip_tags($request->input('mobile_code'));
            $user->mobile = strip_tags($request->input('mobile'));
            $user->address = strip_tags($request->input('address'));
            $user->town = strip_tags($request->input('town'));
            $user->timezone_id = strip_tags($request->input('timezone_id'));
            $user->country = ($request->input('country'));
            $user->state = ($request->input('state'));
            $user->zip = strip_tags($request->input('zip'));
            $user->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Update Password.
     */
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

                return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));

                return $response;
            } else {
                return redirect()->back()->with('fails', 'Incorrect old password');
            }
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            $result = [$e->getMessage()];
            Bugsnag::notifyException($result);

            return response()->json(compact('result'), 500);
        }
    }

    public function getInvoicesByOrderId($orderid, $userid)
    {
        try {
            $order = Order::where('id', $orderid)->where('client', $userid)->first();

            $relation = $order->invoiceRelation()->pluck('invoice_id')->toArray();
            $invoice = new Invoice();
            $invoices = $invoice
                    ->select('number', 'created_at', 'grand_total', 'currency', 'id', 'status')
                    ->whereIn('id', $relation);
            if ($invoices->get()->count() == 0) {
                $invoices = $order->invoice()
                        ->select('number', 'created_at', 'grand_total', 'id', 'status');
            }

            return \DataTables::of($invoices->get())
             ->addColumn('number', function ($model) {
                 $url = $this->getInvoiceLinkUrl($model->id);

                 return '<a href='.url($url).'>'.$model->number.'</a>';
             })
            ->addColumn('products', function ($model) {
                $invoice = $this->invoice->find($model->id);
                $products = $invoice->invoiceItem()->pluck('product_name')->toArray();

                return ucfirst(implode(',', $products));
            })
            ->addColumn('date', function ($model) {
                return getDateHtml($model->created_at);
            })
            ->addColumn('total', function ($model) {
                return currencyFormat($model->grand_total, $code = $model->currency);
            })
            ->addColumn('status', function ($model) {
                if (\Auth::user()->role == 'admin') {
                    return getStatusLabel($model->status);
                }

                return getStatusLabel($model->status, 'badge');
            })
            ->addColumn('action', function ($model) {
                $url = $this->getInvoiceLinkUrl($model->id);

                return '<a href='.url($url)." 
                class='btn btn-sm btn-primary btn-xs'".tooltip('View')."<i class='fa fa-eye' 
                style='color:white;'> </i></a>";
            })
                            ->rawColumns(['number', 'products', 'date', 'total', 'status', 'action'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getInvoiceLinkUrl($invoiceId)
    {
        $link = 'my-invoice/'.$invoiceId;
        if (\Auth::user()->role == 'admin') {
            $link = '/invoices/show?invoiceid='.$invoiceId;
        }

        return $link;
    }

    public function getInvoice($id)
    {
        try {
            $invoice = $this->invoice->findOrFail($id);
            $user = \Auth::user();
            if ($invoice->user_id != $user->id) {
                throw new \Exception('Cannot view invoice. Invalid modification of data.');
            }
            $items = $invoice->invoiceItem()->get();
            $orderId = $invoice->orderRelation()->value('order_id');

            $order = Order::getOrderLink($orderId, 'my-order');
            $currency = CartController::currency($user->id);
            $symbol = $currency['symbol'];

            return view('themes.default1.front.clients.show-invoice', compact('invoice', 'items', 'user', 'currency', 'symbol', 'order'));
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
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

    public function whenDownloadTillExpiry($updateEndDate, $productid, $versions, $clientid, $invoiceid)
    {
        if ($versions->created_at->toDateTimeString()
        < $updateEndDate->update_ends_at) {
            return '<p><a href='.url('download/'.$productid.'/'
            .$clientid.'/'.$invoiceid.'/'.$versions->id).
            " class='btn btn-sm btn-primary'><i class='fa fa-download'>
            </i>&nbsp;&nbsp;Download</a>".'&nbsp;

       </p>';
        } else {
            return '<button class="btn btn-danger 
        btn-sm disabled">Please Renew </button>';
        }
    }

    public function whenDownloadExpiresAfterExpiry($countExpiry, $countVersions, $updatesEndDate, $productid, $versions, $clientid, $invoiceid)
    {
        if ($countExpiry == $countVersions) {
            return '<p><a href='.url('download/'.$productid.'/'
            .$clientid.'/'.$invoiceid.'/'.$versions->id).
            " class='btn btn-sm btn-primary'><i class='fa fa-download'>
            </i>&nbsp;&nbsp;Download</a>".'&nbsp;

       </p>';
        } else {
            return '<button class="btn btn-danger 
        btn-sm disabled">Please Renew </button>';
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
}
