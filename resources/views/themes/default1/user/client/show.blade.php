@extends('themes.default1.layouts.master')
@section('content')
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
        <div id="error">
        </div>
        <div id="success">
        </div>
        <div id="fails">
        </div>
        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
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
<div class="box-header with-border widget-user-2  bg-aqua widget-user-header padfull">
    

    <div class="col-md-8 col-sm-4 padzero">
        <div class="widget-user-image">

            <img class="img-circle" src="{{ $client->profile_pic }}" alt="User Avatar">

        </div>
        <h3 class="widget-user-username">{{ucfirst($client->first_name)}}  {{ucfirst($client->last_name)}}</h3>
        <h5 class="widget-user-desc">{{ucfirst($client->town)}}</h5>
        <h6 class="widget-user-desc">{{$client->email}}<br>@if($client->mobile_code)<b>+</b>{{$client->mobile_code}}@endif{{$client->mobile}}</h6>
    </div>
<!--    <div class="col-md-2 col-sm-4 padzero">
        <div class="padleft">
            <h6 class="rupee colorblack margintopzero"><span class="font18">Rs: {{$client->debit}}</span><br>Open</h6> 
            <h6 class="rupee colorred"><span class="font18">Rs: 0</span><br>Overdue</h6> 
        </div>
    </div>-->

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
<!--                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="{{url('order/execute?clientid='.$client->id)}}">{{Lang::get('message.order_execute')}}</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="{{url('payment/receive?clientid='.$client->id)}}">{{Lang::get('message.payment')}}</a>
                </li>-->
<!--                <li role="presentation">
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
                </li>-->
            </ul>
        </div>


    </div>

</div>



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
                                        <th>{{Lang::get('message.status')}}</th>
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
                                            {{ucfirst($invoice->status)}}
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
                                                    @if($invoice->order()->get()->count()==0)
                                                    <li><a href=# class=null  data-toggle='modal' data-target="#editinvoice{{$invoice->id}}">Execute order</a></li>
                                                    @endif
                                                    
                                                    @if($invoice->status!='success')
                                                    <li><a href="{{url('payment/receive?invoiceid='.$invoice->id)}}">{{Lang::get('message.payment')}}</a></li>
                                                    @endif
                                                   
                                                     <li><a href="{{url('invoices/show?invoiceid='.$invoice->id)}}">View {{Lang::get('message.invoice')}}</a></li>
                                                     <li><a href="{{url('invoices/'.$invoice->id.'/delete')}}">{{Lang::get('message.delete')}}</a></li>
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
                                    <th>Invoice Number</th>
                                    <th>Date</th>
                                    <th>Payment Method</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client->payment()->orderBy('created_at','desc')->get() as $payment)
                                <tr>
                                    <td>
                                        @if($payment->invoice()->first())
                                        {{($payment->invoice()->first()->number)}}
                                        @endif
                                    </td>
                                    <td>{{$payment->created_at}}</td>
                                    <td>
                                        {{ucfirst($payment->payment_method)}}
                                    </td>

                                    <td>{{$payment->amount}}</td>
                                    <td>{{ucfirst($payment->payment_status)}}</td>
                                    <td><a href="{{url('payments/'.$payment->id.'/delete')}}" class="btn btn-danger">{{Lang::get('message.delete')}}</a></td>
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
                                        <h5 class="description-header">{{$client->user_name}}</h5>
                                        <span class="description-text">User Name</span>
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
                                            <strong>Business :</strong> <span class="pull-right">{{$client->bussiness()}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.mobile')}} :</strong> <span class="pull-right">@if($client->mobile_code)<b>+</b>{{$client->mobile_code}}@endif{{$client->mobile}}</span>
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
                                            <strong>{{Lang::get('message.state')}} :</strong> <span class="pull-right">
                                                @if(key_exists('name',\App\Http\Controllers\Front\CartController::getStateByCode($client->state)))
                                            {{\App\Http\Controllers\Front\CartController::getStateByCode($client->state)['name']}}
                                            @endif
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.country')}} :</strong> <span class="pull-right">
                                               
                                            {{\App\Http\Controllers\Front\CartController::getCountryByCode($client->country)}}
                                            
                                            </span>
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
                                     <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.currency')}} :</strong> <span class="pull-right">{{$client->currency}}</span>
                                        </a>
                                    </li>
                                     <li>
                                        <a href="#">
                                            <strong>Company Type :</strong> <span class="pull-right">{{str_replace('-',' ',ucfirst($client->company_type))}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>Company Size :</strong> <span class="pull-right">{{str_replace('-',' ',ucfirst($client->company_size))}}</span>
                                        </a>
                                    </li>
                                     <li>
                                        <a href="#">
                                            <strong>IP :</strong> <span class="pull-right">{{$client->ip}}</span>
                                        </a>
                                    </li>
                                    @if($client && $client->skype)
                                    <li>
                                        <a href="#">
                                            <strong>Skype :</strong> <span class="pull-right">{{$client->skype}}</span>
                                        </a>
                                    </li>
                                    @endif
                                    <?php $manager = $client->manager()->select('id','first_name','last_name')->first(); ?>
                                    @if($client && $manager)
                                    <li>
                                        <a href="{{url('clients/'.$manager->id)}}">
                                            <strong>Account Manager :</strong> <span class="pull-right">{{$manager->first_name}} {{$manager->last_name}}</span>
                                        </a>
                                    </li>
                                    @endif
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
                         <div class="box-body">
                        <table id="example4" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Number</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client->order()->orderBy('created_at','desc')->get() as $order)
                                <tr>
                                    <td>{{$order->created_at}}</td>
                                    <td>
                                        @if($order->product()->first() && $order->product()->first()->name) 
                                        {{$order->product()->first()->name}}
                                        @else 
                                        Unknown
                                        @endif
                                    </td>
                                    <td>{{$order->number}}</td>
                                    <td>{{$order->price_override}}</td>
                                    <td>{{$order->order_status}}</td>
                                    <td><a href="{{url('orders/'.$order->id)}}" class="btn btn-primary">View</a>
                                    <a href="{{url('orders/'.$order->id.'/delete')}}" class="btn btn-danger">{{Lang::get('message.delete')}}</a></td>
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