@extends('themes.default1.layouts.front.master')
@section('title')
Login | Register
@stop
@section('page-header')
Login | Register
@stop
@section('page-heading')
 <h1>Login <span>Sign in or register to use Faveo</span></h1>
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">Login</li>
@stop
@section('main-class') 
main
@stop
@section('content')
<?php
$country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($location['iso_code']);
$states = \App\Http\Controllers\Front\CartController::findStateByRegionId($location['iso_code']);
$states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
$state_code = $location['iso_code'] . "-" . $location['state'];
$state = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
$mobile_code = \App\Http\Controllers\Front\CartController::getMobileCodeByIso($location['iso_code']);


?>
<style>
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }

   
}

.wizard-inner
{
    display:none;
}
   
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



</style>
 <link rel="stylesheet" href="{{asset('client/css/selectpicker.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.js"></script>
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
                                @if(Session::has('success'))
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <strong><i class="far fa-thumbs-up"></i> Well done!</strong>
                                        {{Session::get('success')}}
                                </div>
                                @endif
                                <!-- fail message -->
                                @if(Session::has('fails'))
                                <div class="alert alert-danger alert-dismissable" role="alert">
                                    
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.
                                   <ul>
                                  <li>  {{Session::get('fails')}} </li>
                                </ul>
                                </div>
                                @endif
                                @if (count($errors) > 0)
                                   <div class="alert alert-danger alert-dismissable" role="alert">
                                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.

                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <div class="row">
                                   <div class="col-sm-6">
                                        <div class="featured-box featured-box-primary text-left mt-5">
                                            <div class="box-content">
                                             
                                                <h4 class="heading-primary text-uppercase mb-3">I'm a Returning Customer</h4>
                                                  @if ($captchaStatus==1 && $captchaSiteKey != '00' && $captchaSecretKey != '00')  
                                                {!!  Form::open(['action'=>'Auth\LoginController@postLogin', 'method'=>'post','id'=>'formoid','onsubmit'=>'return validateform()']) !!}
                                                @else
                                                {!!  Form::open(['action'=>'Auth\LoginController@postLogin', 'method'=>'post','id'=>'formoid']) !!}
                                                @endif
                                                 <div class="form-row">
                                                    <div class="form-group col {{ $errors->has('email1') ? 'has-error' : '' }}">
                                                       
                                                            <label class="required">Username or E-mail Address</label>
                                                              <div class="input-group">
                                                              {!! Form::text('email1',null,['class' => 'form-control input-lg','id'=>'username','autocomplete'=>"off" ]) !!}
                                                                <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                          </div>
                                                             
                                                             </div>
                                                             <h6 id="usercheck"></h6>

                                                        
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col {{ $errors->has('password1') ? 'has-error' : '' }}">
                                                           
                                                            <a class="pull-right" href="{{url('password/email')}}">({{Lang::get('message.forgot-my-password')}})</a>
                                                            <label class="required">Password</label>
                                                             <div class="input-group">
                                                            {!! Form::password('password1',['class' => 'form-control input-lg' ,'id'=>'pass']) !!}
                                                              <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                                                          </div>
                                                          
                                                        </div>
                                                        <h6 id="passcheck"></h6>
                                                            <!--<input type="password" value="" class="form-control input-lg">-->
                                                        
                                                    </div>
                                                </div>
                                                
                                                @if ($captchaStatus==1 && $captchaSiteKey != '00' && $captchaSecretKey != '00')  
                                                {!! NoCaptcha::renderJs() !!}
                                              {!! NoCaptcha::display() !!}
                                             <div class="loginrobot-verification"></div>
                                                @endif
                                                 <div class="form-row">
                                                    <div class="form-group col-lg-6">
                                                        <div class="form-check form-check-inline">
                                                       
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" id="rememberme" name="remember">Remember Me
                                                            </label>
                                                        
                                                    </div>
                                                    </div>
                                                     <div class="form-group col-lg-6">
                                                       <input type="submit" value="Login" id="submitbtn" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                                                      <!-- <button type="button" class="btn btn-primary mb-xl next-step float-right" name="sendOtp" id="login" onclick="loginUser()">
                                                                  Send Email
                                                      </button> -->
                                                       
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                         <div class="featured-box featured-box-primary text-left mt-5">
                                            <div class="box-content">
                                               <h4 class="heading-primary text-uppercase mb-3">Register An Account</h4>
                                               
                                                <form name="registerForm" id="regiser-form">
                                              
                                                <div class="row">
                                                   
                                                        <div class="form-group col-lg-6 {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                                          <!--   {!! Form::label('first_name',Lang::get('message.first_name'),['class'=>'required']) !!} -->
                                                          <label class="required">First Name</label>
                                                          
                                                            {!! Form::text('first_name',null,['class'=>'form-control input-lg', 'id'=>'first_name']) !!}
                                                            <span id="first_namecheck"></span>
                                                           </div>
                                                            

                                                        
                                                            <div class="form-group col-lg-6 {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                                                <label class="required">Last Name</label>
                                                                {!! Form::text('last_name',null,['class'=>'form-control input-lg', 'id'=>'last_name']) !!}
                                                             <span id="last_namecheck"></span>

                                                        </div>
                                                        
                                                    
                                                </div>
                                                 <div class="form-row">
                                                    
                                                        <div class="form-group col {{ $errors->has('email') ? 'has-error' : '' }}">
                                                            <label class="required">Email Address</label>
                                                            {!! Form::email('email',null,['class'=>'form-control input-lg', 'id'=>'email']) !!}
                                                            <span id="emailcheck"></span>
                                                        </div>

                                                      
                                                </div>
                                                

                                                <div class="row">
                                                   
                                                        <div class="form-group col-lg-6 {{ $errors->has('company') ? 'has-error' : '' }}">
                                                            <label  class="required">Company Name</label>
                                                            {!! Form::text('company',null,['class'=>'form-control input-lg', 'id'=>'company']) !!}
                                                             <span id="companycheck"></span>
                                                        </div>
                                                       

                                                        <div class="form-group col-lg-6 {{ $errors->has('bussiness') ? 'has-error' : '' }}">
                                                            <label class="required">Industry</label>
                                                            {!! Form::select('bussiness',[''=>'Choose','Industry'=>$bussinesses],null,['class'=>'form-control input-lg', 'id'=>'business']) !!}
                                                    
                                                            <span id="bussinesscheck"></span>
                                                        </div>
                                                        

                                                    
                                                </div>
                                                 
                                                <div class='row'>
                                                    <?php
                                                    $type = DB::table('company_types')->pluck('name', 'short')->toArray();;

                                                    $size = DB::table('company_sizes')->pluck('name', 'short')->toArray();;

                                                    ?>
                                                    <div class="col-md-6 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                                                        <!-- email -->
                                                        {!! Form::label('company_type','Company Type',['class'=>'required']) !!}
                                                        {!! Form::select('company_type',[''=>'Choose','Company Types'=>$type],null,['class' => 'form-control input-lg', 'id'=>'company_type']) !!}
                                                     <span id="company_typecheck"></span>
                                                    </div>
                                                    

                                                    <div class="col-md-6 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                                                        <!-- email -->
                                                        {!! Form::label('company_size','Company Size',['class'=>'required']) !!}
                                                        {!! Form::select('company_size',[''=>'Choose','Company Sizes'=>$size],null,['class' => 'form-control input-lg', 'id'=>'company_size']) !!}
                                                       <span id="company_sizecheck"></span>
                                                    </div>
                                                    

                                                </div>
                                                <div class="form-row">
                                                      <div class="form-group col {{ $errors->has('country') ? 'has-error' : '' }}">
                                                                {!! Form::label('country',Lang::get('message.country'),['class'=>'required']) !!}
                                                                <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>
                                                                {!! Form::select('country',[''=>'','Choose'=>$countries],$country,['class' => 'form-control input-lg selectpicker','data-live-search-style'=>"startsWith",'data-live-search'=>'true','data-live-search-placeholder'=>'Search','data-dropup-auto'=>'false','data-size'=>'10','onChange'=>'getCountryAttr(this.value);','id'=>'country']) !!}
                                                            <span id="countrycheck"></span>

                                                            </div>
                                                            
                                                 </div>
                                                <div class="form-row">
                                                    <div class="col-lg-12 form-group {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                                                        <label class="required">Mobile</label>
                                                        {!! Form::hidden('mobile',null,['id'=>'mobile_code_hidden']) !!}
                                                           <input class="form-control input-lg" id="mobilenum" name="mobile" type="tel">
                                                        {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                                        <span id="valid-msg" class="hide"></span>
                                                        <span id="error-msg" class="hide"></span>
                                                        <span id="mobile_codecheck"></span>
                                                    </div>
                                                     

                                                   
                                                </div>
                                                <div class="form-row">
                                                   
                                                        <div class="form-group col {{ $errors->has('address') ? 'has-error' : '' }}">
                                                            <label class="required">Address</label>
                                                            {!! Form::textarea('address',null,['class'=>'form-control','rows'=>4, 'id'=>'address']) !!}

                                                       <span id="addresscheck"></span>
                                                    </div>
                                                     

                                                </div>
                                                  <div class="form-row">
                                                    
                                                        <div class="form-group col-lg-6 {{ $errors->has('town') ? 'has-error' : '' }}">
                                                            <label>City/Town</label>
                                                            {!! Form::text('town',$location['city'],['class'=>'form-control input-lg', 'id'=>'city']) !!}
                                                             <span id="towncheck"></span>
                                                        </div>
                                                       

                                                        
                                                            <div class="form-group col-lg-6 {{ $errors->has('state') ? 'has-error' : '' }}">
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
                                                <div class="form-row">
                                                    
                                                        
                                                            <div class="form-group col-lg-6 {{ $errors->has('zip') ? 'has-error' : '' }}">
                                                                <label class="required">Zip/Postal Code</label>
                                                                {!! Form::text('zip',$location['zip'],['class'=>'form-control input-lg', 'id'=>'zip']) !!}
                                                                 <span id="zipcheck"></span>
                                                            </div>
                                                            


                                                            <div class="form-group col-md-6 {{ $errors->has('user_name') ? 'has-error' : '' }}">
                                                                <label class="required">User Name/E-mail Id</label>
                                                                {!! Form::text('user_name',null,['class'=>'form-control input-lg', 'id'=>'user_name']) !!}
                                                                 <span id="user_namecheck"></span>
                                                            </div>
                                                            

                                                       
                                                    
                                                </div>
                                                <div class="form-row">
                                                   
                                              <div class="form-group col-lg-6 {{ $errors->has('password') ? 'has-error' : '' }}">
                                                            <label class="required">Password</label>
                                                            {!! Form::password('password',['class'=>'form-control input-lg', 'id'=>'password']) !!}
                                                            <span id="password1check"></span>
                                                        </div>
                                                         

                                                        <div class="form-group col-lg-6 {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                                            <label class="required">Re-enter Password</label>

                                                            {!! Form::password('password_confirmation',['class'=>'form-control input-lg', 'id'=>'confirm_pass']) !!}
                                                             <span id="conpasscheck"></span>
                                                        </div>
                                                        

                                                   
                                                </div>

                                              <!--   <input type="checkbox" name="checkbox" id="option" value="{{old('option')}}"><label for="option"><span></span> <p>I agree to the <a href="#">terms</a></p></label>
 -->                                            
                                                 @if ($captchaStatus==1 && $captchaSiteKey != '00' && $captchaSecretKey != '00')  
                                                 {!! NoCaptcha::display() !!}
                                           <div class="robot-verification" id="captcha"></div>
                                           <span id="captchacheck"></span>
                                                @endif
                                               <div class="form-row">
                                                @if ($termsStatus ==0)
                                                 <div class="form-group col-lg-6">
                                                <input type="hidden" value="true" name="terms" id="term">
                                                  </div>
                                                    @else
                                                    <div class="form-group col-lg-6">
                                                        <label>

                                                            <input type="checkbox" value="false" name="terms" id="term" > {{Lang::get('message.i-agree-to-the')}} <a href="{{$termsUrl}}" target="_blank">{{Lang::get('message.terms')}}</a>
                                                        </label>
                                                        <span id="termscheck"></span>
                                                    </div>
                                                 @endif
                                                  
                                                          <div class="form-group col-lg-6">
                                                              <button type="button"  class="btn btn-primary pull-right marginright mb-xl next-step"  name="register" id="register" onclick="registerUser()">Submit</button>
                                                          </div>
                                                   
                                                </div>

                                                <div class="form-row">
                                                   <div class="form-group col-lg-6">
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
                    <div class="col-lg-6 offset-lg-3">
                    <div id="successMessage1"></div>
                        <div id = "emailsuccess"></div>
                     <!-- <div id="successMessage2"></div> -->

                    <div id="error1">
                    </div>
                    <div class="featured-box featured-box-primary text-left mt-5">
                        <div class="box-content">
                          
                     <form class="form-horizontal" novalidate="novalidate" name="verifyForm">

                                <h4 class="heading-primary text-uppercase mb-md">Confirm Email/Mobile</h4>
                                           
                                <input type="hidden" name="user_id" id="user_id"/>
                                <input type="hidden" name="email_password" id="email_password"/>
                                <input type="hidden" id="checkEmailStatus" value="{{$emailStatus}}">
                             @if($emailStatus == 1)
                              <p>You will be sent a verification email by an automated system, Please click on the verification link in the email. Click next to continue</p>
                              <div class="form-row">
                                  <div class="form-group col">
                                        <label  for="mobile" class="required">Email</label>
                                      <div class="input-group">
                                         <input type="hidden" id="emailstatusConfirm" value="{{$emailStatus}}">  
                                         <input type="email" value="" name="verify_email" id="verify_email" class="form-control form-control input-lg">
                                         <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                         </div>
                                      
                                    </div>
                                      <span id="conemail"></span>
                                  </div>
                                     
                              </div>
                              @endif

                                                     
                                            
                            @if($mobileStatus == 1)   
                             <p>You will be sent an OTP on your mobile immediately by an automated system, Please enter the OTP in the next step. Click next to continue</p>
                              <div class="form-row">
                                   <div class="form-group col">
                                     <input id="mobile_code_hidden" name="mobile_code" type="hidden">
                                      <input class="form-control form-control input-lg"  id="verify_country_code" name="verify_country_code" type="hidden">
                                    <label for="mobile" class="required">Mobile</label><br/>
                                     <input type="hidden" id="mobstatusConfirm" value="{{$mobileStatus}}">  
                                    <input class="form-control input-lg phone"  name="verify_number" type="text" id="verify_number">
                                    <span id="valid-msg1" class="hide"></span>
                                    <span id="error-msg1" class="hide"></span>
                                
                                    <span id="conmobile"></span>
                              </div>
                                 
                              </div>
                              @endif

                                <div class="form-row">
                                     <div class="form-group col">
                                           
                                        <button type="button" class="btn btn-primary mb-xl next-step float-right" name="sendOtp" id="sendOtp" onclick="sendOTP()">
                                                 Next
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
                <div class="col-lg-6 offset-lg-3">
                    <div id="error2">
                    </div>
                    <div id="successMessage2"></div>

                    <div id="alertMessage3"></div>
                    
                   <div class="featured-box featured-box-primary text-left mt-5">
                    <input type="hidden" id="checkOtpStatus" value="{{$mobileStatus}}">
                        <div class="box-content" id="showOtpBox">
                            <h4 class="heading-primary text-uppercase mb-md">OTP Confirmation</h4>
                            <!-- <div class="row verify">
                                <div class="col-md-12">
                                    <label>
                                        <span>Verification email sent on your email and OTP on mobile</span>
                                    </label>
                                </div>
                            </div> -->
                            <form name="verify_otp_form">
                              <label for="mobile" class="required">Enter OTP</label><br/>
                                <div class="row ">
                                    <div class="col-md-6">
                                        
                                        <input type="hidden" name="user_id" id="hidden_user_id"/>
                                        <input class="form-control input-lg"  id="oneTimePassword" name="oneTimePassword" type="text" >
                                         <span id="enterotp"></span>
                                    </div>


                                    <div class="col-md-3">
                                            <button type="button" class="btn btn-primary float-right mb-5" name="verifyOtp" id="verifyOtp" value="Verify OTP" onclick="verifyBySendOtp()" >
                                                        Verify OTP
                                            </button>
                                    </div>


                                    <div class="col-md-3">
                                       <button type="button" class="btn btn-danger float-right mb-5" name="resendOTP" id="resendOTP">
                                          Resend OTP
                                        </button>
                                  

                                    </div>  
                                                          
                                </div>

                                <div class="row">
                                
                                  <div class="col-sm-6 col-md-3 col-lg-6">
                                      <p>Did not receive OTP via SMS?</p>
                                   <button type="button" class="btn btn-secondary" name="voiceOTP" id="voiceOTP" value="Verify OTP" style= "margin-top:-15px;"><i class="fa fa-phone"></i>
                                                 Receive OTP via Voice call
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
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@stop 
@section('script')
 <script async src="https://www.googletagmanager.com/gtag/js?id=AW-1027628032"></script>
<script>

  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-1027628032');
</script>


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

<script type="text/javascript">


      
        
    //Login Form Jquery validation
 $(document).ready(function(){
   $('#usercheck').hide();
   $('#passcheck').hide();

   var userErr = true;
   var passErr = true;

  $('#formoid').submit(function(){
       function username_check(){
    var user_val = $('#username').val();
    if(user_val.length == ''){
        $('#usercheck').show();
        $('#usercheck').html("Please Enter Username/Email");
        $('#usercheck').focus();
        $('#username').css("border-color","red");
       $('#usercheck').css({"color":"red","margin-top":"5px"});

          // userErr =false;
        // return false;
    }
    else if((user_val.length < 3) || (user_val.length > 50))  {
        $('#usercheck').show();
        $('#usercheck').html("Username Length must be between 3 to 20 characters");
        $('#usercheck').focus();
          $('#username').css("border-color","red");
       $('#usercheck').css({"color":"red","margin-top":"5px"});

        // userErr =false;
        // return false;
    }
    else{
         $('#usercheck').hide();
          $('#username').css("border-color","");
         return true;
    }
   }
   

    function password_check(){
        var passStore= $('#pass').val()
    if(passStore.length == ''){
        $('#passcheck').show();
        $('#passcheck').html("Please Enter Password");
        $('#passcheck').focus();
        $('#pass').css("border-color","red");
       $('#passcheck').css({"color":"red","margin-top":"5px"});
        passErr =false;
        return false;
    }
    else if((passStore.length < 3) || (passStore.length > 20)){
        $('#passcheck').show();
        $('#passcheck').html("Password Length must be between 3 and 10");
        $('#passcheck').focus();

        $('#pass').css("border-color","red");
       $('#passcheck').css({"color":"red","margin-top":"5px"});
        passErr =false;
        return false;
    }
    else{
         $('#passcheck').hide();
          $('#pass').css("border-color","");
         return true;
    }
    }
    username_check();
    password_check();
  if(username_check() && password_check()){
        return true;
     }
     else{
        return false;
     }
  });

 });


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
        $("#verifyOtp").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
        var data = {
            "mobile":   $('#verify_number').val().replace(/[\. ,:-]+/g, ''),
            "code"  :   $('#verify_country_code').val(),
            "otp"   :   $('#oneTimePassword').val(),
            'id'    :   $('#hidden_user_id').val(),
        };
        $.ajax({
            url: '{{url('otp/verify')}}',
            type: 'GET',
            data: data,
            success: function (response) {
               $("#verifyOtp").attr('disabled',false);
                $('#error2').hide();
                 $('#error').hide(); 
                $('#alertMessage2').show();
                var result =  '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!.</div>';
                 // $('#alertMessage3').show();
                 $('#successMessage2').hide();
                $('#success').html(result);
                $("#verifyOtp").html("Verify OTP");
                  $('.nav-tabs li a[href="#step1"]').tab('show');
                  $('.wizard-inner').css('display','none');
                setTimeout(()=>{
                        getLoginTab();
                },10)
            },
            error: function (ex) {
               $("#verifyOtp").attr('disabled',false);
                var myJSON = JSON.parse(ex.responseText);
                var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                $("#verifyOtp").html("Verify OTP");
                for (var key in myJSON)
                {
                    html += '<li>' + myJSON[key][0] + '</li>'
                }
                html += '</ul></div>';
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
          type: 'GET',
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

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   $(document).ready(function(){
   $('#usercheck').hide();
   $('#passcheck').hide();

   var userErr = true;
   var passErr = true;

  $('#formoid').submit(function(){
       function username_check(){
    var user_val = $('#username').val();
    if(user_val.length == ''){
        $('#usercheck').show();
        $('#usercheck').html("Please Enter Username/Email");
        $('#usercheck').focus();
        $('#username').css("border-color","red");
       $('#usercheck').css({"color":"red","margin-top":"5px"});

          // userErr =false;
        // return false;
    }
    else if((user_val.length < 3) || (user_val.length > 50))  {
        $('#usercheck').show();
        $('#usercheck').html("Username Length must be between 3 to 20 characters");
        $('#usercheck').focus();
          $('#username').css("border-color","red");
       $('#usercheck').css({"color":"red","margin-top":"5px"});

        // userErr =false;
        // return false;
    }
    else{
         $('#usercheck').hide();
          $('#username').css("border-color","");
         return true;
    }
   }
   

    function password_check(){
        var passStore= $('#pass').val()
    if(passStore.length == ''){
        $('#passcheck').show();
        $('#passcheck').html("Please Enter Password");
        $('#passcheck').focus();
        $('#pass').css("border-color","red");
       $('#passcheck').css({"color":"red","margin-top":"5px"});
        passErr =false;
        return false;
    }
    else if((passStore.length < 3) || (passStore.length > 20)){
        $('#passcheck').show();
        $('#passcheck').html("Password Length must be between 3 and 10");
        $('#passcheck').focus();

        $('#pass').css("border-color","red");
       $('#passcheck').css({"color":"red","margin-top":"5px"});
        passErr =false;
        return false;
    }
    else{
         $('#passcheck').hide();
          $('#pass').css("border-color","");
         return true;
    }
    }
    username_check();
    password_check();
  if(username_check() && password_check()){
        return true;
     }
     else{
        return false;
     }
  });

 });

   
   //robot validation for Login Form
   function validateform() {
    var input = $(".g-recaptcha :input[name='g-recaptcha-response']"); 
    console.log(input.val());
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
        // return false;
          $('html, body').animate({
        scrollTop: $("#first_namecheck").offset().top -200
    }, 1000)
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

   function bussinesscheck(){
    var business_val = $('#business').val();
    if(business_val== ''){
        $('#bussinesscheck').show();
        $('#bussinesscheck').html("Please Select One Industry");
        $('#bussinesscheck').focus();
         $('#business').css("border-color","red");
        $('#bussinesscheck').css({"color":"red","margin-top":"5px"});
        // userErr =false;
         $('html, body').animate({
        scrollTop: $("#companycheck").offset().top - 200
    }, 1000)
    }
   
    else{
         $('#bussinesscheck').hide();
         $('#business').css("border-color","");
         return true;
    }
   }

   function company_typecheck(){
    var companytype_val = $('#company_type').val();
    if(companytype_val == ''){
        $('#company_typecheck').show();
        $('#company_typecheck').html("Please Select Company Type");
        $('#company_typecheck').focus();
          $('#company_type').css("border-color","red");
        $('#company_typecheck').css({"color":"red","margin-top":"5px"});
        // userErr =false;
        $('html, body').animate({
        scrollTop: $("#company_typecheck").offset().top - 200
    }, 1000)
    }
   
    else{
         $('#company_typecheck').hide();
          $('#company_type').css("border-color","");
         return true;
    }
   }

   function company_sizecheck(){
    var companysize_val = $('#company_size').val();
    if(companysize_val == ''){
        $('#company_sizecheck').show();
        $('#company_sizecheck').html("Please Select Company Size");
        $('#company_sizecheck').focus();
        $('#company_size').css("border-color","red");
        $('#company_sizecheck').css({"color":"red","margin-top":"5px"});
        // userErr =false;
          $('html, body').animate({
        scrollTop: $("#company_sizecheck").offset().top - 200
    }, 1000)
    }
   
    else{
         $('#company_sizecheck').hide();
         $('#company_size').css("border-color","");
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

   function zipcheck(){
    var zip_val = $('#zip').val();
    if(zip_val.length == ''){
        $('#zipcheck').show();
        $('#zipcheck').html("Please Enter Zip Code ");
        $('#zipcheck').focus();
        $('#zip').css("border-color","red");
        $('#zipcheck').css({"color":"red","margin-top":"5px"});
        // userErr =false;
           $('html, body').animate({
        scrollTop: $("#zipcheck").offset().top -200
    }, 1000)
    }
   
    else{
         $('#zipcheck').hide();
          $('#zip').css("border-color","");
         return true;
    }
   }
   function user_namecheck(){
    var username_val = $('#user_name').val();
    if(username_val.length == ''){
        $('#user_namecheck').show();
        $('#user_namecheck').html("Please Enter Username ");
        $('#user_namecheck').focus();
        $('#user_name').css("border-color","red");
        $('#user_namecheck').css({"color":"red","margin-top":"5px"});
        // userErr =false;
       
          $('html, body').animate({
        scrollTop: $("#user_namecheck").offset().top -200
    }, 1000)
     
    }
   
    else{
         $('#user_namecheck').hide();
         $('#user_name').css("border-color","");
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
                $('#password1check').html("Password must contain Uppercase/Lowercase/Special Character and Number");
                 $('#password1check').focus();
                $('#password').css("border-color","red");
                $('#password1check').css({"color":"red","margin-top":"5px"});

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

    function gcaptcha(){
    var captcha_val = $('#g-recaptcha-response-1').val();
    if(captcha_val == ''){
        $('#captchacheck').show();
        $('#captchacheck').html("Robot Verification Failed, please try again");
        $('#captchacheck').focus();
        $('#captcha').css("border-color","red");
        $('#captchacheck').css({"color":"red","margin-top":"5px"});
        // userErr =false;
           return false;;
    }
   
    else{
         $('#captchacheck').hide();
          $('#captcha').css("border-color","");
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
 function gtag_report_conversion(url) {
  console.log(typeof(url));
  var callback = function () {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  };
  gtag('event', 'conversion', {
      'send_to': 'AW-1027628032/ZftSCMqHw5YBEIC4geoD',
      'event_callback': callback
  });
  return false;
}
 ////////////////////////////////////////////////////////////////////////////////////////////////////
function registerUser() {

 $('#first_namecheck').hide();
   $('#last_namecheck').hide();
    $('#emailcheck').hide();
     $('#companycheck').hide();
      $('#bussinesscheck').hide();
       $('#company_typecheck').hide();
        $('#company_sizecheck').hide();
         $('#countrycheck').hide();
         $('#mobile_codecheck').hide();
          $('#addresscheck').hide();
           $('#towncheck').hide();
            $('#statecheck').hide();
             $('#zipcheck').hide();
              $('#user_namecheck').hide();
               $('#password1check').hide();
                $('#conpasscheck').hide();
                 $('#termscheck').hide();


         var first_nameErr = true;
         var last_nameErr = true;
         var emailErr = true;
         var companyeErr = true;
         var bussinessErr = true;
         var company_typeErr = true;
         var company_sizeErr = true;
         var countryErr = true;
          var mobile_codeErr = true;
          var addressErr = true;
          var townErr = true;
          var stateErr = true;
          var zipErr = true;
          var user_nameErr = true;
          var password1Err = true;
          var conPassErr = true;
           var termsErr = true;
     // con_password_check();

if(first_namecheck() && last_namecheck() && emailcheck() && companycheck()  && mobile_codecheck() && addresscheck() && towncheck()  && zipcheck() && bussinesscheck() && company_typecheck() && company_sizecheck() && countrycheck() && user_namecheck() && password1check() && conpasscheck()  && terms() && gcaptcha()) 
     {
        // gtag_report_conversion();
     $("#register").attr('disabled',true);
     $("#register").html("<i class='fas fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
        $.ajax({
          url: '{{url("auth/register")}}',
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
                /*setTimeout(function(){ 
                    $('#alertMessage1').hide(); 
                }, 3000);*/
            }
          },
          error: function (data) {
            $("#register").attr('disabled',false);
            location.reload();
            $("#register").html("Register");
            $('html, body').animate({scrollTop:0}, 500);
           

                var html = '<div class="alert alert-success alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+data.responseJSON.message+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><br><ul>';
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
             console.log(val)
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
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
