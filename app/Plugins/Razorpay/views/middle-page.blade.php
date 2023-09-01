@extends('themes.default1.layouts.front.master')
@section('title')
    Razorpay
@stop
@section('page-heading')
    Razorpay
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
 <li><a href="{{url('checkout')}}">Checkout</a></li>
 <li class="active">Razorpay</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
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
$amt_to_credit = \DB::table('payments')
    ->where('user_id', \Auth::user()->id)
    ->where('payment_method','Credit Balance')
    ->where('payment_status','success')
    ->where('amt_to_credit','!=',0)
    ->value('amt_to_credit');

if (\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance')) {
    if ($invoice->grand_total <= $amt_to_credit) {
        $cartTotal = 0;
    } else {
        $cartTotal = $invoice->grand_total - $amt_to_credit;
    }
} else {
    $cartTotal = $invoice->grand_total;
}

if ($currency == 'INR'){
$orderData = [
'receipt'         => 3456,
'amount'          => round($cartTotal*100), // 2000 rupees in paise

'currency'        => 'INR',
'payment_capture' => 0 // auto capture
 
];


} else {

 $orderData = [
'receipt'         => 3456,
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
                                <th>
                                    Invoice
                                </th>
                                <th class="product-version">
                                    Version
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
    <div class="card card-default" style="margin-bottom: 40px;">
        <div class="card-header" style="height: 50px;">
         
        <h4 class="heading-primary">Cart Total</h4>
    </div>
        <table class="cart-totals">
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
                            -{{$balance=currencyFormat($cartBalance,$code = $item->attributes->currency)}}
                        </td>
                    </tr>
                @endif
                     
                <tr class="total">
                    <th>
                            <?php
                            if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance')) {
                                if (\Cart::getTotal() <= $amt_to_credit) {
                                    $totalPaid = 0;
                                } else {
                                    $totalPaid = \Cart::getTotal()-$amt_to_credit;
                                }
                            }
                            else{
                                $totalPaid = \Cart::getTotal();
                            }
                            ?>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                    <b><span class="amount">{{currencyFormat($totalPaid,$code = $item->attributes->currency)}} </span></b>


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
    <div class="col-md-4">
    <div class="card card-default" style="margin-bottom: 40px;">
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
                @if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance'))
                        <?php
                         $amt_to_credit = \DB::table('payments')
                        ->where('user_id', \Auth::user()->id)
                        ->where('payment_method','Credit Balance')
                        ->where('payment_status','success')
                        ->where('amt_to_credit','!=',0)
                        ->value('amt_to_credit');

                        if (\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance')) {
                            if (\Cart::getTotal() <= $amt_to_credit) {
                                $cartBalance = \Cart::getTotal();
                            } else {
                                $cartBalance = $amt_to_credit;
                            }
                        }
                        ?>

                @endif
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

                        {{currencyFormat($totalPaid,$code = $currency)}}
                    </td>
                </tr>
                @endif
                @if(\App\User::where('id',\Auth::user()->id)->value('billing_pay_balance'))
                <tr class="cart-subtotal" style="color: indianred">

                    <th>
                        <strong>Balance</strong>

                    </th>
                    <td>
                        -{{$balance=currencyFormat($cartBalance,$code = $currency)}}
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
                                    $totalPaid = 0;
                                } else {
                                    $totalPaid = \Cart::getTotal()-$amt_to_credit;
                                }
                            }
                            ?>
                    <b><span class="amount">{{currencyFormat($totalPaid,$code = $currency)}} </span></b>


                    </td>
                </tr>
                     

            @endif
                        


                    </table>
                    
                    
                <br> <div class="form-group">
                   <div class="col-md-12" id="not-razor">
        <input type="submit" name="submit" value="Place Your Order And Pay" id="rzp-button1" class="btn btn-primary " data-loading-text="Loading..." style="width:100%;margin-left: -6px">
    </div>
                </div>
                </div>
               </div>
        </div>
    </div>

</div>
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
    <style>
        strong{
            margin-left: 20px;
        }
    </style>


@endsection
