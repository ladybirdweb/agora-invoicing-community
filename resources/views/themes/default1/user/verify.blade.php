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
        #msform {
            text-align: center;
            position: relative;
            margin-top: 20px;
        }

        #msform fieldset {
            background: white;
            border: 0 none;
            border-radius: 0.5rem;
            box-sizing: border-box;
            width: 100%;
            margin: 0;
            padding-bottom: 20px;
            position: relative;
        }

        .form-card {
            text-align: left;
        }

        /*Hide all except first fieldset*/
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        #msform input, #msform textarea {
            padding: 8px 15px 8px 15px;
            border: 1px solid #ccc;
            border-radius: 0px;
            margin-top: 2px;
            width: 100%;
            box-sizing: border-box;
            color: #2C3E50;
            font-size: 15px;
            letter-spacing: 1px;
        }

        #msform input:focus, #msform textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 1px solid #099fdc;
            outline-width: 0;
        }

        /*Next Buttons*/
        #msform .action-button {
            width: 100px;
            background: #099fdc;
            font-weight: 600;
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            margin: 10px 0px 10px 5px;
            float: right;
            text-transform: uppercase;
            border-radius: 5px;
            font-size: 14px;
        }

        #msform .action-button:hover, #msform .action-button:focus {
            background-color: #000000;
            color: #ffffff;
        }

        .card {
            z-index: 0;
            border: none;
            position: relative;
        }

        .purple-text {
            color: #099fdc;
            font-weight: normal;
        }

        /*Icon progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey;
            margin-left: -30px;
        }

        #progressbar .active {
            color: #099fdc;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 15px;
            /* width: 25%; */
            width: 33.33%;
            float: left;
            position: relative;
            font-weight: 400;
        }

        /*Icons in the ProgressBar*/
        #progressbar #otp_li:before {
            font-family: FontAwesome;
            content: "\f13e";
        }

        #progressbar #email_li:before {
            font-family: FontAwesome;
            content: "\f003";
        }

        #progressbar #success_li:before {
            font-family: FontAwesome;
            content: "\f087";
        }

        /*Icon ProgressBar before any progress*/
        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 20px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px;
        }

        /*ProgressBar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1;
        }

        /*Color number of the step and the connector before it*/
        #progressbar li.active:before, #progressbar li.active:after {
            background: #099fdc;
        }

        #otpButton {
            color: #099fdc;
            font-size: 14px;
        }

        #otpButton:disabled {
            color: rgb(106, 106, 106);
        }

        #timer {
            color: rgb(169, 169, 169);
            font-size: 14px;
            font-weight: 600;
        }

        #additionalButton {
            color: #099fdc;
            font-weight: 600;
        }

        #additionalButton:disabled {
            color: rgb(169, 169, 169);
        }

        #otpButtonn {
            color: #099fdc;
            font-size: 14px;
        }

        #otpButtonn:disabled {
            color: rgb(106, 106, 106);
        }

        .emailalert {
            border: 1px solid #262626;
            background: #81818124;
            border-radius: 5px;
            padding: 10px;
            color: #262626;
            font-size: 13px;
        }

    </style>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-9 col-md-7 col-lg-6 col-xl-5 text-center p-0 mt-3 mb-2">
                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">

                    <form id="msform">
                        <!-- progressbar -->
                        <ul id="progressbar">
                            <li class="active" id="otp_li"><strong>Verify OTP</strong></li>
                            <li id="email_li"><strong>Verify Email address</strong></li>
                            <li id="success_li"><strong>You are all set</strong></li>
                        </ul>
                        <br>
                        <!-- fieldsets -->
                        <fieldset id="fieldset_otp">
                            <div class="form-card">

                                <div id="alert-container"></div>

                                <p class="text-left">Enter Code <span class="text-color-danger"> *</span></p>

                                <input class="form-control" type="text" id="otp" name="otp" placeholder="Enter OTP"/>
                                <p class="mt-3">Enter the OTP code for verification which you have received on your
                                    registered
                                    mobile</p>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6 px-0">
                                            <div class="mt-2 d-flex align-items-center">
                                                <button id="otpButton" type="button"
                                                        onclick="resendOTP('mobile','text')" class="btn border-0 p-0"
                                                        style="width: 110px;">
                                                    <i class="fa fa-refresh"></i> Resend OTP
                                                </button>
                                                <div id="timer"></div>
                                            </div>
                                            <div class="mt-1">
                                                <button id="additionalButton" type="button"
                                                        onclick="resendOTP('mobile','voice')"
                                                        class="border-0 px-1 background-transparent"
                                                        disabled><i class="fa fa-phone"></i> Get OTP on call
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-6 px-0">
                                            <input type="button" onclick="submitOtp()" name="next"
                                                   class="next action-button float-right" value="Next"/>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset id="fieldset_email">
                            <div class="form-card">

                                <div id="alert-container-email"></div>

                                <div class="emailalert mt-2 text-center">
                                    Verification link has been sent to your email. Please check mail inbox and spam.
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="row">
                                        <div class="col-6 px-0">
                                            <div class="mt-3 d-flex align-items-center">
                                                <button id="otpButtonn" type="button" onclick="resendOTP('email',null)"
                                                        class="btn border-0 p-0 d-inline-flex align-items-center"
                                                        style="width: 110px; white-space: nowrap;">
                                                    <i class="fa fa-refresh mr-1"></i>Resend Email
                                                </button>
                                                <div id="timerEmail" class="ml-2"></div>
                                            </div>
                                        </div>
                                        <div class="col-6 px-0">
                                            <input onclick="isEmailVerified()" type="button" name="next"
                                                   class="next action-button float-right" value="Verify"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset id="fieldset_success">
                            <div class="form-card">

                                <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2>
                            </div>
                        </fieldset>

                    </form>

                    <div class="mt-2 text-start text-2">
                        Encountering trouble logging in? <a href="#" class="text-decoration-underline">click here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('script')

    <script>
        <?php
        $isMobileVerified = $user->mobile_verified ? true : false;
        $isEmailVerified = $user->active ? true : false;
        ?>

        const otpButton = document.getElementById("otpButton");
        const additionalButton = document.getElementById("additionalButton");
        const timerDisplay = document.getElementById("timer");
        const emailOtpButton = document.getElementById("otpButtonn");
        const emailTimerDisplay = document.getElementById("timerEmail");
        let timerInterval;
        const eid = @json($eid);

        let countdown = 120;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        function updateTimer(display, countdown) {
            display.textContent = countdown.toString().padStart(2, '0') + " seconds";
        }

        function startTimer(button, display, duration) {
            clearInterval(timerInterval);
            var countdown = countdown;
            button.disabled = true;
            additionalButton.disabled = true;
            display.style.display = "block";
            countdown = duration;
            updateTimer(display, countdown);
            timerInterval = setInterval(() => {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(timerInterval);
                    button.disabled = false;
                    additionalButton.disabled = false;
                    display.style.display = "none";
                }
                updateTimer(display, countdown);
            }, 1000);
        }

        function resendOTP(default_type, type) {
            const data = {eid, default_type, type};
            $.ajax({
                url: '{{ url('resend_otp') }}',
                type: 'POST',
                data: data,
                success: function (response) {
                    if (default_type === 'mobile') {
                        startTimer(otpButton, timerDisplay, countdown);
                        showAlert('success', response.message, '#alert-container');
                    } else if (default_type === 'email') {
                        startTimer(emailOtpButton, emailTimerDisplay, countdown);
                        showAlert('success', response.message, '#alert-container-email');
                    }
                },
                error: function (error) {
                    if (default_type === 'mobile') {
                        showAlert('danger', error.responseJSON.message, '#alert-container');
                    } else if (default_type === 'email') {
                        showAlert('danger', error.responseJSON.message, '#alert-container-email');
                    }
                }
            });
        }

        function sendOTP() {
            const data = {eid};
            $.ajax({
                url: '{{ url('otp/send') }}',
                type: 'POST',
                data: data,
                success: function (response) {
                    startTimer(otpButton, timerDisplay, countdown);
                    showAlert('success', response.message, '#alert-container');
                },
                error: function (error) {
                    showAlert('danger', error.responseJSON.message, '#alert-container');
                }
            });
        }

        function submitOtp() {
            const otpField = $('#otp');
            const otpValue = otpField.val();
            const otpRegex = /^[0-9]{6}$/;

            otpField.removeClass('is-invalid').css('border-color', '');
            $('.error').remove();

            if (!otpValue) {
                showError(otpField, 'OTP is required');
                return;
            }

            if (!otpRegex.test(otpValue)) {
                showError(otpField, 'OTP must be a 6-digit number');
                return;
            }

            const data = {eid, otp: otpValue};
            $.ajax({
                url: '{{ url('otp/verify') }}',
                type: 'POST',
                data: data,
                success: function (response) {
                    showAlert('success', response.message, '#alert-container');
                    setTimeout(() => {
                        activateFieldset("fieldSetTwo");
                        sendEmail();
                        startTimer(emailOtpButton, emailTimerDisplay, countdown);
                    }, 5000);
                },
                error: function (error) {
                    showAlert('danger', error.responseJSON.message, '#alert-container');
                }
            });
        }

        function activateFieldset(fieldSet) {
            const fieldsets = {
                fieldSetOne: document.getElementById('fieldset_otp'),
                fieldSetTwo: document.getElementById('fieldset_email'),
                fieldSetThree: document.getElementById('fieldset_success')
            };

            const progressList = {
                fieldSetOne: document.getElementById('otp_li'),
                fieldSetTwo: document.getElementById('email_li'),
                fieldSetThree: document.getElementById('success_li')
            };

            Object.values(fieldsets).forEach(fieldset => fieldset.style.display = 'none');
            // Object.values(progressList).forEach(progress => progress.classList.remove('active'));

            if (fieldsets[fieldSet] && progressList[fieldSet]) {
                if(fieldSet === 'fieldSetOne'){
                    startTimer(otpButton, timerDisplay, countdown);
                    sendOTP();
                }
                else if(fieldSet === 'fieldSetTwo'){
                    startTimer(emailOtpButton, emailTimerDisplay, countdown);
                }
                else if(fieldSet === 'fieldSetThree'){
                    setTimeout(() => {
                        window.location.href = "{{url('/')}}";
                    }, 5000);
                }
                fieldsets[fieldSet].style.display = 'block';
                progressList[fieldSet].classList.add('active');
            }
        }

        function sendEmail() {
            const data = {eid: eid};
            $.ajax({
                url: '{{ url('/send-email') }}',
                type: 'POST',
                data: data,
                success: function (response) {
                    showAlert('success', response.message, '#alert-container-email');
                },
                error: function (error) {
                    showAlert('danger', error.responseJSON.message, '#alert-container-email');
                }
            });
        }

        function isEmailVerified() {
            const data = {eid: eid};
            $.ajax({
                url: '{{ url('email/verify') }}',
                type: 'POST',
                data: data,
                success: function (response) {
                    showAlert('success', response.message, '#alert-container-email');
                    setTimeout(() => {
                        activateFieldset("fieldSetThree");
                    }, 5000);
                },
                error: function (error) {
                    showAlert('danger', error.responseJSON.message, '#alert-container-email');
                }
            });
        }

        function showAlert(type, message, container) {
            console.log(type, message);
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-ban';
            const alertType = type === 'success' ? 'alert-success' : 'alert-danger';

            $(container).html(`
            <div class="alert ${alertType} alert-dismissable">
                <i class="fa ${icon}"></i> ${message}
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
        `);

            setTimeout(() => {
                $(container).find('.alert').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, 5000);
        }

        function showError(field, message) {
            field.addClass('is-invalid').css('border-color', 'red');
            field.after(`<span class="error invalid-feedback">${message}</span>`);
        }
        @if(!$isMobileVerified && !$isEmailVerified)
        activateFieldset('fieldSetOne');
        @elseif($isMobileVerified && !$isEmailVerified)
        activateFieldset('fieldSetTwo');
        @else
        activateFieldset('fieldSetOne');
        @endif
    </script>
@stop