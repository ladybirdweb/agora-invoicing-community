@extends('themes.default1.layouts.front.master')
@section('title')
    Stripe
@stop
@section('page-heading')
    Place Order
@stop
@section('page-heading')
 Checkout
@stop
@section('breadcrumb')
@if(Auth::check())
        <li><a class="text-primary" href="{{url('my-invoices')}}">Home</a></li>
@else
     <li><a class="text-primary" href="{{url('login')}}">Home</a></li>
@endif
 <li class="active text-dark">Stripe</li>
@stop
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
        color: white !important;
        font-size: 20px;
      }
      
    .totals-cart{
       margin-left: -90px !important;
   }

    </style>
<script src="https://js.stripe.com/v3/"></script>

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
@if($regularPayment)
 <div role="main" class="main">

            <div class="container shop py-3">

                <div class="row">

                    <div class="col-lg-7 mb-4 mb-lg-0">

                        <form method="post" action="">

                            <div class="totals-cart">

                                <table class="shop_table cart">

                                    <thead>

                                    <tr class="text-color-dark">

                                        <th class="product-thumbnail">
                                            &nbsp;
                                        </th>

                                        <th class="product-name text-uppercase" width="">

                                            Product

                                        </th>
                                        <th class="product-invoice text-uppercase" width="">

                                            Invoice
                                        </th>

                                        <th class="product-price text-uppercase" width="">

                                            Version
                                        </th>

                                        <th class="product-quantity text-uppercase" width="">

                                            Quantity
                                        </th>
                                         <th class="product-agent text-uppercase" width="">

                                            Agents
                                        </th>

                                        <th class="product-subtotal text-uppercase text-end" style="position: relative;right: 40px;">

                                            Total
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse(Cart::getContent() as $item)
                                     @php
                                    $cartSubtotalWithoutCondition += $item->getPriceSum();
                                    @endphp


                                    <tr class="cart_table_item">

                                        <td class="product-thumbnail">

                                            <div class="product-thumbnail-wrapper">

                                                <a  onclick="removeItem('{{$item->id}}');" class="product-thumbnail-remove"  data-bs-toggle="tooltip" title="Remove Product">

                                                    <i class="fas fa-times"></i>
                                                </a>

                                                <span class="product-thumbnail-image"  data-bs-toggle="tooltip" title="Faveo Enterprise Advance">

                                                        <img width="90" height="90" alt="" class="img-fluid" src="{{$item->associatedModel->image}}">
                                                    </span>
                                            </div>
                                        </td>

                                        <td class="product-name">

                                            <span class="font-weight-semi-bold text-color-dark"> {{$item->name}}</span>
                                        </td>
                                        <td class="product-invoice">

                                            <span class="font-weight-semi-bold text-color-dark">
                                            <a href="{{url('my-invoice/'.$invoice->id)}}" target="_blank">{{$invoice->number}}</a>

                                            </span>
                                        </td>
                              


                                        <td class="product-price">

                                            <span class="amount font-weight-medium text-color-grey">
                                                    @if($item->associatedModel->version)
                                                    {{$item->associatedModel->version}}
                                                    @else
                                                    Not available
                                                    @endif
                                            </span>
                                        </td>

                                        <td class="product-quantity">

                                            <span class="amount font-weight-medium text-color-grey">{{$item->quantity}}</span>
                                        </td>
                                        <td class="product-agent">

                                            <span class="amount font-weight-medium text-color-grey">{{($item->attributes->agents)?$item->attributes->agents:'Unlimited'}}
                                            </span>
                                        </td>


                                        <td class="product-subtotal text-end">
                                            @if(\Session::has('togglePrice') && $item->id == \Session::get('productid'))

                                            <span class="amount text-color-dark font-weight-bold text-4">
                                                {{currencyFormat($item->quantity * \Session::get('togglePrice'),$code = $item->attributes->currency)}}
                                            </span>
                                            @else
                                            <span class="amount text-color-dark font-weight-bold text-4">
                                                {{currencyFormat($item->quantity * $item->price,$code = $item->attributes->currency)}}
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                     @empty 
                                    <p>Your Cart is void</p>


                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-5 position-relative">

                        <div class="card border-width-3 border-radius-0 border-color-hover-dark" data-plugin-sticky data-plugin-options="{'minWidth': 991, 'containerSelector': '.row', 'padding': {'top': 85}}">

                            <div class="card-body">

                                <h4 class="font-weight-bold text-uppercase text-4 mb-3">Your Order</h4>

                                <table class="shop_table cart-totals mb-3">

                                    <tbody>

                                    <tr>
                                        <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">Cart Subtotal</strong>
                                        </td>
                                        <td class="text-end align-top border-top-0">
                                            <span class="amount font-weight-medium text-color-grey">{{currencyFormat($cartSubtotalWithoutCondition,$code = $currency)}}</span>
                                        </td>
                                    </tr>
                                    @if(Session::has('code'))
                                       <tr>
                                        <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">Discount</strong>
                                        </td>
                                        <td class="text-end align-top border-top-0">
                                            <span class="amount font-weight-medium text-color-grey">
                                                <?php
                                                if (strpos(\Session::get('codevalue'), '%') == true) {
                                                        $discountValue = \Session::get('codevalue');
                                                    } else {
                                                        $discountValue = currencyFormat(\Session::get('codevalue'),$code = $item->attributes->currency);
                                                    }
                                                ?>

                                                {{$discountValue}}
                                            </span>
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
                                                        <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">{!! $bifurcateTax['html'] !!}
                                            </strong>
                                        </td>
                                                       <td class="text-end align-top border-top-0">
                                            <span class="amount font-weight-medium text-color-grey">
                                                         {!! $bifurcateTax['tax'] !!}
                                                     </span>
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
                                                        <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">
                                            {!! $bifurcateTax['html'] !!}</strong></td>
                                                        <td class="text-end align-top border-top-0">
                                            <span class="amount font-weight-medium text-color-grey">
                                                         {!! $bifurcateTax['tax'] !!}
                                                     </span>
                                                      </td>
                                                      
                                                       
                                                    </tr>
                                             
                                                    @endif
                                                    
                                                    @endforeach
                                                   @endif
                                                

                                                    @if(count(\Cart::getConditionsByType('fee')))
                                                     @foreach(\Cart::getConditionsByType('fee') as $fee)
                                                     <tr>
                                                         <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">{!! $fee->getName() !!}
                                            </strong>
                                        </td>
                                                          <td class="text-end align-top border-top-0">
                                            <span class="amount font-weight-medium text-color-grey">
                                                         {!! $fee->getValue() !!}
                                                     </span>
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

                                  <td class="border-top-0" style="color: indianred">
                                                        <strong class="d-block text-color-dark line-height-1 font-weight-semibold">Balance
                                                        </strong></td>
                                   <td class="text-end align-top border-top-0">
                                                        <span class="amount font-weight-medium text-color-grey">
                                        -{{$dd=currencyFormat($cartBalance,$code = $item->attributes->currency)}}
                                    </span>
                                    </td>
                                </tr>
                                @endif

      

                                    <tr class="total">

                                        <td>
                                            <strong class="text-color-dark text-3-5">Total</strong>
                                        </td>

                                        <?php
                                        if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance')) {
                                            if (\Cart::getTotal() <= $amt_to_credit) {
                                                $amount = 0;
                                            } else {
                                                $amount = \Cart::getTotal()-$amt_to_credit;
                                            }
                                        }
                                        ?>
                                        <td class="text-end">
                                            <strong class="text-color-dark"><span class="amount text-color-dark text-5">{{currencyFormat($amount,$code = $item->attributes->currency)}}</span></strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>


                                <input type="submit" name="submit" value="Place Your Order And Pay" id="stripe-button1"  class="btn btn-dark btn-modern w-100 text-uppercase text-3 py-3" data-loading-text="Loading...">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @else
         <div role="main" class="main">

            <div class="container shop py-3">

                <div class="row">

                    <div class="col-lg-7 mb-4 mb-lg-0">

                        <form method="post" action="">

                            <div class="totals-cart">

                                <table class="shop_table cart">

                                    <thead>

                                    <tr class="text-color-dark">

                                        <th class="product-thumbnail" width="">
                                            &nbsp;
                                        </th>

                                        <th class="product-name text-uppercase" width="">

                                            Product

                                        </th>
                                        <th class="product-invoice text-uppercase" width="">

                                            Invoice
                                        </th>

                                        <th class="product-price text-uppercase" width="">

                                            Version
                                        </th>

                                        <th class="product-quantity text-uppercase" width="">

                                            Quantity
                                        </th>
                                         <th class="product-agent text-uppercase" width="">

                                            Agents
                                        </th>

                                        <th class="product-subtotal text-uppercase text-end" width="">

                                            Total
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                     @forelse($items as $item)
                                    @php
                                    $taxName[] =  $item->tax_name.'@'.$item->tax_percentage;
                                    if ($item->tax_name != 'null') {
                                        $taxAmt +=  $item->subtotal;
                                     }
                                    @endphp

                                    <tr class="cart_table_item">

                                        <td class="product-thumbnail">

                                            <div class="product-thumbnail-wrapper">

                                                <span class="product-thumbnail-image"  data-bs-toggle="tooltip" title="{{$item->product_name}}">

                                                        <img width="90" height="90" alt="" class="img-fluid" src="{{$product->image}}">
                                                    </span>
                                            </div>
                                        </td>

                                        <td class="product-name">

                                            <span class="font-weight-semi-bold text-color-dark">{{$item->product_name}}</span>
                                        </td>
                                        <td class="product-invoice">

                                            <span class="font-weight-semi-bold text-color-dark">
                                            <a href="{{url('my-invoice/'.$invoice->id)}}" target="_blank">{{$invoice->number}}</a>

                                            </span>
                                        </td>
                              


                                        <td class="product-price">

                                            <span class="amount font-weight-medium text-color-grey">
                                                       @if($product->version)
                                                    {{$product->version}}
                                                    @else 
                                                    Not available
                                                    @endif
                                            </span>
                                        </td>

                                        <td class="product-quantity">

                                            <span class="amount font-weight-medium text-color-grey">{{$item->quantity}}</span>
                                        </td>
                                        <td class="product-agent">

                                            <span class="amount font-weight-medium text-color-grey">{{($item->agents)?$item->agents:'Unlimited'}}
                                            </span>
                                        </td>


                                        <td class="product-subtotal text-end">

                                            <span class="amount text-color-dark font-weight-bold text-4">
                                                {{currencyFormat($item->regular_price,$code = $currency)}}
                                            </span>

                                        </td>
                                    </tr>
                                     @empty 
                                    <p>Your Cart is void</p>


                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>

                <div class="col-lg-5 position-relative">

                        <div class="card border-width-3 border-radius-0 border-color-hover-dark" data-plugin-sticky data-plugin-options="{'minWidth': 991, 'containerSelector': '.row', 'padding': {'top': 85}}">

                            <div class="card-body">

                                <h4 class="font-weight-bold text-uppercase text-4 mb-3">Your Order</h4>

                                <table class="shop_table cart-totals mb-3">

                                    <tbody>

                                    <tr>
                                        <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">Cart Subtotal</strong>
                                        </td>
                                          <?php 
                                        $subtotals = App\Model\Order\InvoiceItem::where('invoice_id',$invoice->id)->pluck('regular_price')->toArray();
                                        $subtotal = array_sum($subtotals);
                                        ?>
                                        <td class="text-end align-top border-top-0">
                                            <span class="amount font-weight-medium text-color-grey">{{currencyFormat($subtotal,$code = $currency)}}</span>
                                        </td>
                                    </tr>
                                    @php
                                    $taxName = array_unique($taxName);
                                    @endphp

                                     @if(Session::has('code'))
                                      <tr class="cart-subtotal">

                                        <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">Discount</strong>
                                        </td>
                                        <td class="text-end align-top border-top-0">
                                            <span class="amount font-weight-medium text-color-grey">
                                             {{currencyFormat(\Session::get('codevalue'),$code = $currency)}}</span>
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
                                            <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">{!! $bifurcateTax['html'] !!}



                                            </strong>
                                        </td>
                                           <td class="text-end align-top border-top-0">
                                            <span class="amount font-weight-medium text-color-grey">
                                               
                                                {!! $bifurcateTax['tax'] !!}
                                            </span>

                                            </td>
                                        </tr>
                                 
                                   
                                        @endif
                                        @endforeach

                                        @if($paid)

                                        <tr class="total">
                                         <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">Paid</strong>
                                        </td>
                                           <td class="border-top-0 text-end">
                                            <span class="amount font-weight-medium">

                                            {{currencyFormat($paid,$code = $currency)}}
                                        </span>
                                        </td>
                                    </tr>

                                    <tr class="total">
                                        <td class="border-top-0">
                                            <strong class="d-block text-color-dark line-height-1 font-weight-semibold">Balance</strong>
                                        </td>
                                            <td class="border-top-0 text-end">
                                            <span class="amount font-weight-medium">

                                            {{currencyFormat($amount,$code = $currency)}}
                                        </span>
                                        </td>
                                    </tr>
                                    @endif

                                    @if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance'))
                                        <tr class="cart-subtotal" style="color: indianred">
                                             <td class="border-top-0">
                                            <strong class="text-color-dark">Balance</strong>

                                            </td>
                                              <td class="border-top-0 text-end">
                                            <span class="amount font-weight-medium">
                                                -{{$dd=currencyFormat($creditBalance,$code = $currency)}}
                                            </span>
                                            </td>
                                        </tr>
                                    @endif

                                        @if(count(\Cart::getConditionsByType('fee')))
                                        @foreach(\Cart::getConditionsByType('fee') as $fee)
                                            <tr>
                                                 <td class="border-top-0">
                                            <strong class="text-color-dark">{!! $fee->getName() !!}</strong><br/>

                                                </td>
                                                <td class="border-top-0 text-end">
                                            <span class="amount font-weight-medium">
                                                    {!! $fee->getValue() !!}
                                                </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif


                                    <tr class="total">

                                        <td>
                                            <strong class="text-color-dark text-3-5">Total</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-color-dark"><span class="amount text-color-dark text-5">{{currencyFormat($amount,$code = $currency)}}</span></strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                 <input type="submit" name="submit" value="Place Your Order And Pay" id="stripe-button1"  class="btn btn-dark btn-modern w-100 text-uppercase text-3 py-3" data-loading-text="Loading...">
                                                             </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endif


        <div class="modal fade" id="stripeModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="padding: 16px;">
             <div class="modal-header">
            <button style="position: absolute; top: -10px; right: -10px; width: 30px; height: 30px; border-radius: 50%; background-color: black;" type="button" class="close custom-close" aria-hidden="true">&times;</button>
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
                                <input id="card_no" type="tel" class="form-control @error('card_no') is-invalid @enderror" name="card_no" value="{{ old('card_no') }}" required autocomplete="card_no" placeholder="Card No." autofocus>
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
     $('.custom-close').click(function(){
               location.reload();
               });
$(document).ready(function() {
    $("#submit_total").validate({
        rules: {
            card_no: {
                required: true,
                digits: true,
                minlength: 15,
                maxlength: 16,
            },
            exp_month: {
                required: true,
                digits: true,
                minlength: 2,
                maxlength: 2,
                range: [1, 12] 
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
                maxlength: "Expiration month must be 2 digits",
                range: "Expiration month cannot exceed 12"
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

    var $form = $("#submit_total");
    var $cardNo = $("#card_no");
    var $expMonth = $("#exp_month");
    var $expYear = $("#exp_year");
    var $cvv = $("#cvv");
    var $payButton = $("#pay_now");

    $form.on("submit", function(event) {
        // Check if each field is valid
        var isCardNoValid = $cardNo.valid();
        var isExpMonthValid = $expMonth.valid();
        var isExpYearValid = $expYear.valid();
        var isCvvValid = $cvv.valid();

        if (isCardNoValid && isExpMonthValid && isExpYearValid && isCvvValid) {
            $payButton.prop("disabled", true);
            $payButton.html("<i class='fa fa-circle-o-notch fa-spin fa-1x'></i> Processing ...");
        } else {
                event.preventDefault();
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


@endsection
