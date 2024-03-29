@extends('themes.default1.layouts.front.master')
@section('title')
Email/Mobile Verification | Faveo Helpdesk
@stop
@section('page-heading')
Email/Mobile Verification
@stop
@section('page-header')
Reset Password
@stop
@section('breadcrumb')
    @if(Auth::check())
        <li><a class="text-primary" href="{{url('my-invoices')}}">Home</a></li>
    @else
         <li><a class="text-primary" href="{{url('login')}}">Home</a></li>
    @endif
     <li class="active text-dark">verify</li>
@stop 
@section('main-class')
main
@stop
@section('content')
<style>
    .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 .selected-flag {
    width: 84px;
    position: relative;
}
</style>
<?php $setting = \App\Model\Common\Setting::where('id', 1)->first(); ?>
<!DOCTYPE html>
<html ng-app="smsApp">

<body ng-controller="smsCtrl">
    <?php
    $domain = [];
    $set = new \App\Model\Common\Setting();
    $set = $set->findOrFail(1);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div id="alertMessage1"></div>
                <div id="alertMessage2"></div>
                <div id="error1">
                    <div id="email1"></div>
                </div>

                @if($user && $user->active != 1 && $user->mobile_verified == 1)
                <div id="snap1" class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Your Email is not Verified..!!
                    <ul>
                        <li>Click the button to resend Verification Email</li>
                    </ul>
                </div>
                @endif

                @if($user && $user->active == 1 && $user->mobile_verified != 1)
                <div id="snap1" class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Your Mobile is not Verified..!!
                    <ul>
                        <li>Click the button to resend OTP</li>
                    </ul>
                </div>
                @endif

                @if($user && $user->active == 0 && $user->mobile_verified == 0)
                <div id="snap1" class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Your Email And Mobile are not Verified..!!
                    <ul>
                        <li>You will be sent a verification email and OTP on your mobile immediately by an automated system, Please click on the verification link in the email and also enter the OTP in the next step. Click Next to continue</li>
                    </ul>
                </div>
                @endif

                <div class="featured-box featured-box-primary text-left mt-5">
                    <div class="box-content" style="border: none;">
                        @if($user)
                        @if ($user->active != 1 && $user->mobile_verified == 1)
                        <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                        <p class="text-2">You will be sent a verification email by an automated system, Please click on the verification link in the email. Click next to continue</p>

                        <label class="form-label text-color-dark text-3">E-mail Address <span class="text-color-danger">*</span></label>
                        <input type="text"  class="form-control form-control-lg text-4" name="email" value="{{$user -> email}}" id="u_email">
                        <input type="hidden" name="oldmail" value="{{$user -> email}}" id="oldmail">
                        <h6 id="mailcheck"></h6>
                        <div class="clear"></div>
                        <div class="form-group col">
                            <button class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" style="margin-top:15px ; margin-right: -15px;" id="sendEmail" ng-click="sendEmail()">Send Email</button>
                        </div>
                        @endif

                        @if($user->mobile_verified !=1 && $user->active == 1)
                      
                        <input type="hidden" name="user_id" value="{{$user->id}}" id="u_id">
                        <div class="form-group col-lg-12">
                            <div class="row" ng-hide="showOTP">
                                @if($user->mobile)
                                <input type="hidden" class="form-control" name="code" value="{{$user -> mobile_code}}" id="u_code">
                                <p class="text-2">You will be sent an OTP on your mobile immediately by an automated system, Please enter the OTP in the next step. Click next to continue</p>

                                 <label for="mobile" class="required">Mobile</label><br />
                                <input id="mobile_code_hiddenve" name="mobile_codeve" type="hidden">
                                <input class="form-control form-control input-lg" value="{{$user->country}}"  id="verify_country_codeve" name="verify_country_codeve" type="hidden">
                                  <input type="text" class="form-control form-control-lg text-4 phonecode"  name="mobile" value="{{$user->mobile}}" id="u_mobile" style="right: 12px;">
                                   <span id="valid-msgve" class="hide"></span>
                                <span id="error-msgve" class="hide"></span>

                                <span id="mobilecheck"></span>
                                 @else
                                 <input type="hidden" name="countryCode" id="u_code">
                                <p class="text-2">You will be sent an OTP on your mobile immediately by an automated system, Please enter the OTP in the next step. Click next to continue</p>

                                  <label for="mobile" class="required">Mobile</label><br />
                                <input id="mobile_code_hiddenve" name="mobile_codeve" type="hidden">
                                <input class="form-control form-control input-lg" value="{{$user->country}}"  id="verify_country_codeve" name="verify_country_codeve" type="hidden">
                                   <input type="text" class="form-control form-control-lg text-4 phonecode" name="mobile" id="u_mobile">
                                    <span id="valid-msgve" class="hide"></span>
                                <span id="error-msgve" class="hide"></span>

                                <span id="mobilecheck"></span>

                                @endif
                                <h6 id="mobcheck"></h6>
                                <div class="clear"></div>
                                <div class="form-group col"><button class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" id="sendOTP" ng-click="sendOTP()" data-loading-text="Loading..." style="margin-top:15px ; margin-right: -15px;">Send OTP</button></div>
                            </div>
                        </div>

                        <div ng-show="showOTP">
                            <label for="mobile" class="required">Enter OTP</label><br />
                            <div class="row">
                                    <input type="text" class="form-control form-control-lg text-4" id="otp" ng-model="otp">
                                    <h6 id="verifyotp"></h6>
                                <button class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" style="width: max-content;" id="verifyOTP" ng-click="submitOTP(otp)">Verify OTP</button>

                                
                            </div><br>
                            <div class="row">
                                
                                    <button type="button"class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3"  name="resendOTP" id="resendOTP">
                                        Resend OTP
                                    </button>
                            </div>

                            <div class="row">
                                    <p>Did not receive OTP via SMS?</p>
                                    <button type="button" class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" name="voiceOTP" id="voiceOTP" value="Verify OTP" style="margin-top:-15px;"><i class="fa fa-phone"></i>
                                        Receive OTP via Voice call
                                    </button>
                                </div>
                        </div>
                        @endif

                        @if($user->mobile_verified == 0 && $user->active == 0)
                        <div class="row">
                            <div class="form-group col-lg-12 email-mobile">
                                <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                                <p class="text-2">You will be sent a verification email by an automated system, Please click on the verification link in the email. Click next to continue</p>

                                <label class="form-label text-color-dark text-3">E-mail Address <span class="text-color-danger">*</span></label>

                                <input type="text" class="form-control form-control-lg text-4" name="email" value="{{$user -> email}}" id="u_email">
                                <input type="hidden" name="oldemail" value="{{$user -> email}}" id="oldemail">
                                <h6 id="emailcheck"></h6>
                                <div class="clear"></div>
                                <div class="form-group col">
                                </div>
                                <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                                <input type="hidden" class="form-control" name="code" value="{{$user->mobile_code}}" id="u_code">
                                <p class="text-2">You will be sent an OTP on your mobile immediately by an automated system, Please enter the OTP in the next step. Click next to continue</p>

                                <label class="form-label text-color-dark text-3">Mobile <span class="text-color-danger">*</span></label>
                                <input id="mobile_code_hiddenve" name="mobile_codeve" type="hidden">
                                <input class="form-control form-control input-lg" value="{{$user->country}}"  id="verify_country_codeve" name="verify_country_codeve" type="hidden">
                                <input type="text" class="form-control form-control-lg text-4 phonecode" name="mobile" value="{{$user-> mobile}}" id="u_mobile" type="tel">
                                <input type="hidden" name="oldemail" value="{{$user->mobile}}" id="oldnumber">
                                <span id="valid-msgve" class="hide"></span>
                                <span id="error-msgve" class="hide"></span>

                                <span id="mobilecheck"></span>
                                <div class="clear"></div>
                                <div class="form-group col"><button class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" id="sendOTPmail" ng-click="sendOTPmail()" data-loading-text="Loading..." style="margin-top:15px ; margin-right: -15px;">Send </button></div>
                            </div>
                        </div>
                        <div class="otp-field" style="display: none;">
                            <label class="form-label text-color-dark text-3">Enter OTP <span class="text-color-danger">*</span></label>

                            <div class="row">
                                    <input type="text" class="form-control input-lg" id="otp1" name="oneTimePassword" ng-model="otp">
                                    <h6 id="confirmotp"></h6>
                                    <button  class="btn btn-dark btn-modern w-100 text-uppercase text-3 mt-3" id="verifyOtp" style="width: max-content;" onclick="verifyBySendOtp()">Verify OTP</button>
                                
                            </div><br>
                            <div class="row">
                                    <button type="button" class="btn btn-dark btn-outline btn-modern w-100 text-uppercase font-weight-bold text-3 mt-2" name="resendOTP" style="width: max-content;" id="resendOTP">
                                        Resend OTP
                                    </button>
                            </div>
                            <div class="row">
                                    <p>Did not receive OTP via SMS?</p>
                                    <button type="button" class="btn btn-dark btn-outline btn-modern w-100 text-uppercase font-weight-bold text-3 mt-2" name="voiceOTP" id="voiceOTP" value="Verify OTP" style="margin-top:-15px;"><i class="fa fa-phone"></i>
                                        Receive OTP via Voice call
                                    </button>
                                </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    @stop
    @section('script')

    <script src="{{asset('client/js/angular.js')}}"></script>
    <script>
        var app = angular.module('smsApp', []);
        app.controller('smsCtrl', function($scope, $http) {

            $scope.sendOTP = function() {
                $('#mobcheck').hide();
                if (verify_mobnumber_check()) {
                    var newnumber = $('#u_mobile').val().replace(/[\. ,:-]+/g, '');
                    $("#sendOTP").attr('disabled', true);
                    $("#sendOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
                    $scope.newObj = {};
                    $scope.newObj['newnumber'] = newnumber;

                    $scope.newObj['id'] = $('#u_id').val();
                    $scope.newObj['code'] = $('#u_code').val();
                    $scope.newObj['mobile'] = $('#u_mobile').val().replace(/[\. ,:-]+/g, '');
                    $http({
                        url: '{{url("otp/send")}}',
                        method: "GET",
                        params: $scope.newObj
                    }).success(function(data) {
                        if (data.type == "success") {
                            $("#sendOTP").attr('disabled', false);
                            $scope.showOTP = true;
                            $scope.msg2 = true;
                            $("#sendOTP").html("Send OTP");
                            var result = '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i>Almost there! </strong>' + data.message + '!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                            $('#alertMessage1').html(result);
                            $('#snap1').hide();
                            $('#alertMessage1').css('color', 'green');
                        }
                    }).error(function(data) {
                        $("#sendOTP").attr('disabled', false);
                        $scope.msg2 = true;
                        var res = "";
                        $("#sendOTP").html("Send OTP");
                        $('#error1').css('color', 'red');
                        $.each(data.errors, function(idx, topic) {
                            res += '<div class="alert alert-danger alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>' + topic + '!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                        });
                        $('#error1').html(res);
                    })
                } else {
                    return false;
                }
            }
            $scope.resendOTP = function() {
                $scope.showOTP = false;
            }
                $scope.submitOTP = function(x) {
                    $('#verifyotp').hide();
                    if (verify_otpverify_check()) {
                        $("#verifyOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                        $scope.newObj['otp'] = x;
                        $http({
                            url: '{{url("otp/verify")}}',
                            method: "POST",
                            params: $scope.newObj
                        }).success(function(data) {
                            window.location.href = 'login';
                            // $scope.proceedo=data.proceed;
                            // $scope.msg2 = true;
                            $("#verifyOTP").html("Verify OTP");
                            var result = '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> </strong>' + data.message + '!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                            $('#alertMessage1').html(result);
                            $('#alertMessage1').css('color', 'green');
    
                        }).error(function(data, status) {
                            $scope.msg2 = true;
                            var res = "";
                            $("#verifyOTP").html("Verify OTP");
                            $('#alertMessage1').hide();
                            $('#error1').css('color', 'red');
                            // var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i>Almost there! </strong>'+data.errors+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                            $.each(data.errors, function(idx, topic) {
                                res += '<div class="alert alert-danger alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>' + topic + '!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                            });
                            $('#error1').html(res);
    
                        })
                    }
                }
            $scope.sendEmail = function() {
                $('#mailcheck').hide();

                if (verify_email_check()) {
                    var oldmail = $('#oldmail').val();
                    var newmail = $('#u_email').val();
                    $("#sendEmail").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
                    $scope.newObj1 = {};
                    $scope.newObj1['id'] = $('#u_id').val();
                    $scope.newObj1['email'] = $('#u_email').val();
                    $http({
                        url: '{{url("email/verify")}}',
                        method: "GET",
                        params: $scope.newObj1
                    }).success(function(data) {
                        $scope.proceedo = data.proceed;
                        $scope.msg1 = true;
                        var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>' + data.message + '!</div>';
                        $('#snap1').hide();
                        $('#email1').html(result);
                        $('#email1').css('color', 'green');
                        $("#sendEmail").html("Send Email");
                    }).error(function(data) {
                        $("#sendEmail").html("Send Email");
                        $scope.msg1 = true;
                        var res = "";
                        $('#email1').css('color', 'red');
                        $.each(data, function(idx, topic) {
                            res += '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>' + topic + '</div>';
                        });
                        $('#snap1').hide();
                        $('#email1').html(res);
                    })
                } else {
                    return false;
                }
            }

            $scope.sendOTPmail = function() {

                $('#emailcheck').hide();
                var mail_error = true;
                var mobile_error = true;
                if ((verify_user_check()) && (verify_number_check())) {
                    var oldemail = $('#oldemail').val();
                    var newemail = $('#u_email').val(); // this.value
                    var oldnumber = $('#oldnumber').val().replace(/[\. ,:-]+/g, '');
                    var newnumber = $('#u_mobile').val().replace(/[\. ,:-]+/g, '');
                    $("#sendOTPmail").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");

                    var data = {
                        "newemail": newemail,
                        "newnumber": newnumber,
                        "oldnumber": oldnumber,
                        "oldemail": oldemail,
                        "email": $('#u_email').val(),
                        "mobile": $('#u_mobile').val().replace(/[\. ,:-]+/g, ''),
                        "code": $('#u_code').val(),
                        'id': $('#u_id').val(),
                        // 'password': $('#email_password').val()
                    };
                    // alert('ok');
                    $.ajax({
                        url: '{{url("otp/sendByAjax")}}',
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            $('.otp-field').show();
                            $('.email-mobile').hide();
                            var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>' + response.message + '!.</div>';
                            $('#alertMessage1').html(result);
                            $('#snap1').hide();
                            $('.wizard-inner').css('display', 'none');
                            var $active = $('.wizard .nav-tabs li.active');
                            $active.next().removeClass('disabled');
                            nextTab($active);
                            window.scrollTo(0, 10);
                            verify_otp_form.elements['hidden_user_id'].value = $('#user_id').val();
                            $("#sendOtp").html("Send");
                        },
                        error: function(ex) {
                            var myJSON = JSON.parse(ex.responseText);
                            var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                            $("#sendOtp").html("Send");
                            for (var key in myJSON) {
                                html += '<li>' + myJSON[key][0] + '</li>'
                            }
                            html += '</ul></div>';
                            $('#alertMessage1').hide();
                            $('#error1').show();
                            document.getElementById('error1').innerHTML = html;
                            setTimeout(function() {
                                $('#error1').hide();
                            }, 5000);
                        }
                    });
                } else {
                    return false;
                }
            }
        })
    </script>
    <!-- mobile verification end -->

    <script>
        //validation when both email and moble are not verified
        $('#u_email').keyup(function() {
            verify_user_check();
        });

        function verify_user_check() {
            var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
            if (pattern.test($('#u_email').val())) {
                $('#emailcheck').hide();
                $('#u_email').css("border-color", "");
                return true;
            } else {
                $('#emailcheck').show();
                $('#emailcheck').html("Please Enter a valid email");
                $('#emailcheck').focus();
                $('#u_email').css("border-color", "red");
                $('#emailcheck').css({
                    "color": "red",
                    "margin-top": "5px"
                });
                return false;
            }
        }
        $('#u_mobile').keyup(function() {
            verify_number_check();
        });
        function verify_number_check() {
            var userNumber = $('#u_mobile').val();
            if (userNumber.length < 1) {
                $('#mobilecheck').show();
                $('#mobcheck').hide();
                $('#mobilecheck').html("Please Enter Your Mobile No.");
                $('#mobilecheck').focus();
                $('#u_mobile').css("border-color", "red");
                $('#mobilecheck').css({
                    "color": "red",
                    "margin-top": "5px"
                });
                return false;
            } else {
                $('#mobilecheck').hide();
                $('#u_mobile').css("border-color", "");
                return true;
            }
        }

        //Validation when Email Is not verified
        $('#u_email').keyup(function() {
            verify_email_check();
        });
        function verify_email_check() {
            var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
            if (pattern.test($('#u_email').val())) {
                $('#mailcheck').hide();
                $('#u_email').css("border-color", "");
                return true;
            } else {
                $('#mailcheck').show();
                $('#mailcheck').html("Not a valid email");
                $('#mailcheck').focus();
                $('#u_email').css("border-color", "red");
                $('#mailcheck').css({
                    "color": "red",
                    "margin-top": "5px"
                });
                return false;
            }
        }

        //Validation when OTP is not verified
        $('#u_mobile').keyup(function() {
            verify_mobnumber_check();
        });
        function verify_mobnumber_check() {
            var userNumber = $('#u_mobile').val();
            if (userNumber.length < 1) {
                $('#mobcheck').show();
                $('#mobilecheck').hide();
                $('#mobcheck').html("Please Enter Your Mobile No.");
                $('#mobcheck').focus();
                $('#u_mobile').css("border-color", "red");
                $('#mobcheck').css({
                    "color": "red",
                    "margin-top": "5px"
                });
                return false;
            } else {
                $('#mobcheck').hide();
                $('#u_mobile').css("border-color", "");
                return true;
            }
        }

        //Validation for Verifying Otp
        $('#otp').keyup(function() {
            verify_otpverify_check();
        });
        function verify_otpverify_check() {
            var userOtp = $('#otp').val();
            if (userOtp.length < 4) {
                $('#verifyotp').show();
                $('#verifyotp').html("Please Enter A Valid OTP");
                $('#verifyotp').focus();
                $('#otp').css("border-color", "red");
                $('#verifyotp').css({
                    "color": "red",
                    "margin-top": "5px"
                });
                return false;
            } else {
                $('#verifyotp').hide();
                $('#otp').css("border-color", "");
                return true;
            }
        }
    </script>
    <script src="{{asset('js/intl/js/intlTelInput.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
      var vetelInput = $("#u_mobile");
      var userCountryCode = $("#verify_country_codeve").val();
      var currentCountry = "";
       errorMsgve = document.querySelector("#error-msgve"),
        validMsgve = document.querySelector("#valid-msgve"),
        addressDropdownve = $("#country");
    
    
      vetelInput.intlTelInput({
     
        geoIpLookup: function(callback) {
          $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            var vecountryCode = (resp && resp.country) ? resp.country : "";
            if (userCountryCode){
                vecountryCode = userCountryCode;
            }
            currentCountry = vecountryCode.toLowerCase();
            callback(vecountryCode);
            
          });
        },
        initialCountry: "auto",
        separateDialCode: true,
        utilsScript: "{{asset('js/intl/js/utils.js')}}",
      });
            var resetve = function() {
            errorMsgve.innerHTML = "";
            errorMsgve.classList.add("hide");
            validMsgve.classList.add("hide");
        };
        
                vetelInput.on('input blur', function () {
            resetve();
            if ($.trim(vetelInput.val())) {
                if (vetelInput.intlTelInput("isValidNumber")) {
                    console.log("valif");
                    $('#u_mobile').css("border-color","");
                    $("#error-msgve").html('');
                    errorMsgve.classList.add("hide");
                    $('#sendOTPmail').attr('disabled',false);
                    $('#sendOTP').attr('disabled',false);
                } else {
                    errorMsgve.classList.remove("hide");
                    errorMsgve.innerHTML = "Please enter a valid number";
                    $('#u_mobile').css("border-color","red");
                    $('#error-msgve').css({"color":"red","margin-top":"5px"});
                    $('#sendOTPmail').attr('disabled',true);
                    $('#sendOTP').attr('disabled',true);
                }
            }
        });
        $('input').on('focus', function () {
            $(this).parent().removeClass('has-error');
        });
        addressDropdownve.change(function() {
            vetelInput.intlTelInput("setCountry", $(this).val());
            if ($.trim(vetelInput.val())) {
                if (vetelInput.intlTelInput("isValidNumber")) {
                    $('#u_mobile').css("border-color","");
                    $("#error-msgve").html('');
                    errorMsgve.classList.add("hide");
                    $('#sendOTPmail').attr('disabled',false);
                    $('#sendOTP').attr('disabled',false);
                } else {
                    errorMsgve.classList.remove("hide");
                    errorMsgve.innerHTML = "Please enter a valid number";
                    $('#u_mobile').css("border-color","red");
                    $('#error-msgve').css({"color":"red","margin-top":"5px"});
                    $('#sendOTPmail').attr('disabled',true);
                    $('#sendOTP').attr('disabled',true);
                }
            }
        })
        

      setTimeout(function() {
        vetelInput.intlTelInput("setCountry", currentCountry);
      }, 500);

        $('form').on('submit', function (e) {
        var selectedCountryData = vetelInput.intlTelInput("getSelectedCountryData");

        var selectedDialCodeDiv = $('.selected-dial-code');
        if (selectedDialCodeDiv.length > 0) {
            var dialCodeFromDiv = selectedDialCodeDiv.text().trim();

            $('input[id=u_code]').val(dialCodeFromDiv);
        } else {
            console.error("Error: Could not find or retrieve dial code from the div.");
        }
    });



      vetelInput.on("countrychange", function(e, countryData) {
            var countryCode = countryData.dialCode;
            $("#u_code").val(countryCode);
          });
        $('.intl-tel-input').css('width', '100%');
        
        });




    </script>
    <script>
        $('#otp1').keyup(function() {
            verify_otp1_check();
        });
        function verify_otp1_check() {
            var userOtp = $('#otp1').val();
            if (userOtp.length < 4) {
                $('#confirmotp').show();
                $('#confirmotp').html("Please Enter A Valid OTP");
                $('#confirmotp').focus();
                $('#otp1').css("border-color", "red");
                $('#confirmotp').css({
                    "color": "red",
                    "margin-top": "5px"
                });
                return false;
            } else {
                $('#confirmotp').hide();
                $('#otp1').css("border-color", "");
                return true;
            }
        }

        function verifyBySendOtp() {
            $('#confirmotp').hide();
            if (verify_otp1_check()) {
                $("#verifyOtp").attr('disabled', true);
                $("#verifyOtp").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                var data = {
                    "mobile": $('#u_mobile').val().replace(/[\. ,:-]+/g, ''),
                    "code": $('#u_code').val(),
                    "otp": $('#otp1').val(),
                    'id': $('#u_id').val()
                };
                $.ajax({
                    url: '{{url('otp/verify ')}}',
                    type: 'post',
                    data: data,
                    success: function(response) {
                        $("#verifyOtp").attr('disabled', false)
                        $('#alertMessage1').hide();
                        localStorage.setItem('successmessage', response.message);
                        window.location.href = 'login';
                        $('#error2').hide();
                        $('#alertMessage2').show();
                        var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>' + response.message + '!</div>';
                        $('#alertMessage1').hide();
                        $("#verifyOtp").html("Verify OTP");
                    },
                    error: function(data) {
                        $("$verifyOtp").attr('disabled', false)
                        var html = '<div class="alert alert-danger alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>' + data.responseJSON.result + ' <br><ul>';
                        $("#verifyOtp").html("Verify OTP");
                        for (var key in data.responseJSON.errors) {
                            html += '<li>' + data.responseJSON.errors[key][0] + '</li>'
                        }
                        html += '</ul></div>';
                        $('#alertMessage1').hide();
                        $('#alertMessage2').hide();
                        $('#error1').show();
                        document.getElementById('error1').innerHTML = html;
                        setTimeout(function() {
                            $('#error1').hide();
                        }, 5000);
                    }
                });
            } else {
                return false;
            }
        }


        //----------------------------------------------Send OTP via SMS----------------------------------------------------------------------//

        $('#resendOTP').on('click', function() {
            var data = {
                "mobile": $('#u_mobile').val(),
                "code": $('#u_code').val(),
                "type": "text",
            };
            $("#resendOTP").attr('disabled', true);
            $("#resendOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Resending..");
            $.ajax({
                url: '{{url('resend_otp ')}}',
                type: 'GET',
                data: data,
                success: function(response) {
                    $("#resendOTP").attr('disabled', false);
                    $("#resendOTP").html("Resend OTP");
                    $('#alertMessage2').show();
                    $('#alertMessage1').hide();
                    $('#error2').hide();
                    var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="far fa-thumbs-up"></i> Well Done! </strong>' + response.message + '.</div>';
                    $('#alertMessage2').html(result + ".");
                },
                error: function(ex) {
                    $("#resendOTP").attr('disabled', false);
                    $("#resendOTP").html("Resend OTP");
                    var myJSON = JSON.parse(ex.responseText);
                    var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                    for (var key in myJSON) {
                        html += '<li>' + myJSON[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    $('#alertMessage2').hide();
                    $('#error2').show();
                    document.getElementById('error2').innerHTML = html;
                }
            });
        });
        //-----------------------------------------------------Send OTP via VoiceCall------------------------------------------------------------//

        $('#voiceOTP').on('click', function() {
            var data = {
                "mobile": $('#u_mobile').val(),
                "code": $('#u_code').val(),
                "type": "voice",
            };
            $("#voiceOTP").attr('disabled', true);
            $("#voiceOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending Voice Call..");
            $.ajax({
                url: '{{url('resend_otp ')}}',
                type: 'GET',
                data: data,
                success: function(response) {
                    $("#voiceOTP").attr('disabled', false);
                    $("#voiceOTP").html("Receive OTP via Voice call");
                    $('#alertMessage2').show();
                    $('#alertMessage1').hide();
                    $('#error2').hide();
                    var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="far fa-thumbs-up"></i> Well Done! </strong>' + response.message + '.</div>';
                    $('#alertMessage2').html(result + ".");
                },
                error: function(ex) {
                    $("#voiceOTP").attr('disabled', false);
                    $("#voiceOTP").html("Receive OTP via Voice call");
                    var myJSON = JSON.parse(ex.responseText);
                    var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                    for (var key in myJSON) {
                        html += '<li>' + myJSON[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    $('#alertMessage2').hide();
                    $('#error2').show();
                    document.getElementById('error2').innerHTML = html;
                }
            });
        });
    </script>

    <!-- send Otp if mobile number is not verified -->
    
    @stop