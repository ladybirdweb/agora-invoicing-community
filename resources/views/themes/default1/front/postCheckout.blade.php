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

    $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));
    $displayCurrency=$invoice->currency;;
    $symbol = $invoice->currency;
 if ($symbol !== 'INR')
{
    $data['display_currency']  = 'USD';
    // $data['display_amount']    = $_POST['amount'];
    
}

    if ($symbol == 'INR'){
   
$orderData = [
    'receipt'         => 3456,
    'amount'          => $invoice->grand_total*100, // 2000 rupees in paise

    'currency'        => 'INR',
    'payment_capture' => 0 // auto capture
     
];


}
else
{
     $url = "http://apilayer.net/api/live?access_key=1af85deb04dd0c538c06c5c005ef73cf";
     $exchange = json_decode(file_get_contents($url));
     $displayAmount =$exchange->quotes->USDINR * $invoice->grand_total ;
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
    "key"               => 'rzp_test_GL0mtsOBCft5Tp',
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
?>
<div class="row">

    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        Review & Payment
                    </a>
                </h4>
            </div>


            <div class="panel-body">

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
                                    <span class="amount"><small>{!! $symbol !!} </small> {{$item->regular_price}}</span>
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
                         <hr class="tall">
                    <h4 class="heading-primary">Cart Totals</h4>
                        <table class="cart-totals">
                            <tbody>


                                <tr class="total">
                                    <th>
                                        <strong>Order Total</strong>
                                    </th>
                                    <td>
                                        <strong><span class="amount"><small>{!! $symbol !!} </small> {{$invoice->grand_total}}</span></strong>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <hr class="tall">
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
                        <strong><span class="amount"><small>{{$symbol}}</small> {{$subtotal}}</span></strong>
                    </td>
                </tr>
                @if ($attributes != null)
                 @foreach($attributes[0]['tax'] as $attribute)
                  
                  
                @if($attribute['name']!='null' && ($attributes[0]['currency'][0]['code'] == "INR" && $attribute['tax_enable'] ==1))
                 @if($attribute['state']==$attribute['origin_state'] && $attribute['ut_gst']=='NULL' && $attribute['status'] ==1)
                <tr class="Taxes">
                    <th>
                        <strong>CGST<span>@</span>{{$attribute['c_gst']}}%</strong><br/>
                        <strong>SGST<span>@</span>{{$attribute['s_gst']}}%</strong><br/>
                       
                    </th>
                    <td>
                        <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['c_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                        <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['s_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                       
                       
                    </td>


                </tr>
                @endif
               
                @if ($attribute['state']!=$attribute['origin_state'] && $attribute['ut_gst']=='NULL' && $attribute['status'] ==1)
               
                <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['i_gst']}}%</strong>
                     
                    </th>
                    <td>
                        <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['i_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                      
                    </td>


                </tr>
                @endif

                @if ($attribute['state']!=$attribute['origin_state'] && $attribute['ut_gst']!='NULL' && $attribute['status'] ==1)
              
                <tr class="Taxes">
                    <th>
                       <strong>CGST<span>@</span>{{$attribute['c_gst']}}%</strong><br/>
                        <strong>UTGST<span>@</span>{{$attribute['ut_gst']}}%</strong>
                       
                    </th>
                    <td>
                         <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['c_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                        <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['ut_gst'],Cart::getSubTotalWithoutConditions())}} <br/>
                       
                    </td>


                </tr>
                @endif
                @endif

                 @if($attribute['name']!='null' && ($attributes[0]['currency'][0]['code'] == "INR" && $attribute['tax_enable'] ==0 && $attribute['status'] ==1))
                 <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>
                       
                         <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotalWithoutConditions())}} <br/>
                         
                       
                    </td>
                  </tr>
                 @endif
           
                @if($attribute['name']!='null' && ($attributes[0]['currency'][0]['code'] != "INR" && $attribute['tax_enable'] ==1 && $attribute['status'] ==1))
                  <tr class="Taxes">
                    <th>
                        <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}</strong><br/>
                       
                         
                    </th>
                    <td>
                      
                         <small>{{$symbol}}</small> {{App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getTotal())}} <br/>
                         
                       
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
                        <strong><span class="amount"><small>{{$symbol}}</small> {{$invoice->grand_total}}</span></strong>
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
        console.log("This code runs when the popup is closed");
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
