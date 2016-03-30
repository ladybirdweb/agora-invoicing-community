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
if ($attributes[0]['currency'][0]['symbol'] == '') {
    $symbol = $attributes[0]['currency'][0]['code'];
} else {
    $symbol = $attributes[0]['currency'][0]['symbol'];
}
?>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Checkout
                    @if(!Auth::user())
                    <a href="{{url('auth/login')}}" class="pull-right">I have account</a>
                    @endif
                </div>
                <div class="panel-body">
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

                    @if(Auth::user())
                    {!! Form::model(Auth::user(),['url'=>'checkout', 'method' => 'PATCH','files'=>true]) !!}
                    @else 
                    {!! Form::open(['url'=>'checkout', 'method' => 'POST','files'=>true]) !!}
                    @endif

                    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('first_name',Lang::get('message.first_name')) !!}
                        {!! Form::text('first_name',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('last_name',Lang::get('message.last_name')) !!}
                        {!! Form::text('last_name',null,['class' => 'form-control']) !!}

                    </div>


                    <div class="form-group">
                        <!-- email -->
                        {!! Form::label('email',Lang::get('message.email')) !!}

                        {!! Form::email('email',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                        <!-- company -->
                        {!! Form::label('company',Lang::get('message.company')) !!}
                        {!! Form::text('company',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('mobile',Lang::get('message.mobile')) !!}
                        {!! Form::text('mobile',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                        <!-- phone number -->
                        {!! Form::label('address',Lang::get('message.address')) !!}
                        {!! Form::textarea('address',null,['class' => 'form-control']) !!}

                    </div>

<!--                    <div class="form-group {{ $errors->has('currency') ? 'has-error' : '' }}">
                         mobile 
                        {!! Form::label('currency',Lang::get('message.currency')) !!}
                        {!! Form::select('currency',DB::table('currencies')->lists('name','id'),null,['class' => 'form-control']) !!}

                    </div>-->

                    <div class="form-group {{ $errors->has('town') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('town',Lang::get('message.town')) !!}
                        {!! Form::text('town',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="row">

                        <div class="col-md-6 form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                            <!-- mobile -->
                            {!! Form::label('state',Lang::get('message.state')) !!}
                            {!! Form::text('state',null,['class' => 'form-control']) !!}

                        </div>

                        <div class="col-md-6 form-group {{ $errors->has('zip') ? 'has-error' : '' }}">
                            <!-- mobile -->
                            {!! Form::label('zip',Lang::get('message.zip')) !!}
                            {!! Form::text('zip',null,['class' => 'form-control']) !!}

                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">

                <div class="panel-body">

                    <div>
                        <table class="table table-responsive">
                            <tr>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                            @forelse($content as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->quantity}}</td>
                                <td><small>{!! $symbol !!} </small> {{$item->getPriceSumWithConditions()}}</td>
                            </tr>
                            @empty 
                            <p>Your Cart is void</p>
                            @endforelse

                        </table>
                        <div class="col-md-12">
                            <table class="table table-responsive">
                                <tr>
                                    <td>Grand Total</td><td><small>{!! $symbol !!} </small>{{Cart::getSubTotal()}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <h4> Available Payment Gateways </h4>
                        <div class="col-md-6 col-md-offset-2">
                            CC avanue {!! Form::radio('payment_gateway','ccavenue') !!}<br><br>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            <button type="submit" class="btn btn-primary">
                                Proceed Payment
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
