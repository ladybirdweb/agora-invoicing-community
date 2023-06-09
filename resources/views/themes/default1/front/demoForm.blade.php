@extends('themes.default1.layouts.front.master')
@section('title')
Book For Demo
@stop
@section('page-header')
Cart
@stop
@section('page-heading')
Schedule Your Demo
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">Request for demo</li>
@stop
@section('main-class') "main shop" @stop
@section('content')   
<style>
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }
    .sub-text {
    font-size: 1.25rem;
    line-height: 1.875rem;
    font-family: neue-haas-grotesk-text,Helvetica,sans-serif;
    font-weight: 400;
    font-style: normal;
    color: #3b3f47;
}
</style>
<?php 
$set = new \App\Model\Common\Setting();
$set = $set->findOrFail(1);
?>

<div class="row">

    <div class="col-md-6">

<h1 class="heading-primary" style="font-weight: bold;"><strong>Empower Your Support Teams with Faveo helpdesk: Book a Personalized Demo</strong></h1>
<p class="sub-text">Our team will provide a live demonstration of Faveo helpdesk, focusing on the features and functionalities that align with your specific needs and challenges. We'll showcase how Faveo helpdesk can streamline your support processes and enhance your agents' productivity.</p>
       <ul class="list list-icons list-icons-style-3 mt-4">
                                <li><i class="fa fa-check" aria-hidden="true"></i>
                               Fill out the Demo Request Form: Provide us with some basic details about your organization and your specific requirements. This information will help us tailor the demo to your needs effectively.</li>
                                <li><i class="fa fa-check" aria-hidden="true"></i>
                                 Schedule a Convenient Time: Choose a date and time that works best for you and your team. We offer flexible scheduling options to accommodate your availability.
                                <li><i class="fa fa-check" aria-hidden="true"></i>
                                Confirmation and Demo Details: Once you submit the request form, our team will review it and reach out to you to confirm the demo session. We'll provide you with all the necessary details, including the meeting link and instructions.</li>
  

    </div>
        <div class="col-md-6">

        {!! Form::open(['url'=>'postDemoReq']) !!}
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
                                                            {!! Form::hidden('mobile',null,['id'=>'mobile_code_hidden','name'=>'country_code']) !!}
                                                            <input class="form-control input-lg" id="mobilenum" name="Mobile" type="tel">
                                                            {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                                            <span id="valid-msg" class="hide"></span>
                                                            <span id="error-msg" class="hide"></span>
                                                            <span id="mobile_codecheck"></span>
                       
                    
                </div>
            </div>
           <div class="form-row">
               <div class="form-group col">
                    
                        <label class="required">Message</label>
                        <textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message" required></textarea>
                    </div>
                
            </div>
              <div class="form-row">
                <div class="form-group col">
                    <input type="submit" style="width: 100%;" value="Book For Demo" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
                </div>
            </div>
            <p style="text-align: center;font-size: 17px;"><a style="color: green;font-weight: bold !important;" href="{{url('login')}}">Or sign up for a 15-day free trial</a></p>
        {!! Form::close() !!}
    </div>

</div>
@stop
@section('script')
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
@stop