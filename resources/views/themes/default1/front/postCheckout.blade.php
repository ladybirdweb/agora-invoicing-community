@extends('themes.default1.layouts.front.master')
@section('title')
Checkout
@stop
@section('page-header')
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
<?php
 
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
    $displayCurrency = \Auth::user()->currency;
    $symbol = \Auth::user()->currency;
    if ($symbol == 'INR'){


$exchangeRate= '';


$orderData = [
    'receipt'         => 3456,
    'amount'          => round($invoice->grand_total*100), // 2000 rupees in paise

    'currency'        => 'INR',
    'payment_capture' => 0 // auto capture
     
];


} else {
 
     $url = "http://apilayer.net/api/live?access_key=$apilayer_key";
     $exchange = json_decode(file_get_contents($url));

     $exchangeRate = $exchange->quotes->USDINR;
     // dd($exchangeRate);
     $displayAmount =$exchangeRate * $invoice->grand_total ;


     $orderData = [
    'receipt'         => 3456,
    'amount'          =>  round($displayAmount)*100, // 2000 rupees in paise

    'currency'        => 'INR',
    'payment_capture' => 0 // auto capture
     
];
}
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorpayOrderId;
$displayAmount = $amount = $orderData['amount'];



$data = [


    "key"               => $rzp_key,


    "name"              => 'Faveo Helpdesk',
    "currency"          => 'INR',
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
    "Currency"          => \Auth::user()->currency,
    "Amount Paid"   => $invoice->grand_total,
    "Exchange Rate"   =>  $exchangeRate,



    "merchant_order_id" =>  $merchant_orderid,
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = 'USD';
    $data['display_amount']    =$invoice->grand_total;
    
}
$json = json_encode($data);


 $currency = \Auth::user()->currency;




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

                @if(Session::has('success'))
                <div class="alert alert-success alert-dismissable">


         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <strong><i class="far fa-thumbs-up"></i> Well done!</strong>
                    {{Lang::get('message.success')}}.


                    {!!Session::get('success')!!}
                </div>
                @endif
                <!-- fail message -->
                @if(Session::has('fails'))
                <div class="alert alert-danger alert-dismissable">

                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> 
                    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
                   


                    {{Session::get('fails')}}
                </div>
                @endif
                @if (count($errors) > 0)
                <div class="alert alert-danger">


                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> There were some problems with your input.<br><br>


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
                                <th class="product-name">
                                    Invoice No.
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

                                <td class="product-invoice">
                                    {{$invoice->number}}
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
                                <td class="product-name">

                                        
                                    <span class="amount">{{currency_format(intval($item->regular_price),$code = $currency)}}</span>
                                </td>
                            </tr>
                            @empty 
                        <p>Your Cart is void</p>
                        @endforelse
                        


                    </table>
                    
                    <div class="col-md-12">

                <div class="form-group">
                   
                  
                   
                    <div class="col-md-6">
                        
                        {!! Form::hidden('invoice_id',$invoice->id) !!}
                        {!! Form::hidden('cost',$invoice->grand_total) !!}
                    </div>
                </div>
                   </div>

                </div>
               
                    <div class="col-md-12">




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
                        <strong><span class="amount">{{currency_format($subtotal,$code = $currency)}}</span></strong>
                    </td>
                </tr>
                @foreach($content as $attributes)
                <?php
                    $tax_attribute =  $attributes['attributes']['tax'];
                    $currency = $attributes['attributes']['currency']['currency'];
                    $symbol = $attributes['attributes']['currency']['symbol'];
                   ?>
                    @if ($tax_attribute[0]['name'] != null)
                @if($tax_attribute[0]['name']!='null' &&  $currency == "INR" && $tax_attribute[0]['tax_enable'] ==1)
                 @if($tax_attribute[0]['state']==$tax_attribute[0]['origin_state'] && $tax_attribute[0]['ut_gst']=='NULL' && $tax_attribute[0]['status'] ==1)
                <tr class="Taxes">
                    <th>
                        <strong>CGST<span>@</span>{{$tax_attribute[0]['c_gst']}}%</strong><br/>
                        <strong>SGST<span>@</span>{{$tax_attribute[0]['s_gst']}}%</strong><br/>
                       
                    </th>
                    <td>
                        <?php 
                        $cgst =  \App\Http\Controllers\Front\CartController::taxValue($tax_attribute[0]['c_gst'],$subtotal);
                        $sgst = \App\Http\Controllers\Front\CartController::taxValue($tax_attribute[0]['s_gst'],$subtotal);
                        ?>
                        {{currency_format( $cgst,$code = $currency)}} <br/>
                        {{currency_format($sgst,$code = $currency)}}<br/>
                       
                       
                    </td>


                </tr>
                @endif
               
                @if ($tax_attribute[0]['state']!=$tax_attribute[0]['origin_state'] && $tax_attribute[0]['ut_gst']=='NULL' && $tax_attribute[0]['status'] ==1)
               
                <tr class="Taxes">
                    <th>
                        <strong>{{$tax_attribute[0]['name']}}<span>@</span>{{$tax_attribute[0]['i_gst']}}%</strong>
                     
                    </th>
                    <td>
                        <?php
                        $igst = \App\Http\Controllers\Front\CartController::taxValue($tax_attribute[0]['i_gst'],$subtotal);
                        ?>
                        {{currency_format( $igst,$code = $currency)}}  <br/>
                      
                    </td>


                </tr>
                @endif

                @if ($tax_attribute[0]['state']!=$tax_attribute[0]['origin_state'] && $tax_attribute[0]['ut_gst']!='NULL' && $tax_attribute[0]['status'] ==1)
              
                <tr class="Taxes">
                    <th>
                       <strong>CGST<span>@</span>{{$tax_attribute[0]['c_gst']}}%</strong><br/>
                        <strong>UTGST<span>@</span>{{$tax_attribute[0]['ut_gst']}}%</strong>
                       
                    </th>
                    <td>
                        <?php
                        $cgst = \App\Http\Controllers\Front\CartController::taxValue($tax_attribute[0]['c_gst'],$subtotal);
                        $utgst = \App\Http\Controllers\Front\CartController::taxValue($tax_attribute[0]['ut_gst'],$subtotal);
                        ?>
                         {{currency_format( $cgst,$code = $currency)}}  <br/>
                          {{currency_format( $utgst,$code = $currency)}}  <br/>
                       
                    </td>


                </tr>
                @endif
                @endif

                 @if($tax_attribute[0]['name']!='null' && ($currency == "INR" && $tax_attribute[0]['tax_enable'] ==0 && $tax_attribute[0]['status'] ==1))
                 <tr class="Taxes">
                    <th>
                        <strong>{{$tax_attribute[0]['name']}}<span>@</span>{{$tax_attribute[0]['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>
                        <?php
                        $value = \App\Http\Controllers\Front\CartController::taxValue($tax_attribute[0]['rate'],$subtotal);
                        ?>
                         {{currency_format( $value,$code = $currency)}} <br/>
                         
                       
                    </td>
                  </tr>
                 @endif
           
                @if($tax_attribute[0]['name']!='null' && ($currency != "INR" && $tax_attribute[0]['tax_enable']==1 && $tax_attribute[0]['status'] ==1))
                  <tr class="Taxes">
                    <th>
                        <strong>{{$tax_attribute[0]['name']}}<span>@</span>{{$tax_attribute[0]['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>
                <?php
                 $value = \App\Http\Controllers\Front\CartController::taxValue($tax_attribute[0]['rate'],Cart::getSubTotalWithoutConditions())
                 ?>
                        {{currency_format( $value,$code = $currency)}} <br/>
                         
                       
                    </td>
                  </tr>
                 @endif
                  @if($tax_attribute[0]['name']!='null' && ($currency != "INR" && $tax_attribute[0]['tax_enable'] ==0 && $tax_attribute[0]['status'] ==1))

                  <tr class="Taxes">
                
                    <th>
                        <strong>{{$tax_attribute[0]['name']}}<span>@</span>{{$tax_attribute[0]['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>
                 <?php
                 $value = \App\Http\Controllers\Front\CartController::taxValue($tax_attribute[0]['rate'],$subtotal);
                 ?>
                        {{currency_format( $value,$code = $currency)}} <br/>
                         
                       
                    </td>
                  
                  </tr>
                 @endif
                 @endif
                @endforeach
                <?php
                $items=$invoice->invoiceItem()->get();
                ?>
                     
                @if ($attributes['attributes']['tax'][0]['name'] == null)
                 
                @foreach ($items as $item)
                 @if($item->tax_name !='null' )
               <tr class="Taxes">
                  <th>
                        <strong>{{$item->tax_name}}<span>@</span>{{$item->tax_percentage}}</strong><br/>
                       
                         
                    </th>
                    <td>
                 <?php
                 $value = \App\Http\Controllers\Front\CartController::taxValue($item->tax_percentage,$item->regular_price);
                 ?>
                       {{currency_format($value,$code = $currency)}} <br/>
                         
                       
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


                        <strong><span class="amount">{{currency_format( $invoice->grand_total,$code = $currency)}} </span></strong>


                    </td>
                </tr>

            </tbody>

        </table>
        <br />
        <div class="form-group">
                   <div class="col-md-12" id="not-razor">
                                                            <input type="submit" name="submit" value="Place Your Order And Pay" id="rzp-button1" class="btn btn-primary " data-loading-text="Loading..." style="width:100%">
                                                        </div>
                </div>
    </div>
</div>
 <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                                             <form name='razorpayform' action="{!!url('payment/'.$invoice->id)!!}" method="POST">     
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
