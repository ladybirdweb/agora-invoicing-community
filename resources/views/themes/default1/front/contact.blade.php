@extends('themes.default1.layouts.front.master')
@section('title')
Contact Us
@stop
@section('page-header')
Cart
@stop
@section('page-heading')
What can we help you with?
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">Contact Us</li>
@stop
@section('main-class') "main shop" @stop
@section('content')   
<style>
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }
</style>
<?php 
$set = new \App\Model\Common\Setting();
$set = $set->findOrFail(1);
?>

<div class="row">
    <div class="col-md-6">

       <h2 class="mb-3 mt-2"><strong>Contact</strong> Us</h2>
        @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                        {!! Form::open(['url'=>'contact-us','method' => 'post','onsubmit'=>'return Recaptcha()']) !!}
                        @else
                        {!! Form::open(['url'=>'contact-us','method' => 'post']) !!}
                        @endif
            <div class="form-row">
                <div class="form-group col-lg-6">
                  
                        <label class="required">Your name</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required>
                    </div>
                   <div class="form-group col-lg-6">
                        <label class="required">Your email address</label>
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email" required>
                    </div>
                </div>
           
            <div class="form-row">
                    <div class="form-group col">
                         <label class="required">Mobile No</label>
                                                            {!! Form::hidden('mobile',null,['id'=>'mobile_code_hiddenco','name'=>'country_code']) !!}
                                                            <input class="form-control input-lg" id="mobilenumcon" name="Mobile" type="tel">
                                                            {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                                            <span id="valid-msgcon" class="hide"></span>
                                                            <span id="error-msgcon" class="hide"></span>
                                                            <span id="mobile_codecheckcon"></span>
                       
                    
                </div>
            </div>
           <div class="form-row">
               <div class="form-group col">
                    
                        <label class="required">Message</label>
                        <textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message" required></textarea>
                    </div>
                
            </div>
            @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display(['id' => 'Contactrecaptcha']) !!}
            <div class="verification"></div>
            @endif
            <br />
            <div class="loginrobot-verification"></div>
              <div class="form-row">
                <div class="form-group col">
                    <input type="submit" value="Send Message" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
                </div>
            </div>
            
        
        {!! Form::close() !!}
    </div>
    <div class="col-md-6">

        <hr>
        <?php
        $state = \DB::table('states_subdivisions')->where('state_subdivision_code',$set->state)->value('state_subdivision_name');
        $country = \DB::table('countries')->where('country_code_char2',$set->country)->value('country_name');
        ?>

        <h4 class="heading-primary">Our Office</h4>
       <ul class="list list-icons list-icons-style-3 mt-4">
                                <li><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> {{$set->address}},{{$set->city}},{{$state}},{{$country}},{{$set->zip}}.</li>
                                <li><i class="fas fa-phone"></i> <strong>Phone: </strong><b>+</b>{{$set->phone_code}} {{$set->phone}}</li>
                                <li><i class="far fa-envelope"></i> <strong>Email:</strong> <a href="mailto:{{$set->company_email}}">{{$set->company_email}}</a></li>
                            </ul>
        <hr>

      <!--  <h4 class="heading-primary">Business <strong>Hours</strong></h4>
                            <ul class="list list-icons list-dark mt-4">
                                <li><i class="far fa-clock"></i> Monday to Friday 09:30AM to 06:30PM IST</li>
                                
                            </ul> -->

    </div>

</div>
@stop
@section('script')
     <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script>
      function Recaptcha() {
    var input = $("#Contactrecaptcha :input[name='g-recaptcha-response']");
            console.log(input.val());
            if(input.val() == null || input.val()==""){
                $('.verification').empty()
                $('.verification').append("<p style='color:red'>Robot verification failed, please try again.</p>")
                return false;
            }
            else{
                return true;
            }
}

    </script>
@if(request()->path() === 'contact-us')
<script type="text/javascript">
    
    
            var telInput = $('#mobilenumcon'),
            errorMsg = document.querySelector("#error-msgcon"),
            validMsg = document.querySelector("#valid-msgcon"),
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
            separateDialCode: false,
            utilsScript: "{{asset('js/intl/js/utils.js')}}"
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
                    $('#mobilenumcon').css("border-color","");
                    $("#error-msgcon").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled',false);
                } else {
                    var errorCode = telInput.intlTelInput("getValidationError");
                    errorMsg.innerHTML = errorMap[errorCode];
                    $('#mobile_codecheckcon').html("");

                    $('#mobilenumcon').css("border-color","red");
                    $('#error-msgcon').css({"color":"red","margin-top":"5px"});
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
                    $('#mobilenumcon').css("border-color","");
                    $("#error-msgcon").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled',false);
                } else {
                    var errorCode = telInput.intlTelInput("getValidationError");
                    errorMsg.innerHTML = errorMap[errorCode];
                    $('#mobile_codecheckcon').html("");

                    $('#mobilenumcon').css("border-color","red");
                    $('#error-msgcon').css({"color":"red","margin-top":"5px"});
                    errorMsg.classList.remove("hide");
                    $('#register').attr('disabled',true);
                }
            }
        });

        $('form').on('submit', function (e) {
            $('input[name=country_code]').attr('value', $('.selected-dial-code').text());
        });

</script>
@endif
@stop