<?php $setting = \App\Model\Common\Setting::where('id', 1)->first(); ?>
<!DOCTYPE html>
<html ng-app="smsApp">
    <head>

        <!-- Basic -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">	

        <title>
            @yield('title')
        </title>	

        <meta name="keywords" content="Faveo" />
        <meta name="description" content="Faveo User Verification">
        <meta name="author" content="vijay">

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{asset('dist/img/faveo.png')}}" type="image/x-icon" />
        <link rel="apple-touch-icon" href="{{asset('dist/img/faveo.png')}}">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- Vendor CSS -->
        <link rel="stylesheet" href="{{asset('cart/vendor/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/simple-line-icons/css/simple-line-icons.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/owl.carousel/assets/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/owl.carousel/assets/owl.theme.default.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/magnific-popup/magnific-popup.min.css')}}">

        <!-- Theme CSS -->
        <link rel="stylesheet" href="{{asset('cart/css/theme.css')}}">
        <link rel="stylesheet" href="{{asset('cart/css/theme-elements.css')}}">
        <link rel="stylesheet" href="{{asset('cart/css/theme-blog.css')}}">
        <link rel="stylesheet" href="{{asset('cart/css/theme-shop.css')}}">
        <link rel="stylesheet" href="{{asset('cart/css/theme-animate.css')}}">

        <!-- Skin CSS -->
        <link rel="stylesheet" href="{{asset('cart/css/skins/default.css')}}">

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="{{asset('cart/css/custom.css')}}">

        <link rel="stylesheet" href="{{asset('dist/css/custom.css')}}">
    </head>
    <body ng-controller="smsCtrl">
        <?php
        $domain = [];
        $set = new \App\Model\Common\Setting();
        $set = $set->findOrFail(1);
        ?>
        <div class="body">
            <header id="header" data-plugin-options='{"stickyEnabled": true, "stickyEnableOnBoxed": true, "stickyEnableOnMobile": true, "stickyStartAt": 57, "stickySetTop": "-57px", "stickyChangeLogo": true}'>
                <div class="header-body">
                    <div class="header-container container">
                        <div class="header-row">
                            <div class="header-column">
                                <div class="header-logo">
                                    <a href="{{url('home')}}">
                                        <img alt="Porto" width="111" height="54" data-sticky-width="82" data-sticky-height="40" data-sticky-top="33" src="{{asset('cart/img/logo/'.$setting -> logo)}}">
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </header>



            <section class="page-header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                @yield('breadcrumb')
                                <!--<li><a href="#">Home</a></li>
                                <li class="active">Pages</li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h1>@yield('page-heading')</h1>
                        </div>
                    </div>
                </div>
            </section>

            <div class="container">

                @if($user)

                @if ($user->active != 1) 
                <h1>Email Verification</h2>
                    <div class="well" ng-show="msg1" id="email1"></div>
                    <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-sm-4" style="margin: 5px"><input type="text" class="form-control" name="email" value="{{$user -> email}}" id="u_email"></div>
                        <div class="col-sm-4" style="margin: 5px">
                            <button class="btn btn-info" id="sendEmail" ng-click="sendEmail()">Send Email</button>
                        </div>
                    </div>
                    @endif
                    @if($user->mobile_verified!=1)
                    <h1>Mobile Verification</h2>
                        <div class="well" ng-show="msg2" id="mobile1"></div>
                        <input type="hidden" name="user_id" value="{{$user -> id}}" id="u_id">
                        <div class="row" ng-hide="showOTP" style="margin-bottom: 10px">
                            <div class="col-sm-1" style="margin: 5px"><input type="text" class="form-control" name="code" value="{{$user -> mobile_code}}" id="u_code"></div>
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
                        <?php $url = url('show/cart'); ?>
                        @else 
                        <?php $url = url('/'); ?>
                        @endif
                        <a href="{{$url}}" class="btn btn-info" style="float: right" ng-show="proceedo">Proceed</a>
                        </div>


                        <footer id="footer">
                            <div class="container">
                                <div class="row">
                                    <div class="footer-ribbon">
                                        <span>Get in Touch</span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="newsletter">
                                            <h4>Newsletter</h4>
                                            <p>Keep up on our always evolving product features and technology. Enter your e-mail and subscribe to our newsletter.</p>

                                            <div class="alert alert-success hidden" id="newsletterSuccess">
                                                <strong>Success!</strong> You've been added to our email list.
                                            </div>

                                            <div class="alert alert-danger hidden" id="newsletterError"></div>

                                            {!! Form::open(['url'=>'mail-chimp/subcribe','method'=>'GET']) !!}
                                            <div class="input-group">
                                                <input class="form-control" placeholder="Email Address" name="email" id="newsletterEmail" type="text">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="submit">Go!</button>
                                                </span>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <h4>Latest Tweets</h4>
                                        <div id="tweets" class="twitter">

                                        </div>
                                    </div>
                                    <?php $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer')->take(1)->get(); ?>
                                    @foreach($widgets as $widget)
                                    <div class="col-md-3">
                                        <div class="contact-details">
                                            <h4>{{ucfirst($widget -> name)}}</h4>
                                            {!! $widget->content !!}
                                        </div>
                                    </div>
                                    @endforeach

                                    <div class="col-md-2">
                                        <a href="#" onclick="window.open('https://www.sitelock.com/verify.php?site=faveohelpdesk.com', 'SiteLock', 'width=600,height=600,left=160,top=170');" ><img class="img-responsive" alt="SiteLock" title="SiteLock" src="//shield.sitelock.com/shield/faveohelpdesk.com" /></a>
                                    </div>

                                </div>
                            </div>
                            <div class="footer-copyright">
                                <div class="container">
                                    <div class="row">


                                        <div class="col-md-12">
                                            <p>Copyright © <?php echo date('Y') ?> · <a href="{{$set -> website}}" target="_blank">{{$set -> company}}</a>. All Rights Reserved.Powered by 
                                                <a href="http://www.ladybirdweb.com/" target="_blank"><img src="{{asset('dist/img/Ladybird1.png')}}" alt="Ladybird"></a></p>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </footer>
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
                        </body>
                        </html>