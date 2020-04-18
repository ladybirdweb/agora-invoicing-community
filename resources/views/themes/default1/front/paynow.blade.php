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

    $currency = $invoice->currency;
    $symbol = \App\Model\Payment\Currency::where('code',$invoice->currency)->pluck('symbol')->first();

?>
<div class="container">
<div class="row">

    <div class="col-md-8">
        <div class="card card-default" style="margin-bottom: 40px;">
             <div class="card-header">
                 <h4 class="card-title m-0">
                    
                        Review & Payment
                    
                </h4>
            </div>


            <div class="card-body">

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
                                    
                                    <span class="amount">{{currency_format(intval($item->subtotal),$code = $currency)}}</span>
                                </td>
                            </tr>
                            @empty 
                        <p>Your Cart is void</p>
                        @endforelse
                        


                    </table>

                </div>
                {!! Form::open(['url'=>'checkout','method'=>'post','id' => 'checkoutsubmitform']) !!}
                  @if($invoice->grand_total > 0)
                <h4 class="heading-primary">Payment</h4>
                    <?php $gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway($invoice->currency);
                      $rzpstatus = \App\Model\Common\StatusSetting::first()->value('rzp_status');
                       ?>
                    
                
                @if(count($gateways)>0 ) 
                  <div class="row">

                    <div class="col-md-6">
                        @foreach($gateways as $gateway)
                        <?php
                          $processingFee = \DB::table(strtolower($gateway))->where('currencies',$invoice->currency)->value('processing_fee');
                        ?>
                        <input type="hidden" name="process_fee" value="{{$processingFee}}">
                        <input type="radio"  data-currency="{{$processingFee}}" id="allow_gateway" name='payment_gateway'  value={{$gateway}}>
                         <img alt="Porto" width="111"  data-sticky-width="52" data-sticky-height="10" data-sticky-top="10" src="{{asset('client/images/'.$gateway.'.png')}}">
                          <br><br>
                         <div id="fee" style="display:none"><p>An extra processing fee of <b>{{$processingFee}}%</b> will be charged on your Order Total during the time of payment</p></div>
                        @endforeach
                    </div>
                </div>
            
            @endif


                 
            
            
             @if($rzpstatus ==1)
                <div class="row">
                    
                    <div class="col-md-6">
                         <input type="radio" id="rzp_selected" data-currency=0 name='payment_gateway' value="razorpay"> 
                         <img alt="Porto" width="111" data-sticky-width="82" data-sticky-height="40" data-sticky-top="33" src="{{asset('client/images/Razorpay.png')}}"><br><br>
                  </div>
                  </div>
                @endif
                  @endif
                   <div class="col-md-6">
                        
                        {!! Form::hidden('invoice_id',$invoice->id) !!}
                        {!! Form::hidden('cost',$invoice->grand_total) !!}
                    </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" id="proceed" class="btn btn-primary">

                            Proceed
                             <i class= "fa fa-forward"></i>
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
                    $subtotals = App\Model\Order\InvoiceItem::where('invoice_id',$invoice->id)->pluck('regular_price')->toArray();
                    $subtotal = array_sum($subtotals);
                    ?>
                    <th>
                        <strong>Cart Subtotal</strong>
                    </th>
                    <td>
                        <strong><span class="amount">{{currency_format($subtotal,$code = $currency)}}</span></strong>
                    </td>
                </tr>
                  @foreach($items->toArray() as $attribute)
                  @if($attribute['tax_name']!='null')
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
                        <strong>{{$tax_name}}<span>@</span>{{$tax_percentage}}</strong><br/>
                         </th>
                    <td>
                    <?php
                     $value = \App\Http\Controllers\Front\CartController::taxValue($attribute['tax_percentage'],$subtotal);
                     ?>
                      {{currency_format($value,$code = $currency)}}
                        
                       
                       
                    </td>


                </tr>
               
                @endif
                @endforeach
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>

                        <strong><span class="amount"> {{currency_format($invoice->grand_total,$code = $currency)}}</span></strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
  $('#checkoutsubmitform').submit(function(){
     $("#proceed").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...")
    $("#proceed").prop('disabled', true);

  });
     $(document).ready(function(){
        $("#rzp_selected").click(function(){
                $('#fee').hide();
        }); 
        $("#allow_gateway").click(function(){
           $('#fee').show();
        });
         
    });
</script>
@endsection
