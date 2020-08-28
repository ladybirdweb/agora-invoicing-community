@extends('themes.default1.layouts.master')
@section('title')
Renew
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Renew Order</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('orders')}}"><i class="fa fa-dashboard"></i> All Orders</a></li>
            <li class="breadcrumb-item active">Renew Order</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')
<div class="card card-primary card-outline">

        {!! Form::open(['url'=>'renew/'.$id,'method'=>'post']) !!}


    <div class="card-body">
                <?php 
                 $plans = App\Model\Payment\Plan::where('product',$productid)->pluck('name','id')->toArray();
                ?>
        <div class="row">

            <div class="col-md-12">



                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('plan') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('plan','Plans',['class'=>'required']) !!}
                          <select name="plan" value= "Choose" onchange="getPrice(this.value)" class="form-control">
                             <option value="Choose">Choose</option>
                           @foreach($plans as $key=>$plan)
                              <option value={{$key}}>{{$plan}}</option>
                          @endforeach
                          </select>
                        <!-- {!! Form::select('plan',[''=>'Select','Plans'=>$plans],null,['class' => 'form-control','onchange'=>'getPrice(this.value)']) !!} -->
                        {!! Form::hidden('user',$userid) !!}
                    </div>

                   

                   <div class="col-md-4 form-group {{ $errors->has('payment_method') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('payment_method',Lang::get('message.payment-method'),['class'=>'required']) !!}
                        {!! Form::select('payment_method',[''=>'Choose','cash'=>'Cash','check'=>'Check','online payment'=>'Online Payment','razorpay'=>'Razorpay','stripe'=>'Stripe'],null,['class' => 'form-control']) !!}

                    </div>
                     <div class="col-md-4 form-group {{ $errors->has('cost') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('cost',Lang::get('message.price'),['class'=>'required']) !!}
                        {!! Form::text('cost',null,['class' => 'form-control','id'=>'price']) !!}

                    </div>
                </div>





            </div>

        </div>
                    <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-save">&nbsp;&nbsp;</i>Save</button>

    </div>

</div>


{!! Form::close() !!}
@stop
<script>
    function getPrice(val) {
        
        var user = document.getElementsByName('user')[0].value;
        $.ajax({
            type: "get",
            url: "{{url('get-renew-cost')}}",
            data: {'user': user, 'plan': val},
            success: function (data) {alert('dsfds');
                var price = data
                $("#price").val(price);
            }
        });
    }
</script>