@extends('themes.default1.layouts.front.master')
@section('title')
Checkout
@stop
@section('page-header')
Checkout
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Checkout</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
<?php

    $symbol = $invoice->currency;

?>
<div class="row">

    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        Review & Payment
                    </a>
                </h4>
            </div>


            <div class="panel-body">

                @if(Session::has('success'))
                <div class="alert alert-success alert-dismissable">
                    {{Lang::get('message.success')}}.
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!!Session::get('success')!!}
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
                <div>
                    <table class="shop_table cart">
                        <thead>
                            <tr>
                                <th class="product-thumbnail">
                                    &nbsp;
                                </th>

                                <th class="product-name">
                                    Product
                                </th>
                                <th class="product-quantity">
                                    Version
                                </th>

                                <th class="product-quantity">
                                    Quantity
                                </th>
                                <th class="product-name">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @forelse($items as $item)
                            
                            <tr class="cart_table_item">

                                <td class="product-thumbnail">
                                    
                                    <img width="100" height="100" alt="" class="img-responsive" src="{{$product->image}}">

                                </td>

                                <td class="product-name">
                                    {{$item->product_name}}
                                </td>

                                <td class="product-quantity">
                                    @if($product->version)
                                    {{$product->version}}
                                    @else 
                                    Not available
                                    @endif
                                </td>

                                <td class="product-quantity">
                                    {{$item->quantity}}
                                </td>
                                <td class="product-name">
                                    <span class="amount"><small>{!! $symbol !!} </small> {{$item->regular_price}}</span>
                                </td>
                            </tr>
                            @empty 
                        <p>Your Cart is void</p>
                        @endforelse
                        


                    </table>
                    <hr class="tall">
                    <h4 class="heading-primary">Cart Totals</h4>
                    <div class="col-md-12">
                        <table class="cart-totals">
                            <tbody>


                                <tr class="total">
                                    <th>
                                        <strong>Order Total</strong>
                                    </th>
                                    <td>
                                        <strong><span class="amount"><small>{!! $symbol !!} </small> {{$invoice->grand_total}}</span></strong>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <hr class="tall">
                    </div>

                </div>
                {!! Form::open(['url'=>'checkout','method'=>'post']) !!}
                
                <h4 class="heading-primary">Payment</h4>
<?php $gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway($symbol); ?>

                <div class="form-group">
                    @forelse($gateways as $gateway)
                    <div class="col-md-6">
                        {{$gateway->from}} {!! Form::radio('payment_gateway',strtolower($gateway->from)) !!}<br><br>
                    </div>
                    @empty
                    @endforelse
                    <div class="col-md-6">
                        
                        {!! Form::hidden('invoice_id',$invoice->id) !!}
                        {!! Form::hidden('cost',$invoice->grand_total) !!}
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Place Order
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <h4 class="heading-primary">Cart Totals</h4>
        <table class="cart-totals">
            <tbody>
                <tr class="cart-subtotal">
                    <?php 
                    $subtotals = App\Model\Order\InvoiceItem::where('invoice_id',$invoice->id)->lists('regular_price')->toArray();
                    $subtotal = array_sum($subtotals);
                    ?>
                    <th>
                        <strong>Cart Subtotal</strong>
                    </th>
                    <td>
                        <strong><span class="amount"><small>{{$symbol}}</small> {{$subtotal}}</span></strong>
                    </td>
                </tr>

                @foreach($items->toArray() as $attribute)
                
                @if($attribute['tax_name']!='null,')
                <?php 
                $tax_name = "";
                $tax_percentage="";
                if(str_finish($attribute['tax_name'], ',')){
                    $tax_name = str_replace(',','',$attribute['tax_name']);
                }
                if(str_finish($attribute['tax_percentage'], ',')){
                    $tax_percentage = str_replace(',','',$attribute['tax_percentage']);
                }
                ?>
                <tr class="Taxes">
                    <th>
                        <strong>{{ucfirst($tax_name)}}<span>@</span>{{$tax_percentage}}%</strong>
                    </th>
                    <?php
                    $price = $product->price()->where('currency',$symbol)->first();
                    $cost = $price->sales_price;
                    if(!$cost){
                        $cost = $price->price;
                    }
                    ?>
                    <td>
                        <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['tax_percentage'],$cost)}}
                    </td>

                </tr>
                @endif
                @endforeach
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                        <strong><span class="amount"><small>{{$symbol}}</small> {{$invoice->grand_total}}</span></strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
