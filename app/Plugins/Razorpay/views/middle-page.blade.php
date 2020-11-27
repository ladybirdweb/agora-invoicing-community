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
if ($currency == 'INR'){

$orderData = [
'receipt'         => 3456,
'amount'          => round($invoice->grand_total*100), // 2000 rupees in paise

'currency'        => 'INR',
'payment_capture' => 0 // auto capture
 
];


} else {

 $orderData = [
'receipt'         => 3456,
'amount'          =>  round($invoice->grand_total*100), // 2000 rupees in paise

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
    "Amount Paid"       => $invoice->grand_total*100,



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
                                    <span class="amount">{{currencyFormat($item->price,$code = $item->attributes->currency)}}</span>
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
         
        <h4 class="heading-primary">Cart Totals</h4>
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
                     
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                    <strong><span class="amount">{{currencyFormat(\Cart::getTotal(),$code = $item->attributes->currency)}} </span></strong>


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

                        {{currencyFormat($totalPaid,$code = $currency)}}
                    </td>
                </tr>
                @endif
                     
                <tr class="total">
                    <th>
                        <strong>Order Total</strong>
                    </th>
                    <td>
                    <strong><span class="amount">{{currencyFormat($totalPaid,$code = $currency)}} </span></strong>


                    </td>
                </tr>
                     

            @endif
                        


                    </table>
                    
                    
                 <div class="form-group">
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


@endsection
