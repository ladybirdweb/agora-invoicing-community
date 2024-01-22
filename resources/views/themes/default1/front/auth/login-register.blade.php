@extends('themes.default1.layouts.front.master')
@section('title')
    Login | Register
@stop
@section('page-header')
    Login | Register
@stop
@section('page-heading')
Sign in or Register
@stop
@section('breadcrumb')
    @if(Auth::check())
        <li><a class="text-primary" href="{{url('my-invoices')}}">Home</a></li>
    @else
         <li><a class="text-primary" href="{{url('login')}}">Home</a></li>
    @endif
     <li class="active text-dark">Sign in &nbsp;&nbsp;or&nbsp;&nbsp; Register</li>
@stop 
@section('main-class')
    main
@stop
@section('content')
    <?php
    use App\Http\Controllers\Front\CartController;
    $country = findCountryByGeoip($location['iso_code']);
    $states = findStateByRegionId($location['iso_code']);
    $states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
    $state_code = $location['iso_code'] . "-" . $location['state'];
    $state = getStateByCode($state_code);


    ?>
    <style>
<?php 
$setting = \App\Model\Common\Setting::where('id', 1)->first();
$everyPageScripts = ''; 
$scripts = \App\Model\Common\ChatScript::where('on_registration', 1)->get();
foreach($scripts as $script) {
    if (strpos($script->script, '<script>') === false && strpos($script->script, '</script>') === false) {
        $everyPageScripts .= "<script>{$script->script}</script>";
    } else {
        $everyPageScripts .= $script->script;
    }
}
?>
  .bootstrap-select.btn-group .btn .filter-option 
  {
      font-weight: normal;
  }
        .required:after{
            content:'*';
            color:red;
            padding-left:0px;
        }


        

        .wizard-inner
        {
            display:none;
        }

        

        .nav-tabs{
            border-bottom: none;
            margin: -5px;
        }
        .tab-content {
            border-radius: 0px;
            box-shadow: inherit;

            border: none ;
            border-top: 0;
            /*padding: 15px;*/
        }

        .open>.dropdown-menu {
            display: block;
            color:black;
        }
        .inner>.dropdown-menu{
            margin-top: 0px;
        }
        .bootstrap-select .dropdown-toggle .caret {
           display: none;
        }

        .form-control:not(.form-control-sm):not(.form-control-lg) {
        /*font-size: 13.6px;
        font-size: 0.85rem;*/
        line-height: normal;
    }



    </style>
    
    <link rel="stylesheet" href="{{asset('client/css/selectpicker.css')}}" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.css" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/2.0.0-beta1/css/bootstrap-select.min.css" />

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/2.0.0-beta1/js/bootstrap-select.min.js"></script> 
    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="wizard">
                    <div class="wizard-inner" style="display: none">

                        <ul class="nav nav-tabs" role="tablist" style=" margin: -5px!important;">
                            <li role="presentation" class="active">
                                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab">


                                </a>
                                <p style="display: none">Contact Information</p>
                            </li>
                            <li role="presentation" class="disabled" >
                                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" >


                                </a>
                                <p style="display: none">Identity Verification</p>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#step3" data-toggle="tab" aria-controls="complete" role="tab" title="Confirmation">


                                </a>
                                <p style="display: none">Confirmation</p>
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
                                <div id="alertMessage1"></div>
                                <div id="alertMessage2"></div>
                                <!-- <div id="error2">
                                </div>
                                <div id="alertMessage2" class="-text" ></div> -->
              
                                <div class="row justify-content-center">
                                          <div class="col-md-6 col-lg-6 mb-5 mb-lg-0 pe-5">

                    <h2 class="font-weight-bold text-5 mb-0">Login</h2>

                     @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                        {!!  Form::open(['url'=>'login', 'method'=>'post','id'=>'formoid','onsubmit'=>'return validateform()']) !!}
                    @else
                        {!!  Form::open(['url'=>'login', 'method'=>'post','id'=>'formoid']) !!}
                    @endif

                        <div class="row">

                            <div class="form-group col {{ $errors->has('email1') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Username or E-mail Address <span class="text-color-danger">*</span></label>

                                 {!! Form::text('email1',null,['class' => 'form-control form-control-lg text-4','id'=>'username','autocomplete'=>"off", 'style' => 'height: calc(1.5em + 0.75rem + 2px);' ]) !!}
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col {{ $errors->has('password1') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Password <span class="text-color-danger">*</span></label>

                                {!! Form::password('password1', ['class' => 'form-control form-control-lg text-4', 'id' => 'pass', 'style' => 'height: calc(1.5em + 0.75rem + 2px);']) !!}
                                                                     

                            </div>
                        </div>

                        <div class="row justify-content-between">

                            <div class="form-group col-md-auto">

                                <div class="custom-control custom-checkbox" style="padding-right: 100px;">

                                    {!! Form::checkbox('remember', '1', false, ['class' => 'custom-control-input', 'id' => 'rememberme']) !!}
                                    <label class="form-label custom-control-label cur-pointer text-2" for="rememberme">Remember Me</label>
                                </div>
                            </div>

                            <div class="form-group col-md-auto {{ $errors->has('password1') ? 'has-error' : '' }}">

                                <a class="text-decoration-none text-color-primary font-weight-semibold text-2" href="{{url('password/reset')}}" style="padding-left: 100px;">({{Lang::get('message.forgot-my-password')}})</a>
                            </div>
                        </div>

                        @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                                                    {!! NoCaptcha::renderJs() !!}
                                                    {!! NoCaptcha::display(['id' => 'recaptcha1']) !!}
                                                    <div class="loginrobot-verification"></div><br>
                        @endif
                   
                        <div class="row">

                            <div class="form-group col">

                                <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" data-loading-text="Loading...">Login</button>
                                @if($google_status == 1 || $twitter_status == 1 || $github_status == 1 ||$linkedin_status == 1)

                                <div class="divider">

                                    <span class="bg-light px-4 position-absolute left-50pct top-50pct transform3dxy-n50">or</span>
                                </div>
                                @endif
                                @if($google_status == 1)

                                <a href="{{ url('/auth/redirect/google') }}" class="btn btn-primary-scale-2 btn-modern w-100 text-transform-none font-weight-bold align-items-center d-inline-flex justify-content-center text-3 py-3" data-loading-text="Loading...">

                                    <i class="fab fa-google text-5 me-2"></i> Login With Google
                                </a><br><br>
                                @endif
                                @if($twitter_status == 1)

                                <a href="{{ url('/auth/redirect/twitter') }}" class="btn btn-primary-scale-2 btn-modern w-100 text-transform-none font-weight-bold align-items-center d-inline-flex justify-content-center text-3 py-3" data-loading-text="Loading...">

                                    <i class="fab fa-twitter text-5 me-2"></i> Login With Twitter
                                </a><br><br>
                                @endif
                                @if($github_status == 1 )

                                <a href="{{ url('/auth/redirect/github') }}"  class="btn btn-primary-scale-2 btn-modern w-100 text-transform-none font-weight-bold align-items-center d-inline-flex justify-content-center text-3 py-3" data-loading-text="Loading...">

                                    <i class="fab fa-github text-5 me-2"></i> Login With Github
                                </a><br><br>
                                @endif
                                 @if($linkedin_status == 1 )

                                <a href="{{ url('/auth/redirect/linkedin') }}"  class="btn btn-primary-scale-2 btn-modern w-100 text-transform-none font-weight-bold align-items-center d-inline-flex justify-content-center text-3 py-3" data-loading-text="Loading...">

                                    <i class="fab fa-linkedin-in text-5 me-2"></i> Login With Linkedin
                                </a><br><br>
                                @endif
                            </div>
                        </div>
                   {!! Form::close() !!}
                </div>
                                    
                <div class="col-md-6 col-lg-6 ps-5">


                    <h2 class="font-weight-bold text-5 mb-0">Register</h2>

                    <form name="registerForm" id="regiser-form">

                        <div class="row">

                            <div class="form-group col {{ $errors->has('first_name') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">First Name <span class="text-color-danger">*</span></label>

                                 {!! Form::text('first_name',null,['class'=>'form-control form-control-lg text-4', 'id'=>'first_name']) !!}
                                <span id="first_namecheck"></span>
                            </div>

                            <div class="form-group col {{ $errors->has('last_name') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Last Name <span class="text-color-danger">*</span></label>

                                {!! Form::text('last_name',null,['class'=>'form-control form-control-lg text-4', 'id'=>'last_name']) !!}
                                <span id="last_namecheck"></span>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col {{ $errors->has('email') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">E-mail address <span class="text-color-danger">*</span></label>

                                {!! Form::email('email',null,['class'=>'form-control form-control-lg text-4', 'id'=>'email']) !!}
                                <span id="emailcheck"></span>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col {{ $errors->has('company') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Company Name <span class="text-color-danger">*</span></label>

                                {!! Form::text('company',null,['class'=>'form-control form-control-lg text-4', 'id'=>'company']) !!}
                                <span id="companycheck"></span>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col {{ $errors->has('address') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Address <span class="text-color-danger">*</span></label>

                                {!! Form::textarea('address',null,['class'=>'form-control form-control-lg text-4','rows'=>4, 'id'=>'address']) !!}
                                 <span id="addresscheck"></span>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col {{ $errors->has('country') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Country <span class="text-color-danger">*</span></label>

                                <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>
                                {!! Form::select('country',[''=>'','Choose'=>$countries],$country,['class' => 'form-select form-control h-auto py-2 selectpicker con','data-live-search-style'=>"startsWith",'data-live-search'=>'true','data-live-search-placeholder'=>'Search','data-dropup-auto'=>'false','data-size'=>'10','onChange'=>'getCountryAttr(this.value);','id'=>'country']) !!}
                                <span id="countrycheck"></span>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col {{ $errors->has('mobile_code') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Mobile <span class="text-color-danger">*</span></label>

                                {!! Form::hidden('mobile',null,['id'=>'mobile_code_hidden']) !!}
                                <input class="form-control form-control-lg text-4" id="mobilenum" name="mobile" type="tel">
                                {!! Form::hidden('mobile_code',null,['class'=>'form-control form-control-lg text-4','disabled','id'=>'mobile_code']) !!}
                                <span id="valid-msg" class="hide"></span>
                                <span id="error-msg" class="hide"></span>
                                <span id="mobile_codecheck"></span>
                                </div>
                        </div>

                      <div class="form-row hidden">
                        <div class="form-group col{{ $errors->has('state') ? 'has-error' : '' }}">
                            {!! Form::label('state',Lang::get('message.state')) !!}
                            <?php
                            $value = "";
                            if (count($state) > 0) {
                                $value = $state;
                            }
                            if (old('state')) {
                                $value = old('state');
                            }
                            ?>

                            {!! Form::select('state',[$states],$value,['class' => 'form-control input-lg','id'=>'state-list']) !!}

                            <span id="statecheck"></span>
                        </div>

                    </div>

                        <div class="row">

                            <div class="form-group col {{ $errors->has('password') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Password <span class="text-color-danger">*</span></label>

                                {!! Form::password('password',['class'=>'form-control form-control-lg text-4', 'id'=>'password']) !!}
                                <span id="password1check"></span>
                            </div>

                            <div class="form-group col {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Re-enter Password <span class="text-color-danger">*</span></label>

                               {!! Form::password('password_confirmation',['class'=>'form-control form-control-lg text-4', 'id'=>'confirm_pass']) !!}
                                <span id="conpasscheck"></span>
                            </div>
                        </div>

                        <div class="form-row">
                        <div class="form-group col-lg-6">
                            @if ($status->recaptcha_status == 1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                                {!! NoCaptcha::display(['id' => 'g-recaptcha-1', 'data-callback' => 'onRecaptcha']) !!}
                                <input type="hidden" id="g-recaptcha-response-1" name="g-recaptcha-response-1">
                                <div class="robot-verification" id="captcha"></div>
                                <span id="captchacheck"></span>
                            @endif
                        </div>
                    </div>

                        <div class="row">
                            @if($status->terms ==0)
                            <div class="form-group col">

                                <div class="form-check">

                                    <input type="hidden" value="true" name="terms" id="term">
                                </div>
                            </div>
                            @else

                            <div class="form-group col">

                                <div class="form-check" style="padding-left: 0px;">

                                    <input type="checkbox" value="false" name="terms" id="term" ><a href="{{$apiKeys->terms_url}}" target="_blank">
                                        Agree to terms and conditions
                                    </a>
                                    <br><span id="termscheck"></span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="row">

                            <div class="form-group col">

                                <button type="button" name="register" id="register" onclick="registerUser()" class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" data-loading-text="Loading...">Register</button>

                            </div>
                        </div>
                    </form>
                </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 tab-pane" id="step2">

                            <div class="container py-4" >
                            <div id="successMessage1" style="width: 500px;position: relative;left: 260px;text-align: center;"></div>
                            <div id = "emailsuccess" style="width: 500px;position: relative;left: 260px;text-align: center;"></div>

            <div class="row justify-content-center">

                <div class="col-md-6 col-lg-6 mb-5 mb-lg-0 pe-5">

                    <p class="text-2">You will be sent a verification email by an automated system, Please click on the verification link in the email. Click next to continue</p>

                    <form novalidate="novalidate" name="verifyForm">
                        <input type="hidden" name="user_id" id="user_id"/>
                        <input type="hidden" name="email_password" id="email_password"/>
                        <input type="hidden" id="checkEmailStatus" value="{{$status->emailverification_status}}">
                        @if($status->emailverification_status == 1)
                        <div class="form-row">
                        <div class="form-group col">
                            <label  for="mobile" class="required">Email</label>
                            <div class="input-group">
                                <input type="hidden" id="emailstatusConfirm" value="{{$status->emailverification_status}}">
                                <input type="email" value="" name="verify_email" id="verify_email" class="form-control form-control input-lg">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                </div>

                            </div>
                            <span id="conemail"></span>
                        </div>

                    </div>
                        @endif

                        <p class="text-2">You will be sent an OTP on your mobile immediately by an automated system, Please enter the OTP in the next step. Click next to continue</p>

                        @if($status->msg91_status == 1)
                        <div class="form-row">
                            <div class="form-group col">
                                <input id="mobile_code_hidden" name="mobile_code" type="hidden">
                                <input class="form-control form-control input-lg"  id="verify_country_code" name="verify_country_code" type="hidden">
                                <label for="mobile" class="required">Mobile</label><br/>
                                <input type="hidden" id="mobstatusConfirm" value="{{$status->msg91_status}}">
                                <input class="form-control input-lg phone"  name="verify_number" type="text" id="verify_number">
                                <span id="valid-msg1" class="hide"></span>
                                <span id="error-msg1" class="hide"></span>

                                <span id="conmobile"></span>
                            </div>

                        </div>
                    @endif

                        <div class="row">

                            <div class="form-group col">

                                <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" data-loading-text="Loading..." name="sendOtp" id="sendOtp" onclick="sendOTP()">Next</button>
                            </div>
                        </div>
                    </form>
                            </div>
                        </div>
                    </div>
                        </div>
                                      <div class="col-md-12 tab-pane" id="step3">
                                        <div id="error2"></div>
                                        <div id="successMessage2" style="width: 500px;position: relative;left: 260px;text-align: center;"></div>
                                        <div id="alertMessage3"></div>
                                        <input type="hidden" id="checkOtpStatus" value="{{$status->msg91_status}}">
                                        <div class="col-md-6 col-lg-6 mb-5 mb-lg-0 pe-5 mx-auto text-cente">
                                        <div class="container">

                                        <div id="alert-resend" class="alert alert-success alert-dismissible d-none" role="alert">

                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                                        <strong>Success!</strong> OTP sent.
                                        </div>

                                        <form name="verify_otp_form">
                                            <label for="mobile" class="required">Enter OTP</label><br />

                                             <div class="row">
                                            <input type="hidden" name="user_id" id="hidden_user_id"/>
                                            <input class="form-control form-control-lg text-4"  id="oneTimePassword" name="oneTimePassword" type="text" >
                                            <span id="enterotp"></span>
                                        </div><br>

                                              

                                                <button name="verifyOtp" id="verifyOtp" onclick="verifyBySendOtp()" class="btn btn-dark btn-modern w-100 text-uppercase text-3 mt-3">Verify OTP</button>


                                                <button id="resendOTP" onclick="resendOTP()" class="btn btn-dark btn-outline btn-modern w-100 text-uppercase font-weight-bold text-3 mt-2">
                                                    Resend OTP
                                                </button>

                                                <a id="voiceOTP" class="btn btn-dark btn-outline btn-modern w-100 text-uppercase font-weight-bold text-3 mt-2">
                                                    <i class="fas fa-phone text-5 me-2"></i> Receive OTP via Voice Call
                                                </a>

                                                  </form>
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
    <!--<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $analyticsTag; ?>"></script>-->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>


    <script>
        ///////////////////////////////////////////////////////////////////////////////
        ///Google Recaptcha
        function recaptchaCallback() {
            document.querySelectorAll('.g-recaptcha').forEach(function (el) {
                grecaptcha.render(el);
            });
        }
        ///////////////////////////////////////////////////////////////////////////////////
    </script>
    <!--Start of Tawk.to Script-->
{!! $everyPageScripts !!}
<!--End of Tawk.to Script-->


    <script type="text/javascript">



        function verify_otp_check(){
            var userOtp = $('#oneTimePassword').val();
            if (userOtp.length < 4){
                $('#enterotp').show();
                $('#enterotp').html("Please Enter A Valid OTP");
                $('#enterotp').focus();
                $('#oneTimePassword').css("border-color","red");
                $('#enterotp').css({"color":"red","margin-top":"5px"});


                // mobile_error = false;
                return false;
            }
            else{
                $('#enterotp').hide();
                $('#oneTimePassword').css("border-color","");
                return true;

            }
        }

        function verifyBySendOtp() {
            $('#enterotp').hide();
            if(verify_otp_check()) {
                $("#verifyOtp").attr('disabled',true);
                // $("#verifyOtp").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                var data = {
                    "mobile":   $('#verify_number').val().replace(/[\. ,:-]+/g, ''),
                    "code"  :   $('#verify_country_code').val(),
                    "otp"   :   $('#oneTimePassword').val(),
                    'id'    :   $('#hidden_user_id').val(),
                };
                $.ajax({
                    url: '{{url('otp/verify')}}',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        $("#verifyOtp").attr('disabled',false);
                        $('#error2').hide();
                        $('#error').hide();
                        $('#alertMessage2').show();
                        var result =  '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                        // $('#alertMessage3').show();
                        $('#successMessage2').hide();
                        $('#success').html(result);
                        $("#verifyOtp").html("Verify OTP");
                        $('.nav-tabs li a[href="#step1"]').tab('show');
                        $('.wizard-inner').css('display','none');
                        setTimeout(()=>{
                            getLoginTab();
                        },10)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        $("#verifyOtp").attr('disabled',false);
                        if (xhr.status === 422) {
                         var myJSON = xhr.responseJSON.message;
                         var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><ul>';
                              for (var key in xhr.responseJSON.errors)
                        {
                            html += '<li>' + xhr.responseJSON.errors[key][0] + '</li>'
                        }
                        html += '</ul></div>';
                        }
                        else{
                        var myJSON = JSON.parse(xhr.responseText);
                        var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><ul>';
                        $("#verifyOtp").html("Verify OTP");
                        for (var key in myJSON)
                        {
                            html += '<li>' + myJSON[key][0] + '</li>'
                        }
                        html += '</ul></div>';
                 
                        }
                        $('#successMessage2').hide();
                        $('#alertMessage2').hide();
                        $('#error2').show();
                        document.getElementById('error2').innerHTML = html;
                        setTimeout(function(){
                            $('#error2').hide();
                        }, 5000);
                    }
                });
            }
            else
            {
                return false;
            }
        }


        function getLoginTab(){
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
            $('.wizard-inner').css('display','none');
        }

        $(".prev-step").click(function (e) {
            getLoginTab();
        });

        //Enter OTP Validation
        $('#oneTimePassword').keyup(function(){
            verify_otp_check();
        });

        //--------------------------------------------------ReSend OTP via SMS---------------------------------------------------//

        $('#resendOTP').on('click',function(){
            var data = {
                "mobile":   $('#verify_number').val().replace(/[\. ,:-]+/g, ''),
                "code"  :  ($('#verify_country_code').val()),
                "type"  :  "text",
            };
            $("#resendOTP").attr('disabled',true);
            $("#resendOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Resending..");
            $.ajax({
                url: '{{url('resend_otp')}}',
                type: 'GET',
                data: data,
                success: function (response) {
                    $("#resendOTP").attr('disabled',false);
                    $("#resendOTP").html("Resend OTP");
                    $('#successMessage2').hide ();
                    $('#alertMessage3').show();
                    $('#error2').hide();
                    var result =  '<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                    $('#alertMessage3').html(result+ ".");
                      setTimeout(function(){
                        $('#alertMessage3').hide();
                        }, 3000);
                },
                error: function (ex) {
                    $("#resendOTP").attr('disabled',false);
                    $("#resendOTP").html("Resend OTP");
                    var myJSON = JSON.parse(ex.responseText);
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oh Snap! </strong>Something went wrong<br><br><ul>';
                    for (var key in myJSON)
                    {
                        html += '<li>' + myJSON[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    ('#successMessage2').hide();
                    $('#alertMessage2').hide();
                    $('#alertMessage3').hide();
                    $('#error2').show();
                    document.getElementById('error2').innerHTML = html;
                }
            })

        });

        //---------------------------------------Resend OTP via voice call--------------------------------------------------//

        $('#voiceOTP').on('click',function(){
            var data = {
                "mobile":   $('#verify_number').val().replace(/[\. ,:-]+/g, ''),
                "code"  :  ($('#verify_country_code').val()),
                "type"  :  "voice",
            };
            $("#voiceOTP").attr('disabled',true);
            $("#voiceOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending Voice Call..");
            $.ajax({
                url: '{{url('resend_otp')}}',
                type: 'GET',
                data: data,
                success: function (response) {
                    $("#voiceOTP").attr('disabled',false);
                    $("#voiceOTP").html("Receive OTP via Voice call");
                    $('#successMessage2').hide ();
                    $('#alertMessage3').show();
                    $('#error2').hide();
                    var result =  '<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                    $('#alertMessage3').html(result+ ".");
                     setTimeout(function(){
                        $('#alertMessage3').hide();
                        }, 3000);
                },
                error: function (ex) {
                    $("#voiceOTP").attr('disabled',false);
                    $("#voiceOTP").html("Receive OTP via Voice call");
                    var myJSON = JSON.parse(ex.responseText);
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oh Snap! </strong>Something went wrong<br><br><ul>';
                    for (var key in myJSON)
                    {
                        html += '<li>' + myJSON[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    $('#alertMessage2').hide();
                    $('#alertMessage3').hide();
                    $('#error2').show();
                    document.getElementById('error2').innerHTML = html;
                }
            })

        });


    </script>




    <script type="text/javascript">
        /*
        * Email ANd Mobile Validation when Send Button is cliced on Tab2
         */
        /////////////////////////////////////////////////////////////////////////////////////////////////
        $('#verify_email').keyup(function(){//Email
            verify_email_check();
        });

        function verify_email_check(){
            if($("#emailstatusConfirm").val() ==1) {//if email verification is active frm admin panlel then validate else don't

                var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
                if (pattern.test($('#verify_email').val())) {
                    $('#conemail').hide();
                    $('#verify_email').css("border-color","");
                    return true;
                } else{
                    $('#conemail').show();
                    $('#conemail').html("Please Enter a valid email");
                    $('#conemail').focus();
                    $('#verify_email').css("border-color","red");
                    $('#conemail').css({"color":"red","margin-top":"5px"});
                    return false;

                }
            }
            return true;

        }

        $('#verify_number').keyup(function(){//Mobile
            verify_number_check();
        });

        function verify_number_check(){

            var userNumber = $('#verify_number').val();
            if($("#mobstatusConfirm").val() ==1) { //If Mobile Status Is Active
                if (userNumber.length < 5){
                    $('#conmobile').show();
                    $('#conmobile').html("Please Enter Your Mobile No.");
                    $('#conmobile').focus();
                    $('#verify_number').css("border-color","red");
                    $('#conmobile').css({"color":"red","margin-top":"5px"});


                    // mobile_error = false;
                    return false;
                }
                else{
                    $('#conmobile').hide();

                    $('#verify_number').css("border-color","");
                    return true;

                }
            }
            return true;

        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /*
          * After Send Button is Clicked on Tab 2 fOR sending OTP AND Email
         */
        function sendOTP() {
            $('#conemail').hide();
            $('#conmobile').hide();
            var mail_error = true;
            var mobile_error = true;
            if((verify_email_check()) && (verify_number_check()))
            {

                var oldemail=sessionStorage.getItem('oldemail');
                var newemail = $('#verify_email').val(); // this.value
                var oldnumber = sessionStorage.getItem('oldemail');
                var newnumber = $('#verify_number').val();

                $("#sendOtp").attr('disabled',true);
                $("#sendOtp").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
                var data = {
                    "newemail": newemail,
                    "newnumber": newnumber,
                    "oldnumber": oldnumber,
                    "oldemail": oldemail,
                    "email": $('#verify_email').val(),
                    "mobile": $('#verify_number').val().replace(/[\. ,:-]+/g, ''),
                    'code': $('#verify_country_code').val(),
                    'id': $('#user_id').val(),
                    'password': $('#email_password').val()
                };
                $.ajax({
                    url: '{{url('otp/sendByAjax')}}',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        // window.history.replaceState(response.type, "TitleTest", "login");
                        $("#sendOtp").attr('disabled',false);
                        var result =  '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Almost there! </strong>'+response.message+'</div>';
                        if (($("#checkOtpStatus").val()) == 1 ) {
                            $('#successMessage2').html(result);
                            $('#error1').hide();
                            $('.wizard-inner').css('display','none');
                            var $active = $('.wizard .nav-tabs li.active');
                            $active.next().removeClass('disabled');
                            nextTab($active);

                            setTimeout(function(){
                                sessionStorage.removeItem('oldemail');
                                sessionStorage.clear();
                            }, 500);
                            window.scrollTo(0, 10);
                            verify_otp_form.elements['hidden_user_id'].value = $('#user_id').val();
                            $("#sendOtp").html("Send");
                        } else {//Show Only Email Success Message when Mobile Status is Not Active
                            $('#emailsuccess').html(result);
                            $('#successMessage1').hide();
                            $("#sendOtp").html("Send");
                            $('#error1').hide();
                        }
                          setTimeout(function(){
                                $('#emailsuccess').hide();
                            }, 3000);
                            setTimeout(function(){
                                $('#successMessage1').hide();
                            }, 3000);
                            setTimeout(function(){
                                $('#successMessage2').hide();
                            }, 3000);
                    },
                    error: function (ex) {
                        $("#sendOtp").attr('disabled',false);
                        var myJSON = JSON.parse(ex.responseText);
                        var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                        $("#sendOtp").html("Send");

                        html += '<li>' + myJSON.message + '</li>'

                        html += '</ul></div>';
                        $('#alertMessage1').hide();
                        $('#successMessage1').hide();
                        $('#error1').show();
                        document.getElementById('error1').innerHTML = html;
                        setTimeout(function(){
                            $('#error1').hide();
                        }, 5000);
                    }
                });
            }
            else{
                return false;
            }

        }

  


        //robot validation for Login Form
        function validateform() {
            var input = $("#recaptcha1 :input[name='g-recaptcha-response']");
            if(input.val() == null || input.val()==""){
                $('.loginrobot-verification').empty()
                $('.loginrobot-verification').append("<p style='color:red'>Robot verification failed, please try again.</p>")
                return false;
            }
            else{
                return true;
            }
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Registration Form Validation

        function first_namecheck(){
            var firrstname_val = $('#first_name').val();
            if(firrstname_val.length == ''){
                $('#first_namecheck').show();
                $('#first_namecheck').html("Please Enter First Name");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color","red");
                $('#first_namecheck').css("color","red");
                // userErr =false;

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top -200
                }, 1000)
                return false;
            }

            if(firrstname_val.length > 30){
                $('#first_namecheck').show();
                $('#first_namecheck').html("Max 30 characters allowed ");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color","red");
                $('#first_namecheck').css("color","red");
                // userErr =false;

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top -200
                }, 1000)
                return false;
            }

            var pattern = new RegExp(/[^a-zA-Z0-9]/);
            if(pattern.test(firrstname_val)) {
                $('#first_namecheck').show();
                $('#first_namecheck').html("Special characters not allowed");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color","red");
                $('#first_namecheck').css("color","red");

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top -200
                }, 1000)
                return false;
            }

            else{
                $('#first_namecheck').hide();
                $('#first_name').css("border-color","");
                return true;
            }
        }
        //Validating last name field
        function last_namecheck(){
            var lastname_val = $('#last_name').val();
            if(lastname_val.length == ''){
                $('#last_namecheck').show();
                $('#last_namecheck').html("Please Enter Last Name");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color","red");
                $('#last_namecheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            }

            if(lastname_val.length > 30 ){
                $('#last_namecheck').show();
                $('#last_namecheck').html("Maximum 30 characters allowed");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color","red");
                $('#last_namecheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            }


            var pattern = new RegExp(/[^a-zA-Z0-9]/);
            if(pattern.test(lastname_val)){
                $('#last_namecheck').show();
                $('#last_namecheck').html("Special characters not allowed");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color","red");
                $('#last_namecheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            }

            else{
                $('#last_namecheck').hide();
                $('#last_name').css("border-color","");
                return true;
            }
        }
        //Validating email field
        function emailcheck(){

            var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
            if (pattern.test($('#email').val())){
                $('#emailcheck').hide();
                $('#email').css("border-color","");
                return true;

            }
            else{
                $('#emailcheck').show();
                $('#emailcheck').html("Please Enter a valid email");
                $('#emailcheck').focus();
                $('#email').css("border-color","red");
                $('#emailcheck').css({"color":"red","margin-top":"5px"});
                // mail_error = false;
                $('html, body').animate({
                    scrollTop: $("#emailcheck").offset().top -200
                }, 1000)
            }

        }

        function companycheck(){
            var company_val = $('#company').val();
            if(company_val.length == ''){
                $('#companycheck').show();
                $('#companycheck').html("Please Enter Company Name");
                $('#companycheck').focus();
                $('#company').css("border-color","red");
                $('#companycheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#companycheck").offset().top - 200
                }, 1000)
            }

            else{
                $('#companycheck').hide();
                $('#company').css("border-color","");
                return true;
            }
        }


            function addresscheck(){
                var address_val = $('#address').val();
                if(address_val.length == ''){
                    $('#addresscheck').show();
                    $('#addresscheck').html("Please Enter Address ");
                    $('#addresscheck').focus();
                     $('#address').css("border-color","red");
                    $('#addresscheck').css({"color":"red","margin-top":"5px"});
                    // userErr =false;
                   $('html, body').animate({
                    scrollTop: $("#addresscheck").offset().top -200
                }, 1000)
                }
                else{
                     $('#addresscheck').hide();
                      $('#address').css("border-color","");
                     return true;
                }
               }





        function countrycheck(){
            var country_val = $('#country').val();
            if(country_val == ''){
                $('#countrycheck').show();
                $('#countrycheck').html("Please Select One Country ");
                $('#countrycheck').focus();
                $('#country').css("border-color","red");
                $('#countrycheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#countrycheck").offset().top - 200
                }, 1000)
            }
            else{
                $('#countrycheck').hide();
                $('#country').css("border-color","");
                return true;
            }
        }

        function mobile_codecheck(){
            var mobile_val = $('#mobilenum').val();
            if(mobile_val.length == ''){
                $('#mobile_codecheck').show();
                $('#mobile_codecheck').html("Please Enter Mobile No. ");
                $('#mobile_codecheck').focus();
                $('#mobilenum').css("border-color","red");
                $('#mobile_codecheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#mobile_codecheck").offset().top -200
                }, 1000)
            }
            else{
                $('#mobile_codecheck').hide();
                $('#mobilenum').css("border-color","");
                return true;
            }
        }



        function towncheck(){
            var town_val = $('#city').val();
            if(town_val.length == ''){
                $('#towncheck').show();
                $('#towncheck').html("Please Enter Town ");
                $('#towncheck').focus();
                $('#city').css("border-color","red");
                $('#towncheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#towncheck").offset().top -200
                }, 1000)
            }
            else{
                $('#towncheck').hide();
                $('#city').css("border-color","");
                return true;
            }
        }

        function statecheck(){
            var state_val = $('#state-list').val();
            if(state_val.length == ''){
                $('#statecheck').show();
                $('#statecheck').html("Please Select a State ");
                $('#statecheck').focus();
                $('#state-list').css("border-color","red");
                $('#statecheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#statecheck").offset().top -200
                }, 1000)
            }

            else{
                $('#statecheck').hide();
                $('#state-list').css("border-color","");
                return true;
            }
        }





        function password1check(){
            var pattern = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/);
            if (pattern.test($('#password').val())){
                $('#password1check').hide();
                $('#password').css("border-color","");
                return true;

            }
            else{
                $('#password1check').show();
                $('#password1check').html("Password must contain Upper/Lowercase/special Character and number");
                $('#password1check').focus();
                $('#password').css("border-color","red");
                $('#password1check').css({"color":"red","margin-top":"0px"});

                // mail_error = false;
                return false;

            }

        }



        //    $('#conpassword').keyup(function(){
        //     con_password_check();
        // });

        function conpasscheck(){
            var confirmPassStore= $('#confirm_pass').val();
            var passwordStore = $('#password').val();
            if(confirmPassStore != passwordStore){
                $('#conpasscheck').show();
                $('#conpasscheck').html("Passwords Don't Match");
                $('#conpasscheck').focus();
                $('#confirm_pass').css("border-color","red");
                $('#conpasscheck').css("color","red");
                $('html, body').animate({
                    scrollTop: $("#conpasscheck").offset().top -200
                }, 1000)
            }
            else{
                $('#conpasscheck').hide();
                $('#confirm_pass').css("border-color","");
                return true;
            }
        }

        function terms(){
            var term_val = $('#term').val();
            if(term_val == 'false'){
                $('#termscheck').show();
                $('#termscheck').html("Terms must be accepted");
                $('#termscheck').focus();
                $('#term').css("border-color","red");
                $('#termscheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                return false;;
            }

            else{
                $('#termscheck').hide();
                $('#term').css("border-color","");
                return true;
            }
        }
        
        
        var recaptchaValid = false;
        function onRecaptcha(response) {
        if (response === '') {
            recaptchaValid = false; // reCAPTCHA validation failed
        } else {
            recaptchaValid = true; // reCAPTCHA validation succeeded
            $('#g-recaptcha-response-1').val(response);
        }
        }
    
         function validateRecaptcha() {
                 var recaptchaResponse = $('#g-recaptcha-response-1').val();

                if (recaptchaResponse === '') {
                    $('#captchacheck').show();
                    $('#captchacheck').html("Robot verification failed, please try again.");
                    $('#captchacheck').focus();
                    $('#captcha').css("border-color", "red");
                    $('#captchacheck').css({"color": "red", "margin-top": "5px"});
                    return false;
                } else {
                    $('#captchacheck').hide();
                    return true;
                }
         }


        ////////////////////////Registration Valdation Ends////////////////////////////////////////////////////////////////////////////////////////////
        ///
        ///////////////////////VALIDATE TERMS AND CNDITION////////////////////////////////////////
        $(document).on('change','#term',function(){
            if($(this).val()=="false"){
                $(this).val("true");
            }
            else{
                $(this).val("false");
            }
        })
        //////////////////////////////Google Analytics Code after Submit button is clicked//////////////////
        function gtag_report_conversion(tag) {
            window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
            
              gtag('config', tag);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        function registerUser() {

            $('#first_namecheck').hide();
            $('#last_namecheck').hide();
            $('#emailcheck').hide();
            $('#companycheck').hide();
            $('#countrycheck').hide();
            $('#mobile_codecheck').hide();
            $('#addresscheck').hide();
            $('#towncheck').hide();
            $('#statecheck').hide();
            $('#password1check').hide();
            $('#conpasscheck').hide();
            $('#termscheck').hide();


            var first_nameErr = true;
            var last_nameErr = true;
            var emailErr = true;
            var companyeErr = true;
            var countryErr = true;
            var addressErr = true;
            var mobile_codeErr = true;
            var password1Err = true;
            var conPassErr = true;
            var termsErr = true;
            // con_password_check();
            
            
      
            if(first_namecheck() && last_namecheck() && emailcheck() && companycheck() && addresscheck() && mobile_codecheck()  && countrycheck()  && password1check() && conpasscheck()  && terms() &&
        validateRecaptcha())
            {

               
                 var tag = "<?php echo $analyticsTag; ?>";
                 if (tag !== "" ){
                        gtag_report_conversion(tag);
                    }
                
                $("#register").attr('disabled',true);
                $("#register").html("<i class='fas fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
                $.ajax({
                    url: '{{url("auth/register")}}',
                    type: 'POST',
                    data: {
                        "first_name": $('#first_name').val(),
                        "last_name": $('#last_name').val(),
                        "email": $('#email').val(),
                        "company": $('#company').val(),
                        "bussiness": $('#business').val(),
                        "company_type": $('#company_type').val(),
                        "company_size": $('#company_size').val(),
                        "country": $('#country').val(),
                        "mobile_code": $('#mobile_code').val().replace(/\s/g, '') ,
                        "mobile": $('#mobilenum').val().replace(/[\. ,:-]+/g, ''),
                        "address": $('#address').val(),
                        "city": $('#city').val(),
                        "state": $('#state-list').val(),
                        "zip": $('#zip').val(),
                        "user_name": $('#user_name').val(),
                        "password": $('#password').val(),
                        "password_confirmation": $('#confirm_pass').val(),
                        "g-recaptcha-response-1":$('#g-recaptcha-response-1').val(),
                        "terms": $('#term').val(),

                        "_token": "{!! csrf_token() !!}",
                    },
                    success: function (response) {
                        // window.history.pushState(response.type, "TitleTest", "thankyou");

                        $("#register").attr('disabled',false);
                        if(response.type == 'success'){
                            $('.wizard-inner').css('display','block');
                            if($("#checkEmailStatus").val() == 0 && $("#checkOtpStatus").val() == 0) {
                                var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Thank You! </strong>'+response.message+'!!</div>';
                                $('#alertMessage1').html(result);
                                window.scrollTo(0,0);
                                $("#register").html("Submit");
                            } else {
                                var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Thank You! </strong>'+response.message+'!!</div>';
                                $('#successMessage1').html(result);
                                var $active = $('.wizard .nav-tabs li.active');
                                $active.next().removeClass('disabled');
                                nextTab($active);
                                window.scrollTo(0,0);
                                verifyForm.elements['user_id'].value = response.user_id;
                                if($("#emailstatusConfirm").val() == 1) {
                                    var emailverfy = verifyForm.elements['verify_email'].value = $('#email').val();
                                    sessionStorage.setItem('oldemail',emailverfy);

                                }

                            }

                            verifyForm.elements['verify_country_code'].value =$('#mobile_code').val();
                            var numberverify= verifyForm.elements['verify_number'].value = $('#mobilenum').val().replace(/[\. ,:-]+/g, '');
                            sessionStorage.setItem('oldenumber',numberverify);
                            verifyForm.elements['email_password'].value = $('#password').val();
                            $("#register").html("Register");
                            setTimeout(function(){
                                $('#alertMessage1').hide();
                            }, 3000);
                             setTimeout(function(){
                                $('#successMessage1').hide();
                            }, 3000);
                        }
                    },
                    error: function (data) {
                        $("#register").attr('disabled',false);
                        $("#register").html("Register");
                        $('html, body').animate({scrollTop:0}, 500);


                        var html = '<div class="alert alert-danger alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+data.responseJSON.message+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><br><ul>';
                        for (var key in data.responseJSON.errors)
                        {
                            html += '<li>' + data.responseJSON.errors[key][0] + '</li>'
                        }
                        html += '</ul></div>';

                        $('#error').show();
                        document.getElementById('error').innerHTML = html;
                        setInterval(function(){
                            $('#error').slideUp(3000);
                        }, 8000);
                    }
                });
            }
            else{
                return false;
            }
        };




        //get login tab1



        $( document ).ready(function() {
            var printitem= localStorage.getItem('successmessage');
            if(printitem != null){
                var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+printitem+'!</div>';
                $('#alertMessage2').html(result);
                localStorage.removeItem('successmessage');
                localStorage.clear();
            }

        });



    </script>



    <script>

        var data='{{json_encode($value)}}';
        var state=JSON.parse(data.replace(/&quot;/g,'"'));
        // console.log(state)
        $(document).ready(function () {
            var val = $("#country").val();
            getCountryAttr(val);
        });

        function getCountryAttr(val) {
            if(val!=""){
                getState(val);
                getCode(val);
            }
            else{
                $("#state-list").html('<option value="">Please select Country</option>').val('');
            }

//        getCurrency(val);

        }

        function getState(val) {
            $.ajax({
                type: "GET",
                url: "{{url('get-loginstate')}}/" + val,
                data: {'country_id':val,'_token':"{{csrf_token()}}"},//'country_id=' + val,
                success: function (data) {

                    $("#state-list").html('<option value="">Please select Country</option>').val('');


                    $("#state-list").html(data).val(state.id);
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
    <script type="text/javascript"
            src="//www.googleadservices.com/pagead/conversion_async.js">
    </script>
    <!-- Facebook Pixel Code -->
    <!-- <script>
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

    </script> -->

    <script type="text/javascript"
            src="//www.googleadservices.com/pagead/conversion_async.js">
    </script>
    <script>
        $(document).ready(function () {

            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

            //Initialize tooltips
            $('.nav-tabs > li a[title]').tooltip();
            $('.nav-tabs .active a[href="#step1"]').click(function(){
                $('.wizard-inner').css('display','none');
            })
            //Wizard
            if(!$('.nav-tabs .active a[href="#step1"]')){
                $('.wizard-inner').css('display','block');
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

            $(".prev").click(function (e) {

                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                prevTab($active);
                $('.wizard-inner').css('display','block');
            });
        });

        function nextTab(elem) {

            $(elem).next().find('a[data-toggle="tab"]').click();
        }
        function prevTab(elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script type="text/javascript">
        var telInput = $('#mobilenum'),
            errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg"),
            addressDropdown = $("#country");
        var errorMap = [ "Invalid number", "Invalid country code", "Number Too short", "Number Too long", "Invalid number"];

        telInput.intlTelInput({
            geoIpLookup: function (callback) {
                $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            initialCountry: "auto",
            separateDialCode: true,
        });
        var reset = function() {
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
            validMsg.classList.add("hide");
        };

        $('.intl-tel-input').css('width', '100%');

        telInput.on('blur', function () {
            reset();
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    $('#mobilenum').css("border-color","");
                    $("#error-msg").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled',false);
                } else {
                    var errorCode = telInput.intlTelInput("getValidationError");
                    errorMsg.innerHTML = errorMap[errorCode];
                    $('#mobile_codecheck').html("");

                    $('#mobilenum').css("border-color","red");
                    $('#error-msg').css({"color":"red","margin-top":"5px"});
                    errorMsg.classList.remove("hide");
                    $('#register').attr('disabled',true);
                }
            }
        });
        $('input').on('focus', function () {
            $(this).parent().removeClass('has-error');
        });
        addressDropdown.change(function() {
            telInput.intlTelInput("setCountry", $(this).val());
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    $('#mobilenum').css("border-color","");
                    $("#error-msg").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled',false);
                } else {
                    var errorCode = telInput.intlTelInput("getValidationError");
                    errorMsg.innerHTML = errorMap[errorCode];
                    $('#mobile_codecheck').html("");

                    $('#mobilenum').css("border-color","red");
                    $('#error-msg').css({"color":"red","margin-top":"5px"});
                    errorMsg.classList.remove("hide");
                    $('#register').attr('disabled',true);
                }
            }
        });

        $('form').on('submit', function (e) {
            $('input[name=country_code]').attr('value', $('.selected-dial-code').text());
        });

    </script>
    <script>
        var tel = $('.phone'),
            country = $('#country').val();
        addressDropdown = $("#country");
        errorMsg1 = document.querySelector("#error-msg1"),
            validMsg1 = document.querySelector("#valid-msg1");
        var errorMap = [ "Invalid number", "Invalid country code", "Number Too short", "Number Too long", "Invalid number"];
        tel.intlTelInput({
            // allowDropdown: false,
            // autoHideDialCode: false,
            // autoPlaceholder: "off",
            // dropdownContainer: "body",
            // excludeCountries: ["us"],
            // formatOnDisplay: false,
            geoIpLookup: function(callback) {
                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    resp.country= country;
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            // hiddenInput: "full_number",
            initialCountry: "auto",
            // nationalMode: false,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            separateDialCode: true,

            utilsScript: "{{asset('js/intl/js/utils.js')}}"
        });
        var reset = function() {
            errorMsg1.innerHTML = "";
            errorMsg1.classList.add("hide");
            validMsg1.classList.add("hide");
        };

        addressDropdown.change(function() {
            tel.intlTelInput("setCountry", $(this).val());
        });

        tel.on('blur', function () {
            reset();
            if ($.trim(tel.val())) {
                if (tel.intlTelInput("isValidNumber")) {
                    $('.phone').css("border-color","");
                    validMsg1.classList.remove("hide");
                    $('#sendOtp').attr('disabled',false);
                } else {
                    var errorCode = tel.intlTelInput("getValidationError");
                    errorMsg1.innerHTML = errorMap[errorCode];
                    $('#conmobile').html("");

                    $('.phone').css("border-color","red");
                    $('#error-msg1').css({"color":"red","margin-top":"5px"});
                    errorMsg1.classList.remove("hide");
                    $('#sendOtp').attr('disabled',true);
                }
            }
        });


    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=308328899511239&ev=PageView
&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
@stop