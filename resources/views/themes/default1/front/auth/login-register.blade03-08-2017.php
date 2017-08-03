@extends('themes.default1.layouts.front.master')
@section('title')
Login | Register
@stop
@section('page-header')
Login | Register
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Login</li>
@stop
@section('main-class') 
main
@stop
@section('content')
<?php
$location = \GeoIP::getLocation();
$country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($location['isoCode']);
//$states = \App\Http\Controllers\Front\CartController::findStateByRegionId($location['isoCode']);
$states = \App\Model\Common\State::lists('state_subdivision_name', 'state_subdivision_code')->toArray();
$state_code = $location['isoCode'].'-'.$location['state'];
$state = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
$mobile_code = \App\Http\Controllers\Front\CartController::getMobileCodeByIso($location['isoCode']);
?>
<style>
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }
</style>

<div class="row">
    <div class="col-md-12">

        <div class="featured-boxes">
            <div id="error">
            </div>
            <div id="success">
            </div>
            <div id="fails">
            </div>
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

                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{Session::get('fails')}}
            </div>
            @endif
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Something went wrong<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="featured-box featured-box-primary align-left mt-xlg">
                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">I'm a Returning Customer</h4>
                            {!!  Form::open(['action'=>'Auth\AuthController@postLogin', 'method'=>'post','id'=>'formoid']) !!}
                            <div class="row">
                                <div class="form-group  {{ $errors->has('email1') ? 'has-error' : '' }}">
                                    <div class="col-md-12">
                                        <label class="required">Username or E-mail Address</label>
                                        {!! Form::text('email1',null,['class' => 'form-control input-lg']) !!}

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group {{ $errors->has('password1') ? 'has-error' : '' }}">
                                    <div class="col-md-12">
                                        <a class="pull-right" href="{{url('password/email')}}">({{Lang::get('message.forgot-my-password')}})</a>
                                        <label class="required">Password</label>
                                        {!! Form::password('password1',['class' => 'form-control input-lg']) !!}
                                        <!--<input type="password" value="" class="form-control input-lg">-->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="remember-box checkbox">
                                        <label for="rememberme">
                                            <input type="checkbox" id="rememberme" name="remember">Remember Me
                                        </label>
                                    </span>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" value="Login" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="featured-box featured-box-primary align-left mt-xlg">
                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">Register An Account</h4>
                            {!! Form::open(['url'=>'/auth/register']) !!}
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6 {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                        {!! Form::label('first_name',Lang::get('message.first_name'),['class'=>'required']) !!}
                                        {!! Form::text('first_name',null,['class'=>'form-control input-lg']) !!}

                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                            <label class="required">Last Name</label>
                                            {!! Form::text('last_name',null,['class'=>'form-control input-lg']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label class="required">Email Address</label>
                                        {!! Form::email('email',null,['class'=>'form-control input-lg']) !!}
                                    </div>


                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6 {{ $errors->has('company') ? 'has-error' : '' }}">
                                        <label  class="required">Company Name</label>
                                        {!! Form::text('company',null,['class'=>'form-control input-lg']) !!}
                                    </div>
                                    <div class="col-md-6 {{ $errors->has('bussiness') ? 'has-error' : '' }}">
                                        <label class="required">Industry</label>
                                        {!! Form::select('bussiness',[''=>'Select','Industries'=>$bussinesses],null,['class'=>'form-control input-lg']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-3 {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                                        <label class="required">Code</label>
                                        {!! Form::text('mobile_code',$mobile_code,['class'=>'form-control input-lg']) !!}
                                    </div>
                                    <div class="col-md-5 {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                        <label class="required">Mobile No</label>
                                        {!! Form::text('mobile',null,['class'=>'form-control input-lg']) !!}
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4 {{ $errors->has('country') ? 'has-error' : '' }}">
                                            {!! Form::label('country',Lang::get('message.country'),['class'=>'required']) !!}
<?php $countries = \App\Model\Common\Country::lists('country_name', 'country_code_char2')->toArray(); ?>
                                            {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],$country,['class' => 'form-control input-lg','onChange'=>'getState(this.value);']) !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 {{ $errors->has('address') ? 'has-error' : '' }}">
                                        <label class="required">Address</label>
                                        {!! Form::textarea('address',null,['class'=>'form-control','rows'=>4]) !!}

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6 {{ $errors->has('town') ? 'has-error' : '' }}">
                                        <label>City/Town</label>
                                        {!! Form::text('town',$location['city'],['class'=>'form-control input-lg']) !!}
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 {{ $errors->has('state') ? 'has-error' : '' }}">
                                            {!! Form::label('state',Lang::get('message.state')) !!}
                                            <?php
                                            $value = '';
                                            if (count($state) > 0) {
                                                $value = $state;
                                            }
                                            if (old('state')) {
                                                $value = old('state');
                                            }
                                            //dd($value);
                                            ?>
                                            {!! Form::select('state',[$states],$value,['class' => 'form-control input-lg','id'=>'state-list']) !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="col-md-6 {{ $errors->has('zip') ? 'has-error' : '' }}">
                                            <label class="required">Zip/Postal Code</label>
                                            {!! Form::text('zip',$location['postal_code'],['class'=>'form-control input-lg']) !!}
                                        </div>

                                        <div class="col-md-6 {{ $errors->has('user_name') ? 'has-error' : '' }}">
                                            <label class="required">User Name/E-mail Id</label>
                                            {!! Form::text('user_name',null,['class'=>'form-control input-lg']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6 {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <label class="required">Password</label>
                                        {!! Form::password('password',['class'=>'form-control input-lg']) !!}
                                    </div>
                                    <div class="col-md-6 {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                        <label class="required">Re-enter Password</label>

                                        {!! Form::password('password_confirmation',['class'=>'form-control input-lg']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>
                                        <input type="checkbox" name="terms"> {{Lang::get('message.i-agree-to-the')}} <a href="http://www.faveohelpdesk.com/terms-conditions" target="_blank">{{Lang::get('message.terms')}}</a>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Register" class="btn btn-primary mb-xl" data-loading-text="Loading...">
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    function getState(val) {


        $.ajax({
            type: "POST",
            url: "{{url('get-state')}}",
            data: 'country_id=' + val,
            success: function (data) {
                $("#state-list").html(data);
            }
        });
    }
</script>

@stop