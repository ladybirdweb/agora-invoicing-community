@extends('themes.default1.layouts.front.master')
@section('title')
Contact Us
@stop
@section('page-header')
Cart
@stop
@section('page-heading')
Contact us
@stop
@section('breadcrumb')
@if(Auth::check())
        <li><a class="text-primary" href="{{url('my-invoices')}}">Home</a></li>
@else
     <li><a class="text-primary" href="{{url('login')}}">Home</a></li>
@endif
 <li class="active text-dark">Contact us</li>
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
$address = preg_replace("/^\R+|\R+\z/", '', $set->address);
?>

        <div class="container">

            <div class="row py-4">

                <div class="col-lg-6">

                    <p class="mb-4">Feel free to ask for details, don't save any questions!</p>

                     @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                        {!! Form::open(['url'=>'contact-us','method' => 'post','onsubmit'=>'return Recaptcha()']) !!}
                        @else
                        {!! Form::open(['url'=>'contact-us','method' => 'post']) !!}
                        @endif


                        <div class="row">

                            <div class="form-group col-lg-6">

                                <label class="form-label mb-1 text-2">Your Name <span class="text-color-danger">*</span></label>

                                <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control text-3 h-auto py-2" name="name" id="name" required>
                            </div>

                            <div class="form-group col-lg-6">

                                <label class="form-label mb-1 text-2">Your E-mail Address <span class="text-color-danger">*</span></label>

                                <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control text-3 h-auto py-2" name="email" id="email" required>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col">

                                <label class="form-label mb-1 text-2">Mobile <span class="text-color-danger">*</span></label>

                                {!! Form::hidden('mobile',null,['id'=>'mobile_code_hiddenco','name'=>'country_code']) !!}
                                <input class="form-control input-lg" id="mobilenumcon" name="Mobile" type="tel">
                                {!! Form::hidden('mobile_code',null,['class'=>'form-control text-3 h-auto py-2','disabled','id'=>'mobile_code']) !!}
                                <span id="valid-msgcon" class="hide"></span>
                                <span id="error-msgcon" class="hide"></span>
                                <span id="mobile_codecheckcon"></span>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col">

                                <label class="form-label mb-1 text-2">Message <span class="text-color-danger">*</span></label>

                                <textarea maxlength="5000" data-msg-required="Please enter your message." rows="8" class="form-control text-3 h-auto py-2" name="message" id="message" required></textarea>
                            </div>
                        </div>
                                  @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display(['id' => 'Contactrecaptcha']) !!}
                                <div class="verification"></div><br>
                                @endif

                        <div class="row">

                            <div class="form-group col">

                                <button type="submit" class="btn btn-dark btn-modern text-3" data-loading-text="Loading...">Send Message</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>


                <div class="col-lg-6">

                    <div>

                        <h4 class="mt-2 mb-1"><strong>Our Office</strong></h4>

                        <ul class="list list-icons list-icons-style-2 mt-2">

                            <li><i class="fas fa-map-marker-alt top-6"></i> <strong class="text-dark">Address:</strong> {{ $address }}<br>{{ implode(', ', array_filter([$set->city, $state, $country, $set->zip])) }}</li>

                            <li><i class="fas fa-phone top-6"></i> <strong class="text-dark">Phone:</strong> +</b>{{$set->phone_code}} {{$set->phone}}</li>

                            <li><i class="fas fa-envelope top-6"></i> <strong class="text-dark">Email:</strong> <a href="mailto:{{$set->company_email}}">{{$set->company_email}}</a></li>
                        </ul>
                    </div>


                </div>
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