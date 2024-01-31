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
$state = \DB::table('states_subdivisions')->where('state_subdivision_code',$set->state)->value('state_subdivision_name');
$country = \DB::table('countries')->where('country_code_char2',$set->country)->value('country_name');

?>
<div id="successMessage"></div>
<div id="errorMessage"></div>

        <div class="container">

            <div class="row py-4">

                <div class="col-lg-6">

                    <p class="mb-4">Feel free to ask for details, don't save any questions!</p>

                     <form id="contactForm" method="post">


                        <div class="row">

                            <div class="form-group col-lg-6">

                                <label class="form-label mb-1 text-2">Name <span class="text-color-danger">*</span></label>

                                <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control text-3 h-auto py-2" name="conName" id="conName" required>
                            </div>

                            <div class="form-group col-lg-6">

                                <label class="form-label mb-1 text-2">E-mail Address <span class="text-color-danger">*</span></label>

                                <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control text-3 h-auto py-2" name="email" id="email" required>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col">

                                <label class="form-label mb-1 text-2">Mobile <span class="text-color-danger">*</span></label>

                                {!! Form::hidden('mobile',null,['id'=>'mobile_code_hiddenco','name'=>'country_code']) !!}
                                <input class="form-control input-lg" id="mobilenumcon" name="Mobile" type="tel" required>
                                {!! Form::hidden('mobile_code',null,['class'=>'form-control text-3 h-auto py-2','disabled','id'=>'mobile_codecon']) !!}
                                <span id="valid-msgcon" class="hide"></span>
                                <span id="error-msgcon" class="hide"></span>
                                <span id="mobile_codecheckcon"></span>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col">

                                <label class="form-label mb-1 text-2">Message <span class="text-color-danger">*</span></label>

                                <textarea maxlength="5000" data-msg-required="Please enter your message." rows="8" class="form-control text-3 h-auto py-2" name="conmessage" id="conmessage" required></textarea>
                            </div>
                        </div>
                         <!-- Honeypot fields (hidden) -->
                                <div style="display: none;">
                                    <label>Leave this field empty</label>
                                    <input type="text" name="conatcthoneypot_field" value="">
                                </div>
                                
                                 @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                                 {!! NoCaptcha::display(['id' => 'g-recaptcha-1', 'data-callback' => 'onRecaptcha']) !!}
                                <input type="hidden" id="congg-recaptcha-response-1" name="congg-recaptcha-response-1">
                                <div class="robot-verification" id="captcha"></div>
                                <span id="captchacheck"></span>
                                @endif
                                <br>

                        <div class="row">

                            <div class="form-group col">

                                <button type="submit" class="btn btn-dark btn-modern text-3" data-loading-text="Loading..." id="contactSubmit">Send Message</button>
                            </div>
                        </div>
                    </form>
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
        ///////////////////////////////////////////////////////////////////////////////
        ///Google Recaptcha
        function recaptchaCallback() {
            document.querySelectorAll('.g-recaptcha-1').forEach(function (el) {
                grecaptcha.render(el);
            });
        }
        ///////////////////////////////////////////////////////////////////////////////////
    </script>
        <script>
      var recaptchaValid = false;
        function onRecaptcha(response) {
        if (response === '') {
            recaptchaValid = false; 
        } else {
            recaptchaValid = true; 
            $('#congg-recaptcha-response-1').val(response);
        }
        }
    
         function validateRecaptcha() {
                 var recaptchaResponse = $('#congg-recaptcha-response-1').val();

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
            separateDialCode: true,
            utilsScript: "{{asset('js/intl/js/utils.js')}}"
        });
        var reset = function() {
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
            validMsg.classList.add("hide");
        };

        $('.intl-tel-input').css('width', '100%');

        telInput.on('input blur', function () {
            reset();
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    $('#mobilenumcon').css("border-color","");
                    $("#error-msgcon").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled',false);
                } else {
                    errorMsg.classList.remove("hide");
                    errorMsg.innerHTML = "Please enter a valid number";
                    $('#mobilenumcon').css("border-color","red");
                    $('#error-msgcon').css({"color":"red","margin-top":"5px"});
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
                    errorMsg.classList.remove("hide");
                    errorMsg.innerHTML = "Please enter a valid number";
                    $('#mobilenumcon').css("border-color","red");
                    $('#error-msgcon').css({"color":"red","margin-top":"5px"});
                    $('#register').attr('disabled',true);
                }
            }
        });

               $('form').on('submit', function (e) {
                var selectedCountry = demotelInput.intlTelInput('getSelectedCountryData');
                var countryCode = '+' + selectedCountry.dialCode;
                $('#mobile_code_hiddenco').val(countryCode);
        
                });

</script>
@endif
<script>
$(document).ready(function() {
    $('#contactForm').submit(function(event) {
        event.preventDefault(); 


       var formData = {
            "conName": $('#conName').val(),
            "email": $('#email').val(),
            "country_code": $('#mobile_code_hiddenco').val().replace(/\s/g, ''),
            "Mobile": $('#mobilenumcon').val().replace(/[\. ,:-]+/g, ''),
            "conmessage": $('#conmessage').val(),
            "conatcthoneypot_field": $('input[name=conatcthoneypot_field]').val(),
            "congg-recaptcha-response-1": $('#congg-recaptcha-response-1').val(),
            "_token": "{{ csrf_token() }}"
        };
        
        var recaptchaEnabled = '{{ $status->recaptcha_status }}';
        if (recaptchaEnabled == 1) {
            if (!validateRecaptcha()) {
                $("#contactSubmit").attr('disabled', false);
                $("#contactSubmit").html("Send Message");
                return;
            }
        }
        $('#successMessage').empty();
        $('#errorMessage').empty();
        $("#contactSubmit").attr('disabled',true);
        $("#contactSubmit").html("<i class='fas fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
     
        $.ajax({
            type: 'POST',
            url: 'contact-us',
            data: formData,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#contactSubmit").attr('disabled',false);
                $("#contactSubmit").html("Send Message");
                $('#successMessage').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + response.message + '</div>');
                $('#contactForm')[0].reset();
                setTimeout(function() {
                    window.location.reload();
                }, 5000);

            },
            error: function(response) {
                $("#contactSubmit").attr('disabled', false);
                $("#contactSubmit").html("Send Message");
            
                var errorMessageHtml = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                
                if (response.responseJSON && response.responseJSON.errors) {
                    for (var key in response.responseJSON.errors) {
                        errorMessageHtml += '<li>' + response.responseJSON.errors[key][0] + '</li>';
                    }
            
                    errorMessageHtml += '</ul>';
                } else if (response.responseJSON && response.responseJSON.message) {
                    errorMessageHtml += response.responseJSON.message;
                }
            
                errorMessageHtml += '</div>';
            
                $('#errorMessage').html(errorMessageHtml);
            }


        });
        
    });
});
</script>
@stop