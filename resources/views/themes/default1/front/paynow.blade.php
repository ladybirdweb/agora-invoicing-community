@extends('themes.default1.layouts.front.master')
@section('title')
Checkout
@stop
@section('page-heading')
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
    $taxAmt = 0;
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
                            @php
                            Session::forget('code');
                            $taxName[] =  $item->tax_name.'@'.$item->tax_percentage;
                            if ($item->tax_name != 'null') {
                                $taxAmt +=  $item->subtotal;
                             }
                             @endphp
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
                                    
                                    <span class="amount">{{currencyFormat(($item->regular_price),$code = $currency)}}</span>
                                </td>
                            </tr>
                            @empty 
                        <p>Your Cart is void</p>
                        @endforelse
                        


                    </table>

                </div>
                {!! Form::open(['url'=>'checkout-and-pay','method'=>'post','id' => 'checkoutsubmitform']) !!}
                  @if($invoice->grand_total > 0)
                <h4 class="heading-primary">Payment</h4>
                    <?php $gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway($invoice->currency);
                       ?>
                    
                
                @if(count($gateways)) 
                  <div class="row">

                    <div class="col-md-6">
                        @foreach($gateways as $gateway)
                        <?php
                          $processingFee = \DB::table(strtolower($gateway))->where('currencies',$invoice->currency)->value('processing_fee');
                        ?>
                        {!! Form::radio('payment_gateway',$gateway,false,['id'=>'allow_gateway','onchange' => 'getGateway(this)','processfee'=>$processingFee]) !!}
                         <img alt="Porto" width="111"  data-sticky-width="52" data-sticky-height="10" data-sticky-top="10" src="{{asset('client/images/'.$gateway.'.png')}}">
                          <br><br>
                         <div id="fee" style="display:none"><p>An extra processing fee of <b>{{$processingFee}}%</b> will be charged on your Order Total during the time of payment</p></div>
                        @endforeach
                    </div>
                </div>
            
            @endif


                 
            
            
            
                  @endif
                     <div class="col-md-6">
                        
                        {!! Form::hidden('invoice_id',$invoice->id) !!}
                        {!! Form::hidden('cost',$invoice->grand_total) !!}
                    </div>

                <div class="row">
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
                       <span class="amount">{{currencyFormat($subtotal,$code = $currency)}}</span>
                    </td>
                </tr>
                 @php
                $taxName = array_unique($taxName);
                @endphp
                  @foreach($taxName as $tax)
                  @php
                  $taxDetails = explode('@', $tax);
                  @endphp
                  @if ($taxDetails[0]!= 'null')
                                            
                                       
                    <tr>
                         <?php
                        $bifurcateTax = bifurcateTax($taxDetails[0],$taxDetails[1],\Auth::user()->currency, \Auth::user()->state, $taxAmt);
                        ?>
                        <th>

                            <strong>{!! $bifurcateTax['html'] !!}</strong>


                        </th>
                        <td>
                           
                            {!! $bifurcateTax['tax'] !!}

                        </td>
                    </tr>
             
               
                    @endif
                    @endforeach


                    @if($paid)

                    <tr class="total">
                    <th>
                        <strong>Paid</strong>
                    </th>
                    <td>

                        {{currencyFormat($paid,$code = $currency)}}
                    </td>
                </tr>

                <tr class="total">
                    <th>
                        <strong>Balance</strong>
                    </th>
                    <td>

                        {{currencyFormat($invoice->grand_total,$code = $currency)}}
                    </td>
                </tr>
                @endif
                
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>

                        <strong><span class="amount"> {{currencyFormat($invoice->grand_total,$code = $currency)}}</span></strong>
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
    var $gateways = $('input:radio[name = payment_gateway]');
    if($gateways.is(':checked') === false) {
        $gateways.filter('[value=Razorpay]').attr('checked', true);
        $('#fee').hide();
    } else {
        $gateways.filter('[value=Stripe]').attr('checked', true);
        $('#fee').show();
    }
  });

  function getGateway($this)
  {
    var gateWayName = $this.value;
    var fee = $this.getAttribute("processfee");
    console.log(fee)
    if (fee == '0') {
        $('#fee').hide();
    } else {
        $('#fee').show();
    }
  }
</script>
@endsection
