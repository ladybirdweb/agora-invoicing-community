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
<div class="row">
    <div class="col-md-12">
        <hr class="tall">
    </div>
</div>
<?php
if ($attributes[0]['currency'][0]['symbol'] == '') {
    $symbol = $attributes[0]['currency'][0]['code'];
} else {
    $symbol = $attributes[0]['currency'][0]['symbol'];
}
?>
<div class="row">
    <div class="col-md-12">

        <div class="featured-boxes">
            <div class="row">
                <div class="col-md-8">
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
                                            <th class="product-price">
                                                Tax
                                            </th>
                                            <th class="product-price">
                                                Price
                                            </th>
                                            <th class="product-quantity">
                                                Quantity
                                            </th>
                                            <th class="product-subtotal">
                                                Subtotal
                                            </th>
                                            <th class="product-subtotal">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cartCollection as $item)
                                        <tr class="cart_table_item">
                                            <td class="product-remove">
                                                <a title="Remove this item" class="remove" href="#" onclick="removeItem('{{$item->id}}');">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                            <td class="product-price">
                                                <a href="shop-product-sidebar.html">
                                                    <img width="100" height="100" alt="" class="img-responsive" src="{{asset('cart/img/products/product-1.jpg')}}">
                                                </a>
                                            </td>
                                            <td class="product-price">
                                                {{$item->name}}
                                            </td>
                                            <td class="product-price">
                                                <ul class="list-unstyled">

                                                    @foreach($item->attributes['tax'] as $attribute)
                                                    @if($attribute['name']!='null')
                                                    <li>
                                                        {{$attribute['name']}}={{$attribute['rate']}}% 
                                                    </li>
                                                    @endif
                                                    @endforeach

                                                </ul>

                                            </td>
                                            <td class="product-price">
                                                <span class="amount"><small>{!! $symbol !!} </small>{{$item->price}}</span>
                                            </td>
                                            <td class="product-quantity">
                                               {{$item->quantity}}
                                            </td>
                                            <td class="product-subtotal">
                                                <span class="amount"><small>{!! $symbol !!} </small>{{$item->getPriceWithConditions()}}</span>
                                            </td>
                                            <td class="product-subtotal">
                                                <span class="amount"><small>{!! $symbol !!} </small>{{$item->getPriceSumWithConditions()}}</span>
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
                </div>
                <div class="col-sm-4">
                    <div class="featured-box featured-box-primary align-left mt-sm">
                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">Cart Totals</h4>
                            <table class="cart-totals">
                                <tbody>


                                    <tr class="total">
                                        <th>
                                            <strong>Order Total</strong>
                                        </th>
                                        <td>

                                            <strong><span class="amount"><small>{!! $symbol !!}  </small>{{Cart::getSubTotal()}}</span></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class=" col-md-6"><br><br><br><br>
                            <a href="{{url('cart/clear')}}"><button class="btn btn-danger btn-lg">Clear My Cart<i class="fa fa-angle-right ml-xs"></i></button></a>
                        </div>
                        <div class="col-md-6"><br><br><br><br>
                            <a href="{{url('checkout')}}"><button class="btn btn-primary btn-lg">Proceed to Checkout<i class="fa fa-angle-right ml-xs"></i></button></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!--
                                <div class="featured-boxes">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="featured-box featured-box-primary align-left mt-xlg">
                                                <div class="box-content">
                                                    <h4 class="heading-primary text-uppercase mb-md">Calculate Shipping</h4>
                                                    <form action="/" id="frmCalculateShipping" method="post">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <label>Country</label>
                                                                    <select class="form-control">
                                                                        <option value="">Select a country</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-md-6">
                                                                    <label>State</label>
                                                                    <input type="text" value="" class="form-control">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Zip Code</label>
                                                                    <input type="text" value="" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="submit" value="Update Totals" class="btn btn-default pull-right mb-xl" data-loading-text="Loading...">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="featured-box featured-box-primary align-left mt-xlg">
                                                <div class="box-content">
                                                    <h4 class="heading-primary text-uppercase mb-md">Cart Totals</h4>
                                                    <table class="cart-totals">
                                                        <tbody>
                                                            <tr class="cart-subtotal">
                                                                <th>
                                                                    <strong>Cart Subtotal</strong>
                                                                </th>
                                                                <td>
                                                                    <strong><span class="amount">$431</span></strong>
                                                                </td>
                                                            </tr>
                                                            <tr class="shipping">
                                                                <th>
                                                                    Shipping
                                                                </th>
                                                                <td>
                                                                    Free Shipping
                                                                    <input type="hidden" value="free_shipping" id="shipping_method" name="shipping_method">
                                                                </td>
                                                            </tr>
                                                            <tr class="total">
                                                                <th>
                                                                    <strong>Order Total</strong>
                                                                </th>
                                                                <td>
                                                                    <strong><span class="amount">$431</span></strong>
                                                                </td>
                                                            </tr>
                                                           
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
        
                                        </div>
                                    </div>
        
                                </div>
        
        -->



    </div>
</div>
<script>

            function removeItem(id) {

            $.ajax({
            type: "GET",
                    data:"id=" + id,
                    url: "{{url('cart/remove/')}}",
                    success: function (data) {
                    location.reload();
                    }
            });
            }

    function reduceQty(id){
    $.ajax({
    type: "GET",
            data:"id=" + id,
            url: "{{url('cart/reduseqty/')}}",
            success: function (data) {
            location.reload();
            }
    });
    }
    function increaseQty(id){
    $.ajax({
    type: "GET",
            data:"id=" + id,
            url: "{{url('cart/increaseqty/')}}",
            success: function (data) {
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

