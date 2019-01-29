@extends('themes.default1.layouts.front.master')
@section('title')
Checkout
@stop
@section('page-header')
Checkout
@stop
@section('page-heading')
 <h1>Checkout</h1>
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Checkout</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
@if (!\Cart::isEmpty())

<?php

$tax=  0;


$sum = 0;



?>
<div class="container">
<div class="row">

    <div class="col-lg-8">
         <div class="card card-default" style="margin-bottom: 40px;">
             <div class="card-header">
              <h4 class="card-title m-0">
                           
                        Review Your Order
                                            
               </h4>
                
            </div>


            <div class="card-body">

                @if(Session::has('success'))
                <div class="alert alert-success">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <strong><i class="far fa-thumbs-up"></i> Well done!</strong>
                    {{Lang::get('message.success')}}.
                    
                    {!!Session::get('success')!!}
                </div>
                @endif
                <!-- fail message -->
                @if(Session::has('fails'))
              <div class="alert alert-danger alert-dismissable" role="alert">
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.
                   {{Lang::get('message.alert')}}! {{Lang::get('message.failed')}}.
                  
                    {{Session::get('fails')}}
                </div>
                @endif
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.
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
                                    $currency = $item->attributes['currency']['currency'] ;
                                    $symbol = $item->attributes['currency']['symbol'];
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

                                <td class="product-price">
                                  
                                   
                                    <span class="amount">{!! $symbol !!}    {{\App\Http\Controllers\Front\CartController::rounding($item->getPriceSum())}}</span>


                                </td>
                            </tr>
                            @empty 
                        <p>Your Cart is void</p>
                        @endforelse


                    </table>
                    
                  
                    <div class="col-md-12">


                       


                        <hr class="tall">
                    </div>

                </div>
                <h4 class="heading-primary">Payment</h4>
                {!! Form::open(['url'=>'checkout','method'=>'post']) !!}
                @if(Cart::getTotal()>0)
                
                 <?php 
                $gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway($item['attributes']['currency']['currency']);
                $total = Cart::getSubTotal();
                  // 
                ?>
                @if($gateways) {
                  <div class="form-group">

                    <div class="col-md-6">
                        {{ucfirst($gateways)}} {!! Form::radio('payment_gateway',strtolower($gateways)) !!}<br><br>
                    </div>
                </div>
            }
            @endif
                
                <div class="form-group">


                   <div class="form-row">
                    <div class="col-md-6">
                        {!! Form::radio('payment_gateway',strtolower('Razorpay')) !!}
                       <img alt="Porto" width="111"  data-sticky-width="82" data-sticky-height="40" data-sticky-top="33" src="{{asset('images/logo/Razorpay.png')}}"><br><br>
                    </div>
                </div>


                    
                </div>
                @endif

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Proceed <i class="fa fa-forward"></i>
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
                



           </div>
        </div>
    </div>

    <div class="col-lg-4">
        <h4 class="heading-primary">Cart Totals</h4>
        <table class="cart-totals">
            <tbody>
                <tr class="cart-subtotal">

                    <th>
                        <strong>Cart Subtotal</strong>
                    </th>
                    <td>
                   

                        <strong><span class="amount"> {{$symbol}}  @if($currency == "INR")

                                            {{App\Http\Controllers\Front\CartController::rounding(Cart::getSubTotalWithoutConditions())}}
                                            @else
                                            {{App\Http\Controllers\Front\CartController::rounding(Cart::getSubTotalWithoutConditions())}}
                                           
                                            @endif
                    </td>
                </tr>
                @foreach($item->attributes['tax'] as $attribute)
                    @if($attribute['name']!='null' && ($currency == "INR" && $attribute['tax_enable'] ==1))
                 @if($attribute['state']==$attribute['origin_state'] && $attribute['ut_gst']=='NULL' && $attribute['status'] ==1)

                <tr class="Taxes">
                    <th>
                        <strong>CGST<span>@</span>{{$attribute['c_gst']}}%</strong><br/>
                        <strong>SGST<span>@</span>{{$attribute['s_gst']}}%</strong><br/>
                       
                    </th>
                    <td>

                        {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($attribute['c_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                        {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($attribute['s_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                       
                       

                    </td>


                </tr>
                @endif
                
                @if ($attribute['state']!=$attribute['origin_state'] && $attribute['ut_gst']=='NULL' &&$attribute['status'] ==1)
               

                <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['i_gst']}}%</strong>
                     
                    </th>
                    <td>

                        {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($attribute['i_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                      

                    </td>


                </tr>
                @endif

                @if ($attribute['state']!=$attribute['origin_state'] && $attribute['ut_gst']!='NULL' &&$attribute['status'] ==1)
              
                <tr class="Taxes">
                    <th>
                       <strong>CGST<span>@</span>{{$attribute['c_gst']}}%</strong><br/>
                        <strong>UTGST<span>@</span>{{$attribute['ut_gst']}}%</strong>
                       
                    </th>
                    <td>
                        {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($attribute['c_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                        {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($attribute['ut_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                       
                    </td>


                </tr>
                @endif
                @endif

                 @if($attribute['name']!='null' && ($currency == "INR" && $attribute['tax_enable'] ==0 && $attribute['status'] ==1))

                 <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}%</strong><br/>
                       
                         
                    </th>
                    <td>
                       
                       {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotalWithoutConditions())}} <br/>
                         
                       
                    </td>
                  </tr>
                 @endif
           
                @if($attribute['name']!='null' && ($currency != "INR" && $attribute['tax_enable'] ==1 && $attribute['status'] ==1))

                  <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>

                      
                        {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotalWithoutConditions())}} <br/>
                         
                       
                    </td>
                  </tr>
                 @endif
                 @if($attribute['name']!='null' && ($currency != "INR" && $attribute['tax_enable'] ==0 && $attribute['status'] ==1))

                  <tr class="Taxes">
                  
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>
                       
                         {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotalWithoutConditions())}} <br/>
                         
                       
                    </td>
                  
                  </tr>
                 @endif
                @endforeach
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>



                         @if($currency == "INR")
                           
                                          {{$symbol}}  {{App\Http\Controllers\Front\CartController::rounding(Cart::getTotal())}}
                                            @else
                                           {{$symbol}}  {{App\Http\Controllers\Front\CartController::rounding(Cart::getTotal())}}


                                            @endif

                       
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
@else 
<div class="row">

    <div class="col-md-12">
       
            <div class="panel-heading">
                <h4 class="panel-title">
                
                        Order
                    
                </h4>
            </div>


            <div class="panel-body">

                @if(Session::has('success'))
                <div>
                    <!-- {{Lang::get('message.success')}}. -->
                    
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
@endif
@endsection
