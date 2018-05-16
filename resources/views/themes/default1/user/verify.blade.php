@extends('themes.default1.layouts.front.master')
@section('title')
Email/Mobile Verification | Faveo Helpdesk
@stop
@section('page-heading')
 <h1>Email/Mobile Verification</h1>
@stop
@section('page-header')
Reset Password
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Verify</li>
@stop
@section('main-class') 
main
@stop
@section('content')
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
                        <div class="box-content">
                @if($user)

                @if ($user->active != 1 && $user->mobile_verified == 1) 
              
                 

                  
                    <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                     &nbsp &nbsp <label for="mobile" class="required">Email</label><br/>
                   
                       <input type="text" class="form-control input-lg" name="email" value="{{$user -> email}}" id="u_email">
                       <div class="clear"></div>
                       <div class="form-group col">
                            <button class="btn btn-primary float-right mb-5" style="margin-top:15px ; margin-right: -15px;" id="sendEmail" ng-click="sendEmail()">Send Email</button>
                        </div>
                   
                    @endif
                    @if($user->mobile_verified !=1 && $user->active == 1)
                    
                       <!--   <div class="alert alert-danger alert-dismissable">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p>You have not verified your OTP..Click the button below to receive OTP on your registered Mobile Number!!</p>
                </div> -->
                        
                       
                        <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                            <div class="form-group col-lg-12">
                          
                        <div class="row" ng-hide="showOTP">
                             <input type="hidden" class="form-control" name="code" value="{{$user -> mobile_code}}" id="u_code">
                             <label for="mobile" class="required">Mobile</label><br/>
                          
                         
                           <input type="text" class="form-control input-lg phonecode"  name="mobile" value="{{$user-> mobile}}" id="u_mobile">
                           <div class="clear"></div>
                      
                            <div class="form-group col" ><button class="btn btn-primary float-right mb-5" id="sendOTP" ng-click="sendOTP()" data-loading-text="Loading..." style="margin-top:15px ; margin-right: -15px;">Send OTP</button></div>
                       
                       </div>
                       
                    </div>




                        <div class="row" ng-show="showOTP">
                               <label for="mobile" class="required">Enter OTP</label><br/>

                             <div class="row">
                                    <div class="col-md-6">
                                       <input type="text" class="form-control input-lg"   ng-model="otp">
                                    </div>
                                    <div class="col-md-3">
                                       <button class="btn btn-primary float-right mb-5" id="verifyOTP" ng-click="submitOTP(otp)">Verify OTP</button>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="btn btn-danger float-right mb-5" ng-click="resendOTP()"  id="resendOTP" style="background: grey; color:white;">Resend OTP</a>
                                    </div>
                               </div>


                       
                    </div>
                        @endif

                         @if($user->mobile_verified ==0 && $user->active == 0)
                          <div class="row">
                           <div class="form-group col-lg-12 email-mobile">
                           <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                     &nbsp &nbsp <label for="mobile" class="required">Email</label><br/>
                   
                       <input type="text" class="form-control input-lg" name="email" value="{{$user -> email}}" id="u_email">
                       <div class="clear"></div>
                       <div class="form-group col">
                           
                        </div>


                        <input type="hidden"  name="user_id" value="{{$user -> id}}" id="u_id">
                          
                          
                       
                             <input type="hidden" class="form-control" name="code" value="{{$user -> mobile_code}}" id="u_code">
                             <label for="mobile" class="required">Mobile</label><br/>
                          
                         
                           <input type="text" class="form-control input-lg phonecode"  name="mobile" value="{{$user-> mobile}}" id="u_mobile">
                           <div class="clear"></div>
                      
                            <div class="form-group col" ><button class="btn btn-primary float-right mb-5" id="sendOTPmail" ng-click="sendOTPmail()" data-loading-text="Loading..." style="margin-top:15px ; margin-right: -15px;">Send </button></div>
                       
                       </div>
                       
                    </div>
                      <div class="row otp-field" style="display: none;">
                               <label for="mobile" class="required">Enter OTP</label>
                               <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control input-lg"  id="oneTimePassword" name="oneTimePassword"  ng-model="otp">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary float-right mb-5" id="verifyOtp"  onclick="verifyBySendOtp()" >Verify OTP</button>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="btn btn-danger float-right mb-5" onclick="reOTP()" id="resendOTP" style="background: grey; color:white;">Resend OTP</a>
                                    </div>
                               </div>
                       
                    </div>

                   

                         @endif
                        @endif
                        @if($user->role=='user')
                        <?php $url = url('login'); ?>
                        @else 
                        <?php $url = url('/'); ?>
                        @endif
                        <a href="{{$url}}"  class="btn btn-info"  ng-show="proceedo">Click here to login</a>
                        </div>


                        </div>
                        
                    </div>
                </div>
                </div>
            </div>
       </div>

                     
                     
                        @stop

@section('script')

                        <script src="{{asset('css/jquery/jquery.min.js')}}"></script>
                        <script src="{{asset('css/bootstrap/js/bootstrap.min.js')}}"></script>
                        <script src="{{asset('dist/js/angular.min.js')}}"></script>
                                    <script type="text/javascript">
    var telInput = $(".phonecode");
    telInput.intlTelInput({
        geoIpLookup: function (callback) {
            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        initialCountry: "auto",
        separateDialCode: true,
        utilsScript: "js/intl/js/utils.js"
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
    function verifyBySendOtp() {
                                    $("#verifyOtp").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                                    var data = {
                                        "mobile":   $('#u_mobile').val(),
                                        "code"  :   $('#u_code').val(),
                                        "otp"   :   $('#oneTimePassword').val(),
                                        'id'    :   $('#u_id').val()
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



                                          function reOTP() {
                                        var data = {
                                            "mobile":   $('#u_mobile').val(),
                                            "code"  :   $('#u_code').val(),
                                        };
                                        $.ajax({
                                          url: '{{url('resend_otp')}}',
                                          type: 'GET',
                                          data: data,
                                          success: function (response) {
                                                $('#alertMessage2').show();
                                                $('#alertMessage1').hide();
                                                $('#error2').hide();
                                                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> Well Done! </strong>'+response.message+'.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
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
                                var app = angular.module('smsApp', []);
                                app.controller('smsCtrl', function ($scope, $http) {

                                    $scope.sendOTP = function () {
                                        $("#sendOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
                                        $scope.newObj = {};
                                        $scope.newObj['id'] = $('#u_id').val();
                                        $scope.newObj['code'] = $('#u_code').val();
                                        $scope.newObj['mobile'] = $('#u_mobile').val();
                                        console.log($scope.newObj);
                                        $http({
                                            url: '{{url("otp/send")}}',
                                            method: "GET",
                                            params: $scope.newObj
                                        }).success(function (data) {
                                            if (data.type == "success") {
                                                $scope.showOTP = true;
                                                $scope.msg2 = true;
                                                $("#sendOTP").html("Send OTP");
                                                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i>Almost there! </strong>'+data.message+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                                $('#alertMessage1').html(result);
                                                $('#snap1').hide();
                                                $('#alertMessage1').css('color', 'green');
                                            }
                                        }).error(function (data) {
                                           console.log(data)
                                            $scope.msg2 = true;
                                            var res = "";
                                            $("#sendOTP").html("Send OTP");
                                            $('#error1').css('color', 'red');
                                            $.each(data, function (idx, topic) {
                                                res += "<li style='list-style-type:none'>" + topic + "</li>";
                                            });
                                            $('#error1').html(res);
                                        })
                                    };
                                    $scope.resendOTP=function(){
                                        $scope.showOTP = false;
                                    }
                                    $scope.submitOTP = function (x) {
                                        $("#verifyOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                                        $scope.newObj['otp'] = x;
                                        $http({
                                            url: '{{url("otp/verify")}}',
                                            method: "GET",
                                            params: $scope.newObj
                                        }).success(function (data) {
                                            $scope.proceedo=data.proceed;
                                            $scope.msg2 = true;
                                            $("#verifyOTP").html("Verify OTP");
                                             var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> </strong>'+data.message+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                            $('#alertMessage1').html(result);
                                            $('#alertMessage1').css('color', 'green');
                                    
                                        }).error(function (data,status) {
                                           console.log(data)
                                            $scope.msg2 = true;
                                            var res = "";
                                            $("#verifyOTP").html("Verify OTP");
                                             $('#alertMessage1').hide();
                                            $('#error1').css('color', 'red');
                                             // var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i>Almost there! </strong>'+data.errors+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                            $.each(data.errors, function (idx, topic) {
                                                res += '<div class="alert alert-success alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+topic+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                            });
                                            $('#error1').html(res);
                                         
                                        })
                                    }
                                    $scope.sendEmail = function () {
                                        $("#sendEmail").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
                                        $scope.newObj1 = {};
                                        $scope.newObj1['id'] = $('#u_id').val();
                                        $scope.newObj1['email'] = $('#u_email').val();
                                        $http({
                                            url: '{{url("email/verify")}}',
                                            method: "GET",
                                            params: $scope.newObj1
                                        }).success(function (data) {
                                            $scope.proceedo=data.proceed;
                                            $scope.msg1 = true;
                                            $('#email1').html(data.message);
                                            $('#email1').css('color', 'green');
                                            $("#sendEmail").html("Send Email");
                                        }).error(function (data) {
                                            $("#sendEmail").html("Send Email");
                                            $scope.msg1 = true;
                                            var res = "";
                                            $('#email1').css('color', 'red');
                                            $.each(data, function (idx, topic) {
                                                res += "<li style='list-style-type:none'>" + topic + "</li>";
                                            });
                                            $('#email1').html(res);
                                        })
                                    }
                       
                               $scope.sendOTPmail=function(){
                                        $("#sendOtpmail").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
                                        var data = {
                                            "email": $('#u_email').val(),
                                            "mobile": $('#u_mobile').val(),
                                            "code": $('#u_code').val(),
                                            'id': $('#u_id').val(),
                                            // 'password': $('#email_password').val()
                                        };
                                           // alert('ok');
                                        $.ajax({
                                          url: '{{url("otp/sendByAjax")}}',
                                          type: 'GET',
                                          data: data,
                                          success: function (response) {
                                            $('.otp-field').show();
                                            $('.email-mobile').hide();
                                                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                                $('#alertMessage1').html(result);
                                                $('#snap1').hide();
                                                $('.wizard-inner').css('display','none');
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

                                   

            })
</script>
                      
         
        @stop