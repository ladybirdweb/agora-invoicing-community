@extends('themes.default1.layouts.front.master')
@section('title')
Cart
@stop
@section('page-header')
Cart
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Cart</li>
@stop
@section('main-class') "main shop" @stop
@section('content')

<?php
$symbol = '';
if (count($attributes) > 0) {
    if ($attributes[0]['currency'][0]['symbol'] == '') {
        $symbol = $attributes[0]['currency'][0]['code'];
    } else {
        $symbol = $attributes[0]['currency'][0]['symbol'];
    }
}
?>
<div class="row">
    <div class="col-md-12">
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

        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('success')}}
        </div>
        @endif
        <!-- fail message -->
        @if(Session::has('fails'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('fails')}}
        </div>
        @endif


        @if(!Cart::isEmpty())
        <div class="featured-boxes">
            <div class="row">

                <div class="col-md-8">
                    <ul class="list-unstyled">
                        <li>
                            <div class="featured-box featured-box-primary align-left mt-sm">
                                <div class="box-content">
                                    <form method="post" action="">
                                        <table class="shop_table cart">
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
                                                    <th class="product-quantity">
                                                        Quantity
                                                    </th>
                                                    <th class="product-subtotal">
                                                        Subtotal
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @forelse($cartCollection as $key=>$item)

                                                <tr class="cart_table_item">
                                                    <td class="product-remove">
                                                        <a title="Remove this item" class="remove" href="#" onclick="removeItem('{{$item->id}}');">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </td>
                                                    <td class="product-price">
                                                        <?php
                                                        $domain = [];
                                                        $price = 0;
                                                        $product = App\Model\Product\Product::where('id', $item->id)->first();
                                                        $cart_controller = new App\Http\Controllers\Front\CartController();
                                                        $value = $cart_controller->cost($product->id);
                                                        $price += $value;
                                                        if ($product->require_domain == 1) {
                                                            $domain[$key] = $product->id;
                                                        }
                                                        $multi_product = \App\Http\Controllers\Product\ProductController::checkMultiProduct($item->id);
                                                        $total = Cart::getSubTotal();
                                                        $sum = $item->getPriceSum();
                                                        $tax = $total-$sum;
                                                        
                                                        ?>

                                                        <img width="100" height="100" alt="" class="img-responsive" src="{{$product->image}}">

                                                    </td>
                                                    <td class="product-subtotal">
                                                        {{$item->name}}
                                                    </td>

                                                    <td class="product-price">
                                                        <span class="amount"><small>{!! $symbol !!}&nbsp;</small>{{\App\Http\Controllers\Front\CartController::calculateTax($item->id,$item->price,1,1,0)}}</span>
                                                    </td>
                                                    <td class="product-quantity">
                                                        @if($multi_product==true)
                                                        
                                                        <input type="number"  title="Qty" value="{{$item->quantity}}" name="quantity" id="quantity" min="1"  step="1" style="width: 50%" onchange="changeQty(this.value,'{{$item->id}}')">
                                                        
                                                        @else 
                                                            {{$item->quantity}}
                                                        @endif
                                                    </td>
                                                    <td class="product-subtotal">
                                                        <span class="amount"><small>{!! $symbol !!}&nbsp;</small>{{\App\Http\Controllers\Front\CartController::calculateTax($item->id,$item->getPriceSum(),1,1,0)}}</span>
                                                    </td>

                                                </tr>

<!--                                        <tr>
                                            <td class="actions" colspan="6">
                                                <div class="actions-continue">
                                                    <input type="submit" value="Update Cart" name="update_cart" class="btn btn-default">
                                                </div>
                                            </td>
                                        </tr>-->
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <?php $addons = \App\Http\Controllers\Front\CartController::addons();
                        //dd($addons);
                        ?>
                        @if(count($addons)>0)
                        <li>


                            @foreach($addons as $addon)
                            <div class="col-md-3">
                                <div class="featured-box featured-box-primary">
                                    <div class="box-content">
                                        <span><img width="50" height="50" alt="" class="img-responsive" src="{{$addon->image}}"></span>
                                        <span>{{ucfirst($addon->name)}}</span>
                                        <span><a href="{{$addon->shoping_cart_link}}" class="btn btn-small btn-primary">Buy</a></span>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </li>
                        @endif
                    </ul>
                </div>

                <div class="col-md-4">
                    <div class="featured-box featured-box-primary align-left mt-sm">
                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">Cart Totals</h4>
                            <table class="cart-totals">
                                <tbody>


                                    @foreach($item->attributes['tax'] as $attribute)
                                    @if($attribute['name']!='null')
                                    <tr>
                                        <th>
                                            <strong>{{$attribute['name']}}<span>@</span>{{$attribute['rate']}}%</strong>
                                        </th>
                                        <td>
                                            
                                            {{App\Http\Controllers\Front\CartController::taxValue($attribute['rate'],Cart::getSubTotal())}}
                                        </td>

                                    </tr>
                                    @endif
                                    @endforeach

                                    <tr class="total">
                                        <th>
                                            <strong>Order Total</strong>
                                        </th>
                                        <td>
                                            <strong><span class="amount"><small>{!! $symbol !!}&nbsp;</small>{{App\Http\Controllers\Front\CartController::rounding(Cart::getSubTotal())}}</span></strong>
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
                                <div class="form-group col-md-8">

                                    <label for="coupon"><b>{{Lang::get('message.coupon-code')}}</b></label>
                                    <input type="text" name="coupon" class="form-control">

                                </div>
                                <div class="form-group col-md-4-5">
                                    <input type="submit" value="Update">
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <div class=" col-md-6"><br><br><br><br>
                            <a href="{{url('cart/clear')}}"><button class="btn btn-danger btn-lg">Clear My Cart<i class="fa fa-angle-right ml-xs"></i></button></a>
                        </div>
                        <div class="col-md-6"><br><br><br><br>

                            @if(count($domain)>0)

                            <a href="#domain" data-toggle="modal" data-target="#domain"><button class="btn btn-primary btn-lg">Proceed to Checkout<i class="fa fa-angle-right ml-xs"></i></button></a>

                            @else
                            <a href="{{url('checkout')}}"><button class="btn btn-primary btn-lg">Proceed to Checkout<i class="fa fa-angle-right ml-xs"></i></button></a>
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
                                <a href="{{url('home')}}" class="btn btn-primary">CONTINUE SHOPPING</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        @endif





    </div>
</div>
<script>
    function changeQty(qty,productid){
        //alert(productid);
    $.ajax({
    type: "GET",
            data:{'qty':qty,'productid':productid},
            url: "{{url('update-qty')}}",
            success: function () {
                location.reload();
            }
    });
    }

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

