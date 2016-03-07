@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="box-header">

        <h4>{{Lang::get('message.invoice')}}
            <!--<a href="{{url('orders/create')}}" class="btn btn-primary pull-right   ">{{Lang::get('message.create')}}</a></h4>-->
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
    <div id="response"></div>

    <div class="box-body">
        <div class="row">

            <div class="col-md-12">

                <?php $set = App\Model\Common\Setting::where('id', '1')->first();?>
                <!-- Main content -->
                <section class="invoice">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> {{ucfirst($set->company)}}
                                <small class="pull-right">Date: {{$invoice->created_at}}</small>
                            </h2>
                        </div><!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            From
                            <address>

                                <strong>{{$set->company}}</strong><br>
                                {{$set->address}}<br>
                                Phone: {{$set->phone}}<br/>
                                Email: {{$set->email}}
                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                <strong>{{$user->first_name}} {{$user->last_name}}</strong><br>
                                {{$user->address}}<br/>
                                {{$user->town}}<br/>
                                {{$user->state}} {{$user->zip}}<br/>
                                Country : {{$user->country}}<br/>
                                Mobile: {{$user->mobile}}<br/>
                                Email : {{$user->email}}
                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Invoice   #{{$invoice->number}}</b><br/>
                            <br/>

                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Taxes</th>
                                        <th>Tax Rates</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($invoiceItems as $item)
                                    <tr>

                                        <td>{{$item->product_name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->regular_price}}</td>
                                        <td>
                                            <?php $taxes = explode(',', $item->tax_name); ?>
                                            <ul class="list-unstyled">
                                                @forelse($taxes as $tax)
                                                <li>{{$tax}}</li>
                                                @empty 
                                                <li>No Tax</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td>
                                            <?php $taxes = explode(',', $item->tax_percentage); ?>
                                            <ul class="list-unstyled">
                                                @forelse($taxes as $tax)
                                                <li>{{$tax}}</li>
                                                @empty 
                                                <li>No Tax Rates</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td>{{$item->subtotal}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">

                        </div><!-- /.col -->
                        <div class="col-xs-6">
                            <p class="lead">Amount</p>
                            <div class="table-responsive">
                                <table class="table">

                                    <tr>
                                        <th style="width:50%">Total:</th>
                                        <td>INR {{$invoice->grand_total}}</td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">	
                            <a href="{{url('')}}"><button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button></a>
                        </div>
                    </div>
                </section><!-- /.content -->


            </div>
        </div>
    </div>
</div>



@stop