@extends('app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            @if(Session::has('success'))
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-ban"></i>
                <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{Session::get('success')}}
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

            <table class="table table-responsive">
                <tr>
                    <th>Product Name</th>
                    <th>Tax</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>

                @forelse($cartCollection as $item)
                <tr>
                    <td>{{$item->name}}</td>

                    <td>
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

                    <td>{{$item->price}}</td>
                    <td>{{$item->getPriceWithConditions()}}</td>
                    <td><a href="" onclick="reduceQty({{$item->id}});"> - </a>{{$item->quantity}}<a href="" onclick="increaseQty({{$item->id}});"> + </a></td>
                    <td>{{$item->getPriceSumWithConditions()}}</td>
                    <td><a href="#" onclick="removeItem('{{$item->id}}')">Remove</a></td>
                </tr>

                @empty
                <tr><p>No records</p></tr>
                @endforelse

            </table>
            <div class="col-md-4 pull-right">
                <table class="table table-responsive">
                    <tr>
                        <td>Grand Total</td><td>{{Cart::getSubTotal()}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        @if(count($addons)>0)
        <div class="col-md-12">
            <h3> Addons  </h3>
        </div>
        @endif
        @forelse($addons as $addon)
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">{{$addon->name}}</div>
                <div class="panel-body">
                    @if($addon->description)
                    <p><b>Description :</b>{{$addon->description}}</p>
                    @endif
                    <p><b>Price :</b> {{$addon->selling_price}} </p>
                    <a href="#" onclick="Addon({{$addon->id}})">Add to cart</a>
                </div>
            </div>
        </div>
        @empty 
        <p>No addons</p>
        @endforelse
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="{{url('checkout')}}"><button class="btn btn-success pull-right">Check Out</button></a>
                    </div>
                </div>
            </div>
        </div>

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