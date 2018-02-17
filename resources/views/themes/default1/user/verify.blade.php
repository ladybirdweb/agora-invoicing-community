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



<style>
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }

    .wizard {
        margin: 20px auto;
        background: #fff;
    }

    .wizard .nav-tabs {
        position: relative;
        margin: 40px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
    }

.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 70%;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: 37%;
    z-index: 1;
}

.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
    
}
.wizard .nav-tabs > li{
    margin-bottom: 0px;
}
p.round-tab {
    width: 70px;
    height: 70px;
    line-height: 70px;
    display: inline-block;
    border-radius: 100px;
    background: #fff;
    border: 2px solid #e0e0e0;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 25px;
}
p.round-tab i{
    color:#555555;
}
.wizard li.active p.round-tab {
    background: #fff;
    border: 2px solid #5bc0de;
    
}
.wizard li.active p.round-tab i{
    color: #5bc0de;
}

p.round-tab:hover {
    color: #333;
    border: 2px solid #333;
}

.wizard .nav-tabs > li {
    width: 33.333%;
}

.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: #5bc0de;
    transition: 0.1s ease-in-out;
}

.wizard li.active:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 1;
    margin: 0 auto;
    bottom: 0px;
    border: 10px solid transparent;
    border-bottom-color: #5bc0de;
}

.wizard .nav-tabs > li a {
    width: 70px;
    height: 70px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
    border-left: none;
border-right: none;
border-top: none;
}

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

.wizard .tab-pane {
    position: relative;
    
}

.wizard h3 {
    margin-top: 0;
}

@media( max-width : 585px ) {

    .wizard {
        width: 90%;
        height: auto !important;
    }

    p.round-tab {
        font-size: 16px;
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard .nav-tabs > li a {
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 35%;
    }
    
}

.nav-tabs{
      border-bottom: none;
}
.tab-content {
    border-radius: 0px;
    box-shadow: inherit;
   
    border: none ;
    border-top: 0;
    padding: 15px;
}
</style>





<div class="container">
 <div class="row">
                <div class="col-sm-10" style="float: none;margin: auto">
                    <div id="alertMessage1"></div>
                    <div id="error1">
                    </div>
                    <div class="featured-box featured-box-primary align-left mt-xlg" style="max-height: 1156px;height: auto">
                        <div class="box-content">
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

                        <script src="{{asset('cart/vendor/jquery/jquery.min.js')}}"></script>
                        <script src="{{asset('cart/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
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