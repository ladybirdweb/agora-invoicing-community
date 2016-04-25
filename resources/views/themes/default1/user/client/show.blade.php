@extends('themes.default1.layouts.master')
@section('content')
<div class="box-header with-border widget-user-2  bg-aqua widget-user-header padfull">

    <div class="col-md-8 col-sm-4 padzero">
        <div class="widget-user-image">

            <img class="img-circle" src="{{ $client->profile_pic }}" alt="User Avatar">

        </div>
        <h3 class="widget-user-username">{{ucfirst($client->first_name)}}  {{ucfirst($client->last_name)}}</h3>
        <h5 class="widget-user-desc">{{ucfirst($client->town)}}</h5>
        <h6 class="widget-user-desc">{{$client->email}}<br>{{$client->mobile}}</h6>
    </div>
    <div class="col-md-2 col-sm-4 padzero">
        <div class="padleft">
            <h6 class="rupee colorblack margintopzero"><span class="font18">Rs: {{$client->debit}}</span><br>Open</h6> 
            <h6 class="rupee colorred"><span class="font18">Rs: 0</span><br>Overdue</h6> 
        </div>
    </div>

    <div class="box-tools pull-right col-md-2 col-sm-4 padfull paddownfive">

        <!--                <a data-toggle="modal" data-target="#editdetail" class="btn btn-block btn-default btn-sm btn-flat ">
                            Edit 
                        </a>-->
        <a href="{{url('clients/'.$client->id.'/edit')}}" class="btn btn-block btn-default btn-sm btn-flat ">
            {{Lang::get('message.edit')}}
        </a>
        <div class="dropdown">

            <a class="btn btn-block btn-primary btn-sm btn-flat dropdown-toggle" data-toggle="dropdown" href="#">
                New Transation <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="{{url('invoice/generate?clientid='.$client->id)}}">{{Lang::get('message.invoice')}}</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="{{url('order/execute?clientid='.$client->id)}}">{{Lang::get('message.order_execute')}}</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="{{url('payment/receive?clientid='.$client->id)}}">{{Lang::get('message.payment')}}</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Estimate</a>
                </li>
                <li role="presentation" class="divider"></li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Sales recepit</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Credit Note</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Delayed Charge</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Time Activity</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Statement</a>
                </li>
            </ul>
        </div>


    </div>

</div>

@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success alert-dismissable">
    <i class="fa fa-ban"></i>
    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{Session::get('success')}}
</div>
@endif
<!-- fail message -->
@if(Session::has('fails'))
<div class="alert alert-danger alert-dismissable">
    <i class="fa fa-ban"></i>
    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{Session::get('fails')}}
</div>
@endif

<div class="margintop20">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#activity" data-toggle="tab">{{Lang::get('message.transation_detail')}}</a>
            </li>
            <li><a href="#settings" data-toggle="tab">{{Lang::get('message.customer_detail')}}</a>
            </li>
            <li><a href="#timeline" data-toggle="tab">{{Lang::get('message.payment_detail')}}</a>
            </li>
            <li><a href="#order" data-toggle="tab">{{Lang::get('message.order_detail')}}</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="active tab-pane" id="activity">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-12">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>{{Lang::get('message.date')}}</th>
                                        <th>{{Lang::get('message.invoice_number')}}</th>
                                        <th>{{Lang::get('message.total')}}</th>
                                        <th>{{Lang::get('message.due_balance')}}</th>
                                        <th>{{Lang::get('message.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice) 
                                    <tr>
                                        <td>
                                            {{$invoice->date}}
                                        </td>
                                        <td>
                                            <a href="{{url('invoices/show?invoiceid='.$invoice->id)}}">{{$invoice->number}}</a>
                                        </td>
                                        <td>
                                            {{$invoice->grand_total}}
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">{{Lang::get('message.action')}}</button>
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <?php
                                                $template_controller = new \App\Http\Controllers\Common\TemplateController();
                                                $user = $client;
                                                $invoiceItems = \App\Model\Order\InvoiceItem::where('invoice_id', $invoice->id)->get();
                                                $body = Form::open(['url' => 'order/execute', 'method' => 'GET']);
                                                $body .= "<input type=hidden value=$invoice->id name=invoiceid />";
                                                $body .= view('themes.default1.invoice.pdfinvoice', compact('invoice', 'user', 'invoiceItems'))->render();
                                                $model_popup = $template_controller->popup('Invoice', $body, 897, 'execute order', 'invoice' . $invoice->id);
                                                ?>
                                                <ul class="dropdown-menu" role="menu">
                                                    @if(empty($invoice->order()->get()))
                                                    <li><a href=# class=null  data-toggle='modal' data-target="#editinvoice{{$invoice->id}}">Execute order</a></li>
                                                    @endif
                                                    @if($invoice->payment()->first()->payment_status!='success')
                                                    <li><a href="{{url('payment/receive?invoiceid='.$invoice->id)}}">{{Lang::get('message.payment')}}</a></li>
                                                    @endif
                                                </ul>
                                                {!! $model_popup !!}

                                            </div>
                                        </td>

                                    </tr>
                                    @empty 
                                    <tr>
                                        <td>No Invoices</td>
                                    </tr>
                                    @endforelse


                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
            <div class="tab-pane" id="timeline">
                <div>
                    <div class="box-body">
                        <table id="example4" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Payment Method</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <?php $payment = \App\Model\Order\Payment::where('invoice_id', $order->invoice_id)->first(); ?>
                                <tr>
                                    <td>{{$order->created_at}}</td>
                                    <td>
                                        @if($payment)
                                        {{ucfirst($payment->payment_method)}}
                                        @else 
                                        {{Lang::get('message.no-payment')}}
                                        @endif
                                    </td>

                                    <td>{{$order->price_override}}</td>
                                    <td>Overdue</td>
                                    <td style="text-align: center">
                                        <div class="tools">

                                            <span data-toggle="modal" data-target="#myModaledit1">
                                                <a data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            </span>

                                            <span data-toggle="modal" data-target="#id">
                                                <a data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                                            </span>

                                            <span data-toggle="modal" data-target="#id">
                                                <a data-toggle="tooltip" data-placement="top" title="View" href="#"><i class="fa fa-eye"></i></a>
                                            </span>

                                        </div>

                                    </td>
                                </tr>
                                @empty 
                                <tr>
                                    <td>
                                        {{Lang::get('message.no-record')}}
                                    </td>
                                </tr>
                                @endforelse



                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.tab-pane -->

            <div class="tab-pane" id="settings">

                <div>
                    <div class="box box-widget widget-user">

                        <div>
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{$client->email}} </h5>
                                        <span class="description-text">{{Lang::get('message.email')}}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ucfirst($client->company)}}</h5>
                                        <span class="description-text">{{Lang::get('message.company')}}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">{{$client->website}}</h5>
                                        <span class="description-text">{{Lang::get('message.website')}}</span>
                                    </div>
                                    <!-- /.description-block -->

                                </div>
                                <!-- /.col -->

                            </div>
                            <!-- /.row -->
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">

                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.mobile')}} :</strong> <span class="pull-right">{{$client->mobile}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.address')}} :</strong> <span class="pull-right">{{ucfirst($client->address)}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.town')}} :</strong> <span class="pull-right">{{ucfirst($client->town)}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.state')}} :</strong> <span class="pull-right">{{ucfirst($client->state)}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.zip')}} :</strong> <span class="pull-right">{{$client->zip}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.role')}} :</strong> <span class="pull-right">{{ucfirst($client->role)}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box box-widget widget-user -->
                </div>
            </div>

            <!-- order !-->


            <div class="tab-pane" id="order">

                <div>
                    <div class="box box-widget widget-user">

                       
                    </div>
                    <!-- /.box box-widget widget-user -->
                </div>
            </div>


        </div>
    </div>
</div>
<div class='modal fade' id=editinvoice23>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title'>Invoice</h4>
            </div>
            <div class='modal-body'>
                body
            </div>
            <div class='modal-footer'>
                <button type=button id=close class='btn btn-default pull-left' data-dismiss=modal>Close</button>
                <input type=submit class='btn btn-primary' value=Save>
            </div>
        </div>
    </div>
</div>


@stop

@section('icheck')
<script>
    $(function () {


        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });


    });
</script>
@stop