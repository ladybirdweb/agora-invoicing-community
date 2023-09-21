@extends('themes.default1.layouts.front.master')
@section('title')
    Stripe
@stop
@section('page-heading')
    Stripe
@stop
@section('page-heading')
 Checkout
@stop
@section('breadcrumb')
 <style>
        .horizontal-images {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .horizontal-images img {
            height: auto;
            width: 12%;
            margin-right: 5px;
        }
        .custom-close {
        position: absolute;
        top: -20px;
        right: -20px;
        width: 30px;
        height: 30px;
        background-color: red;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 20px;
      }

    </style>
<script src="https://js.stripe.com/v3/"></script>

 @if(Auth::check())
     <li><a href="{{url('my-invoices')}}">Home</a></li>
 @else
     <li><a href="{{url('login')}}">Home</a></li>
 @endif
 <li><a href="{{url('checkout')}}">Checkout</a></li>
 <li class="active">Stripe</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
<style>
 .fa-1x {
            font-size: 17px;
            margin-right: 5px;
            margin-top: 2px;
        }
</style>
<?php
 $taxAmt = 0;
$cartSubtotalWithoutCondition = 0;
$currency = $invoice->currency;
?>

<div class="row">

    <div class="col-md-8">


         <div class="card card-default" style="margin-bottom: 40px;">
            <div class="card-header">
                <h4 class="card-title m-0">
                        Payment
                </h4>
            </div>


            <div class="panel-body">

    
       
        
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
                                <th class="product-invoice">
                                    Invoice No
                                </th>
                                <th class="product-version">
                                    Version
                                </th>
                                <th class="product-agents">
                                    Agents
                                </th>

                                <th class="product-quantity">
                                    Quantity
                                </th>
                                <th class="product-total">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <!----------------WHEN REGULAR PAYMENT--------------->
                            @if($regularPayment)

                               @forelse(Cart::getContent() as $item)
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
                                <td class="product-invoice">
                                    <a href="{{url('my-invoice/'.$invoice->id)}}" target="_blank">{{$invoice->number}}</a>
                                </td>
                                <td class="product-version">
                                    @if($item->associatedModel->version)
                                    {{$item->associatedModel->version}}
                                    @else
                                    Not available
                                    @endif
                                </td>
                                <th class="product-agents">
                                    {{($item->attributes->agents)?$item->attributes->agents:'Unlimited'}}
                                </th>



                                <td class="product-quantity">
                                    {{$item->quantity}}
                                </td>
                                <td class="product-total">
                                    @if(\Session::has('togglePrice') && $item->id == \Session::get('productid'))
                                    <span class="amount">{{currencyFormat($item->quantity * \Session::get('togglePrice'),$code = $item->attributes->currency)}}</span>
                                    @else
                                    <span class="amount">{{currencyFormat($item->quantity * $item->price,$code = $item->attributes->currency)}}</span>

                                    @endif
                                </td>
                            </tr>
                            @empty 
                        <p>Your Cart is void</p>
                        @endforelse
                        


                    </table>
                    
                    

                </div>
               </div>
        </div>
    </div>
    <div class="col-md-4">
         <div class="card card-default">
         <div class="card-header" style="height: 50px; padding-left: 8px"> 
        <h4 class="heading-primary" >Cart Total</h4>
    </div>
        <table class="cart-totals m-2">
            <tbody>
                <tr class="cart-subtotal">
                  
                    <th>
                        <strong>Cart Subtotal</strong>
                    </th>
                    <td>
                        <span class="amount">{{currencyFormat($cartSubtotalWithoutCondition,$code = $currency)}}</span>
                    </td>
                </tr>
                 @if(Session::has('code'))
                  <tr class="cart-subtotal">

                    <th>
                        <strong>Discount</strong>
                    </th>
                    <td>
                        <?php
                        if (strpos(\Session::get('codevalue'), '%') == true) {
                                $discountValue = \Session::get('codevalue');
                            } else {
                                $discountValue = currencyFormat(\Session::get('codevalue'),$code = $item->attributes->currency);
                            }
                        ?>

                        {{$discountValue}}
                    </td>
                </tr>
                @endif
                
                @if(count(\Cart::getConditionsByType('tax')) == 1)
                @foreach(\Cart::getConditionsByType('tax') as $tax)



                 @if($tax->getName()!= 'null')
                <tr class="Taxes">
                    <?php
                    $bifurcateTax = bifurcateTax($tax->getName(),$tax->getValue(),$item->attributes->currency, \Auth::user()->state, \Cart::getContent()->sum('price'));
                    ?>
                   <th>

                       <span style="display: inline-block; margin-left: 20px;"><b>{!! $bifurcateTax['html'] !!}</b></span><br/>

                   </th>
                    <td>
                     {!! $bifurcateTax['tax'] !!}
                  </td>
                  
                   
                </tr>
                @endif
                @endforeach

                @else
                @foreach(Cart::getContent() as $tax)
                @if($tax->conditions)
                <tr class="Taxes">
                    <?php
                    $bifurcateTax = bifurcateTax($tax->conditions->getName(),$tax->conditions->getValue(),$item->attributes->currency, \Auth::user()->state, $tax->price*$tax->quantity);
                    ?>
                   <th>

                       <span style="display: inline-block; margin-left: 20px;"><b>{!! $bifurcateTax['html'] !!}</b></span><br/>


                   </th>
                    <td>
                     {!! $bifurcateTax['tax'] !!}
                  </td>
                  
                   
                </tr>
         
                @endif
                
                @endforeach
               @endif
            

                @if(count(\Cart::getConditionsByType('fee')))
                 @foreach(\Cart::getConditionsByType('fee') as $fee)
                 <tr>
                     <th>
                        <strong>{!! $fee->getName() !!}</strong><br/>

                    </th>
                    <td>
                     {!! $fee->getValue() !!}
                  </td>
                 </tr>
                 @endforeach
                @endif

                @if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance'))
                    <tr class="cart-subtotal" style="color: indianred">
                            <?php
                            $amt_to_credit = \DB::table('payments')
                                ->where('user_id', \Auth::user()->id)
                                ->where('payment_method','Credit Balance')
                                ->where('payment_status','success')
                                ->where('amt_to_credit','!=',0)
                                ->value('amt_to_credit');
                            if (\Cart::getTotal() <= $amt_to_credit) {
                                $cartBalance = \Cart::getTotal();
                            } else {
                                $cartBalance = $amt_to_credit;
                            }
                            ?>

                        <th>
                            <strong>Balance</strong>

                        </th>
                        <td>
                            -{{$dd=currencyFormat($cartBalance,$code = $item->attributes->currency)}}
                        </td>
                    </tr>
                @endif
                 
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                            <?php
                            if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance')) {
                                if (\Cart::getTotal() <= $amt_to_credit) {
                                    $amount = 0;
                                } else {
                                    $amount = \Cart::getTotal()-$amt_to_credit;
                                }
                            }
                            ?>
                    <b><span class="amount">{{currencyFormat($amount,$code = $item->attributes->currency)}} </span></b>


                    </td>
                </tr>


                @else

              <!-----------------WHEN RENEWAL--------------------->

                 @forelse($items as $item)
                    @php
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
                        <td class="product-invoice">
                            <a href="{{url('my-invoice/'.$invoice->id)}}" target="_blank">{{$invoice->number}}</a>
                        </td>
                        <td class="product-version">
                             @if($product->version)
                            {{$product->version}}
                            @else 
                            Not available
                            @endif
                        </td>
                        <td class="product-agents">
                            {{($item->agents)?$item->agents:'Unlimited'}}
                        </td>
                         

                        <td class="product-quantity">
                            {{$item->quantity}}
                        </td>
                        <td class="product-total">
                            <span class="amount">{{currencyFormat($item->regular_price,$code = $currency)}}</span>
                        </td>
                    </tr>
                    @empty 
                <p>Your Cart is void</p>
                @endforelse
                


            </table>
                    
                    

                </div>
               </div>
        </div>
    </div>
    <div class="col-md-4 ">
        <div class="card card-default">
            <div class="card-header" style="height: 50px;">
         
        <h4 class="heading-primary">Cart Total</h4>
    </div>
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

                 @if(Session::has('code'))
                  <tr class="cart-subtotal">

                    <th>
                        <strong>Discount</strong>
                    </th>
                    <td>
                         {{currencyFormat(\Session::get('codevalue'),$code = $currency)}}
                    </td>
                </tr>
                @endif

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

                            <span style="display: inline-block; margin-left: 20px;"><b>{!! $bifurcateTax['html'] !!}</b></span><br/>



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

                        {{currencyFormat($amount,$code = $currency)}}
                    </td>
                </tr>
                @endif

                @if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance'))
                    <tr class="cart-subtotal" style="color: indianred">
                        <th>
                            <strong>Balance</strong>

                        </th>
                        <td>
                            -{{$dd=currencyFormat($creditBalance,$code = $currency)}}
                        </td>
                    </tr>
                @endif
                @if(count(\Cart::getConditionsByType('fee')))
                    @foreach(\Cart::getConditionsByType('fee') as $fee)
                        <tr>
                            <th>
                                <strong>{!! $fee->getName() !!}</strong><br/>

                            </th>
                            <td>
                                {!! $fee->getValue() !!}
                            </td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                    <b><span class="amount">{{currencyFormat($amount,$code = $currency)}} </span></b>


                    </td>
                </tr>
                     

            @endif
                        


                    </table>
                    
                    
                <br> <div class="form-group">
                   <div class="col-md-12" id="stripe-modal">
        <input type="submit" name="submit" value="Place Your Order And Pay" id="stripe-button1" class="btn btn-primary " data-loading-text="Loading..." style="width:100%;margin-left: -6px">
    </div>
                </div>
                </div>
               </div>
        </div>
    </div>

</div>


<div class="modal fade" id="stripeModal" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" style="padding: 16px;">
             <div class="modal-header">
            <button style="position: absolute; top: -10px; right: -10px; width: 30px; height: 30px; border-radius: 50%; background-color: black;" type="button" class="close custom-close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 style="white-space: nowrap;" class="modal-title" id="defaultModalLabel">Stripe Payment</h4>

         <div class="horizontal-images">
        <img class="img-responsive" src="https://static.vecteezy.com/system/resources/previews/020/975/567/non_2x/visa-logo-visa-icon-transparent-free-png.png">
        <img class="img-responsive" src="https://pngimg.com/d/mastercard_PNG23.png">
        <img class="img-responsive" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ2lfp0fkZmeGd6aCOzuIBC1QDTvcyGcM6OGQ&usqp=CAU">
    </div>


               
            </div>
            <div class="col-md-12 ">
            <div class="modal-body">
     
                    <form method="POST" class="require-validation" id="submit_total" action="{{ url('stripe') }}" >
                        <div id="payment-element">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="card_no" type="number" class="form-control @error('card_no') is-invalid @enderror" name="card_no" value="{{ old('card_no') }}" required autocomplete="card_no" placeholder="Card No." autofocus>
                                @error('card_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <input id="exp_month" type="number" class="form-control @error('exp_month') is-invalid @enderror" name="exp_month" value="{{ old('exp_month') }}" required autocomplete="exp_month" placeholder="Exp. Month(02)" autofocus>
                                @error('exp_month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input id="exp_year" type="number" class="form-control @error('exp_year') is-invalid @enderror" name="exp_year" value="{{ old('exp_year') }}" required autocomplete="exp_year" placeholder="Exp. Year(20)" autofocus>
                                @error('exp_year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="cvv" type="password" class="form-control @error('cvv') is-invalid @enderror" name="cvv" required autocomplete="current-password" placeholder="CVV">
                                @error('cvv')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="amount" type="text" value={{currencyFormat($amount,$code=$currency)}} class="form-control @error('amount') is-invalid @enderror" required autocomplete="current-password" name="amount" placeholder="Amount" readonly>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" id="pay_now" class="btn btn-primary btn-block">
                                    {{ __('PAY NOW') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    </form>
   
        </div>
        </div>
        </div>
    </div>
</div> 

<script>
$(document).ready(function() {
    $("#submit_total").validate({
        rules: {
            card_no: {
                required: true,
                digits: true,
                minlength: 15,
                maxlength: 16
            },
            exp_month: {
                required: true,
                digits: true,
                minlength: 2,
                maxlength: 2
            },
            exp_year: {
                required: true,
                digits: true,
                minlength: 2,
                maxlength: 2,
                notPastYear: true
            },
            cvv: {
                required: true,
                digits: true,
                rangelength: [3, 4] 
            }
        },
        messages: {
            card_no: {
                required: "Card number is required",
                digits: "Please enter digits only",
                minlength: "Card number must be at least 15 digits",
                maxlength: "Card number cannot exceed 16 digits"
            },
            exp_month: {
                required: "Expiration month is required",
                digits: "Please enter digits only",
                minlength: "Expiration month must be 2 digits",
                maxlength: "Expiration month must be 2 digits"
            },
            exp_year: {
                required: "Expiration year is required",
                digits: "Please enter digits only",
                minlength: "Expiration year must be 2 digits",
                maxlength: "Expiration year must be 2 digits",
                notPastYear: "Expiration year cannot be in the past"
            },
             cvv: {
                required: "CVV is required",
                digits: "Please enter digits only",
                rangelength: "CVV must be either 3 or 4 digits"
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass("invalid-feedback");
            error.insertAfter(element);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        }
    });

    $.validator.addMethod("notPastYear", function(value, element) {
        var currentYear = new Date().getFullYear() % 100;
        var enteredYear = parseInt(value, 10);
        return enteredYear >= currentYear;
    }, "Expiration year cannot be in the past");
});
</script>








<script>
    $('#stripe-button1').on('click',function(){
        $('#stripeModal').modal('show');
    })

        $('#submit_total').submit(function(){
     $("#pay_now").html("<i class='fa fa-circle-o-notch fa-spin fa-1x ' ></i>Processing ...")
    $("#pay_now").prop('disabled', true);

  });
$(function() {
    var $form         = $(".require-validation");
  $('form.require-validation').bind('submit', function(e) {
    var $form         = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;
        $errorMessage.addClass('hide');
 
        $('.has-error').removeClass('has-error');
    $inputs.each(function(i, el) {
      var $input = $(el);
      if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorMessage.removeClass('hide');
        e.preventDefault();
      }
    });
  
    // if (!$form.data('cc-on-file')) {
    //   e.preventDefault();
    //   Stripe.setPublishableKey($form.data('stripe-publishable-key'));
    //   Stripe.createToken({
    //     number: $('.card-number').val(),
    //     cvc: $('.card-cvc').val(),
    //     exp_month: $('.card-expiry-month').val(),
    //     exp_year: $('.card-expiry-year').val()
    //   }, stripeResponseHandler);
    // }
  
  });
  
  function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
  
});
</script>
    <style>
        strong{
            margin-left: 20px;
        }
    </style>




@endsection
