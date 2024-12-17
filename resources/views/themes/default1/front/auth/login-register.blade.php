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
    <div class="row">
        <div class="col-md-12">

            <section>
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

                                <div class="input-group">
                                    {!! Form::password('password1', ['class' => 'form-control form-control-lg text-4', 'id' => 'pass', 'style' => 'height: calc(1.5em + 0.75rem + 2px);']) !!}

                                    <div class="input-group-append">
                                        <span class="input-group-text" role="button" onclick="togglePasswordVisibility(this)">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
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

                                <label class="form-label text-color-dark text-3">E-mail Address <span class="text-color-danger">*</span></label>

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
                                <div class="input-group">
                                {!! Form::password('password',['class'=>'form-control form-control-lg text-4', 'id'=>'password']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text pointer" role="button" onclick="togglePasswordVisibility(this)">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="password1check"></span>
                            </div>

                            <div class="form-group col {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Re-enter Password <span class="text-color-danger">*</span></label>
                                <div class="input-group">
                               {!! Form::password('password_confirmation',['class'=>'form-control form-control-lg text-4', 'id'=>'confirm_pass']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text pointer" role="button" onclick="togglePasswordVisibility(this)">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="conpasscheck"></span>
                            </div>
                        </div>

                        <small class="text-sm text-muted" id="pswd_info" style="display: none;">
                            <span class="font-weight-bold">{{ \Lang::get('message.password_requirements') }}</span>
                            <ul class="pl-4">
                                @foreach (\Lang::get('message.password_requirements_list') as $requirement)
                                    <li id="{{ $requirement['id'] }}" class="text-danger">{{ $requirement['text'] }}</li>
                                @endforeach
                            </ul>
                        </small>

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

        $(document).ready(function () {
            // Cache the selectors for better performance
            var $pswdInfo = $('#pswd_info');
            var $newPassword = $('#password');
            var $length = $('#length');
            var $letter = $('#letter');
            var $capital = $('#capital');
            var $number = $('#number');
            var $special = $('#space');

            // Function to update validation classes
            function updateClass(condition, $element) {
                $element.toggleClass('text-success', condition).toggleClass('text-danger', !condition);
            }

            // Initially hide the password requirements
            $pswdInfo.hide();

            // Show/hide password requirements on focus/blur
            $newPassword.focus(function () {
                $pswdInfo.show();
            }).blur(function () {
                $pswdInfo.hide();
            });

            // Perform real-time validation on keyup
            $newPassword.on('keyup', function () {
                var pswd = $(this).val();

                // Validate the length (8 to 16 characters)
                updateClass(pswd.length >= 8 && pswd.length <= 16, $length);

                // Validate lowercase letter
                updateClass(/[a-z]/.test(pswd), $letter);

                // Validate uppercase letter
                updateClass(/[A-Z]/.test(pswd), $capital);

                // Validate number
                updateClass(/\d/.test(pswd), $number);

                // Validate special character
                updateClass(/[~*!@$#%_+.?:,{ }]/.test(pswd), $special);
            });
        });

    </script>




    <script type="text/javascript">
        {{--window.location.href = "{{url('/verify')}}";--}}

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Registration Form Validation

        function first_namecheck() {
            var firrstname_val = $('#first_name').val();
            if (firrstname_val.length == '') {
                $('#first_namecheck').show();
                $('#first_namecheck').html("Please Enter First Name");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color", "red");
                $('#first_namecheck').css("color", "red");
                // userErr =false;

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top - 200
                }, 1000)
                return false;
            }

            if (firrstname_val.length > 30) {
                $('#first_namecheck').show();
                $('#first_namecheck').html("Max 30 characters allowed ");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color", "red");
                $('#first_namecheck').css("color", "red");
                // userErr =false;

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top - 200
                }, 1000)
                return false;
            }

            var pattern = new RegExp(/[^a-zA-Z0-9]/);
            if (pattern.test(firrstname_val)) {
                $('#first_namecheck').show();
                $('#first_namecheck').html("Special characters not allowed");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color", "red");
                $('#first_namecheck').css("color", "red");

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top - 200
                }, 1000)
                return false;
            } else {
                $('#first_namecheck').hide();
                $('#first_name').css("border-color", "");
                return true;
            }
        }

        //Validating last name field
        function last_namecheck() {
            var lastname_val = $('#last_name').val();
            if (lastname_val.length == '') {
                $('#last_namecheck').show();
                $('#last_namecheck').html("Please Enter Last Name");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color", "red");
                $('#last_namecheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            }

            if (lastname_val.length > 30) {
                $('#last_namecheck').show();
                $('#last_namecheck').html("Maximum 30 characters allowed");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color", "red");
                $('#last_namecheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            }


            var pattern = new RegExp(/[^a-zA-Z0-9]/);
            if (pattern.test(lastname_val)) {
                $('#last_namecheck').show();
                $('#last_namecheck').html("Special characters not allowed");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color", "red");
                $('#last_namecheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            } else {
                $('#last_namecheck').hide();
                $('#last_name').css("border-color", "");
                return true;
            }
        }

        //Validating email field
        function emailcheck() {

            var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
            if (pattern.test($('#email').val())) {
                $('#emailcheck').hide();
                $('#email').css("border-color", "");
                return true;

            } else {
                $('#emailcheck').show();
                $('#emailcheck').html("Please Enter a valid email");
                $('#emailcheck').focus();
                $('#email').css("border-color", "red");
                $('#emailcheck').css({"color": "red", "margin-top": "5px"});
                // mail_error = false;
                $('html, body').animate({
                    scrollTop: $("#emailcheck").offset().top - 200
                }, 1000)
            }

        }

        function companycheck() {
            var company_val = $('#company').val();
            if (company_val.length == '') {
                $('#companycheck').show();
                $('#companycheck').html("Please Enter Company Name");
                $('#companycheck').focus();
                $('#company').css("border-color", "red");
                $('#companycheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#companycheck").offset().top - 200
                }, 1000)
            } else {
                $('#companycheck').hide();
                $('#company').css("border-color", "");
                return true;
            }
        }


        function addresscheck() {
            var address_val = $('#address').val();
            if (address_val.length == '') {
                $('#addresscheck').show();
                $('#addresscheck').html("Please Enter Address ");
                $('#addresscheck').focus();
                $('#address').css("border-color", "red");
                $('#addresscheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#addresscheck").offset().top - 200
                }, 1000)
            } else {
                $('#addresscheck').hide();
                $('#address').css("border-color", "");
                return true;
            }
        }


        function countrycheck() {
            var country_val = $('#country').val();
            if (country_val == '') {
                $('#countrycheck').show();
                $('#countrycheck').html("Please Select One Country ");
                $('#countrycheck').focus();
                $('#country').css("border-color", "red");
                $('#countrycheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#countrycheck").offset().top - 200
                }, 1000)
            } else {
                $('#countrycheck').hide();
                $('#country').css("border-color", "");
                return true;
            }
        }

        function mobile_codecheck() {
            var mobile_val = $('#mobilenum').val();
            if (mobile_val.length == '') {
                $('#mobile_codecheck').show();
                $('#mobile_codecheck').html("Please Enter Mobile No. ");
                $('#mobile_codecheck').focus();
                $('#mobilenum').css("border-color", "red");
                $('#mobile_codecheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#mobile_codecheck").offset().top - 200
                }, 1000)
            } else {
                $('#mobile_codecheck').hide();
                $('#mobilenum').css("border-color", "");
                return true;
            }
        }


        function towncheck() {
            var town_val = $('#city').val();
            if (town_val.length == '') {
                $('#towncheck').show();
                $('#towncheck').html("Please Enter Town ");
                $('#towncheck').focus();
                $('#city').css("border-color", "red");
                $('#towncheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#towncheck").offset().top - 200
                }, 1000)
            } else {
                $('#towncheck').hide();
                $('#city').css("border-color", "");
                return true;
            }
        }

        function statecheck() {
            var state_val = $('#state-list').val();
            if (state_val.length == '') {
                $('#statecheck').show();
                $('#statecheck').html("Please Select a State ");
                $('#statecheck').focus();
                $('#state-list').css("border-color", "red");
                $('#statecheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                $('html, body').animate({
                    scrollTop: $("#statecheck").offset().top - 200
                }, 1000)
            } else {
                $('#statecheck').hide();
                $('#state-list').css("border-color", "");
                return true;
            }
        }


        function password1check() {
            var pattern = new RegExp(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[~*!@$#%_+.?:,{ }])[A-Za-z\d~*!@$#%_+.?:,{ }]{8,16}$/);
            if (pattern.test($('#password').val())) {
                $('#password1check').hide();
                $('#password').css("border-color", "");
                return true;

            } else {
                $('#password1check').show();
                $('#password1check').html(@json(\Lang::get('message.strong_password')));
                $('#password1check').focus();
                $('#password').css("border-color", "red");
                $('#password1check').css({"color": "red", "margin-top": "0px"});

                // mail_error = false;
                return false;

            }

        }


        //    $('#conpassword').keyup(function(){
        //     con_password_check();
        // });

        function conpasscheck() {
            var confirmPassStore = $('#confirm_pass').val();
            var passwordStore = $('#password').val();
            if (confirmPassStore != passwordStore) {
                $('#conpasscheck').show();
                $('#conpasscheck').html("Passwords Don't Match");
                $('#conpasscheck').focus();
                $('#confirm_pass').css("border-color", "red");
                $('#conpasscheck').css("color", "red");
                $('html, body').animate({
                    scrollTop: $("#conpasscheck").offset().top - 200
                }, 1000)
            } else {
                $('#conpasscheck').hide();
                $('#confirm_pass').css("border-color", "");
                return true;
            }
        }

        function terms() {
            var term_val = $('#term').val();
            if (term_val == 'false') {
                $('#termscheck').show();
                $('#termscheck').html("Terms must be accepted");
                $('#termscheck').focus();
                $('#term').css("border-color", "red");
                $('#termscheck').css({"color": "red", "margin-top": "5px"});
                // userErr =false;
                return false;
                ;
            } else {
                $('#termscheck').hide();
                $('#term').css("border-color", "");
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
        $(document).on('change', '#term', function () {
            if ($(this).val() == "false") {
                $(this).val("true");
            } else {
                $(this).val("false");
            }
        })

        //////////////////////////////Google Analytics Code after Submit button is clicked//////////////////
        function gtag_report_conversion(tag) {
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

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


            if (first_namecheck() && last_namecheck() && emailcheck() && companycheck() && addresscheck() && mobile_codecheck() && countrycheck() && password1check() && conpasscheck() && terms() &&
                validateRecaptcha()) {


                var tag = "<?php echo $analyticsTag; ?>";
                if (tag !== "") {
                    gtag_report_conversion(tag);
                }

                $("#register").attr('disabled', true);
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
                        "mobile_code": $('#mobile_code').val().replace(/\s/g, ''),
                        "mobile": $('#mobilenum').val().replace(/[\. ,:-]+/g, ''),
                        "address": $('#address').val(),
                        "city": $('#city').val(),
                        "state": $('#state-list').val(),
                        "zip": $('#zip').val(),
                        "user_name": $('#user_name').val(),
                        "password": $('#password').val(),
                        "password_confirmation": $('#confirm_pass').val(),
                        "g-recaptcha-response-1": $('#g-recaptcha-response-1').val(),
                        "terms": $('#term').val(),

                        "_token": "{!! csrf_token() !!}",
                    },
                    success: function (response) {

                        // Re-enable the register button
                        $("#register").attr('disabled', false);

                        // Prepare success alert
                        var result = `
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>
                <i class="far fa-thumbs-up"></i> Thank You!
            </strong> ${response.message} !!
        </div>
    `;

                        $('#alertMessage1').html(result);
                        window.scrollTo(0,0);

                        if (response.data.need_verify === 1) {
                            setTimeout(function () {
                                window.location.href = "{{ url('/verify') }}";
                            }, 5000);
                        }
                    }
                    ,
                    error: function (data) {
                        $("#register").attr('disabled', false);
                        $("#register").html("Register");
                        $('html, body').animate({scrollTop: 0}, 500);
                        // Parse the error response
                        var response = data.responseJSON ? data.responseJSON : JSON.parse(data.responseText);

                        // Create the alert box
                        var html = '<div class="alert alert-danger alert-dismissable">' +
                            '<strong><i class="fas fa-exclamation-triangle"></i> Oh Snap! </strong>' +
                            response.message +
                            ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                            '<br><ul>';

                        // Loop through errors and display them
                        if (response.errors) {
                            for (var key in response.errors) {
                                if (response.errors.hasOwnProperty(key)) {
                                    html += '<li>' + response.errors[key][0] + '</li>';
                                }
                            }
                        }

                        // Pick the message or set a default message
                        var message = response.message ? response.message : 'An error occurred. Please try again.';

                        var html = '<div class="alert alert-danger alert-dismissable">' +
                            '<strong><i class="fas fa-exclamation-triangle"></i> Oh Snap! </strong>' + message +
                            ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><br>';

                        // If there are errors, loop through and add to the alert
                        // if (response.errors) {
                        //     html += '<ul>';
                        //     for (var key in response.errors) {
                        //         html += '<li>' + response.errors[key][0] + '</li>';
                        //     }
                        //     html += '</ul>';
                        // }

                        html += '</div>';

                        $('#error').show();
                        $('#error').html(html);

                        setTimeout(function () {
                            $('#error').slideUp(3000);
                        }, 8000);
                    }
                });
            } else {
                return false;
            }
        };


        //get login tab1


        $(document).ready(function () {
            var printitem = localStorage.getItem('successmessage');
            if (printitem != null) {
                var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>' + printitem + '!</div>';
                $('#alertMessage2').html(result);
                localStorage.removeItem('successmessage');
                localStorage.clear();
            }

        });


    </script>



    <script>

        var data = '{{json_encode($value)}}';
        var state = JSON.parse(data.replace(/&quot;/g, '"'));
        // console.log(state)
        $(document).ready(function () {
            var val = $("#country").val();
            getCountryAttr(val);
        });

        function getCountryAttr(val) {
            if (val != "") {
                getState(val);
                getCode(val);
            } else {
                $("#state-list").html('<option value="">Please select Country</option>').val('');
            }

//        getCurrency(val);

        }

        function getState(val) {
            $.ajax({
                type: "GET",
                url: "{{url('get-loginstate')}}/" + val,
                data: {'country_id': val, '_token': "{{csrf_token()}}"},//'country_id=' + val,
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
                data: {'country_id': val, '_token': "{{csrf_token()}}"},//'country_id=' + val,
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
                data: {'country_id': val, '_token': "{{csrf_token()}}"},//'country_id=' + val,
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
        goog_snippet_vars = function () {
            var w = window;
            w.google_conversion_id = 1027628032;
            w.google_conversion_label = "uBhoCLT3i3AQgLiB6gM";
            w.google_remarketing_only = false;
        }
        // DO NOT CHANGE THE CODE BELOW.
        goog_report_conversion = function (url) {
            goog_snippet_vars();
            window.google_conversion_format = "3";
            var opt = new Object();
            opt.onload_callback = function () {
                if (typeof (url) != 'undefined') {
                    window.location = url;
                }
            }
            var conv_handler = window['google_trackConversion'];
            if (typeof (conv_handler) == 'function') {
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
        goog_snippet_vars = function () {
            var w = window;
            w.google_conversion_id = 1027628032;
            w.google_conversion_label = "uBhoCLT3i3AQgLiB6gM";
            w.google_remarketing_only = false;
        }
        // DO NOT CHANGE THE CODE BELOW.
        goog_report_conversion = function (url) {
            goog_snippet_vars();
            window.google_conversion_format = "3";
            var opt = new Object();
            opt.onload_callback = function () {
                if (typeof (url) != 'undefined') {
                    window.location = url;
                }
            }
            var conv_handler = window['google_trackConversion'];
            if (typeof (conv_handler) == 'function') {
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

            $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});

            //Initialize tooltips
            $('.nav-tabs > li a[title]').tooltip();
            $('.nav-tabs .active a[href="#step1"]').click(function () {
                $('.wizard-inner').css('display', 'none');
            })
            //Wizard
            if (!$('.nav-tabs .active a[href="#step1"]')) {
                $('.wizard-inner').css('display', 'block');
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
                $('.wizard-inner').css('display', 'block');
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

        telInput.intlTelInput({
            geoIpLookup: function (callback) {
                $.get("https://ipinfo.io", function () {
                }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            initialCountry: "auto",
            separateDialCode: true,
        });
        var reset = function () {
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
            validMsg.classList.add("hide");
        };

        $('.intl-tel-input').css('width', '100%');

        telInput.on('input blur', function () {
            reset();
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    $('#mobilenum').css("border-color", "");
                    $("#error-msg").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled', false);
                } else {
                    errorMsg.classList.remove("hide");
                    errorMsg.innerHTML = "Please enter a valid number";

                    $('#mobilenum').css("border-color", "red");
                    $('#error-msg').css({"color": "red", "margin-top": "5px"});
                    $('#register').attr('disabled', true);
                }
            }
        });
        $('input').on('focus', function () {
            $(this).parent().removeClass('has-error');
        });
        addressDropdown.change(function () {
            telInput.intlTelInput("setCountry", $(this).val());
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    $('#mobilenum').css("border-color", "");
                    $("#error-msg").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled', false);
                } else {
                    errorMsg.classList.remove("hide");
                    errorMsg.innerHTML = "Please enter a valid number";
                    $('#mobilenum').css("border-color", "red");
                    $('#error-msg').css({"color": "red", "margin-top": "5px"});
                    $('#register').attr('disabled', true);
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
        tel.intlTelInput({
            // allowDropdown: false,
            // autoHideDialCode: false,
            // autoPlaceholder: "off",
            // dropdownContainer: "body",
            // excludeCountries: ["us"],
            // formatOnDisplay: false,
            geoIpLookup: function (callback) {
                $.get("https://ipinfo.io", function () {
                }, "jsonp").always(function (resp) {
                    resp.country = country;
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
        var reset = function () {
            errorMsg1.innerHTML = "";
            errorMsg1.classList.add("hide");
            validMsg1.classList.add("hide");
        };

        addressDropdown.change(function () {

            tel.intlTelInput("setCountry", $(this).val());
        });

        tel.on('input blur', function () {
            reset();
            if ($.trim(tel.val())) {
                if (tel.intlTelInput("isValidNumber")) {
                    $('.phone').css("border-color", "");
                    validMsg1.classList.remove("hide");
                    $('#sendOtp').attr('disabled', false);
                } else {

                    errorMsg1.classList.remove("hide");
                    errorMsg1.innerHTML = "Please enter a valid number";
                    $('.phone').css("border-color", "red");
                    $('#error-msg1').css({"color": "red", "margin-top": "5px"});
                    $('#sendOtp').attr('disabled', true);
                }
            }
        });


        tel.on("countrychange", function (e, countryData) {
            var countryCodeLog = countryData.dialCode;
            $("#verify_country_code").val(countryCodeLog);
        });

        $('.intl-tel-input').css('width', '100%');


    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=308328899511239&ev=PageView
&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
@stop