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
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
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
                  
                   <li> {{Session::get('fails')}} </li>
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
                                  
                                   
                                    <span class="amount">
                                     {{currency_format($item->getPriceSum(),$code = $currency)}}    
                                       


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
             
                       
                {!! Form::open(['url'=>'checkout','method'=>'post','id' => 'checkoutsubmitform' ]) !!}

                @if(Cart::getTotal()>0)
                  
                 <?php 
                $gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway($item['attributes']['currency']['currency']);
                $total = Cart::getSubTotal();
                $rzpstatus = \App\Model\Common\StatusSetting::first()->value('rzp_status');

                  // 
                ?>
                @if(count($gateways)>0 ) 
                <div class="row">
                  

                    <div class="col-md-6">
                        @foreach($gateways as $gateway)
                        <?php
                        $processingFee = \DB::table(strtolower($gateway))->where('currencies',$item['attributes']['currency']['currency'])->first()->processing_fee;
                        ?>
                         <input type="radio"  data-currency="{{$processingFee}}" id="allow_gateway" name='payment_gateway'  value={{$gateway}}>
                         <img alt="{{$gateway}}" width="111"  src="{{asset('client/images/'.$gateway.'.png')}}">
                          <br><br>
                       <div id="fee" style="display:none"><p>An extra processing fee of <b>{{$processingFee}}%</b> will be charged on your Order Total during the time of payment</p></div>
                        @endforeach
                    </div>

                
              </div>
               
            @endif
                @if($rzpstatus ==1)
                <div class="row">
                    <div class="col-md-6">
                        <input type="radio" id="rzp_selected" data-currency=0 name='payment_gateway' value="razorpay"> &nbsp;&nbsp;&nbsp;
                       <img alt="Porto" width="111"  data-sticky-width="82" data-sticky-height="40" data-sticky-top="33" src="{{asset('client/images/Razorpay.png')}}"><br><br>
                    </div>

              </div>
                @endif
                @endif

                <div class="row">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" id="proceed" class="btn btn-primary">
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
                   

                        <strong><span class="amount"> 

                                {{currency_format(Cart::getSubTotalWithoutConditions(),$code = $currency)}}
                                           
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
                     <?php
                     $cgst = \App\Http\Controllers\Front\CartController::taxValue($attribute['c_gst'],Cart::getSubTotalWithoutConditions());
                     $sgst = \App\Http\Controllers\Front\CartController::taxValue($attribute['s_gst'],Cart::getSubTotalWithoutConditions());
                     ?>
                       {{currency_format($cgst,$code = $currency)}}<br/>
                       {{currency_format($sgst,$code = $currency)}} <br/>
                       
                       

                    </td>


                </tr>
                @endif
                
                @if ($attribute['state']!=$attribute['origin_state'] && $attribute['ut_gst']=='NULL' &&$attribute['status'] ==1)
               

                <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['i_gst']}}%</strong>
                     
                    </th>
                    <td>
                     <?php 
                    $igst = \App\Http\Controllers\Front\CartController::taxValue($attribute['i_gst'],Cart::getSubTotalWithoutConditions());
                     ?>
                       {{currency_format($igst,$code = $currency)}} <br/><br/>
                      

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
                        <?php
                        $cgst = \App\Http\Controllers\Front\CartController::taxValue($attribute['c_gst'],Cart::getSubTotalWithoutConditions());
                        $utgst = \App\Http\Controllers\Front\CartController::taxValue($attribute['ut_gst'],Cart::getSubTotalWithoutConditions())
                        ?>
                         {{currency_format($cgst,$code = $currency)}} <br/>
                         {{currency_format($utgst,$code = $currency)}} <br/>
                       
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
                       <?php
                       $value = \App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotalWithoutConditions())
                       ?>
                        {{currency_format($value,$code = $currency)}} <br/>
                         
                       
                    </td>
                  </tr>
                 @endif
           
                @if($attribute['name']!='null' && ($currency != "INR" && $attribute['tax_enable'] ==1 && $attribute['status'] ==1))

                  <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>
                     <?php
                     $value = \App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotalWithoutConditions())
                     ?>
                      
                        {{currency_format($value,$code = $currency)}} <br/>
                         
                       
                    </td>
                  </tr>
                 @endif
                 @if($attribute['name']!='null' && ($currency != "INR" && $attribute['tax_enable'] ==0 && $attribute['status'] ==1))

                  <tr class="Taxes">
                  
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>
                        <?php
                        $value = \App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotalWithoutConditions())
                        ?>
                       
                         {{currency_format($value,$code = $currency)}} <br/>
                         
                       
                    </td>
                  
                  </tr>
                 @endif
                @endforeach
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                        <?php
                          Cart::removeCartCondition('Processing fee');
                          $total = \App\Http\Controllers\Front\CartController::rounding(Cart::getTotal());
                          ?>
                          <div id="total-price" value={{$total}} hidden></div>

                          <div>{{currency_format($total,$code = $currency)}} </div>
                                        
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
  $('#checkoutsubmitform').submit(function(){
     $("#proceed").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...")
    $("#proceed").prop('disabled', true);

  });
     $(document).ready(function(){
            var finalPrice = $('#total-price').val();
            console.log(finalPrice);
        $("#rzp_selected").click(function(){
            
            var processingFee = $(this).attr('data-currency');
            var totalPrice = finalPrice;
            $('#fee').hide();
            $.ajax({
                type:'POST',
                data: {'processing_fee':processingFee,'price':totalPrice},
                 beforeSend: function () {
                 $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                },
                 url: "{{url('update-final-price')}}",
                 success: function () {
              }
            });
        }); 
        $("#allow_gateway").click(function(){
            var processingFee = $(this).attr('data-currency');
            var totalPrice = finalPrice;
            $('#fee').show();
            $.ajax({
                type:'POST',
                data: {'processing_fee':processingFee,'price':totalPrice},
                 beforeSend: function () {
                 $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                },
                 url: "{{url('update-final-price')}}",
                 success: function () {
                    // location.reload();
                   
              }

              
            });
        });
         
    });
</script>
@endsection
