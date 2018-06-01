@extends('themes.default1.layouts.front.master')
@section('title')
Login | Register | Faveo Helpdesk
@stop
@section('page-header')
Login | Register
@stop
@section('page-heading')
 <h1>Login <span>Sign in or register to use Faveo</span></h1>
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

// if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
//     {
//       $ip=$_SERVER['HTTP_CLIENT_IP'];
//     }
//     elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
//     {
//       $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
//     }
//     else
//     {
//       $ip=$_SERVER['REMOTE_ADDR'];
//     }

//   if($ip!='::1')
//    {$location = json_decode(file_get_contents('http://ip-api.com/json/'.$ip),true);}
//    else
//     {$location = json_decode(file_get_contents('http://ip-api.com/json'),true);}

$country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($location['countryCode']);
$states = \App\Http\Controllers\Front\CartController::findStateByRegionId($location['countryCode']);
$states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
$state_code = $location['countryCode'] . "-" . $location['region'];
$state = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
$mobile_code = \App\Http\Controllers\Front\CartController::getMobileCodeByIso($location['countryCode']);


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
</style>


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
                                                {!!  Form::open(['action'=>'Auth\LoginController@postLogin', 'method'=>'post','id'=>'formoid']) !!}
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
                                                 <div class="form-row">
                                                    <div class="form-group col-lg-6">
                                                        <div class="form-check form-check-inline">
                                                       
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" id="rememberme" name="remember">Remember Me
                                                            </label>
                                                        
                                                    </div>
                                                    </div>
                                                     <div class="form-group col-lg-6">
                                                        <input type="submit" id="submitbtn" value="Login" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
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
                                                <form name="registerForm" id="regiser-form" >
                                                <div class="row">
                                                   
                                                        <div class="form-group col-lg-6 {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                                          <!--   {!! Form::label('first_name',Lang::get('message.first_name'),['class'=>'required']) !!} -->
                                                          <label class="required">First Name</label>
                                                          
                                                            {!! Form::text('first_name',null,['class'=>'form-control input-lg', 'id'=>'first_name']) !!}
                                                            <h6 id="first_namecheck"></h6>
                                                           </div>
                                                            

                                                        
                                                            <div class="form-group col-lg-6 {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                                                <label class="required">Last Name</label>
                                                                {!! Form::text('last_name',null,['class'=>'form-control input-lg', 'id'=>'last_name']) !!}
                                                            
                                                        </div>
                                                         <h6 id="last_namecheck"></h6>

                                                    
                                                </div>
                                                 <div class="form-row">
                                                    
                                                        <div class="form-group col {{ $errors->has('email') ? 'has-error' : '' }}">
                                                            <label class="required">Email Address</label>
                                                            {!! Form::email('email',null,['class'=>'form-control input-lg', 'id'=>'email']) !!}
                                                        </div>

                                                      <h6 id="emailcheck"></h6>
                                                </div>
                                                

                                                <div class="row">
                                                   
                                                        <div class="form-group col-lg-6 {{ $errors->has('company') ? 'has-error' : '' }}">
                                                            <label  class="required">Company Name</label>
                                                            {!! Form::text('company',null,['class'=>'form-control input-lg', 'id'=>'company']) !!}
                                                        </div>
                                                        <h6 id="companycheck"></h6>

                                                        <div class="form-group col-lg-6 {{ $errors->has('bussiness') ? 'has-error' : '' }}">
                                                            <label class="required">Industry</label>
                                                            {!! Form::select('bussiness',[''=>'Select','Industries'=>$bussinesses],null,['class'=>'form-control input-lg', 'id'=>'business']) !!}
                                                        </div>
                                                        <h6 id="bussinesscheck"></h6>

                                                    
                                                </div>
                                                 
                                                <div class='row'>
                                                    <?php
                                                    $type = DB::table('company_types')->pluck('name', 'short')->toArray();;

                                                    $size = DB::table('company_sizes')->pluck('name', 'short')->toArray();;

                                                    ?>
                                                    <div class="col-md-6 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                                                        <!-- email -->
                                                        {!! Form::label('company_type','Company Type',['class'=>'required']) !!}
                                                        {!! Form::select('company_type',[''=>'Select','Company Types'=>$type],null,['class' => 'form-control input-lg', 'id'=>'company_type']) !!}

                                                    </div>
                                                     <h6 id="company_typecheck"></h6>

                                                    <div class="col-md-6 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                                                        <!-- email -->
                                                        {!! Form::label('company_size','Company Size',['class'=>'required']) !!}
                                                        {!! Form::select('company_size',[''=>'Select','Company Sizes'=>$size],null,['class' => 'form-control input-lg', 'id'=>'company_size']) !!}

                                                    </div>
                                                     <h6 id="company_sizecheck"></h6>

                                                </div>
                                                <div class="form-row">
                                                      <div class="form-group col {{ $errors->has('country') ? 'has-error' : '' }}">
                                                                {!! Form::label('country',Lang::get('message.country'),['class'=>'required']) !!}
                                                                <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>
                                                                {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],$country,['class' => 'form-control input-lg','onChange'=>'getCountryAttr(this.value);','id'=>'country']) !!}

                                                            </div>
                                                             <h6 id="countrycheck"></h6>

                                                 </div>
                                                <div class="form-row">
                                                    <div class="col-lg-12 form-group {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                                                        <label class="required">Mobile</label>
                                                        {!! Form::hidden('mobile',null,['id'=>'mobile_code_hidden']) !!}
                                                           <input class="form-control input-lg" id="mobilenum" name="mobile" type="tel">
                                                        {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                                    </div>
                                                     <h6 id="mobile_codecheck"></h6>

                                                   
                                                </div>
                                                <div class="form-row">
                                                   
                                                        <div class="form-group col {{ $errors->has('address') ? 'has-error' : '' }}">
                                                            <label class="required">Address</label>
                                                            {!! Form::textarea('address',null,['class'=>'form-control','rows'=>4, 'id'=>'address']) !!}

                                                      
                                                    </div>
                                                     <h6 id="addresscheck"></h6>

                                                </div>
                                                  <div class="form-row">
                                                    
                                                        <div class="form-group col-lg-6 {{ $errors->has('town') ? 'has-error' : '' }}">
                                                            <label>City/Town</label>
                                                            {!! Form::text('town',$location['city'],['class'=>'form-control input-lg', 'id'=>'city']) !!}
                                                        </div>
                                                        <h6 id="towncheck"></h6>

                                                        
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
                                                                // dd($value);
                                                                ?>
                                                                {!! Form::select('state',[$states],$value,['class' => 'form-control input-lg','id'=>'state-list']) !!}

                                                            </div>
                                                             <h6 id="statecheck"></h6>

                                                        
                                                   
                                                </div>
                                                <div class="form-row">
                                                    
                                                        
                                                            <div class="form-group col-lg-6 {{ $errors->has('zip') ? 'has-error' : '' }}">
                                                                <label class="required">Zip/Postal Code</label>
                                                                {!! Form::text('zip',$location['zip'],['class'=>'form-control input-lg', 'id'=>'zip']) !!}
                                                            </div>
                                                             <h6 id="zipcheck"></h6>


                                                            <div class="form-group col-md-6 {{ $errors->has('user_name') ? 'has-error' : '' }}">
                                                                <label class="required">User Name/E-mail Id</label>
                                                                {!! Form::text('user_name',null,['class'=>'form-control input-lg', 'id'=>'user_name']) !!}
                                                            </div>
                                                             <h6 id="user_namecheck"></h6>

                                                       
                                                    
                                                </div>
                                                <div class="form-row">
                                                   
                                              <div class="form-group col-lg-6 {{ $errors->has('password') ? 'has-error' : '' }}">
                                                            <label class="required">Password</label>
                                                            {!! Form::password('password',['class'=>'form-control input-lg', 'id'=>'password']) !!}
                                                        </div>
                                                         <h6 id="passwordcheck"></h6>

                                                        <div class="form-group col-lg-6 {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                                            <label class="required">Re-enter Password</label>

                                                            {!! Form::password('password_confirmation',['class'=>'form-control input-lg', 'id'=>'confirm_pass']) !!}
                                                        </div>
                                                         <h6 id="conpasscheck"></h6>

                                                   
                                                </div>

                                               <div class="form-row">
                                                    <div class="form-group col-lg-6">
                                                        <label>
                                                            <input type="checkbox" name="terms" id="terms" > {{Lang::get('message.i-agree-to-the')}} <a href="http://www.faveohelpdesk.com/terms-conditions" target="_blank">{{Lang::get('message.terms')}}</a>
                                                        </label>
                                                    </div>
                                                    <div class="form-row">
                                                          <div class="form-group col-lg-6">
                                                              <button type="button"  class="btn btn-primary pull-right marginright mb-xl next-step" data-loading-text="Loading..." name="register" id="register" onclick="registerUser()" style="margin-right:-230px;margin-top:4px;">Submit</button>
                                                          </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                  
                                                <div class="pull-left obligatory-fields required" >
                                                     </div>&nbsp;
                                                  Required fields
                                              </div>
                                                <div class="form-row">
                                                    <div class="form-group col-lg-6 ">
                                                        
                                                               <!--  <div class="pull-right marginright">
                    <button type="submit" class="btn btn-medium btn-custom ">Submit</button>
                </div> -->
                                                      
                                                       <!--  <button type="button" class="btn btn-primary mb-xl next-step" name="register" id="register" onclick="registerUser()">Register
                                                        </button> -->
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
                     <!-- <div id="successMessage2"></div> -->

                    <div id="error1">
                    </div>
                   <div class="featured-box featured-box-primary text-left mt-5">
                        <div class="box-content">
                          
                            <form class="form-horizontal" novalidate="novalidate" name="verifyForm">
                                <h4 class="heading-primary text-uppercase mb-md">Confirm Email and Mobile</h4>
                                            <p>You will be sent a verification email and OTP on your mobile immediately by an automated system, Please click on the verification link in the email and also enter the OTP in the next step. Click next to continue</p>
                                <input type="hidden" name="user_id" id="user_id"/>
                                <input type="hidden" name="email_password" id="email_password"/>
                                <div class="form-row">
                                                        <div class="form-group col">
                                                            <label>Email</label>
                                                             <div class="input-group">

                                                  


                                                            <input type="email" value="" name="verify_email" id="verify_email" class="form-control form-control input-lg">
                                                             <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                          </div>
                                                        </div>
                                                        </div>
                                                         <h6 id="conemail"></h6>
                                                    </div>


                                                     
                                            

                                                     <div class="form-row">
                                                        <div class="form-group col">
                                                        <input id="mobile_code_hidden" name="mobile_code" type="hidden">
                                                         <input class="form-control form-control input-lg"  id="verify_country_code" name="verify_country_code" type="hidden">
                                                          <label for="mobile" class="required">Mobile</label><br/>
                                                            
                                                       <input class="form-control input-lg phone"  name="verify_number" type="text" id="verify_number">
                                                        
                                                      
  
                                                      </div>
                                                       <h6 id="conmobile"></h6>
                                                  </div>

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
                        <div class="box-content">
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
                                <div class="row verify">
                                    <div class="form-group col-lg-6">
                                        
                                        <input type="hidden" name="user_id" id="hidden_user_id"/>
                                        <input class="form-control input-lg"  id="oneTimePassword" name="oneTimePassword" type="text" >
                                    </div>


                                        
                                              <div class="form-group col-lg-2">
                                               <button type="button" class="btn btn-primary float-right mb-5" name="verifyOtp" id="verifyOtp" value="Verify OTP" onclick="verifyBySendOtp()" style="margin-right:-22px;">
                                                        Verify OTP
                                                 </button>
                                                 </div>
                                                     <div class="form-group col-lg-2">
                                                        <a  class="btn btn-danger float-right mb-5" name="resendOTP" onclick="resendOTP()" id="resendOTP" ng-click="resendOTP()" style="margin-right:-55px; background: grey; color:white;">Resend OTP</a>
                                                         </div>  
                                                       
                                                            
                                                       


                                   
                                </div>
                             
                            </form>
                           <!--  <div class="row">
                                <div class="col-md-6">
                                    <h5>Didn't recieve OTP via SMS</h5>
                                    <button type="button" class="btn btn-default mb-xl" data-loading-text="Loading..." name="resendOTP" id="resendOTP" onclick="resendOTP()" style="background: grey; color: white;" ><i class="fa fa-phone" style="font-size: 18px;"></i>&nbsp;&nbsp; Get OTP via Voice </button>
                                </div>
                            </div> -->
                           <!--   <div class="row">
                                <div class="col-md-6">
                                    <input type="button" value="Login" class="btn btn-default mb-xl prev-step" data-loading-text="Loading..." style="background: grey; color:white;">
                                    <input type="button" value="Back To Verification" class="btn btn-default mb-xl prev" data-loading-text="Loading..." style="background: grey; color:white;" >
                                    <input type="submit" value="Register" class="btn btn-primary mb-xl" data-loading-text="Loading..." onclick='goog_report_conversion()'>

                                </div>
                            </div> -->
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
<script type="text/javascript">
    //register Validdation
  

  


  

</script>

<script type="text/javascript">
    //Login Form Jquery validation
 $(document).ready(function(){
   $('#usercheck').hide();
   $('#passcheck').hide();

   var userErr = true;
   var passErr = true;
   // var conPassErr = true;

   // $('#username').keyup(function(){
   //     username_check();
   // });



   //  $('#pass').keyup(function(){
   //     password_check();
   // });

   

  //   $('#conpassword').keyup(function(){
  //      con_password_check();
  //  });

  //    function con_password_check(){
  //       var conPassStore= $('#conpassword').val();
  //        var passStore = $('#pass').val();
  //        if(conPassStore != passStore)
         
  //       $('#confirm_pass').show();
  //       $('#confirm_pass').html("**password Don;t Match");
  //       $('#confirm_pass').focus();
  //       $('#confirm_pass').css("color","red");
  //       conPassErr =false;
  //       return false;
    
  //   else{
  //        $('#passcheck').hide();
  //   }

  // }
  $('#formoid').submit(function(){
       function username_check(){
    var user_val = $('#username').val();
    if(user_val.length == ''){
        $('#usercheck').show();
        $('#usercheck').html("**Please Enter Username/Email");
        $('#usercheck').focus();
        $('#usercheck').css("color","red");
        // userErr =false;
        // return false;
    }
    else if((user_val.length < 3) || (user_val.length > 10))  {
        $('#usercheck').show();
        $('#usercheck').html("**Username Length must be between 3 to 20 characters");
        $('#usercheck').focus();
        $('#usercheck').css("color","red");
        // userErr =false;
        // return false;
    }
    else{
         $('#usercheck').hide();
         return true;
    }
   }
   

    function password_check(){
        var passStore= $('#pass').val()
    if(passStore.length == ''){
        $('#passcheck').show();
        $('#passcheck').html("**Please Enter Password");
        $('#passcheck').focus();
        $('#passcheck').css("color","red");
        passErr =false;
        return false;
    }
    else if((passStore.length < 3) || (passStore.length > 10)){
        $('#passcheck').show();
        $('#passcheck').html("**Password Length must be between 3 and 10");
        $('#passcheck').focus();
        $('#passcheck').css("color","red");
        passErr =false;
        return false;
    }
    else{
         $('#passcheck').hide();
         return true;
    }
    }
    // userErr = true;
    // passErr = true;
   // var conPassErr = true;

    username_check();
    password_check();
     // con_password_check();
console.log(username_check(),password_check())
     if(username_check() && password_check()){
        return true;
     }
     else{
        return false;
     }
  });

 });






 function first_namecheck(){
    var firrstname_val = $('#first_name').val();
    if(firrstname_val.length == ''){
        $('#first_namecheck').show();
        $('#first_namecheck').html("**Please Enter First Name");
        $('#first_namecheck').focus();
        $('#first_namecheck').css("color","red");
        // userErr =false;
        // return false;
          $('html, body').animate({
        scrollTop: $("#first_namecheck").offset().top
    }, 1000)
    }
   
    else{
         $('#first_namecheck').hide();
         return true;
    }
   }
    //Validating last name field
    function last_namecheck(){
    var lastname_val = $('#last_name').val();
    if(lastname_val.length == ''){
        $('#last_namecheck').show();
        $('#last_namecheck').html("**Please Enter Last Name");
        $('#last_namecheck').focus();
        $('#last_namecheck').css("color","red");
        // userErr =false;
        return false;
    }
   
    else{
         $('#last_namecheck').hide();
         return true;
    }
   }
    //Validating email field
    function emailcheck(){
    var email_val = $('#email').val();
    if(email_val.length == ''){
        $('#emailcheck').show();
        $('#emailcheck').html("**Please Enter Email");
        $('#emailcheck').focus();
        $('#emailcheck').css("color","red");
        // userErr =false;
        return false;
    }
   
    else{
         $('#emailcheck').hide();
         return true;
    }
   }

    function companycheck(){
    var company_val = $('#company').val();
    if(company_val.length == ''){
        $('#companycheck').show();
        $('#companycheck').html("**Please Enter Company");
        $('#companycheck').focus();
        $('#companycheck').css("color","red");
        // userErr =false;
        return false;
    }
   
    else{
         $('#companycheck').hide();
         return true;
    }
   }

   // function bussinesscheck(){
   //  var business_val = $('#business').val();
   //  if(business_val.length == ''){
   //      $('#bussinesscheck').show();
   //      $('#bussinesscheck').html("**Please Select One Industry");
   //      $('#bussinesscheck').focus();
   //      $('#bussinesscheck').css("color","red");
   //      // userErr =false;
   //      return false;
   //  }
   
   //  else{
   //       $('#bussinesscheck').hide();
   //       return true;
   //  }
   // }

   // function company_typecheck(){
   //  var companytype_val = $('#company_type').val();
   //  if(business_val.length == ''){
   //      $('#company_typecheck').show();
   //      $('#company_typecheck').html("**Please Select Company Type");
   //      $('#company_typecheck').focus();
   //      $('#company_typecheck').css("color","red");
   //      // userErr =false;
   //      return false;
   //  }
   
   //  else{
   //       $('#company_typecheck').hide();
   //       return true;
   //  }
   // }

   // function company_sizecheck(){
   //  var companysize_val = $('#company_size').val();
   //  if(business_val.length == ''){
   //      $('#company_sizecheck').show();
   //      $('#company_sizecheck').html("**Please Select company Size ");
   //      $('#company_sizecheck').focus();
   //      $('#company_sizecheck').css("color","red");
   //      // userErr =false;
   //       return false;
   //  }
   
   //  else{
   //       $('#company_sizecheck').hide();
   //       return true;
   //  }
   // }
   
   // function countrycheck(){
   //  var country_val = $('#country').val();
   //  if(business_val.length == ''){
   //      $('#countrycheck').show();
   //      $('#countrycheck').html("**Please Select One Country ");
   //      $('#countrycheck').focus();
   //      $('#countrycheck').css("color","red");
   //      // userErr =false;
   //       return false;
   //  }
   //  else{
   //       $('#countrycheck').hide();
   //       return true;
   //  }
   // }

    function mobile_codecheck(){
    var mobile_val = $('#mobilenum').val();
    if(mobile_val.length == ''){
        $('#mobile_codecheck').show();
        $('#mobile_codecheck').html("**Please Select One Country ");
        $('#mobile_codecheck').focus();
        $('#mobile_codecheck').css("color","red");
        // userErr =false;
         return false;
    }
    else{
         $('#mobile_codecheck').hide();
         return true;
    }
   }
    
    function addresscheck(){
    var address_val = $('#address').val();
    if(address_val.length == ''){
        $('#addresscheck').show();
        $('#addresscheck').html("**Please Enter Address ");
        $('#addresscheck').focus();
        $('#addresscheck').css("color","red");
        // userErr =false;
        return false;
    }
    else{
         $('#addresscheck').hide();
         return true;
    }
   }

    function towncheck(){
    var town_val = $('#city').val();
    if(town_val.length == ''){
        $('#towncheck').show();
        $('#towncheck').html("**Please Enter Town ");
        $('#towncheck').focus();
        $('#towncheck').css("color","red");
        // userErr =false;
         return false;
    }
    else{
         $('#towncheck').hide();
         return true;
    }
   }

    function statecheck(){
    var state_val = $('#state-list').val();
    if(state_val.length == ''){
        $('#statecheck').show();
        $('#statecheck').html("**Please Enter Address ");
        $('#statecheck').focus();
        $('#statecheck').css("color","red");
        // userErr =false;
         return false;
    }
   
    else{
         $('#statecheck').hide();
         return true;
    }
   }

   function zipcheck(){
    var zip_val = $('#zip').val();
    if(zip_val.length == ''){
        $('#zipcheck').show();
        $('#zipcheck').html("**Please Enter Zip Code ");
        $('#zipcheck').focus();
        $('#zipcheck').css("color","red");
        // userErr =false;
         return false;
    }
   
    else{
         $('#statecheck').hide();
         return true;
    }
   }
   function user_namecheck(){
    var username_val = $('#user_name').val();
    if(username_val.length == ''){
        $('#user_namecheck').show();
        $('#user_namecheck').html("**Please Enter Userbname ");
        $('#user_namecheck').focus();
        $('#user_namecheck').css("color","red");
        // userErr =false;
       
            $('html, body').animate({
        scrollTop: $("#user_namecheck").offset().top
    }, 10000)
     
    }
   
    else{
         $('#user_namecheck').hide();
         return true;
    }
   }


   

    function password1check(){
        var passwordStore= $('#password').val()
    if(passwordStore.length == ''){
        $('#password1check').show();
        $('#password1check').html("**Please Enter Password");
        $('#password1check').focus();
        $('#password1check').css("color","red");
      
    }
    else if((passwordStore.length < 3) || (passwordStore.length > 10)){
        $('#password1check').show();
        $('#password1check').html("**Password Length must be between 3 and 10");
        $('#password1check').focus();
        $('#password1check').css("color","red");
       
    }
    else{
         $('#password1check').hide();
         return true;
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
            $('#conpasscheck').html("**password Don;t Match");
            $('#conpasscheck').focus();
            $('#conpasscheck').css("color","red");
            return false;
         }
        else{
             $('#conpasscheck').hide();
               return true;
        }
  }
 
function registerUser() {
     // $("#register").html("<i class='fas fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");

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

     // con_password_check();
if(first_namecheck() && last_namecheck() && emailcheck() && companycheck()  && mobile_codecheck() && addresscheck() && towncheck() && statecheck() && zipcheck() &&  user_namecheck() && password1check() && conpasscheck())
     {
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
                "mobile_code": $('#mobile_code').val(),
                "mobile": $('#mobilenum').val(),
                "address": $('#address').val(),
                "city": $('#city').val(),
                "state": $('#state-list').val(),
                "zip": $('#zip').val(),
                "user_name": $('#user_name').val(),
                "password": $('#password').val(),
                "password_confirmation": $('#confirm_pass').val(),
                "terms": $('#terms').val(),
                "_token": "{!! csrf_token() !!}",
          },
          success: function (response) {
            if(response.type == 'success'){
                var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Thank You! </strong>'+response.message+'!!</div>';
                $('#successMessage1').html(result);
                $('.wizard-inner').css('display','block');
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
                window.scrollTo(0, 10);
                verifyForm.elements['user_id'].value = response.user_id;
                var $emailverfy = verifyForm.elements['verify_email'].value = $('#email').val();
                sessionStorage.setItem('oldemail',$emailverfy);
                

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
            var data = JSON.parse(ex.responseText);
            // console.log(data.errors);
            $("#register").html("Register");
            $('html, body').animate({scrollTop:0}, 500);
            var res = "";

                function ash(idx, topic) {
                    // console.log(idx);
                   if(typeof topic==="object"){
                      $.each(topic, ash);
                   }
                   else{
                    
                     res += '<li>' + topic + '</li>';
                     
                   }
                };
                 $.each(data, ash);
                // console.log(res)
                $('#error').html('<div class="alert alert-danger"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+data.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+res+'</div>');
              
            $('#error').show();
            setTimeout(function(){ 
                $('#error').hide(); 
            }, 500000);
          }
        });
     }
    
       else{
        return false;
        
        
     }
    };
    

    function sendOTP() {

           $(document).ready(function(){
            $('#conemail').hide();
            $('#conmobile').hide();

            var mail_error = true;
            var mobile_error = true;

            $('#verify_email').keyup(function(){
              verify_email_check();
            });
            function verify_email_check(){

              var userEmail = $('#verify_email').val();
              if(userEmail.length == ''){
                $('#conemail').show();
                $('#conemail').html("**Please Enter Your Email");
                $('#conemail').focus();
                $('#conemail').css("color","red");
                mail_error = false;
                return false;
              }
              else{
                $('#conemail').show();
              }
            }
        });
            
        


        var oldemail=sessionStorage.getItem('oldemail');
        var newemail = $('#verify_email').val(); // this.value
       

            $.ajax({ 
                url: '{{url("change/email")}}',
                data: { newemail: newemail,oldemail:oldemail },
                type: 'get'
            });
  



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
           
            //  sessionStorage.removeItem(oldemail);
            // console.log(response)
                var result =  '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Almost there! </strong>'+response.message+'</div>';
                $('#successMessage2').html(result);
                $('#error1').hide();
                $('.wizard-inner').css('display','none');
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
                  setTimeout(function(){ 
               sessionStorage.removeItem(oldemail); 
            }, 500);
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
 
    //get login tab1
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
                var result =  '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!.</div>';
                 // $('#alertMessage3').show();
                 $('#successMessage2').hide();
                $('#success').html(result);
                $("#verifyOtp").html("Verify OTP");
                setTimeout(()=>{
                        getLoginTab();
                },0)
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
               
                 $('#successMessage2').hide ();
                  $('#alertMessage3').show();
                $('#error2').hide();
                var result =  '<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                $('#alertMessage3').html(result+ ".");
          },
          error: function (ex) {
                var myJSON = JSON.parse(ex.responseText);
                var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oh Snap! </strong>Something went wrong<br><br><ul>';
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
                console.log()
              $("#state-list").html(data).val(state.id);
            }
        });
    }

    // function getState(val) {


    //     $.ajax({
    //         type: "POST",
    //         url: "{{url('get-state')}}",
    //         data: {'country_id':val,'_token':"{{csrf_token()}}"},//'country_id=' + val,
    //         success: function (data) {
    //             $("#state-list").html(data);
    //         }
    //     });
    // }
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
<script src="{{asset("lb-faveo/js/intlTelInput.js")}}"></script>
<script type="text/javascript">
    var telInput = $('#mobilenum');
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
<script>
              $(".phone").intlTelInput({
        // allowDropdown: false,
        // autoHideDialCode: false,
        // autoPlaceholder: "off",
        // dropdownContainer: "body",
        // excludeCountries: ["us"],
        // formatOnDisplay: false,
        geoIpLookup: function(callback) {
          $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
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
        utilsScript: "js/intl/js/utils.js"
      });
        </script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=308328899511239&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
@stop
