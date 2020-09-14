<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Order\Invoice;
use App\Model\Order\Order;

class AdminOrderInvoiceController extends Controller
{
    public function getClientInvoice($id)
    {
        $client = $this->user->where('id', $id)->select('currency')->first();
        $invoices = Invoice::leftJoin('order_invoice_relations', 'invoices.id', '=', 'order_invoice_relations.invoice_id')
        ->select('invoices.id', 'invoices.user_id', 'invoices.date', 'invoices.number', 'invoices.grand_total', 'order_invoice_relations.order_id', 'invoices.is_renewed', 'invoices.status')
        ->groupBy('invoices.number')
        ->where('invoices.user_id', '=', $id)
        ->orderBy('invoices.created_at', 'desc')
        ->get();

        return\ DataTables::of($invoices)
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
                                 return Order::find($model->order_id)->first()->getOrderLink($model->order_id, 'orders');
                             } else {
                                 $allOrders = ($model->order()->select('id', 'number')->get());
                                 $orderArray = '';
                                 foreach ($allOrders as $orders) {
                                     $orderArray .= $orders->getOrderLink($orders->id, 'orders');
                                 }

                                 return $orderArray;
                             }
                         })
                        ->addColumn('total', function ($model) use ($client) {
                            return currencyFormat($model->grand_total, $code = $client->currency);
                        })
                         ->addColumn('paid', function ($model) use ($client) {
                             $payment = \App\Model\Order\Payment::where('invoice_id', $model->id)->select('amount')->get();
                             $c = count($payment);
                             $sum = 0;

                             for ($i = 0; $i <= $c - 1; $i++) {
                                 $sum = $sum + $payment[$i]->amount;
                             }

                             return currencyFormat($sum, $code = $client->currency);
                         })
                         ->addColumn('balance', function ($model) use ($client) {
                             $payment = \App\Model\Order\Payment::where('invoice_id', $model->id)->select('amount')->get();
                             $c = count($payment);
                             $sum = 0;

                             for ($i = 0; $i <= $c - 1; $i++) {
                                 $sum = $sum + $payment[$i]->amount;
                             }
                             $pendingAmount = ($model->grand_total) - ($sum);

                             return currencyFormat($pendingAmount, $code = $client->currency);
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
                                    <button type="submit" style="margin-top:-10px;" class="btn btn-sm btn-info btn-xs"'.tooltip('Execute&nbsp;Order').'<i class="fa fa-tasks" style="color:white;"></i></button></form></p>';
                            }
                            $editAction = '<a href='.url('invoices/edit/'.$model->id)
                                ." class='btn btn-sm btn-secondary btn-xs'".tooltip('Edit')."
                                <i class='fa fa-edit' style='color:white;'>
                                 </i></a>";

                            return '<a href='.url('invoices/show?invoiceid='.$model->id)
                            ." class='btn btn-sm btn-primary btn-xs' ".tooltip('View')."<i class='fa fa-eye' 
                            style='color:white;'></i></a>"
                                    ."   $editAction $action";
                        })
                         ->rawColumns(['checkbox', 'date', 'invoice_no', 'order_no', 'total', 'paid', 'balance', 'status', 'action'])
                        ->make(true);
    }

    public function getOrderDetail($id)
    {
        $client = $this->user->where('id', $id)->first();
        $order = $client->order()->orderBy('created_at', 'desc')->get();

        return\ DataTables::of($order)
                        ->addColumn('checkbox', function ($model) {
                            return "<input type='checkbox' class='order_checkbox' 
                            value=".$model->id.' name=select[] id=checkorder>';
                        })
                        ->addColumn('date', function ($model) {
                            return getDateHtml($model->created_at);
                        })
                        ->addColumn('product', function ($model) {
                            $productName = $model->product()->first() && $model->product()->first()->name ?
                            $model->product()->first()->name : 'Unknown';

                            return $productName;
                        })
                        ->addColumn('number', function ($model) {
                            $number = $model->number;

                            return $number;
                        })
                         ->addColumn('status', function ($model) {
                             $status = $model->order_status;

                             return $status;
                         })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('orders/'.$model->id)." 
                            class='btn btn-sm btn-primary btn-xs'".tooltip('View')."<i class='fa fa-eye' 
                            style='color:white;'> </i></a>";
                        })
                        ->rawColumns(['checkbox', 'date', 'product', 'number', 'total', 'status', 'action'])
                        ->make(true);
    }

    //Get Payment Details on Invoice Page
    public function getPaymentDetail($id)
    {
        $client = $this->user->where('id', $id)->first();
        $payments = $client->payment()->orderBy('created_at', 'desc')->get();
        $extraAmt = $this->getExtraAmtPaid($id);

        return\ DataTables::of($payments)
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
                                 $amount = currencyFormat($extraAmt, $code = $client->currency);
                             } else {
                                 $amount = currencyFormat($model->amount, $code = $client->currency);
                             }

                             return $amount;
                         })
                         ->addColumn('status', function ($model) {
                             return ucfirst($model->payment_status);
                         })

                         ->addColumn('action', function ($model) {
                             '<input type="hidden" class="paymentid" value="{{$model->id}}">';
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
