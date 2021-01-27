<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Order\InstallationDetail;
use App\Model\Order\Invoice;
use App\Model\Order\Order;

class AdminOrderInvoiceController extends Controller
{
    public function getClientInvoice($id)
    {
        $client = $this->user->where('id', $id)->first();
        $invoices = Invoice::leftJoin('order_invoice_relations', 'invoices.id', '=', 'order_invoice_relations.invoice_id')
        ->select('invoices.id', 'invoices.user_id', 'invoices.date', 'invoices.number', 'invoices.grand_total', 'order_invoice_relations.order_id', 'invoices.is_renewed', 'invoices.status', 'invoices.currency')
        ->groupBy('invoices.number')
        ->where('invoices.user_id', '=', $id)
        ->orderBy('invoices.created_at', 'desc')
        ->get();

        return\DataTables::of($invoices)
                        ->addColumn('checkbox', function ($model) {
                            return "<input type='checkbox' class='invoice_checkbox' 
                            value=".$model->id.' name=select[] id=check>';
                        })
                        ->addColumn('date', function ($model) {
                            return getDateHtml($model->date);
                        })
                        ->addColumn('invoice_no', function ($model) {
                            $label = '<a href='.url('invoices/show?invoiceid='.$model->id).'>'.$model->number.'</a>';
                            if ($model->is_renewed) {
                                return $label.'&nbsp;'.getStatusLabel('renewed');
                            }

                            return $label;
                        })
                         ->addColumn('order_no', function ($model) {
                             if ($model->is_renewed) {
                                 $order = Order::find($model->order_id);
                                 if ($order) {
                                     return $order->first()->getOrderLink($model->order_id, 'orders');
                                 } else {
                                     return '--';
                                 }
                             } else {
                                 $allOrders = $model->order()->select('id', 'number')->get();
                                 $orderArray = '';
                                 foreach ($allOrders as $orders) {
                                     $orderArray .= $orders->getOrderLink($orders->id, 'orders');
                                 }

                                 return $orderArray;
                             }
                         })
                        ->addColumn('total', function ($model) {
                            return currencyFormat($model->grand_total, $code = $model->currency);
                        })
                         ->addColumn('paid', function ($model) {
                             $payment = \App\Model\Order\Payment::where('invoice_id', $model->id)->select('amount')->get();
                             $c = count($payment);
                             $sum = 0;

                             for ($i = 0; $i <= $c - 1; $i++) {
                                 $sum = $sum + $payment[$i]->amount;
                             }

                             return currencyFormat($sum, $code = $model->currency);
                         })
                         ->addColumn('balance', function ($model) {
                             $payment = \App\Model\Order\Payment::where('invoice_id', $model->id)->select('amount')->get();
                             $c = count($payment);
                             $sum = 0;

                             for ($i = 0; $i <= $c - 1; $i++) {
                                 $sum = $sum + $payment[$i]->amount;
                             }
                             $pendingAmount = ($model->grand_total) - ($sum);

                             return currencyFormat($pendingAmount, $code = $model->currency);
                         })
                          ->addColumn('status', function ($model) {
                              return getStatusLabel($model->status);
                          })
                        ->addColumn('action', function ($model) {
                            $action = '';
                            $cont = new \App\Http\Controllers\Order\TaxRatesAndCodeExpiryController();
                            $check = $cont->checkExecution($model->id);
                            if ($check == false) {
                                $action = '<p><form method="post" action='.url('order/execute?invoiceid='.$model->id).'>'.'<input type="hidden" name="_token" value='.\Session::token().'>'.'
                                    <button type="submit" style="margin-top:-10px;" class="btn btn-sm btn-secondary btn-xs"'.tooltip('Execute&nbsp;Order').'<i class="fa fa-tasks" style="color:white;"></i></button></form></p>';
                            }
                            $editAction = '<a href='.url('invoices/edit/'.$model->id)
                                ." class='btn btn-sm btn-secondary btn-xs'".tooltip('Edit')."
                                <i class='fa fa-edit' style='color:white;'>
                                 </i></a>";

                            return '<a href='.url('invoices/show?invoiceid='.$model->id)
                            ." class='btn btn-sm btn-secondary btn-xs' ".tooltip('View')."<i class='fa fa-eye' 
                            style='color:white;'></i></a>"
                                    ."   $editAction $action";
                        })
                         ->rawColumns(['checkbox', 'date', 'invoice_no', 'order_no', 'total', 'paid', 'balance', 'status', 'action'])
                        ->make(true);
    }

    public function getOrderDetail($id)
    {
        $order = Order::leftJoin('subscriptions', 'orders.id', '=', 'subscriptions.order_id')
            ->leftJoin('users', 'orders.client', '=', 'users.id')
            ->leftJoin('products', 'orders.product', '=', 'products.id')
            ->where('orders.client', '=', $id)
            ->select(
                'orders.id', 'orders.created_at', 'price_override', 'order_status', 'product', 'number', 'serial_key',
                'subscriptions.update_ends_at as subscription_ends_at', 'subscriptions.id as subscription_id', 'subscriptions.version as product_version', 'subscriptions.updated_at as subscription_updated_at', 'subscriptions.created_at as subscription_created_at',
                'products.name as product_name', \DB::raw("concat(first_name, ' ', last_name) as client_name"), 'client as client_id',
            );

        return\DataTables::of($order)
                        ->addColumn('checkbox', function ($model) {
                            return "<input type='checkbox' class='order_checkbox' 
                            value=".$model->id.' name=select[] id=checkorder>';
                        })
                        ->addColumn('date', function ($model) {
                            return getDateHtml($model->created_at);
                        })
                        ->addColumn('product', function ($model) {
                            return $model->product_name;
                        })
                        ->addColumn('number', function ($model) {
                            $orderLink = '<a href='.url('orders/'.$model->id).'>'.$model->number.'</a>';
                            if ($model->subscription_updated_at) {//For few older clients subscription was not generated, so no updated_at column exists
                                $orderLink = '<a href='.url('orders/'.$model->id).'>'.$model->number.'</a>'.installationStatusLabel($model->subscription_updated_at, $model->subscription_created_at);
                            }

                            return $orderLink;
                        })
                         ->addColumn('version', function ($model) {
                             $installedVersions = InstallationDetail::where('order_id', $model->id)->pluck('version')->toArray();
                             if (count($installedVersions)) {
                                 $latest = max($installedVersions);

                                 return getVersionAndLabel($latest, $model->product);
                             } else {
                                 return '--';
                             }
                         })
                          ->addColumn('expiry', function ($model) {
                              $ends_at = strtotime($model->subscription_ends_at) > 1 ? $model->subscription_ends_at : '--';

                              return getExpiryLabel($ends_at);
                          })
                         ->addColumn('status', function ($model) {
                             $status = $model->order_status;

                             return $status;
                         })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('orders/'.$model->id)." 
                            class='btn btn-sm btn-secondary btn-xs'".tooltip('View')."<i class='fa fa-eye' 
                            style='color:white;'> </i></a>";
                        })
                        ->rawColumns(['checkbox', 'date', 'product', 'number', 'version', 'expiry', 'status', 'action'])
                        ->make(true);
    }

    //Get Payment Details on Invoice Page
    public function getPaymentDetail($id)
    {
        $client = $this->user->where('id', $id)->first();
        $payments = $client->payment()->orderBy('created_at', 'desc')->get();
        $extraAmt = $this->getExtraAmtPaid($id);

        return\DataTables::of($payments)
                        ->addColumn('checkbox', function ($model) {
                            return "<input type='checkbox' class='payment_checkbox' 
                            value=".$model->id.' name=select[] id=checkpayment>';
                        })
                        ->addColumn('invoice_no', function ($model) {
                            return $model->invoice()->count() ? '<a href='.url('invoices/show?invoiceid='.$model->invoice()->first()->id).'>'.$model->invoice()->first()->number.'</a><br>' : '--';
                        })
                        ->addColumn('date', function ($model) {
                            return getDateHtml($model->created_at);
                        })
                        ->addColumn('payment_method', function ($model) {
                            return $model->payment_method;
                        })

                         ->addColumn('total', function ($model) use ($client, $extraAmt) {
                             if ($model->invoice_id == 0) {
                                 $amount = currencyFormat($extraAmt, $code = getCurrencyForClient($client->country));
                             } else {
                                 $currency = Invoice::find($model->invoice_id)->currency;
                                 $amount = currencyFormat($model->amount, $code = $currency);
                             }

                             return $amount;
                         })
                         ->addColumn('status', function ($model) {
                             return ucfirst($model->payment_status);
                         })

                         ->addColumn('action', function ($model) {
                             if ($model->invoice_id == 0) {
                                 return '<a href='.url('payments/'.$model->id.'/edit/')." class='btn btn-sm btn-secondary btn-xs' ".tooltip('Edit')." <i class='fa fa-edit' style='color:white;'> </i></a>";
                             } else {
                                 return '--';
                             }
                         })

                        ->rawColumns(['checkbox', 'invoice_no', 'date', 'payment_method', 'total', 'status', 'action'])

                        ->make(true);
    }
}
