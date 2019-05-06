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
                        <div class="box-content">
                @if($user)

                @if ($user->active != 1 && $user->mobile_verified == 1) 
              
                 

                  
                    <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                     <label for="mobile" class="required">Email</label><br/>
                   
                       <input type="text" class="form-control input-lg" name="email" value="{{$user -> email}}" id="u_email">
                       <input type="hidden"  name="oldmail" value="{{$user -> email}}" id="oldmail">
                       <h6 id ="mailcheck"></h6>
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
                        
                       
                        <input type="hidden" name="user_id" value="{{$user->id}}" id="u_id">
                            <div class="form-group col-lg-12">
                          
                        <div class="row" ng-hide="showOTP">
                             <input type="hidden" class="form-control" name="code" value="{{$user -> mobile_code}}" id="u_code">
                             <label for="mobile" class="required">Mobile</label><br/>
                          
                         
                           <input type="text" class="form-control input-lg phonecode"  name="mobile" value="{{$user ->  mobile}}" id="u_mobile">
                            <input type="hidden"  name="oldno" value="{{$user -> mobile}}" id="oldno">
                            <h6 id = "mobcheck"></h6>
                           <div class="clear"></div>
                      
                            <div class="form-group col" ><button class="btn btn-primary float-right mb-5" id="sendOTP" ng-click="sendOTP()" data-loading-text="Loading..." style="margin-top:15px ; margin-right: -15px;">Send OTP</button></div>
                       
                       </div>
                       
                    </div>




                        <div ng-show="showOTP">
                               <label for="mobile" class="required">Enter OTP</label><br/>

                             <div class="row">
                                    <div class="col-md-6">
                                       <input type="text" class="form-control input-lg" id="otp"  ng-model="otp">
                                        <h6 id="verifyotp"></h6>
                                    </div>
                                    <div class="col-md-3">
                                       <button class="btn btn-primary float-right mb-5" id="verifyOTP" ng-click="submitOTP(otp)">Verify OTP</button>
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


                       
                    </div>
                        @endif

                         @if($user->mobile_verified == 0 && $user->active == 0)
                          <div class="row">
                           <div class="form-group col-lg-12 email-mobile">
                           <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                     <label for="mobile" class="required">Email</label><br/>
                   
                       <input type="text" class="form-control input-lg" name="email" value="{{$user -> email}}" id="u_email">
                        <input type="hidden"  name="oldemail" value="{{$user -> email}}" id="oldemail">
                        <h6 id="emailcheck"></h6>
                       <div class="clear"></div>
                       <div class="form-group col">
                           
                        </div>

                         
                        <input type="hidden"  name="user_id" value="{{$user -> id}}" id="u_id">
                          
                          
                       
                             <input type="hidden" class="form-control" name="code" value="{{$user->mobile_code}}" id="u_code">
                             <label for="mobile" class="required">Mobile</label><br/>
                          
                         
                           <input type="text" class="form-control input-lg phonecode"  name="mobile" value="{{$user-> mobile}}" id="u_mobile" type="tel">
                            <input type="hidden"  name="oldemail" value="{{$user->mobile}}" id="oldnumber">
                            <h6 id="mobilecheck"></h6>
                           <div class="clear"></div>
                      
                            <div class="form-group col" ><button class="btn btn-primary float-right mb-5" id="sendOTPmail" ng-click="sendOTPmail()" data-loading-text="Loading..." style="margin-top:15px ; margin-right: -15px;">Send </button></div>
                       
                       </div>
                       
                    </div>
                      <div class="otp-field" style="display: none;">
                               <label for="mobile" class="required">Enter OTP</label>
                               <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control input-lg"  id="otp1" name="oneTimePassword"  ng-model="otp">
                                        <h6 id ="confirmotp"></h6>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <button class="btn btn-primary float-right mb-5" id="verifyOtp"  onclick="verifyBySendOtp()" >Verify OTP</button>
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




                    
                      
                        <script src="{{asset('dist/js/angular.min.js')}}"></script>

          <script src="{{asset('js/intl/js/intlTelInput.js')}}"></script>
          
        <script>
             //validation when both email and moble are not verified
          $('#u_email').keyup(function(){
                 verify_user_check();
            });
           function verify_user_check(){
              var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
              if (pattern.test($('#u_email').val())){
                 $('#emailcheck').hide();
                  $('#u_email').css("border-color","");
                 return true;
               
              }
              else{
                 $('#emailcheck').show();
                $('#emailcheck').html("Please Enter a valid email");
                 $('#emailcheck').focus();
                $('#u_email').css("border-color","red");
                $('#emailcheck').css({"color":"red","margin-top":"5px"});

                   // mail_error = false;
                return false;
                
              }

            }

             $('#u_mobile').keyup(function(){
            verify_number_check();
         });

         function verify_number_check(){
            var userNumber = $('#u_mobile').val();
            if (userNumber.length < 5){
                $('#mobilecheck').show();
                $('#mobilecheck').html("Please Enter Your Mobile No.");
                $('#mobilecheck').focus();
                 $('#u_mobile').css("border-color","red");
                $('#mobilecheck').css({"color":"red","margin-top":"5px"});

               
                // mobile_error = false;
                return false;
            }
            else{
                $('#mobilecheck').hide();
                 $('#u_mobile').css("border-color","");
                return true;
                
              }
         }



        //Validation when Email Is not verified
         $('#u_email').keyup(function(){
                 verify_email_check();
            });
           function verify_email_check(){
              var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
              if (pattern.test($('#u_email').val())){
                 $('#mailcheck').hide();
                  $('#u_email').css("border-color","");
                 return true;
               
              }
              else{
                 $('#mailcheck').show();
                $('#mailcheck').html("Not a valid email");
                 $('#mailcheck').focus();
                $('#u_email').css("border-color","red");
                $('#mailcheck').css({"color":"red","margin-top":"5px"});

                   // mail_error = false;
                return false;
                
              }

            }


            //Validation when OTP is not verified
              $('#u_mobile').keyup(function(){
            verify_mobnumber_check();
         });

         function verify_mobnumber_check(){
            var userNumber = $('#u_mobile').val();
            if (userNumber.length < 5){
                $('#mobcheck').show();
                $('#mobcheck').html("Please Enter Your Mobile No.");
                $('#mobcheck').focus();
                 $('#u_mobile').css("border-color","red");
                $('#mobcheck').css({"color":"red","margin-top":"5px"});

               
                // mobile_error = false;
                return false;
            }
            else{
                $('#mobcheck').hide();
                 $('#u_mobile').css("border-color","");
                return true;
                
              }
         }

         //Validation for Verifying Otp
             $('#otp').keyup(function(){
                 verify_otpverify_check();
            });


            function verify_otpverify_check(){
            var userOtp = $('#otp').val();
            if (userOtp.length < 4){
                $('#verifyotp').show();
                $('#verifyotp').html("Please Enter A Valid OTP");
                $('#verifyotp').focus();
                 $('#otp').css("border-color","red");
                $('#verifyotp').css({"color":"red","margin-top":"5px"});

               
                // mobile_error = false;
                return false;
            }
            else{
                $('#verifyotp').hide();
                $('#otp').css("border-color","");
                return true;
                
              }
         }
        </script>



    <script type="text/javascript">
     $(document).ready(function(){
          var telInput = $(".phonecode");
    let currentCountry="";
     telInput.intlTelInput({
        geoIpLookup: function (callback) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                currentCountry=countryCode.toLowerCase()
                callback(countryCode);
            });
        },
        initialCountry: "auto",
        separateDialCode: true,
         utilsScript: "{{asset('js/intl/js/utils.js')}}"
    }); 
    setTimeout(()=>{
         telInput.intlTelInput("setCountry", currentCountry);
    },50)
    $('.intl-tel-input').css('width', '100%');

    // telInput.on('blur', function () {
    //     if ($.trim(telInput.val())) {
    //         if (!telInput.intlTelInput("isValidNumber")) {
    //             telInput.parent().addClass('has-error');
    //         }
    //     }
    // });
    $('input').on('focus', function () {
        $(this).parent().removeClass('has-error');
    });

    $('form').on('submit', function (e) {
        $('input[name=country_code]').attr('value', $('.selected-dial-code').text());
    });

    });


</script>
<script> 
      $('#otp1').keyup(function(){
                 verify_otp1_check();
            });


            function verify_otp1_check(){
            var userOtp = $('#otp1').val();
            if (userOtp.length < 4){
                $('#confirmotp').show();
                $('#confirmotp').html("Please Enter A Valid OTP");
                $('#confirmotp').focus();
                 $('#otp1').css("border-color","red");
                $('#confirmotp').css({"color":"red","margin-top":"5px"});

               
                // mobile_error = false;
                return false;
            }
            else{
                $('#confirmotp').hide();
                $('#otp1').css("border-color","");
                return true;
                
              }
         }

                                  function verifyBySendOtp() {
                                     $('#confirmotp').hide();
                                      if(verify_otp1_check()) {
                                    $("#verifyOtp").attr('disabled',true);   
                                    $("#verifyOtp").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                                    var data = {
                                        "mobile":   $('#u_mobile').val().replace(/[\. ,:-]+/g, ''),
                                        "code"  :   $('#u_code').val(),
                                        "otp"   :   $('#otp1').val(),
                                        'id'    :   $('#u_id').val()
                                    };
                                    $.ajax({
                                        url: '{{url('otp/verify')}}',
                                        type: 'GET',
                                        data: data,
                                        success: function (response) {
                                          $("#verifyOtp").attr('disabled',false)
                                          $('#alertMessage1').hide(); 
                                          localStorage.setItem('successmessage', response.message);
                                              window.location.href = 'login';
                                            $('#error2').hide(); 
                                            $('#alertMessage2').show();
                                            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                                            $('#alertMessage1').hide(); 
                                            // $('#alertMessage2').html(result);
                                            $("#verifyOtp").html("Verify OTP");
                                          
                                              // response.success("Success");
                                            
                                        },
                                        error: function (data) {
                                          $("$verifyOtp").attr('disabled',false)
                                             var html = '<div class="alert alert-success alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+data.responseJSON.result+' <br><ul>';
                                            $("#verifyOtp").html("Verify OTP");
                                              for (var key in data.responseJSON.errors)
                                            {
                                                html += '<li>' + data.responseJSON.errors[key][0] + '</li>'
                                            }
                                            html += '</ul></div>';
                                           $('#alertMessage1').hide(); 
                                             $('#alertMessage2').hide();
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


//----------------------------------------------Send OTP via SMS----------------------------------------------------------------------//

                                        $('#resendOTP').on('click',function(){
                                            var data = {
                                            "mobile":   $('#u_mobile').val(),
                                            "code"  :   $('#u_code').val(),
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
                                                $('#alertMessage2').show();
                                                $('#alertMessage1').hide();
                                                $('#error2').hide();
                                                var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="far fa-thumbs-up"></i> Well Done! </strong>'+response.message+'.</div>';
                                                $('#alertMessage2').html(result+ ".");
                                          },
                                          error: function (ex) {
                                                $("#resendOTP").attr('disabled',false);
                                                $("#resendOTP").html("Resend OTP");
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
                                      });
//-----------------------------------------------------Send OTP via VoiceCall------------------------------------------------------------//
                                       
                                       $('#voiceOTP').on('click',function(){
                                        var data = {
                                        "mobile":   $('#u_mobile').val(),
                                        "code"  :   $('#u_code').val(),
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
                                                $('#alertMessage2').show();
                                                $('#alertMessage1').hide();
                                                $('#error2').hide();
                                                var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="far fa-thumbs-up"></i> Well Done! </strong>'+response.message+'.</div>';
                                                $('#alertMessage2').html(result+ ".");
                                          },
                                          error: function (ex) {
                                                $("#voiceOTP").attr('disabled',false);
                                                $("#voiceOTP").html("Receive OTP via Voice call");
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
                                        });


                                  </script>
                               <script>
                                var app = angular.module('smsApp', []);
                                app.controller('smsCtrl', function ($scope, $http) {
                                  
                                    $scope.sendOTP = function () {
                                       $('#mobcheck').hide();
                                      if(verify_mobnumber_check()) { 
                                    var oldnumber = $('#oldno').val().replace(/[\. ,:-]+/g, '');
                                    var newnumber = $('#u_mobile').val().replace(/[\. ,:-]+/g, '');
                                       $("#sendOTP").attr('disabled',true);
                                        $("#sendOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
                                        $scope.newObj = {};
                                        $scope.newObj['oldnumber'] = oldnumber;
                                        $scope.newObj['newnumber'] = newnumber;
                                        $scope.newObj['id'] = $('#u_id').val();
                                        $scope.newObj['code'] = $('#u_code').val();
                                        $scope.newObj['mobile'] = $('#u_mobile').val().replace(/[\. ,:-]+/g, '');
                                        console.log($scope.newObj);
                                        $http({
                                            url: '{{url("otp/send")}}',
                                            method: "GET",
                                            params: $scope.newObj
                                        }).success(function (data) {
                                            if (data.type == "success") {
                                              $("#sendOTP").attr('disabled',false);
                                                $scope.showOTP = true;
                                                $scope.msg2 = true;
                                                $("#sendOTP").html("Send OTP");
                                                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i>Almost there! </strong>'+data.message+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                                $('#alertMessage1').html(result);
                                                $('#snap1').hide();
                                                $('#alertMessage1').css('color', 'green');
                                            }
                                        }).error(function (data) {
                                          $("#sendOTP").attr('disabled',false);
                                            $scope.msg2 = true;
                                            var res = "";
                                            $("#sendOTP").html("Send OTP");
                                            $('#error1').css('color', 'red');
                                             $.each(data.errors, function (idx, topic) {
                                                res += '<div class="alert alert-success alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+topic+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                            });
                                            $('#error1').html(res);
                                            // $('#error1').html(res);
                                        })
                                      }
                                       else{
                                        return false;
                                      }
                                    }
                                    $scope.resendOTP=function(){
                                        $scope.showOTP = false;
                                    }
                                    $scope.submitOTP = function (x) {
                                          $('#verifyotp').hide();
                                      if(verify_otpverify_check()) { 
                                        $("#verifyOTP").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                                        $scope.newObj['otp'] = x;
                                        $http({
                                            url: '{{url("otp/verify")}}',
                                            method: "GET",
                                            params: $scope.newObj
                                        }).success(function (data) {
                                           window.location.href = 'login';
                                            // $scope.proceedo=data.proceed;
                                            // $scope.msg2 = true;
                                            $("#verifyOTP").html("Verify OTP");
                                             var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> </strong>'+data.message+'!.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                            $('#alertMessage1').html(result);
                                            $('#alertMessage1').css('color', 'green');
                                    
                                        }).error(function (data,status) {
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
                                  }
                                    $scope.sendEmail = function () {
                                      $('#mailcheck').hide();
                                 
                                   if(verify_email_check()) {
                                       var oldmail = $('#oldmail').val();
                                    var newmail = $('#u_email').val();
                                        $("#sendEmail").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Sending...");
                                        $scope.newObj1 = {};
                                         $scope.newObj1['oldmail'] = oldmail;
                                        $scope.newObj1['newmail'] = newmail;
                                        $scope.newObj1['id'] = $('#u_id').val();
                                        $scope.newObj1['email'] = $('#u_email').val();
                                        $http({
                                            url: '{{url("email/verify")}}',
                                            method: "GET",
                                            params: $scope.newObj1
                                        }).success(function (data) {
                                            $scope.proceedo=data.proceed;
                                            $scope.msg1 = true;
                                             var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+data.message+'!</div>';
                                             $('#snap1').hide();
                                            $('#email1').html(result);
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
                                      else{
                                        return false;
                                      }
                                    }
                       
                               $scope.sendOTPmail=function(){

                                 $('#emailcheck').hide();
                                  var mail_error = true;
                                  var mobile_error = true;
                                   if((verify_user_check()) && (verify_number_check())){
                                     var oldemail=$('#oldemail').val();
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
                                          type: 'GET',
                                          data: data,
                                          success: function (response) {
                                            $('.otp-field').show();
                                            $('.email-mobile').hide();
                                                var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!.</div>';
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
                                    else{
                                            return false;
                                        }
                                  }

                                   

            })
</script>
                      
         
        @stop