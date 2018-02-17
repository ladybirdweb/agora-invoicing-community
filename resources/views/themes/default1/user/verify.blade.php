@extends('themes.default1.layouts.front.master')
@section('title')
reset password
@stop
@section('page-header')
Reset Password
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Reset Password</li>
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
                <div class="col-sm-10" style="float: none;margin: auto">
                    <div id="alertMessage1"></div>
                    <div id="error1">
                    </div>
                    <div class="featured-box featured-box-primary align-left mt-xlg" style="max-height: 1156px;height: auto">
                        <div class="box-content" style="margin-top:65px;">
                @if($user)

                @if ($user->active != 1) 
                <h1>Email Verification</h2>
                    <div class="alert alert-danger alert-dismissable">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p>You have not verified your Email..Click the button below to receive verification link on your registered Email Address!!</p>
                </div>
                    <div class="well" ng-show="msg1" id="email1"></div>
                    <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                     &nbsp &nbsp<label class="required">Email Address</label>
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-sm-4" style="margin: 5px"><input type="text" class="form-control" name="email" value="{{$user -> email}}" id="u_email"></div>
                        <div class="col-sm-4" style="margin: 5px">
                            <button class="btn btn-info" id="sendEmail" ng-click="sendEmail()">Send Email</button>
                        </div>
                    </div>
                    @endif
                    @if($user->mobile_verified!=1)
                    <h1>Mobile Verification</h2>
                         <div class="alert alert-danger alert-dismissable">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p>You have not verified your OTP..Click the button below to receive OTP on your registered Mobile Number!!</p>
                </div>
                        
                        <div class="well" ng-show="msg2" id="mobile1"></div>
                        <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                          &nbsp &nbsp<label class="required">Country code</label> &nbsp &nbsp  &nbsp &nbsp  &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp
                          <label class="required">Mobile Number</label>
                        <div class="row" ng-hide="showOTP" style="margin-bottom: 10px">
                            <div class="col-sm-2" style="margin: 5px"><input type="text" class="form-control" name="code" value="{{$user -> mobile_code}}" id="u_code"></div>
                       
                       
                        
                            <div class="col-sm-4" style="margin: 5px"><input type="text" class="form-control" name="mobile" value="{{$user -> mobile}}" id="u_mobile"></div>
                            <div class="col-sm-4" style="margin: 5px"><button class="btn btn-info" id="sendOTP" ng-click="sendOTP()">Send OTP</button></div>
                        </div>




                        <div class="row" ng-show="showOTP">
                            <div class="col-sm-2" style="margin: 5px"><label>Enter OTP</label></div>
                            <div class="col-sm-4" style="margin: 5px"><input type="text" class="form-control"   ng-model="otp"></div>
                            <div class="col-sm-4" style="margin: 5px"><button class="btn btn-info" id="verifyOTP" ng-click="submitOTP(otp)">Verify OTP</button><a  ng-click="resendOTP()" style="margin-left: 5px;line-height: 2.5;cursor: pointer">Resend OTP</a></div>
                        </div>
                        @endif
                        @endif
                        @if($user->role=='user')
                        <?php $url = url('login'); ?>
                        @else 
                        <?php $url = url('/'); ?>
                        @endif
                        <a href="{{$url}}" class="btn btn-info" style="float: right" ng-show="proceedo">Proceed</a>
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
                     
                        </html>
                        @stop