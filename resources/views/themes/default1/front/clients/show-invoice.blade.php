@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Agora | Invoice
@stop
@section('nav-invoice')
active
@stop

@section('content')
<div class="featured-boxes">
    <div class="row">
        <?php $set = App\Model\Common\Setting::where('id', '1')->first(); ?>
        <div class="featured-box featured-box-primary align-left mt-xlg">
            <div class="box-content">
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            Invoice
                            <small>#{{$invoice->number}}</small>
                        </h1>

                    </section>



                    <!-- Main content -->
                    <section class="invoice">
                        <!-- title row -->

                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                From
                                <address>
                                    <strong>{{$set->company}}</strong><br>
                                    {{$set->address}}
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
                                    @if(key_exists('name',App\Http\Controllers\Front\CartController::getStateByCode($user->state)))
                                    {{App\Http\Controllers\Front\CartController::getStateByCode($user->state)['name']}}
                                    @endif
                                    {{$user->zip}}<br/>
                                    Country : {{App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}<br/>
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
                                        @foreach($items as $item)
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
                                            <td><small>{!! $invoice->currency !!}</small> {{$invoice->grand_total}}</td>
                                        </tr>

                                    </table>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-xs-12">	
                                <a href="{{url('pdf?invoiceid='.$invoice->id)}}"><button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button></a>
                            </div>
                        </div>
                    </section><!-- /.content -->

                </div><!-- /.content-wrapper -->

                <div class="control-sidebar-bg"></div>
            </div><!-- ./wrapper -->
        </div> 
    </div>
</div>

@stop