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
                    <div id="error1">
                    </div>
                       <div class="alert alert-danger alert-dismissable">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Your Email is not Verified..!!
                   <ul>
                   <li>Click the button to resend Verification Email</li>
                                            
                     </ul>
                </div>
                  <div class="featured-box featured-box-primary text-left mt-5">
                        <div class="box-content">
                @if($user)

                @if ($user->active != 1) 
              
                 

                  
                    <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                     &nbsp &nbsp <label for="mobile" class="required">Email</label><br/>
                   
                       <input type="text" class="form-control input-lg" name="email" value="{{$user -> email}}" id="u_email">
                       <div class="clear"></div>
                       <div class="form-group col">
                            <button class="btn btn-primary float-right mb-5" style="margin-top:15px ; margin-right: -15px;" id="sendEmail" ng-click="sendEmail()">Send Email</button>
                        </div>
                   
                    @endif
                    @if($user->mobile_verified!=1)
                    
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
                                 <div class="row verify">
                                     
                            <div class="form-group col-lg-6" ><input type="text" class="form-control input-lg"   ng-model="otp"></div>
                              <div class="form-group col-lg-2"><button class="btn btn-primary float-right mb-5" id="verifyOTP" ng-click="submitOTP(otp)" style="margin-right:-28px";>Verify OTP</button></div>

                                                       <div class="form-group col-lg-2">
                                                       

                              <a class="btn btn-danger float-right mb-5" ng-click="resendOTP()" style="background: grey; color:white;">Resend OTP</a>
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
                        <a href="{{$url}}"   ng-show="proceedo">Click here to login</a>
                        </div>


                        </div>
                        
                    </div>
                </div>
                </div>
            </div>
       </div>

                        <script src="{{asset('css/jquery/jquery.min.js')}}"></script>
                        <script src="{{asset('css/bootstrap/js/bootstrap.min.js')}}"></script>
                        <script src="{{asset('dist/js/angular.min.js')}}"></script>
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
                                                $('#mobile1').html(data.message);
                                                $('#mobile1').css('color', 'green');
                                            }
                                        }).error(function (data) {
                                            $scope.msg2 = true;
                                            var res = "";
                                            $("#sendOTP").html("Send OTP");
                                            $('#mobile1').css('color', 'red');
                                            $.each(data, function (idx, topic) {
                                                res += "<li style='list-style-type:none'>" + topic + "</li>";
                                            });
                                            $('#mobile1').html(res);
                                        })
                                    }
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
                                            $('#mobile1').html(data.message);
                                            $('#mobile1').css('color', 'green');
                                    
                                        }).error(function (data,status) {
                                            $scope.msg2 = true;
                                            var res = "";
                                            $("#verifyOTP").html("Verify OTP");
                                            $('#mobile1').css('color', 'red');
                                            $.each(data, function (idx, topic) {
                                                res += "<li style='list-style-type:none'>" + topic + "</li>";
                                            });
                                            $('#mobile1').html(res);
                                         
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
                                })
                        </script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
                       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                      <script type="text/javascript">
              $(".phonecode").intlTelInput({
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
                     
                        </html>
                        @stop