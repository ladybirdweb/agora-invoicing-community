@extends('themes.default1.layouts.front.master')
@section('title')
    {{$gateway}}
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
 <li class="active text-dark">{{$gateway}}</li>
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
   .img-fluid{
       max-width: 300px !important;
   }
     .border-top{
            border-bottom: 0.5px solid #000;
            border-color: lightgrey;

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
    .heading {
        font-size: 14px;
    }

</style>

<?php
 $taxAmt = 0;
$cartSubtotalWithoutCondition = 0;
$currency = $invoice->currency;

$feeAmount = intval(ceil($invoice->grand_total*0.01));
?>
<?php
$taxAmt = 0;
$cartSubtotalWithoutCondition = 0;
 
use Razorpay\Api\Api;
 $merchant_orderid= generateMerchantRandomString();  

function generateMerchantRandomString($length = 10) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}
 $api = new Api($rzp_key, $rzp_secret);
$currency = $invoice->currency;
if (\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance') && $regularPayment) {
    $amt_to_credit = \DB::table('payments')
    ->where('user_id', \Auth::user()->id)
    ->where('payment_method','Credit Balance')
    ->where('payment_status','success')
    ->where('amt_to_credit','!=',0)
    ->value('amt_to_credit');
    if ($invoice->grand_total <= $amt_to_credit) {
        $cartTotal = 0;
    } else {
        $cartTotal = $invoice->grand_total - $amt_to_credit;
    }
} else {
    $cartTotal = $invoice->grand_total;
}
$cartTotal = intval($cartTotal);
if ($currency == 'INR'){
$orderData = [
'receipt'         => '3456',
'amount'          => round($cartTotal*100), // 2000 rupees in paise

'currency'        => 'INR',
'payment_capture' => 0 // auto capture
 
];


} else {

 $orderData = [
'receipt'         => '3456',
'amount'          =>  round($cartTotal*100), // 2000 rupees in paise

'currency'        => $currency,
'payment_capture' => 0 // auto capture
     
];
}
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];




$data = [
    "key"               => $rzp_key,
    "name"              => 'Faveo Helpdesk',
     "prefill"=> [
        "contact"=>    \Auth::user()->mobile_code .\Auth::user()->mobile,
        "email"=>      \Auth::user()->email,
    ],
    "description"       =>  'Order for Invoice No' .-$invoice->number,
    "notes"             => [
    "First Name"         => \Auth::user()->first_name,
    "Last Name"         =>  \Auth::user()->last_name,
    "Company Name"      => \Auth::user()->company,
    "Address"           =>  \Auth::user()->address,
    "Email"             =>  \Auth::user()->email,
    "Country"           =>  \Auth::user()->country,
    "State"             => \Auth::user()->state,
    "City"              => \Auth::user()->town,
    "Zip"               => \Auth::user()->zip,
    "Amount Paid"       => $cartTotal*100,



    "merchant_order_id" =>  $merchant_orderid,
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];


$json = json_encode($data);




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

                                        <th class="product-name text-uppercase heading" width="">

                                            Product

                                        </th>
                                     

                                        <th class="product-quantity text-uppercase heading" width="">

                                            Quantity
                                        </th>
                                         <th class="product-agent text-uppercase heading" width="">

                                            Agents
                                        </th>

                                        <th class="product-subtotal text-uppercase heading">

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

                                                <a  onclick="removeItem('{{$item->id}}');" class="product-thumbnail-remove"  data-bs-toggle="tooltip" title="Remove Product" style="top: -15px;right: 15px;">

                                                    <i class="fas fa-times"></i>
                                                </a>

                                                <span class="product-thumbnail-image">

                                                        <img width="90" height="90" alt="" class="img-fluid" src="{{$item->associatedModel->image}}"   data-bs-toggle="tooltip" title="{{$item->name}}">
                                                    </span>
                                            </div>
                                        </td>

                                        <td class="product-name">

                                            <span class="font-weight-semi-bold text-color-dark" style="font-family: Arial;"> {{$item->name}}</span>
                                        </td>
                            
                                        <td class="product-quantity">

                                            <span class="amount font-weight-medium text-color-grey">{{$item->quantity}}</span>
                                        </td>
                                        <td class="product-agent">

                                            <span class="amount font-weight-medium text-color-grey">{{($item->attributes->agents)?$item->attributes->agents:'Unlimited'}}
                                            </span>
                                        </td>


                                        <td class="product-subtotal">
                                            @if(\Session::has('togglePrice') && $item->id == \Session::get('productid'))

                                            <span class="amount text-color-dark font-weight-bold text-4" style="font-family: Arial;">
                                                {{currencyFormat($item->quantity * \Session::get('togglePrice'),$code = $item->attributes->currency)}}
                                            </span>
                                            @else
                                            <span class="amount text-color-dark font-weight-bold text-4" style="font-family: Arial;">
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

                                    <tr class="border-top">
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
                                                   <?php
                                                        $bifurcateTax = bifurcateTax($tax->getName(), $tax->getValue(), $item->attributes->currency, \Auth::user()->state, \Cart::getContent()->sum('price'));
                                                        $partsHtml = explode('<br>', $bifurcateTax['html']);
                                                        $taxParts = explode('<br>', $bifurcateTax['tax']);
                                                        ?>
                                                    
                                                
                                                   @foreach($partsHtml as $index => $part)
                                                   @php
                                                        $parts = explode('@', $part);
                                                        $cgst = $parts[0];
                                                        $percentage = $parts[1]; 
                                                    @endphp
                                                    <tr class="Taxes border-top-0 border-bottom">
                                                        <th class="d-block font-weight-medium text-color-grey ">{{ $cgst }}
                                                            <label style="font-size: 12px;font-weight: normal;">({{$percentage}})</label>
                                                        </th>
                                                        <td data-title="CGST" class="text-end align-top border-top-0">
                                                            <span class="align-top border-top-0">
                                                                <span class="amount font-weight-medium text-color-grey"></span>{{ $taxParts[$index] }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                  @endforeach
                                                    @endif
                                                    @endforeach

                                                    @else
                                                    @foreach(Cart::getContent() as $tax)
                                                    @if($tax->conditions)
                                                      <?php
                                                        $bifurcateTax = bifurcateTax($tax->conditions->getName(),$tax->conditions->getValue(),$item->attributes->currency, \Auth::user()->state, $tax->price*$tax->quantity);

                                                        $partsHtml = explode('<br>', $bifurcateTax['html']);
                                                        $taxParts = explode('<br>', $bifurcateTax['tax']);
                                                        ?>
                                                    
                                                  @if (strpos($bifurcateTax['html'], 'null') === false)
                                                     @foreach($partsHtml as $index => $part)
                                                   @php
                                                        $parts = explode('@', $part);
                                                        $cgst = $parts[0];
                                                        $percentage = $parts[1]; 
                                                    @endphp
                                                    <tr class="Taxes border-top-0 border-bottom">
                                                        <th class="d-block font-weight-semibold text-color-grey ">{{ $cgst }}
                                                            <label style="font-size: 12px;font-weight: normal;">({{$percentage}})</label>
                                                        </th>
                                                        <td data-title="CGST" class="text-end align-top border-top-0">
                                                            <span class="align-top border-top-0">
                                                                <span class="amount font-weight-medium text-color-grey"></span>{{ $taxParts[$index] }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                  @endforeach
                                                  @endif
                                             
                                                    @endif
                                                    
                                                    @endforeach
                                                   @endif
                                                

                                                    @if(count(\Cart::getConditionsByType('fee')))
                                                     @foreach(\Cart::getConditionsByType('fee') as $fee)
                                                     <tr>
                                                         <td class="border-top-0">
                                            <strong class="d-block text-color-grey  font-weight-semibold">{!! $fee->getName() !!}
                                                <label style="font-size: 12px;font-weight: normal;">({!! $fee->getValue() !!})</label>
                                            </strong>
                                        </td>
                                         @if($fee->getValue() === '0%')
                                                    <td class="text-end align-top border-top-0">
                                                        <span class="amount font-weight-medium text-color-grey">
                                                            0
                                                        </span>
                                                    </td>
                                                @else
                                                    <td class="text-end align-top border-top-0">
                                                        <span class="amount font-weight-medium text-color-grey">
                                                            {{ currencyFormat($feeAmount, $code = $item->attributes->currency) }}
                                                        </span>
                                                    </td>
                                                @endif
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

                              <input type="submit" name="submit" value="Place Your Order And Pay" id="{{ strtolower($gateway) === 'stripe' ? 'stripe-button1' : 'rzp-button1' }}" class="btn btn-dark btn-modern w-100 text-uppercase text-3 py-3" data-loading-text="Loading...">


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

                                        <th class="product-name text-uppercase heading" width="">

                                            Product

                                        </th>
                                     

                                        <th class="product-quantity text-uppercase heading" width="">

                                            Quantity
                                        </th>
                                         <th class="product-agent text-uppercase heading" width="">

                                            Agents
                                        </th>

                                        <th class="product-subtotal text-uppercase heading" width="">

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
        

                                        <td class="product-quantity">

                                            <span class="amount font-weight-medium text-color-grey">{{$item->quantity}}</span>
                                        </td>
                                        <td class="product-agent">

                                            <span class="amount font-weight-medium text-color-grey">{{($item->agents)?$item->agents:'Unlimited'}}
                                            </span>
                                        </td>


                                        <td class="product-subtotal">

                                            <span class="amount text-color-dark font-weight-bold text-3">
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

                                    <tr class="border-top">
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
                                                                
                                                           
                                             <?php
                                            $bifurcateTax = bifurcateTax($taxDetails[0],$taxDetails[1],\Auth::user()->currency, \Auth::user()->state, $taxAmt);
                                            $partsHtml = explode('<br>', $bifurcateTax['html']);
                                                        $taxParts = explode('<br>', $bifurcateTax['tax']);
                                                        ?>
                                                    
                                                
                                                    @foreach($partsHtml as $index => $part)
                                                   @php
                                                        $parts = explode('@', $part);
                                                        $cgst = $parts[0];
                                                        $percentage = $parts[1]; 
                                                    @endphp
                                                    <tr class="Taxes border-top-0 border-bottom">
                                                        <th class="d-block font-weight-semibold text-color-grey ">{{ $cgst }}
                                                            <label style="font-size: 12px;font-weight: normal;">({{$percentage}})</label>
                                                        </th>
                                                        <td data-title="CGST" class="text-end align-top border-top-0">
                                                            <span class="align-top border-top-0">
                                                                <span class="amount font-weight-medium text-color-grey"></span>{{ $taxParts[$index] }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                  @endforeach
                                 
                                   
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
                                            <strong class="text-color-grey font-weight-semibold">{!! $fee->getName() !!}</strong>
                                             <label style="font-size: 12px;font-weight: normal;">({!! $fee->getValue() !!})</label>
                                                </td>
                                              @if($fee->getValue() === '0%')
                                            <td class="align-top border-top-0">
                                                <span class="amount font-weight-medium text-color-grey">
                                                    0
                                                </span>
                                            </td>
                                        @else
                                            <td class="text-end align-top border-top-0">
                                                <span class="amount font-weight-medium text-color-grey">
                                                    {{ currencyFormat($feeAmount, $code = $item->attributes->currency) }}
                                                </span>
                                            </td>
                                        @endif
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
                              <input type="submit" name="submit" value="Place Your Order And Pay" id="{{ strtolower($gateway) === 'stripe' ? 'stripe-button1' : 'rzp-button1' }}" class="btn btn-dark btn-modern w-100 text-uppercase text-3 py-3" data-loading-text="Loading...">

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
                <h4 style="white-space: nowrap;" class="modal-title" id="defaultModalLabel">{{ __('message.enter_card_details') }}</h4>

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
                                <div id="card-errors" style="display: none;"></div>
                                <div id="card-element" class="form-control"></div>
                                <input type="hidden" name="stripeToken" id="stripe-token" value="">
                                <div id="card-errors" style="color: red; margin-top: 10px;"></div>
                            </div>
                        </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="amount" type="text" value={{currencyFormat($amount,$code=$currency)}} class="form-control @error('amount') is-invalid @enderror" required autocomplete="current-password" name="amount" placeholder="Amount" disabled>
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

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe("{{ $stripe_key }}");
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    function generateStripeToken(event) {
        event.preventDefault();

        // Reset previous error message
        var alertBox = document.getElementById('card-errors');
        alertBox.style.display = 'none';
        alertBox.innerHTML = '';

        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                // Show Bootstrap alert with error message
                alertBox.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${result.error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
                alertBox.style.display = 'block';
                $("#pay_now").prop("disabled", false);
                $("#pay_now").html("{{ __('PAY NOW') }}");
            } else {
                if (result.token) {
                    document.getElementById('stripe-token').value = result.token.id;
                    document.getElementById('submit_total').submit();
                }
            }
        });
    }

    document.getElementById('submit_total').addEventListener('submit', generateStripeToken);
</script>
<script>
     $('.custom-close').click(function(){
               location.reload();
               });
$(document).ready(function() {
    var $form = $("#submit_total");
    var $payButton = $("#pay_now");

    $form.on("submit", function(event) {
        $payButton.prop("disabled", true);
        $payButton.html("<i class='fa fa-circle-o-notch fa-spin fa-1x'></i> Processing ...");
    });
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

 <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
 <form name='razorpayform' action="{!!url('payment/'.$invoice->id)!!}" method="POST">
      {{ csrf_field() }}
 <!--<button id="rzp-button1" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">Pay Now</button>-->
<!--<form name='razorpayform' action="verify.php" method="POST">                                -->
<input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
<input type="hidden" name="razorpay_signature"  id="razorpay_signature" >


</form>

 <script>

    // Checkout details as a json
var options = <?php echo $json; ?>


/**
 * The entire list of Checkout fields is available at
 * https://docs.razorpay.com/docs/checkout-form#checkout-fields
 */
options.handler = function (response){
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;
   
    document.razorpayform.submit();
};

// Boolean whether to show image inside a white frame. (default: true)
options.theme.image_padding = false;

options.modal = {
    ondismiss: function() {
    },
    // Boolean indicating whether pressing escape key 
    // should close the checkout form. (default: true)
    escape: true,
    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
};

var rzp = new Razorpay(options);

document.getElementById('rzp-button1').onclick = function(e){
    
    rzp.open();
    e.preventDefault();
}
</script>


@endsection
