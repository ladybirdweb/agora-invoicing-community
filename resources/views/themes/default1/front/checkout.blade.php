@extends('themes.default1.layouts.front.master')
@section('title')
Checkout
@stop
@section('page-header')
Checkout
@stop
@section('page-heading')
 Checkout
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
                                <th class="product-version">
                                    Version
                                </th>

                                <th class="product-quantity">
                                    Quantity
                                </th>
                                <th class="product-subtotal">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($content as $item)
                            <tr class="cart_table_item">

                                <td class="product-thumbnail">
                                  
                                    <img width="100" height="100" alt="" class="img-responsive" src="{{$item->associatedModel->image}}">

                                </td>

                                <td class="product-name">
                                    {{$item->name}}
                                </td>

                                <td class="product-version">
                                    @if($item->associatedModel->version)
                                    {{$item->associatedModel->version}}
                                    @else
                                    Not available
                                    @endif
                                </td>

                                <td class="product-quantity">
                                    {{$item->quantity}}
                                </td>

                                <td class="product-subtotal">
                                    <span class="amount">
                                     {{currencyFormat($item->getPriceSum(),$code = $item->attributes->currency)}}
                                </td>
                            </tr>
                            @empty
                        <p>Your Cart is empty</p>
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
                $gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway($item->attributes['currency']);
                // $total = Cart::getSubTotal();
                $rzpstatus = \App\Model\Common\StatusSetting::first()->value('rzp_status');

                  //
                ?>
                @if($gateways)
                <div class="row">


                    <div class="col-md-6">
                        @foreach($gateways as $gateway)
                        <?php
                        $processingFee = \DB::table(strtolower($gateway))->where('currencies',$item->attributes['currency'])->value('processing_fee');
                        ?>
                        {!! Form::radio('payment_gateway',$gateway,false,['id'=>'allow_gateway','data-currency'=>$processingFee]) !!}
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
                     {!! Form::radio('payment_gateway','razorpay',false,['id'=>'rzp_selected','data-currency'=>0]) !!}&nbsp;&nbsp;&nbsp;
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

                        <span class="amount">

                                {{currencyFormat(Cart::getSubTotalWithoutConditions(),$code = $item->attributes->currency)}}
                      

                    </td>
                </tr>
                @if($taxConditions->getName() != 'null')
                <tr class="Taxes">
                    <?php
                    $bifurcateTax = bifurcateTax($taxConditions->getName(),$taxConditions->getValue(),$item->attributes->currency, \Auth::user()->state);
                    ?>
                   <th>
                        
                        <strong>{!! $bifurcateTax['html'] !!}</strong><br/>

                    </th>
                    <td>
                     {!! $bifurcateTax['tax'] !!}
                  </td>
                  
                   
                </tr>
                @endif
               
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                        <strong class="text-dark">
                            <span class="amount">
                                <?php
                                    Cart::removeCartCondition('Processing fee');
                                    $total = \App\Http\Controllers\Front\CartController::rounding(Cart::getTotal());
                                ?>
                                  <div id="total-price" value={{$total}} hidden></div>
                                  <div>{{currencyFormat($total,$code = $item->attributes->currency)}} </div>
                            </span>
                        </strong>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
@elseif (\Cart::isEmpty())
    <div class="featured-boxes">
        <div class="row">
            <div class="col-md-12">
                <div class="featured-box featured-box-primary align-left mt-sm">
                    <div class="box-content">

                        <div class="col-md-offset-5">

                            @if(Auth::check())

                                <a href="{{url('my-invoices')}}" class="btn btn-primary">CONTINUE SHOPPING
                                    @else
                                        <a href="{{url('login')}}" class="btn btn-primary">CONTINUE SHOPPING
                                            @endif
                                        </a>
                        </div>
                    </div>
                </div>
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
            $("#rzp_selected").click(function(){
            $('#fee').hide();
        });
        $("#allow_gateway").click(function(){
           $('#fee').show();
        });
    });
</script>
@endsection
