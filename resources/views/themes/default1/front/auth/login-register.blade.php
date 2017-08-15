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
$state_code = $location['isoCode'] . "-" . $location['state'];
$state = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
$mobile_code = \App\Http\Controllers\Front\CartController::getMobileCodeByIso($location['isoCode']);
?>
<style>
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }

    .wizard {
        margin: 20px auto;
        background: #fff;
    }

    .wizard .nav-tabs {
        position: relative;
        margin: 40px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
    }

.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 70%;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: 37%;
    z-index: 1;
}

.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
    
}
.wizard .nav-tabs > li{
    margin-bottom: 0px;
}
p.round-tab {
    width: 70px;
    height: 70px;
    line-height: 70px;
    display: inline-block;
    border-radius: 100px;
    background: #fff;
    border: 2px solid #e0e0e0;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 25px;
}
p.round-tab i{
    color:#555555;
}
.wizard li.active p.round-tab {
    background: #fff;
    border: 2px solid #5bc0de;
    
}
.wizard li.active p.round-tab i{
    color: #5bc0de;
}

p.round-tab:hover {
    color: #333;
    border: 2px solid #333;
}

.wizard .nav-tabs > li {
    width: 33.333%;
}

.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: #5bc0de;
    transition: 0.1s ease-in-out;
}

.wizard li.active:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 1;
    margin: 0 auto;
    bottom: 0px;
    border: 10px solid transparent;
    border-bottom-color: #5bc0de;
}

.wizard .nav-tabs > li a {
    width: 70px;
    height: 70px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
    border-left: none;
border-right: none;
border-top: none;
}

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

.wizard .tab-pane {
    position: relative;
    padding-top: 50px;
}

.wizard h3 {
    margin-top: 0;
}

@media( max-width : 585px ) {

    .wizard {
        width: 90%;
        height: auto !important;
    }

    p.round-tab {
        font-size: 16px;
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard .nav-tabs > li a {
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 35%;
    }
    
}

.nav-tabs{
      border-bottom: none;
}
.tab-content {
    border-radius: 0px;
    box-shadow: inherit;
   
    border: none ;
    border-top: 0;
    padding: 15px;
}
</style>

<div class="row">
    <div class="col-md-12">

        <section>
            <div class="wizard">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Contact Information">
                                    <p class="round-tab">
                                        <i class="fa fa-user"></i>
                                    </p>
                                    
                                </a>
                                <p style="font-size: 17px;margin-left: 122px;">Contact Information</p>
                            </li>
                            <li role="presentation" class="disabled" >
                                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Identity Verification">
                                    <p class="round-tab">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </p>
                                    
                                </a>
                                <p style="font-size: 17px;margin-left: 120px;">Identity Verification</p>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#step3" data-toggle="tab" aria-controls="complete" role="tab" title="Confirmation">
                                    <p class="round-tab">
                                        <i class="fa fa-check"></i>
                                    </p>
                                    
                                </a>
                                <p style="font-size: 17px;margin-left: 150px;">Confirmation</p>
                            </li>
                        </ul>
                    </div>
                    <div class="row tab-content">
                        <div class="col-md-12 tab-pane active" id="step1">
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
                                                <form name="registerForm" id="register-form">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-md-6 {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                                            {!! Form::label('first_name',Lang::get('message.first_name'),['class'=>'required']) !!}
                                                            {!! Form::text('first_name',null,['class'=>'form-control input-lg', 'id'=>'first_name']) !!}
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-6 {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                                                <label class="required">Last Name</label>
                                                                {!! Form::text('last_name',null,['class'=>'form-control input-lg', 'id'=>'last_name']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-md-12 {{ $errors->has('email') ? 'has-error' : '' }}">
                                                            <label class="required">Email Address</label>
                                                            {!! Form::email('email',null,['class'=>'form-control input-lg', 'id'=>'email']) !!}
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-md-6 {{ $errors->has('company') ? 'has-error' : '' }}">
                                                            <label  class="required">Company Name</label>
                                                            {!! Form::text('company',null,['class'=>'form-control input-lg', 'id'=>'company']) !!}
                                                        </div>
                                                        <div class="col-md-6 {{ $errors->has('bussiness') ? 'has-error' : '' }}">
                                                            <label class="required">Industry</label>
                                                            {!! Form::select('bussiness',[''=>'Select','Industries'=>$bussinesses],null,['class'=>'form-control input-lg', 'id'=>'business']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <?php
                                                    $type = DB::table('company_types')->pluck('name', 'short');
                                                    $size = DB::table('company_sizes')->pluck('name', 'short');
                                                    ?>
                                                    <div class="col-md-6 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                                                        <!-- email -->
                                                        {!! Form::label('company_type','Company Type',['class'=>'required']) !!}
                                                        {!! Form::select('company_type',[''=>'Select','Company Types'=>$type],null,['class' => 'form-control input-lg', 'id'=>'company_type']) !!}

                                                    </div>
                                                    <div class="col-md-6 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                                                        <!-- email -->
                                                        {!! Form::label('company_size','Company Size',['class'=>'required']) !!}
                                                        {!! Form::select('company_size',[''=>'Select','Company Sizes'=>$size],null,['class' => 'form-control input-lg', 'id'=>'company_size']) !!}

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">


                                                        <div class="form-group">
                                                            <div class="col-md-12 {{ $errors->has('country') ? 'has-error' : '' }}">
                                                                {!! Form::label('country',Lang::get('message.country'),['class'=>'required']) !!}
                                                                <?php $countries = \App\Model\Common\Country::lists('nicename', 'country_code_char2')->toArray(); ?>
                                                                {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],$country,['class' => 'form-control input-lg','onChange'=>'getCountryAttr(this.value);','id'=>'country']) !!}

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 form-group {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                                                        <label class="required">Country code</label>
                                                        {!! Form::hidden('mobile_code',null,['id'=>'mobile_code_hidden']) !!}
                                                        {!! Form::text('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                                    </div>
                                                    <div class="col-md-8 form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                                        <!-- mobile -->
                                                        {!! Form::label('mobile',Lang::get('message.mobile'),['class'=>'required']) !!}
                                                        {!! Form::text('mobile',null,['class' => 'form-control input-lg', 'id'=>'mobilenum']) !!}

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-md-12 {{ $errors->has('address') ? 'has-error' : '' }}">
                                                            <label class="required">Address</label>
                                                            {!! Form::textarea('address',null,['class'=>'form-control','rows'=>4, 'id'=>'address']) !!}

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-md-6 {{ $errors->has('town') ? 'has-error' : '' }}">
                                                            <label>City/Town</label>
                                                            {!! Form::text('town',$location['city'],['class'=>'form-control input-lg', 'id'=>'city']) !!}
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-md-6 {{ $errors->has('state') ? 'has-error' : '' }}">
                                                                {!! Form::label('state',Lang::get('message.state')) !!}
                                                                <?php
                                                                $value = "";
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
                                                                {!! Form::text('zip',$location['postal_code'],['class'=>'form-control input-lg', 'id'=>'postal_code']) !!}
                                                            </div>

                                                            <div class="col-md-6 {{ $errors->has('user_name') ? 'has-error' : '' }}">
                                                                <label class="required">User Name/E-mail Id</label>
                                                                {!! Form::text('user_name',null,['class'=>'form-control input-lg', 'id'=>'user_name']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fo
                                                    rm-group">
                                                        <div class="col-md-6 {{ $errors->has('password') ? 'has-error' : '' }}">
                                                            <label class="required">Password</label>
                                                            {!! Form::password('password',['class'=>'form-control input-lg', 'id'=>'password']) !!}
                                                        </div>
                                                        <div class="col-md-6 {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                                            <label class="required">Re-enter Password</label>

                                                            {!! Form::password('password_confirmation',['class'=>'form-control input-lg', 'id'=>'confirm_pass']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>
                                                            <input type="checkbox" name="terms" id="terms"> {{Lang::get('message.i-agree-to-the')}} <a href="http://www.faveohelpdesk.com/terms-conditions" target="_blank">{{Lang::get('message.terms')}}</a>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <!-- <input type="button" value="Register" class="btn btn-primary mb-xl next-step" data-loading-text="Loading..." name="register" id="register" onclick="registerUser(this)"> -->
                                                        <button type="button" class="btn btn-primary mb-xl next-step" name="register" id="register" onclick="registerUser()">Register
                                                        </button>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 tab-pane" id="step2">

        <div class="featured-boxes">
            
                        <!-- fail message -->
            <div class="row">
                <div class="col-sm-10" style="float: none;margin: auto">
                    <div id="alertMessage1"></div>
                    <div id="error1">
                    </div>
                    <div class="featured-box featured-box-primary align-left mt-xlg" style="max-height: 1156px;height: auto">
                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">Email and Mobile Verification</h4>
                            <p>You will be send verification email and OTP on your mobile immediately by an automated system, Please click on the verification link in the email and also enter the OTP in the next step.</p>
                            <form name="verifyForm" >
                                <input type="hidden" name="user_id" id="user_id"/>
                                <input type="hidden" name="email_password" id="email_password"/>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12 ">
                                            <label class="required">Email Address</label>
                                            <input class="form-control input-lg" name="verify_email" id="verify_email" type="email">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group ">
                                        <label class="required">Country code</label>
                                        <input id="mobile_code_hidden" name="mobile_code" type="hidden">
                                        <input class="form-control input-lg"  id="verify_country_code" name="verify_country_code" type="text">
                                    </div>
                                    <div class="col-md-8 form-group ">
                                        <!-- mobile -->
                                        <label for="mobile" class="required">Mobile</label>
                                        <input class="form-control input-lg" name="verify_number" type="text" id="verify_number">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" style="float: right;">
                                        <!-- <input type="button" value="Verify" class="btn btn-primary mb-xl next-step" data-loading-text="Loading..." name="sendOtp" id="sendOtp" onclick="sendOTP()"> -->
                                        <button type="button" class="btn btn-primary mb-xl next-step" name="sendOtp" id="sendOtp" onclick="sendOTP()">
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
       </div>
</div>
<div class="col-md-12 tab-pane" id="step3">

        <div class="featured-boxes">
                        <!-- fail message -->
            <div class="row">
                <div class="col-sm-10" style="float: none;margin: auto">
                    <div id="error2">
                    </div>
                    <div id="alertMessage2" class="-text" ></div>
                    <div class="featured-box featured-box-primary align-left mt-xlg" style="max-height: 1156px;height: auto">
                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">Confirmation</h4>
                            <!-- <div class="row verify">
                                <div class="col-md-12">
                                    <label>
                                        <span>Verification email sent on your email and OTP on mobile</span>
                                    </label>
                                </div>
                            </div> -->
                            <form name="verify_otp_form">
                                <div class="row verify">
                                    <div class="col-md-4 form-group ">
                                        <input type="hidden" name="user_id" id="hidden_user_id"/>
                                        <input class="form-control input-lg"  id="oneTimePassword" name="oneTimePassword" type="text" placeholder="Enter OTP">
                                    </div>
                                    <div class="col-md-2 form-group ">
                                        <!-- <input type="button" class="btn btn-primary mb-xl" name="verifyOtp" id="verifyOtp" value="Verify OTP" onclick="verifyBySendOtp()" style="margin-top: 6px;"> -->
                                        <button type="button" class="btn btn-primary mb-xl" name="verifyOtp" id="verifyOtp" onclick="verifyBySendOtp()" style="margin-top: 6px;">
                                            Verify OTP
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Didn't recieve OTP via SMS</h5>
                                    <button type="button" class="btn btn-default mb-xl" data-loading-text="Loading..." name="resendOTP" id="resendOTP" onclick="resendOTP()" style="background: grey; color: white;" ><i class="fa fa-phone" style="font-size: 18px;"></i>&nbsp;&nbsp; Get OTP via Voice </button>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-md-6">
                                    <input type="button" value="Login" class="btn btn-default mb-xl prev-step" data-loading-text="Loading..." style="background: grey; color:white;">
                                    <!-- <input type="button" value="Back To Verification" class="btn btn-default mb-xl prev" data-loading-text="Loading..." style="background: grey; color:white;" > -->
                                    <!-- <input type="submit" value="Register" class="btn btn-primary mb-xl" data-loading-text="Loading..." onclick='goog_report_conversion()'> -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </div>
</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@stop 
@section('script')
<script type="text/javascript">

    function registerUser() {
        $("#register").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Registering...");
        $.ajax({
          url: '{{url('auth/register')}}',
          type: 'post',
          data: {
                "first_name": $('#first_name').val(),
                "last_name": $('#last_name').val(),
                "email": $('#email').val(),
                "company": $('#company').val(),
                "bussiness": $('#business').val(),
                "company_type": $('#company_type').val(),
                "company_size": $('#company_size').val(),
                "country": $('#country').val(),
                "mobile_code": $('#mobile_code').val(),
                "mobile": $('#mobilenum').val(),
                "address": $('#address').val(),
                "city": $('#city').val(),
                "state": $('#state-list').val(),
                "zip": $('#postal_code').val(),
                "user_name": $('#user_name').val(),
                "password": $('#password').val(),
                "password_confirmation": $('#confirm_pass').val(),
                "terms": $('#terms').val(),
                "_token": "{!! csrf_token() !!}",
          },
          success: function (response) {
            if(response.type == 'success'){
                var result =  '<div class="alert alert-success alert-dismissable"></i><b>'+response.message+'!</b>.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                $('#alertMessage1').html(result);
                $('.wizard-inner').show();
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
                window.scrollTo(0, 10);
                verifyForm.elements['user_id'].value = response.user_id;
                verifyForm.elements['verify_email'].value = $('#email').val();
                verifyForm.elements['verify_country_code'].value = $('#mobile_code').val();
                verifyForm.elements['verify_number'].value = $('#mobilenum').val();
                verifyForm.elements['email_password'].value = $('#password').val();
                $("#register").html("Register");
                /*setTimeout(function(){ 
                    $('#alertMessage1').hide(); 
                }, 3000);*/
            }
          },
          error: function (ex) {
            var myJSON = JSON.parse(ex.responseText);
            var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
            $("#register").html("Register");
            $('html, body').animate({scrollTop:0}, 500);
            for (var key in myJSON)
            {
                html += '<li>' + myJSON[key][0] + '</li>'
            }
            html += '</ul></div>';
            $('#error').show();
            document.getElementById('error').innerHTML = html;
            setTimeout(function(){ 
                $('#error').hide(); 
            }, 5000);
          }
        });
    }

    function sendOTP() {
        $("#sendOtp").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
        var data = {
            "email": $('#verify_email').val(),
            "mobile": $('#verify_number').val(),
            "code": $('#verify_country_code').val(),
            'id': $('#user_id').val(),
            'password': $('#email_password').val()
        };
        $.ajax({
          url: '{{url('otp/sendByAjax')}}',
          type: 'GET',
          data: data,
          success: function (response) {
                var result =  '<div class="alert alert-success alert-dismissable"><b>'+response.message+'!</b>.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                $('#alertMessage2').html(result);
                $('#error1').hide();
                $('.wizard-inner').show();
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
                window.scrollTo(0, 10);
                verify_otp_form.elements['hidden_user_id'].value = $('#user_id').val();
                $("#sendOtp").html("Send");
          },
          error: function (ex) {
            var myJSON = JSON.parse(ex.responseText);
            var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
            $("#sendOtp").html("Send");
            for (var key in myJSON)
            {
                html += '<li>' + myJSON[key][0] + '</li>'
            }
            html += '</ul></div>';
            $('#alertMessage1').hide();
            $('#error1').show();
            document.getElementById('error1').innerHTML = html;
            setTimeout(function(){ 
                $('#error1').hide(); 
            }, 5000);
          }
        });
    }

    function verifyBySendOtp() {
        $("#verifyOtp").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
        var data = {
            "mobile":   $('#verify_number').val(),
            "code"  :   $('#verify_country_code').val(),
            "otp"   :   $('#oneTimePassword').val(),
            'id'    :   $('#hidden_user_id').val()
        };
        $.ajax({
            url: '{{url('otp/verify')}}',
            type: 'GET',
            data: data,
            success: function (response) {
                $('#error2').hide(); 
                $('#alertMessage2').show();
                var result =  '<div class="alert alert-success alert-dismissable"></i><b>'+response.message+'!</b>.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                $('#alertMessage2').html(result);
                $("#verifyOtp").html("Verify OTP");
            },
            error: function (ex) {
                var myJSON = JSON.parse(ex.responseText);
                var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                $("#verifyOtp").html("Verify OTP");
                for (var key in myJSON)
                {
                    html += '<li>' + myJSON[key][0] + '</li>'
                }
                html += '</ul></div>';
                $('#alertMessage2').hide(); 
                $('#error2').show();
                document.getElementById('error2').innerHTML = html;
                setTimeout(function(){ 
                    $('#error2').hide(); 
                }, 5000);
            }
        });
    }

    function resendOTP() {
        var data = {
            "mobile":   $('#verify_number').val(),
            "code"  :   $('#verify_country_code').val(),
        };
        $.ajax({
          url: '{{url('resend_otp')}}',
          type: 'GET',
          data: data,
          success: function (response) {
                $('#alertMessage2').show();
                $('#error2').hide();
                var result =  '<div class="alert alert-success alert-dismissable"></i><b>'+response.message+'!</b>.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                $('#alertMessage2').html(result+ ".");
          },
          error: function (ex) {
                var myJSON = JSON.parse(ex.responseText);
                var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                for (var key in myJSON)
                {
                    html += '<li>' + myJSON[key][0] + '</li>'
                }
                html += '</ul></div>';
                $('#alertMessage2').hide();
                $('#error2').show(); 
                document.getElementById('error2').innerHTML = html;
          }
        });
    }
</script>

<script>

    $(document).ready(function () {
        var val = $("#country").val();
        getCountryAttr(val);
    });

    function getCountryAttr(val) {
        getState(val);
        getCode(val);

//        getCurrency(val);

    }

    function getState(val) {


        $.ajax({
            type: "POST",
            url: "{{url('get-state')}}",
            data: {'country_id':val,'_token':"{{csrf_token()}}"},//'country_id=' + val,
            success: function (data) {
                $("#state-list").html(data);
            }
        });
    }
    function getCode(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-code')}}",
            data: {'country_id':val,'_token':"{{csrf_token()}}"},//'country_id=' + val,
            success: function (data) {
                $("#mobile_code").val(data);
                $("#mobile_code_hidden").val(data);
            }
        });
    }
    function getCurrency(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-currency')}}",
            data: {'country_id':val,'_token':"{{csrf_token()}}"},//'country_id=' + val,
            success: function (data) {
                $("#currency").val(data);
            }
        });
    }
</script>
<!-- Google Code for Help Desk Pro | Campaign 001 Conversion Page
In your html page, add the snippet and call
goog_report_conversion when someone clicks on the
chosen link or button. -->
<script type="text/javascript"> 
//<![CDATA[
  goog_snippet_vars = function() {
    var w = window;
    w.google_conversion_id = 1027628032;
    w.google_conversion_label = "uBhoCLT3i3AQgLiB6gM";
    w.google_remarketing_only = false;
  }
  // DO NOT CHANGE THE CODE BELOW.
  goog_report_conversion = function(url) {
    goog_snippet_vars();
    window.google_conversion_format = "3";
    var opt = new Object();
    opt.onload_callback = function() {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  }
  var conv_handler = window['google_trackConversion'];
  if (typeof(conv_handler) == 'function') {
    conv_handler(opt);
  }
fbq('track', 'CompleteRegistration');
}
//]]>
</script>
<!-- Google Code for Help Desk Pro | Campaign 001 Conversion Page
In your html page, add the snippet and call
goog_report_conversion when someone clicks on the
chosen link or button. -->
<script type="text/javascript"> 
//<![CDATA[
  goog_snippet_vars = function() {
    var w = window;
    w.google_conversion_id = 1027628032;
    w.google_conversion_label = "uBhoCLT3i3AQgLiB6gM";
    w.google_remarketing_only = false;
  }
  // DO NOT CHANGE THE CODE BELOW.
  goog_report_conversion = function(url) {
    goog_snippet_vars();
    window.google_conversion_format = "3";
    var opt = new Object();
    opt.onload_callback = function() {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  }
  var conv_handler = window['google_trackConversion'];
  if (typeof(conv_handler) == 'function') {
    conv_handler(opt);
  }
fbq('track', 'CompleteRegistration');
}
//]]>
</script>
>>>>>>> refs/remotes/origin/master
<script type="text/javascript"
  src="//www.googleadservices.com/pagead/conversion_async.js">
</script>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '308328899511239'); 
fbq('track', 'PageView');

</script>

<script type="text/javascript"
  src="//www.googleadservices.com/pagead/conversion_async.js">
</script>
<script>
  $(document).ready(function () {

    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    $('.nav-tabs .active a[href="#step1"]').click(function(){
         $('.wizard-inner').hide();
    })
    //Wizard
    if($('.nav-tabs .active a[href="#step1"]')){
        $('.wizard-inner').hide();
    }
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    /*$(".next-step").click(function (e) {
        $('.wizard-inner').show();
        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);
        window.scrollTo(0, 10);

    });*/
    $(".prev-step").click(function (e) {

        registerForm.elements['first_name'].value = '';
        registerForm.elements['last_name'].value = '';
        registerForm.elements['email'].value = '';
        registerForm.elements['company'].value = '';
        registerForm.elements['bussiness'].value = '';
        registerForm.elements['company_type'].value = '';
        registerForm.elements['company_size'].value = '';
        registerForm.elements['mobile'].value = '';
        registerForm.elements['address'].value = '';
        registerForm.elements['user_name'].value = '';
        registerForm.elements['password'].value = '';
        registerForm.elements['password_confirmation'].value = '';
        registerForm.elements['terms'].checked = false;

        $('.nav-tabs li a[href="#step1"]').tab('show');
        $('.wizard-inner').hide();

    });
    $(".prev").click(function (e) {
        
        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        prevTab($active);
        $('.wizard-inner').show();
    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{asset("lb-faveo/js/intlTelInput.js")}}"></script>
<script type="text/javascript">
    var telInput = $('#mobile');
    telInput.intlTelInput({
        geoIpLookup: function (callback) {
            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        initialCountry: "auto",
        separateDialCode: true,
        utilsScript: "{{asset('lb-faveo/js/utils.js')}}"
    });
    $('.intl-tel-input').css('width', '100%');

    telInput.on('blur', function () {
        if ($.trim(telInput.val())) {
            if (!telInput.intlTelInput("isValidNumber")) {
                telInput.parent().addClass('has-error');
            }
        }
    });
    $('input').on('focus', function () {
        $(this).parent().removeClass('has-error');
    });

    $('form').on('submit', function (e) {
        $('input[name=country_code]').attr('value', $('.selected-dial-code').text());
    });

</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=308328899511239&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
@stop
