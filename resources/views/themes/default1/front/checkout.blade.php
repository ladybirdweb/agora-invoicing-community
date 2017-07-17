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
@if (!\Cart::isEmpty())
<?php
if ($attributes[0]['currency'][0]['symbol'] == '') {
    $symbol = $attributes[0]['currency'][0]['code'];
} else {
    $symbol = $attributes[0]['currency'][0]['symbol'];
}
$tax=  0;
                $sum = 0;
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
                            @forelse($content as $item)
                            <tr class="cart_table_item">

                                <td class="product-thumbnail">
                                    <?php
                                    $product = App\Model\Product\Product::where('id', $item->id)->first();
                                    $price = 0;
                                    $cart_controller = new App\Http\Controllers\Front\CartController();
                                    $value = $cart_controller->cost($product->id);
                                    $price += $value;
                                    ?>
                                    <img width="100" height="100" alt="" class="img-responsive" src="{{$product->image}}">

                                </td>

                                <td class="product-name">
                                    {{$item->name}}
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
                                    <?php $subtotals[] = \App\Http\Controllers\Front\CartController::calculateTax($product->id, $attributes[0]['currency'][0]['code'], 1, 1, 0); ?>
                                    <span class="amount"><small>{!! $symbol !!} </small> {{\App\Http\Controllers\Front\CartController::calculateTax($item->id,$item->getPriceSum(),1,1,0)}}</span>
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
                                        <strong><span class="amount"><small>{!! $symbol !!} </small> {{App\Http\Controllers\Front\CartController::rounding(Cart::getTotal())}}</span></strong>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <hr class="tall">
                    </div>

                </div>
                {!! Form::open(['url'=>'checkout','method'=>'post']) !!}
                @if(Cart::getTotal()>0)
                <h4 class="heading-primary">Payment</h4>
                <?php 
                
                $gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway($attributes[0]['currency'][0]['code']);
                $total = Cart::getSubTotal();
                                                        $sum = $item->getPriceSum();
                                                        $tax = $total-$sum;
                ?>
                <div class="form-group">
                    @forelse($gateways as $gateway)
                    <div class="col-md-6">
                        {{ucfirst($gateway->from)}} {!! Form::radio('payment_gateway',strtolower($gateway->from)) !!}<br><br>
                    </div>
                    @empty
                    @endforelse
                </div>
                @endif
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
                    <th>
                        <strong>Cart Subtotal</strong>
                    </th>
                    <td>
                        <strong><span class="amount"><small>{{$symbol}}</small> {{\App\Http\Controllers\Front\CartController::rounding($item->getPriceSum())}}</span></strong>
                    </td>
                </tr>

                @foreach($item->attributes['tax'] as $attribute)
                @if($attribute['name']!='null')
                <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}%</strong>
                    </th>
                    <td>
                        <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotal())}}
                    </td>

                </tr>
                @endif
                @endforeach
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                        <strong><span class="amount"><small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::rounding(Cart::getSubTotal())}}</span></strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@else 
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        Order
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
            </div>
        </div>
    </div>
</div>
@endif
@endsection
