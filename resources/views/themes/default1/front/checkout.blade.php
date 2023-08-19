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
 <style>
    .remove-coupon-btn {
      background: none;
      border: none;
      cursor: pointer;
      padding: 0;
    }
    .amount-with-icon {
        position: relative;
    }
    .amount {
        display: inline-block;
    }
    .remove-icon {
        position: absolute;
        top: 0;
        left: 100%; 
        margin-left: 5px; 
        font-size: 17px;
        background: none;
       border: none;

        }
        .fa-1x {
            font-size: 15px;
            margin-right: 3.4px;
        }
 </style>
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">Checkout</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
    <?php
      $amt_to_credit = 0;
      $curr ='';
      if(empty($content)){
          $curr = '';
      }
      else{
          foreach ($content as $item){
              $curr = $item->attributes->currency;
          }
      }
    ?>
@if (!\Cart::isEmpty())
<?php
$cartSubtotalWithoutCondition = 0;
?>
<div class="container">
<div class="row">

    <div class="col-lg-8">
         <div class="card card-default">
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
                                <th class="product-agents">
                                    Agents
                                </th>
                                <th class="product-subtotal">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                              {{Cart::removeCartCondition('Processing fee')}}
                            @forelse($content as $item)
                           

                            @php
                            $cartSubtotalWithoutCondition += $item->getPriceSum();
                            @endphp
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
                                <td class="product-agents">
                                    {{$item->attributes->agents}}
                                </td>

                                <td class="product-subtotal">
                                   <?php
                                    $productId = \DB::table('products')->where('name', $item->name)->value('id');
                                    $planid = null;
                                    if (\Session::has('toggleState') || \Session::get('toggleState') == null) {
                                        $toggleState = \Session::get('toggleState');
                                        $price = $item->price;
                                    }
                                    else{
                                      $planid = \DB::table('plans')->where('product',$item->id)->value('id');
                                      $price = \DB::table('plan_prices')->where('plan_id', $planid)->where('currency', $item->attributes->currency)->value('add_price');
                                    }    

                                    ?>

                                    <span class="amount">

                                           @if ($item->conditions && $item->conditions->getType() === 'coupon')
                                           <?php
                                           \Session::put('togglePrice',$item->conditions->getName())
                                           ?>

                                                {{ $item->quantity * $item->conditions->getName() }}
                                           
                                           

                                            @else
                                                {{currencyFormat($item->quantity * $price,$code = $item->attributes->currency)}}
                                            @endif
                                                                 
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
                @if(Cart::getTotal()>0)
                <h4 class="heading-primary">Select a payment method</h4>

                <div class="row">
                <div class="col-md-6 col-md-offset-4">
                <p class="underline-label"></p>
                </div>
                </div>
                @endif


                {!! Form::open(['url'=>'checkout-and-pay','method'=>'post','id' => 'checkoutsubmitform' ]) !!}

                @if(Cart::getTotal()>0)

                 <?php
                $gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway($item->attributes['currency']);

                ?>
                @if($gateways)
                <div class="row">
                        <?php $amt_to_credit = \DB::table('payments')
                        ->where('user_id', \Auth::user()->id)
                        ->where('payment_method','Credit Balance')
                        ->where('payment_status','success')
                        ->where('amt_to_credit','!=',0)
                        ->value('amt_to_credit');
                        ?>
                    <div class="col-md-6">
                        @if($amt_to_credit)
                        <div class="checkbox-container">
                            <h5 class="heading-primary">Your available balance</h5>
                            @if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance'))
                            <input type="checkbox" id="billing-pay-balance" class="custom-checkbox" checked>
                            @else
                                <input type="checkbox" id="billing-pay-balance" class="custom-checkbox">
                            @endif
                            <label for="billing-pay-balance" class="checkbox-label"><b>Use your balance: {{currencyFormat($amt_to_credit,$code = $item->attributes->currency)}}</b></label>
                        </div>
                        <p class="underline-label"></p>
                        <br>
                        @endif
                        <h5 class="heading-primary">Payment gateway</h5>

                    @foreach($gateways as $gateway)
                        <?php
                        $processingFee = \DB::table(strtolower($gateway))->where('currencies',$item->attributes['currency'])->value('processing_fee');
                        ?>

                        {!! Form::radio('payment_gateway',$gateway,false,['id'=>'allow_gateway','onchange' => 'getGateway(this)','processfee'=>$processingFee]) !!}

                         <img alt="{{$gateway}}" width="111" src="{{asset('storage/client/images/'.$gateway.'.png')}}"><br>

                      <div id="fee" style="display:none"><p>An extra processing fee of <b>{{$processingFee}}%</b> will be charged on your Order Total during the time of payment</p></div>
                        @endforeach
                        <p class="underline-label"></p>

                    </div>


              </div>

            @endif

                @endif

                @if(Cart::getTotal()>0)
                <div class="row">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" id="proceed" class="btn btn-primary">
                            Use this payment method <i class="fa fa-forward"></i>
                        </button>
                    </div>
                </div>
              @else
                <div class="row">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" id="proceed" class="btn btn-primary">
                            Place your order <i class="fa fa-forward"></i>
                        </button>
                    </div>
                </div>
                @endif
                {!! Form::close() !!}




           </div>
        </div>
    </div>

    <div class="col-lg-4">
        <h4 class="heading-primary">Cart Total</h4>
        <table class="cart-totals">
            <tbody>
                <tr class="cart-subtotal">

                    <th>
                        <strong>Cart Subtotal</strong>
                    </th>
                    <td>

                        <span class="amount">

                                {{currencyFormat($cartSubtotalWithoutCondition,$code = $item->attributes->currency)}}
                            </span>
                      

                    </td>
                </tr>
                @if(Session::has('code'))
                  <tr class="cart-subtotal">

                    <th>
                        <strong>Discount</strong>
                    </th>
                    <td style="position: absolute;">
                        <?php
                        if (strpos(\Session::get('codevalue'), '%') == true) {
                                $discountValue = currencyFormat($discountPrice,$code = $item->attributes->currency);
                                echo $discountValue . '(<strong title="Coupon code">' . (\Session::get('code')) . '</strong>)';
                            } else {
                                $discountValue = currencyFormat(\Session::get('codevalue'),$code = $item->attributes->currency);
                                echo $discountValue . '(<strong title="Coupon code">' . (\Session::get('code')) . '</strong>)';
                            }
                        ?>
                    <form action="{{ url('remove-coupon') }}" method="POST">
                    @csrf
                    <button type="submit" class="remove-icon" title="Click to remove">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </form>
                    </td>

                </tr>
                @endif
                @if(count(\Cart::getConditionsByType('tax')) == 1)
                @foreach(\Cart::getConditions() as $tax)

                 @if($tax->getName()!= 'null')
                <tr class="Taxes">
                    <?php
                    $bifurcateTax = bifurcateTax($tax->getName(),$tax->getValue(),$item->attributes->currency, \Auth::user()->state, \Cart::getContent()->sum('price'));
                    ?>
                   <th>
                        
                        <strong>{!! $bifurcateTax['html'] !!}</strong><br/>

                    </th>
                    <td>
                     {!! $bifurcateTax['tax'] !!}
                  </td>
                  
                   
                </tr>
                @endif
                @endforeach

                @else
                @foreach(Cart::getContent() as $tax)
                @if($tax->conditions->getName() != 'null')
                <tr class="Taxes">
                    <?php
                    $bifurcateTax = bifurcateTax($tax->conditions->getName(),$tax->conditions->getValue(),$item->attributes->currency, \Auth::user()->state, $tax->price*$tax->quantity);
                    ?>
                   <th>
                        
                        <strong>{!! $bifurcateTax['html'] !!}</strong><br/>

                    </th>
                    <td>
                     {!! $bifurcateTax['tax'] !!}
                  </td>
                  
                   
                </tr>
                @endif
                
                @endforeach
               @endif
                    <tr id="balance-row" class="cart-subtotal" style="color: indianred; display: none;">
                        <th><strong>Balance</strong></th>
                        <td>
                                <?php
                                if (\Cart::getTotal() <= $amt_to_credit) {
                                    $cartTotal = \Cart::getTotal();
                                } else {
                                    $cartTotal = $amt_to_credit;
                                }
                                ?>
                            -{{$dd=currencyFormat($cartTotal, $item->attributes->currency)}}
                        </td>
                    </tr>
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                        <strong class="text-dark">
        <span class="amount">
            <div id="balance-content">
                <?php
                    if (\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance')) {
                        if (\Cart::getTotal() <= $amt_to_credit) {
                            $cartTotal = 0;
                        } else {
                            $cartTotal = \Cart::getTotal() - $amt_to_credit;
                        }
                    } else {
                        $cartTotal = \Cart::getTotal();
                    }
                    ?>
                {{ currencyFormat($cartTotal, $code = $item->attributes->currency) }}
            </div>
        </span>
                        </strong>
                    </td>


                </tr>
            </tbody>
        </table>
        {!! Form::open(['url'=>'pricing/update','method'=>'post']) !!}
           <div class="input-group" style="margin-top: 10px;">
    <input type="text" name="coupon" class="form-control input-lg" placeholder="{{Lang::get('message.coupon-code')}}">
    <div class="input-group-append">
        <input type="submit" value="Apply" class="btn btn-primary">
    </div>
</div>
        {!! Form::close() !!}
    </div>

</div>
</div>
@elseif (\Cart::isEmpty())
   
@endif
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('.clear-icon').click(function() {
            $(this).closest('.input-group').find('input[name=coupon]').val('').focus();
        });
    });
</script>
<script>

  $('#checkoutsubmitform').submit(function(){
     $("#proceed").html("<i class='fa fa-circle-o-notch fa-spin fa-1x ''></i>Processing ...")
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
    if (fee == '0') {
        $('#fee').hide();
    } else {
        $('#fee').show();
    }
  }
      $(document).ready(function () {
      $('#billing-pay-balance').on('change', function () {
          var isChecked = $(this).prop('checked');

          $.ajax({
              type: "POST",
              url: "{{ route('update-session') }}",
              data: { isChecked: isChecked },
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
              }
          });
      });
  });
      $(document).ready(function () {
      $('#billing-pay-balance').on('change', function () {
          var isChecked = $(this).prop('checked');
          $('#balance-row').toggle(isChecked);
      });
  });

  $(document).ready(function () {
      function updateContent() {
          var isChecked = $('#billing-pay-balance').prop('checked'); // Get the checkbox status
          var cartTotal = parseFloat('{{ \Cart::getTotal() }}');
          // Check if the PHP variable exists and has a value
          var amountToCredit = parseFloat('{{ $amt_to_credit }}');
          var currency = '{{$curr}}';
          var updatedValue = 0;

          // Calculate the updated value based on the checkbox status and PHP values
          if(isChecked){
              if(cartTotal<=amountToCredit){
                  updatedValue = 0;
              }
              else{
                  updatedValue = cartTotal - amountToCredit
              }
          }
          else{
                  updatedValue = cartTotal;
          }
          // Make an AJAX request to the API endpoint
          $.ajax({
              type: "GET",
              url: "{{ url('format-currency') }}",
              data: {
                  amount: updatedValue,
                  currency: currency
              },
              success: function (data) {
                  // Update the content in the HTML element with the formatted value
                  $('#balance-content').html(data.formatted_value);
              },
              error: function (xhr, status, error) {
                  console.log(error);
              }
          });
      }

      // Initial update on page load
      updateContent();

      // Update content when the checkbox is clicked
      $('#billing-pay-balance').on('change', function () {
          updateContent();
      });
  });


</script>
    <style>
        .underline-label {
            display: inline-block; /* Ensure the label takes the width of its content */
            border-bottom: 0.5px solid gray; /* Adjust color and thickness as needed */
            width: 100%; /* Make the underline span the entire width of the container */
            padding-bottom: 3px; /* Add some spacing between label and underline */
        }

    </style>
@endsection
