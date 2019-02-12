@extends('themes.default1.layouts.front.master')
@section('title')
Cart
@stop
@section('page-header')
<br>
Cart
@stop
@section('page-heading')
 <h1>Cart</h1>
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">Cart</li>
@stop
@section('main-class') "main shop" @stop
@section('content')

<?php
$symbol = '';
if (count($attributes) > 0) {
    if ($attributes[0]['currency']['symbol'] == '') {
        $symbol = $attributes[0]['currency']['currency'];
    } else {
        $symbol = $attributes[0]['currency']['symbol'];
    }
}


?>
<div class="row">
    <div class="col-md-12">
        @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong>There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(Session::has('success'))
    </br>
</br>


         <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong><i class="far fa-thumbs-up"></i> Well done!</strong>


            {{Session::get('success')}}
        </div>
        @endif
        <!-- fail message -->
        @if(Session::has('fails'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> There were some problems with your input.
            <ul>
           <li> {{Session::get('fails')}} </li>
        </ul>
        </div>
        @endif

        
        @if(!Cart::isEmpty())
        <div class="featured-boxes">
            <div class="row">

                <div class="col-md-8">
                   
                       
                           <div class="featured-box featured-box-primary align-left mt-sm">

                                <div class="box-content">
                                   
                                        <table class="shop_table cart">
                                        @forelse($cartCollection as $key=>$item)
                                          <?php
                                            $product = App\Model\Product\Product::where('id', $item->id)->first();
                                            $domain = [];
                                  
                                            
                                          
                                            if ($product->require_domain == 1) {
                                                $domain[$key] = $product->id;
                                                $productName = $product->name;
                                            
                                            }
                                            $cont = new \App\Http\Controllers\Product\ProductController();
                                            $isAgentAllowed = $cont->allowQuantityOrAgent($item->id);
                                            $isAllowedtoEdit = $cont->isAllowedtoEdit($item->id);

                                            ?>

                                            <thead>

                                                <tr>
                                                    <th class="product-price">
                                                        &nbsp;
                                                    </th>
                                                    <th class="product-price">
                                                        &nbsp;
                                                    </th>
                                                    <th class="product-price">
                                                        Product
                                                    </th>

                                                    <th class="product-subtotal">
                                                        Price
                                                    </th>
                                                    @if( $isAgentAllowed ==false)
                                                    <th class="product-quantity">
                                                        Quantity
                                                    </th>
                                                    @else
                                                    <th class="product-agents">
                                                        Agents
                                                    </th>
                                                    @endif
                                                    <th class="product-subtotal">
                                                        Subtotal
                                                    </th>

                                                </tr>
                                            </thead>
                                          
                                            <tbody>
                                                 
                                             
                                               
                                                <tr class="cart_table_item">
                                                  
                                                    <td class="product-remove">
                                                        <a title="Remove this item" class="remove" href="#" onclick="removeItem('{{$item->id}}');">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </td>
                                                    <td class="product-price">
                                                      <img width="100" height="100" alt="" class="img-responsive" src="{{$product->image}}">
                                                    </td>
                                                    <td class="product-subtotal">
                                                        {{$item->name}}
                                                    </td>

                                                    <td class="product-price">


                                                         <span class="amount">&nbsp;
                                                           
                                                            {{currency_format($item->price,$code = $attributes[0]['currency']['currency'])}}
                                                         <!-- {{\App\Http\Controllers\Front\CartController::rounding($item->getPriceSumWithConditions())}} -->
                                                     </span>
                                                       <div id="response"></div>
                                                    </td>
                                                      
                                                      @if( $isAgentAllowed ==false)
                                                    <td class="product-quantity">
                                                        @if($isAllowedtoEdit['quantity']==1)
                                                        <div class="quantity">
                                                        <input type="button" id="quantityminus" class="minus" value="-">
                                                        <input type = "hidden" class="productid" value="{{$item->id}}">
                                                        <input type = "hidden" class="quatprice" id="quatprice" value=" {{$item->price}}">
                                                        <input type="text" class="input-text qty text" title="Qty" id="qty" value="{{$item->quantity}}" name="quantity" id="quantity" min="1" step="1" disabled>
                                                        <input type="button" class="plus" value="+" id="quantityplus" >
                                                       </div>
                                                 <!--       <input type="number"  title="Qty" value="{{$item->quantity}}" name="quantity" id="quantity" min="1"  step="1" style="width: 50%" onchange="changeQty(this.value,'{{$item->id}}')">-->
                                                        
                                                        @else 
                                                            {{$item->quantity}}
                                                        @endif
                                                    </td>
                                                     @else
                                                     <td class="product-agent">
                                                        @if ($item->attributes->agents == 0)
                                                         {{'Unlimited Agents'}}
                                                         @else
                                                        @if($isAllowedtoEdit['agent']==1)
                                                        <div class="quantity">
                                                          <input type="button" id='agentminus' class="minus" value="-">
                                                           <input type="hidden" id="initialagent" value="{{$item->attributes->initialagent}}">
                                                         <input type = "hidden" class="currency" value="{{$item->attributes->currency['currency']}}">
                                                         <input type = "hidden" class="symbol" value="{{$item->attributes->currency['symbol']}}">
                                                       <input type = "hidden" class="productid" value="{{$item->id}}">
                                                    <input type = "hidden" class="agentprice" id="agentprice" value=" {{$item->getPriceSum()}}">
                                                        <input type="text" class="input-text qty text" id="agtqty" title="Qty" value="{{$item->attributes->agents}}" name="quantity" min="1" step="1" disabled>
                                                        <input type="button" class="plus" value="+" id="agentplus">
                                                    </div>
                                                        
                                                        @else 
                                                            {{$item->attributes->agents}}
                                                        @endif
                                                    </td>
                                                    @endif
                                                     @endif
                                                         
                                                    <td class="product-subtotal">


                                                      <span class="amount">&nbsp;
                                                        {{currency_format($item->getPriceSum(),$code = $attributes[0]['currency']['currency'])}}
                                                    </span>


                                                            
                                                    </td>

                                                </tr>


                                                @endforeach
                                            </tbody>
                                             
                                        </table>
                                   
                                </div>
                            </div>
                       
                       
                </div>

                <div class="col-md-4">


                   <div class="featured-box featured-box-primary text-left ">


                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">Cart Totals</h4>
                            <table class="cart-totals">
                                <tbody>

                               
                                
                                    <tr class="total">
                                        <th>
                                            <strong>Order Total</strong>
                                        </th>
                                        <td>


                                            <strong><span class="amount"><small>&nbsp;</small>  
                                                {{currency_format(Cart::getSubTotalWithoutConditions(),$code= $attributes[0]['currency']['currency'])}}
                                           </span></strong>


                                        </td>
                                    </tr>

                                    <tr>
                                        <th>

                                        </th>
                                        <td>

                                        </td>
                                    </tr>



                                </tbody>
                            </table>
                            <div class="row">
                                {!! Form::open(['url'=>'pricing/update','method'=>'post']) !!}
                                <div class="form-group col-md-12">

                                    <label for="coupon"><b>{{Lang::get('message.coupon-code')}}</b></label>
                                    <input type="text" name="coupon" class="form-control input-lg">

                                </div>
                                <div class="form-group col-md-4-5">
                                    <input type="submit" value="Update">
                                </div>
                                {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                         <!-- </div> -->
                           <div class="row">
                        <div class=" col-md-6">
                            <a href="{{url('cart/clear')}}"><button class="btn btn-danger btn-sm" style="margin-bottom: 20px;">Clear My Cart&nbsp;<i class="fa fa-angle-right ml-xs"></i></button></a>
                        
                       </div>
                        <div class=" col-md-6">
                            @if(count($domain)>0)

                            <a href="#domain" data-toggle="modal" data-target="#domain"><button class="btn btn-primary btn-sm "style="margin-bottom: 20px;"> Proceed to Checkout&nbsp;<i class="fa fa-angle-right ml-xs"></i></button></a>

                            @else
                            <a href="{{url('checkout')}}"><button class="btn btn-primary btn-sm" style="margin-bottom: 20px;">Proceed to Checkout&nbsp;<i class="fa fa-angle-right ml-xs"></i></button></a>
                            @endif
                          

                        </div>


                      </div>


                </div>


            </div>
        </div>
        @else 
        <div class="featured-boxes">
            <div class="row">
                <div class="col-md-12">
                    <div class="featured-box featured-box-primary align-left mt-sm">
                        <div class="box-content">

                            <div class="col-md-offset-5">
                                <p>There are no items in this cart.</p>
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





    </div>
</div>
 <script src="{{asset('client/js/jquery.min.js')}}"></script>
<script>
        /*
         * Increase No. Of Agents
         */
        $('#agentplus').on('click',function(){
        var $agtqty=$(this).parents('.quantity').find('.qty');
        var $productid = $(this).parents('.quantity').find('.productid');
        var $agentprice = $(this).parents('.quantity').find('.agentprice');
        var $currency = $(this).parents('.quantity').find('.currency');
        var $symbol  = $(this).parents('.quantity').find('.symbol');
        var currency = $currency.val();//Get the Currency for the Product
        var symbol = $symbol.val();//Get the Symbol for the Currency
        var productid = parseInt($productid.val()); //get Product Id
        var currentAgtQty = parseInt($agtqty.val()); //Get Current Quantity of Prduct
        var actualAgentPrice = parseInt($agentprice.val()); //Get Initial Price of Prduct
        // console.log(productid,currentVal,actualprice);
        if (!isNaN(currentAgtQty)) {
         var finalAgtqty = $('#agtqty').val(currentAgtQty * 2 ).val() ; //Quantity After Increasing
         var finalAgtprice = $('#agentprice').val(actualAgentPrice * 2 ).val(); //Final Price aftr increasing qty
           }
               $.ajax({
               type: "GET",
            data:{'symbol':symbol,'currency':currency,'agtQty':finalAgtqty,'productid':productid,'actualAgentprice':finalAgtprice},
             beforeSend: function () {
                 $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

                },
            url: "{{url('update-agent-qty')}}",
            success: function () {
               location.reload();
              }
            });
         });
<!----------------------------------------------------------------------------------------------------------------------------------->
        /*
        *Decrease No. of Agents
         */ 
        $(document).ready(function(){
             var currentagtQty = $('#agtqty').val();
             if (parseInt(currentagtQty) <= 5) {
               agentminus.Enabled = false; 
           } else {
              $('#agentminus').on('click',function(){
              
        var $agtqty=$(this).parents('.quantity').find('.qty');
        var $productid = $(this).parents('.quantity').find('.productid');
        var $agentprice = $(this).parents('.quantity').find('.agentprice');
        var $currency = $(this).parents('.quantity').find('.currency');
        var $symbol  = $(this).parents('.quantity').find('.symbol');
        var currency = $currency.val();//Get the Currency for the Product
        var symbol = $symbol.val();//Get the Symbol for the Currency
        var productid = parseInt($productid.val()); //get Product Id
        var currentAgtQty = parseInt($agtqty.val()); //Get Current Agent of Prduct
        var actualAgentPrice = parseInt($agentprice.val()); //Get Initial Price of Prduct
        // console.log(productid,currentVal,actualprice);
        if (!isNaN(currentAgtQty)) {
         var finalAgtqty = $('#agtqty').val(currentAgtQty / 2 ).val() ; //Quantity After decreasinf
         var finalAgtprice = $('#agentprice').val(actualAgentPrice / 2 ).val(); //Final Price aftr decresing  qty
           }
               $.ajax({
               type: "GET",
            data:{'symbol':symbol,'currency':currency,'agtQty':finalAgtqty,'productid':productid,'actualAgentprice':finalAgtprice},
            beforeSend: function () {
                 $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                },
            url: "{{url('reduce-agent-qty')}}",
            success: function () {
               location.reload();
              }
            });
          
    });
      }
           
        });
          
           
       
        
<!----------------------------------------------------------------------------------------------------------------------------------->
        /*
        *Increse Product Quantity
         */  
        $('#quantityplus').on('click',function(){
        var $qty=$(this).parents('.quantity').find('.qty');
        var $productid = $(this).parents('.quantity').find('.productid');
        var $price = $(this).parents('.quantity').find('.quatprice');
        var productid = parseInt($productid.val()); //get Product Id
        var currentQty = parseInt($qty.val()); //Get Current Quantity of Prduct
        var incraesePrice = parseInt($price.val()); //Get Initial Price of Prduct
        // console.log(productid,currentVal,actualprice);
        if (!isNaN(currentQty)) {
         var finalqty = $('#qty').val(currentQty +1 ).val() ; //Quantity After Increasing
         var finalprice = $('#quatprice').val(incraesePrice).val(); //Final Price aftr increasing qty
           }
               $.ajax({
               type: "GET",
            data: {'qty':finalqty,'productid':productid,'actualprice':finalprice},
            beforeSend: function () {
                 $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
            },
            url: "{{url('update-qty')}}",
            success: function () {
               location.reload();
              }
            });
         });
<!----------------------------------------------------------------------------------------------------------------------------------->

            /*
             * Reduce Procut Quantity
             */
        $('#quantityminus').on('click',function(){
        var $qty=$(this).parents('.quantity').find('.qty');
        var $productid = $(this).parents('.quantity').find('.productid');
        var $price = $(this).parents('.quantity').find('.quatprice');
        var productid = parseInt($productid.val()); //get Product Id
        var currentQty = parseInt($qty.val()); //Get Current Quantity of Prduct
        var incraesePrice = parseInt($price.val()); //Get Initial Price of Prduct
        // console.log(productid,currentVal,actualprice);
        if (!isNaN(currentQty)) {
         var finalqty = $('#qty').val(currentQty -1 ).val() ; //Quantity After Increasing
          var finalprice = $('#quatprice').val(incraesePrice).val(); //Final Price aftr increasing qty
           }
               $.ajax({
               type: "GET",
            data: {'qty':finalqty,'productid':productid,'actualprice':finalprice},
            beforeSend: function () {
                 $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
            },
            url: "{{url('reduce-product-qty')}}",
            success: function () {
               location.reload();
              }
            });
         });


  

    function Addon(id){
    $.ajax({
    type: "GET",
            data:{"id": id, "category": "addon"},
            url: "{{url('cart')}}",
            success: function (data) {
            location.reload();
            }
    });
    }

</script>

@stop

