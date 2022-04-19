
<?php
use App\Http\Controllers\Front\CartController;
$country = findCountryByGeoip($location['iso_code']);
?>
<style>

    .required:after{
        content:'*';
        color:red;
        padding-left:0;
    } 
    .wizard-inner
    {
        display:none;
    }
    .nav-tabs{
        border-bottom: none;
        margin: -5px;
    }
    .tab-content {
        border-radius: 0;
        box-shadow: inherit;

        border: none ;
        border-top: 0;
        /*padding: 15px;*/
    }
    .open>.dropdown-menu {
        display: block;
        color:black;
    }
    .inner>.dropdown-menu{
        margin-top: 0;
    }
    .bootstrap-select .dropdown-toggle .caret {
        display: none;
    }
    .form-control:not(.form-control-sm):not(.form-control-lg) {
        /*font-size: 13.6px;
        font-size: 0.85rem;*/
        line-height: normal;

    }



</style>
<section>
    <div class="wizard">
        <div class="wizard-inner" style="display: none">
            <ul class="nav nav-tabs" role="tablist" style=" margin: -5px!important;">
                <li role="presentation" class="active">
                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab">


                    </a>
                    <p style="display: none">Contact Information</p>
                </li>
                <li role="presentation" class="disabled" >
                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" >


                    </a>
                    <p style="display: none">Identity Verification</p>
                </li>
                <li role="presentation" class="disabled">
                    <a href="#step3" data-toggle="tab" aria-controls="complete" role="tab" title="Confirmation">


                    </a>
                    <p style="display: none">Confirmation</p>
                </li>


            </ul>
        </div>

        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Login</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="box-content">

                            @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                                {!!  Form::open(['action'=>'Auth\LoginController@login', 'method'=>'post','id'=>'formoid','onsubmit'=>'return validateform()']) !!}
                            @else
                                {!!  Form::open(['action'=>'Auth\LoginController@login', 'method'=>'post','id'=>'formoid']) !!}
                            @endif
                            <div class="form-row">
                                <div class="form-group col {{ $errors->has('email1') ? 'has-error' : '' }}">

                                    <label class="required" >E-mail Address</label>
                                    <div class="input-group">
                                        {!! Form::text('email1',null,['class' => 'form-control input-lg','id'=>'username','autocomplete'=>"off" ]) !!}
                                        <div class="input-group-append">
                                            {{--                                    <span class="input-group-text"><i class="fa fa-user"></i></span>--}}
                                        </div>

                                    </div>
                                    <!-- <h6 id="usercheck"></h6> -->


                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col {{ $errors->has('password1') ? 'has-error' : '' }}">

                                    <a class="pull-right" href="{{url('password/reset')}}">({{Lang::get('message.forgot-my-password')}})</a>
                                    <label class="required" >Password</label>
                                    <div class="input-group">
                                        {!! Form::password('password1',['class' => 'form-control input-lg' ,'id'=>'pass']) !!}
                                        <div class="input-group-append">
                                            {{--                                    <span class="input-group-text"><i class="fa fa-key"></i></span>--}}
                                        </div>

                                    </div>
                                    <!-- <h6 id="passcheck"></h6> -->
                                    <!--<input type="password" value="" class="form-control input-lg">-->

                                </div>
                            </div>

                            @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                                <div class="loginrobot-verification"></div>
                            @endif

                            <div class="form-group">
                                <div class="form-check form-check-inline">

                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" id="rememberme" name="remember" >Remember Me
                                    </label>

                                </div>
                            </div>
                            <hr style="width: 100%;">
                            <div class="form-group pull-right">
                                <button type="button" class="btn btn-default closebutton" id="closebutton" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>

                                <input type="submit" value="Login" id="submitbtn" class="btn btn-primary" data-loading-text="Loading...">
                                <!-- <button type="button" class="btn btn-primary mb-xl next-step float-right" name="sendOtp" id="login" onclick="loginUser()">
                                            Send Email
                                </button> -->

                            </div>

                             <span >Not a user? <a data-dismiss="modal" data-toggle="modal" href="#register-modal">Signup</a></span>

                            {!! Form::close() !!}
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Register</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="card-body">

                        <form name="registerForm" id="regiser-form">

                            <div class="row">

                             <div class="form-group col-lg-6 {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                                    
                                    <label class="required">First Name</label>

                                    {!! Form::text('first_name',null,['class'=>'form-control input-lg', 'id'=>'first_name']) !!}
                                    <span id="first_namecheck"></span>
                                    </div>



                                    <div class="form-group col-lg-6 {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                        <label class="required">Last Name</label>
                                        {!! Form::text('last_name',null,['class'=>'form-control input-lg', 'id'=>'last_name']) !!}
                                        <span id="last_namecheck"></span>

                                                        </div>


                                                    </div>

                            <div class="form-row">
                                <div class="form-group col {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label class="required">Email Address</label>
                                    {!! Form::email('email',null,['class'=>'form-control input-lg', 'id'=>'mail']) !!}
                                    <span id="emailcheck"></span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col col-lg-6 {{ $errors->has('country') ? 'has-error' : '' }}">
                                    {!! Form::label('country',Lang::get('message.country'),['class'=>'required']) !!}
                                    <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>
                                    {!! Form::select('country',[''=>'','Choose'=>$countries],$country,['class' => 'form-control selectpicker','data-live-search-style'=>"startsWith",'data-live-search'=>'true','data-live-search-placeholder'=>'Search','data-dropup-auto'=>'false','data-size'=>'10','onChange'=>'getCountryAttr(this.value);','id'=>'country']) !!}
                                    <span id="countrycheck"></span>
                                </div>

                          
                                <div class="col-lg-6 form-group {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                                    <label class="required">Mobile</label>
                                    {!! Form::hidden('mobile',null,['id'=>'phone_code_hidden']) !!}
                                    <input class="form-control input-lg" id="phonenum" name="mobile" >
                                    {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                    <span id="valid-msg" class="hide"></span>
                                    <span id="error-msg" class="hide"></span>
                                    <span id="mobile_codecheck"></span>
                                </div>
                            </div>
                        <!--   <input type="checkbox" name="checkbox" id="option" value="{{old('option')}}"><label for="option"><span></span> <p>I agree to the <a href="#">terms</a></p></label>-->
                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')

                                        {!! NoCaptcha::display() !!}

                                        <div class="robot-verification" id="captcha"></div>
                                        <span id="captchacheck"></span>
                                    @endif
                                </div>
                            </div>

                            @if ($status->terms ==0)
                                <div class="form-group">
                                    <input type="hidden" value="true" name="terms" id="term">
                                </div>
                            @else
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" value="false" name="terms" id="term" > {{Lang::get('message.i-agree-to-the')}} <a href="{{$apiKeys->terms_url}}" target="_blank">{{Lang::get('message.terms')}}</a>
                                    </label>
                                    <br><span id="termscheck"></span>
                                </div>
                            @endif
                            <hr style="width: 100%;">
                            <div class="form-group pull-right">
                                <button type="button" class="btn btn-default closebutton" id="closebutton" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>

                                <button type="button"  class="btn btn-primary" value="0" name="register" id="register" onclick="registerUser(0)">Submit</button>
                                <!-- <button type="button" class="btn btn-primary mb-xl next-step float-right" name="sendOtp" id="login" onclick="loginUser()">
                                            Send Email
                                </button> -->

                            </div>


                                    <span>Already user?<a data-dismiss="modal" data-toggle="modal" href="#login-modal">Sign in</a></span>



                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>

<script src="build/js/intlTelInput.js"></script>

<script>
  var input = document.querySelector("#phonenum");
  window.intlTelInput(input, {
    // any initialisation options go here
  });
</script>

</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $analyticsTag; ?>"></script>

<script>
    ///////////////////////////////////////////////////////////////////////////////
    ///Google Recaptcha
    function recaptchaCallback() {
        document.querySelectorAll('.g-recaptcha').forEach(function (el) {
            grecaptcha.render(el);
        });
    }
    ///////////////////////////////////////////////////////////////////////////////////
</script>





<script type="text/javascript">

    $('.closebutton').on('click',function(){
        location.reload();
    });
    //robot validation for Login Form
    function validateform() {
        var input = $(".g-recaptcha :input[name='g-recaptcha-response']");
        console.log(input.val());
        if(input.val() == null || input.val()==""){
            $('.loginrobot-verification').empty()
            $('.loginrobot-verification').append("<p style='color:red'>Robot verification failed, please try again.</p>")
            return false;
        }
        else{
            return true;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Registration Form Validation

    function first_namecheck(){
            var firrstname_val = $('#first_name').val();
            if(firrstname_val.length == ''){
                $('#first_namecheck').show();
                $('#first_namecheck').html("Please Enter First Name");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color","red");
                $('#first_namecheck').css("color","red");
                // userErr =false;

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top -200
                }, 1000)
                return false;
            }

            if(firrstname_val.length > 30){
                $('#first_namecheck').show();
                $('#first_namecheck').html("Max 30 characters allowed ");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color","red");
                $('#first_namecheck').css("color","red");
                // userErr =false;

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top -200
                }, 1000)
                return false;
            }

            var pattern = new RegExp(/[^a-zA-Z0-9]/);
            if(pattern.test(firrstname_val)) {
                $('#first_namecheck').show();
                $('#first_namecheck').html("Special characters not allowed");
                $('#first_namecheck').focus();
                $('#first_name').css("border-color","red");
                $('#first_namecheck').css("color","red");

                $('html, body').animate({
                    scrollTop: $("#first_namecheck").offset().top -200
                }, 1000)
                return false;
            }

            else{
                $('#first_namecheck').hide();
                $('#first_name').css("border-color","");
                return true;
            }
        }
        //Validating last name field
        function last_namecheck(){
            var lastname_val = $('#last_name').val();
            if(lastname_val.length == ''){
                $('#last_namecheck').show();
                $('#last_namecheck').html("Please Enter Last Name");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color","red");
                $('#last_namecheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            }

            if(lastname_val.length > 30 ){
                $('#last_namecheck').show();
                $('#last_namecheck').html("Maximum 30 characters allowed");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color","red");
                $('#last_namecheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            }


            var pattern = new RegExp(/[^a-zA-Z0-9]/);
            if(pattern.test(lastname_val)){
                $('#last_namecheck').show();
                $('#last_namecheck').html("Special characters not allowed");
                $('#last_namecheck').focus();
                $('#last_name').css("border-color","red");
                $('#last_namecheck').css({"color":"red","margin-top":"5px"});
                // userErr =false;
                $('html, body').animate({

                    scrollTop: $("#last_namecheck").offset().top - 200
                }, 1000)
                return false;
            }

            else{
                $('#last_namecheck').hide();
                $('#last_name').css("border-color","");
                return true;
            }
        }

    //Validating email field
    function emailcheck(){

        var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
        if (pattern.test($('#mail').val())){
            $('#emailcheck').hide();
            $('#email').css("border-color","");
            return true;

        }
        else{
            $('#emailcheck').show();
            $('#emailcheck').html("Please Enter a valid email");
            $('#emailcheck').focus();
            $('#mail').css("border-color","red");
            $('#emailcheck').css({"color":"red","margin-top":"5px"});
            // mail_error = false;
            $('html, body').animate({
                scrollTop: $("#emailcheck").offset().top -200
            }, 1000)
        }

    }






    function countrycheck(){
        var country_val = $('#country').val();
        if(country_val == ''){
            $('#countrycheck').show();
            $('#countrycheck').html("Please Select One Country ");
            $('#countrycheck').focus();
            $('#country').css("border-color","red");
            $('#countrycheck').css({"color":"red","margin-top":"5px"});
            // userErr =false;
            $('html, body').animate({
                scrollTop: $("#countrycheck").offset().top - 200
            }, 1000)
        }
        else{
            $('#countrycheck').hide();
            $('#country').css("border-color","");
            return true;
        }
    }

    function mobile_codecheck(){
        var mobile_val = $('#phonenum').val();
        if(mobile_val.length == ''){
            $('#mobile_codecheck').show();
            $('#mobile_codecheck').html("Please Enter Mobile No. ");
            $('#mobile_codecheck').focus();
            $('#phonenum').css("border-color","red");
            $('#mobile_codecheck').css({"color":"red","margin-top":"5px"});
            // userErr =false;
            $('html, body').animate({
                scrollTop: $("#mobile_codecheck").offset().top -200
            }, 1000)
        }
        else{
            $('#mobile_codecheck').hide();
            $('#phonenum').css("border-color","");
            return true;
        }
    }


    //    $('#conpassword').keyup(function(){
    //     con_password_check();


    function terms(){
        var term_val = $('#term').val();
        if(term_val == 'false'){
            $('#termscheck').show();
            $('#termscheck').html("Terms must be accepted");
            $('#termscheck').focus();
            $('#term').css("border-color","red");
            $('#termscheck').css({"color":"red","margin-top":"5px"});
            // userErr =false;
            return false;
        }

        else{
            $('#termscheck').hide();
            $('#term').css("border-color","");
            return true;
        }
    }

    function gcaptcha(){
        var captcha_val = $('#g-recaptcha-response-1').val();
        if(captcha_val == ''){
            $('#captchacheck').show();
            $('#captchacheck').html("Robot Verification Failed, please try again");
            $('#captchacheck').focus();
            $('#captcha').css("border-color","red");
            $('#captchacheck').css({"color":"red","margin-top":"5px"});
            // userErr =false;
            return false;
        }

        else{
            $('#captchacheck').hide();
            $('#captcha').css("border-color","");
            return true;
        }
    }


    ////////////////////////Registration Valdation Ends////////////////////////////////////////////////////////////////////////////////////////////
    ///
    ///////////////////////VALIDATE TERMS AND CNDITION////////////////////////////////////////
    $(document).on('change','#term',function(){
        if($(this).val()=="false"){
            $(this).val("true");
        }
        else{
            $(this).val("false");
        }
    })
    //////////////////////////////Google Analytics Code after Submit button is clicked//////////////////
    function gtag_report_conversion(tag) {
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', tag);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    function registerUser(value) {
         this.value= value; 


        $('#first_namecheck').hide();
        $('#last_namecheck').hide();
        $('#emailcheck').hide();
        $('#countrycheck').hide();
        $('#mobile_codecheck').hide();
        $('#termscheck').hide();

         
        var first_nameErr = true;
        var last_nameErr = true;
        var emailErr = true;
        var countryErr = true;
        var mobile_codeErr = true;
        var termsErr = true;
        // con_password_check();

        if(first_namecheck() && last_namecheck() && emailcheck() &&   mobile_codecheck()  && countrycheck()  && terms() && gcaptcha())
        {

            var tag = "<?php echo $analyticsTag; ?>";
            if (tag !== "" ){
                gtag_report_conversion(tag);
            }

            $("#register").attr('disabled',true);
            $("#register").html("<i class='fas fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
            $.ajax({
                url: '{{url("auth/register")}}',
                type: 'POST',
                data: {
                    "first_name": $('#first_name').val(),
                    "last_name": $('#last_name').val(),
                    "email": $('#mail').val(),
                    "country": $('#country').val(),
                    "mobile_code": $('#mobile_code').val().replace(/\s/g, '') ,
                    "mobile": $('#phonenum').val().replace(/[\. ,:-]+/g, ''),
                    "g-recaptcha-response-1":$('#g-recaptcha-response-1').val(),
                    "terms": $('#term').val(),
                    "_token": "{!! csrf_token() !!}",
                    "value" : value,
                },
                success: function (response) {
                    // window.history.pushState(response.type, "TitleTest", "thankyou");

                    $("#register").attr('disabled',false);
                    if(response.type == 'success'){
                        $('.wizard-inner').css('display','block');
                        if($("#checkEmailStatus").val() == 0 && $("#checkOtpStatus").val() == 0) {
                            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Thank You! </strong>'+response.message+'!!</div>';
                            $('#alertMessage1').html(result);
                            window.scrollTo(0,0);
                            $("#register").html("Submit");
                        } else {
                            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Thank You! </strong>'+response.message+'!!</div>';
                            $('#successMessage1').html(result);
                            var $active = $('.wizard .nav-tabs li.active');
                            $active.next().removeClass('disabled');
                            nextTab($active);
                            window.scrollTo(0,0);
                            verifyForm.elements['user_id'].value = response.user_id;
                            if($("#emailstatusConfirm").val() == 1) {
                                var emailverfy = verifyForm.elements['verify_email'].value = $('#mail').val();
                                sessionStorage.setItem('oldemail',emailverfy);
                            }

                        }
                        verifyForm.elements['verify_country_code'].value =$('#mobile_code').val();
                        var numberverify= verifyForm.elements['verify_number'].value = $('#phonenum').val().replace(/[\. ,:-]+/g, '');
                        sessionStorage.setItem('oldenumber',numberverify);
                        verifyForm.elements['email_password'].value = $('#password').val();
                        $("#register").html("Register");
                        /*setTimeout(function(){
                            $('#alertMessage1').hide();
                        }, 3000);*/
                    }
                },
                error: function (data) {
                    $("#register").attr('disabled',false);
                    location.reload();
                    $("#register").html("Register");
                    $('html, body').animate({scrollTop:0}, 500);


                    var html = '<div class="alert alert-success alert-dismissable"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+data.responseJSON.message+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><br><ul>';
                    for (var key in data.responseJSON.errors)
                    {
                        html += '<li>' + data.responseJSON.errors[key][0] + '</li>'
                    }
                    html += '</ul></div>';

                    $('#error').show();
                    document.getElementById('error').innerHTML = html;
                    setInterval(function(){
                        $('#error').slideUp(3000);
                    }, 8000);
                }
            });
        }
        else{
            return false;
        }
    }




    //get login tab1



    $( document ).ready(function() {
        var printitem= localStorage.getItem('successmessage');
        if(printitem != null){
            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+printitem+'!</div>';
            $('#alertMessage2').html(result);
            localStorage.removeItem('successmessage');
            localStorage.clear();
        }

    });



</script>



<script>


    // console.log(state)
    $(document).ready(function () {
        var val = $("#country").val();
        getCountryAttr(val);
    });

    function getCountryAttr(val) {
        if(val!=""){

            getCode(val);
        }
     

       // getCurrency(val);

    }




    function getCode(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-code')}}",
            data: {'country_id':val,'_token':"{{csrf_token()}}"},//'country_id=' + val,
            success: function (data) {
                $("#mobile_code").val(data);
                $("#phone_code_hidden").val(data);
            }
        });
    }
    function getCurrency(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-currency')}}",
            data: {'country_id':val,'_token':"{{csrf_token()}}"},//'country_id=' + val,
            success: function (data) {
                $("#currency").val(data);
            }
        });
    }
</script>
<!-- Google Code for Help Desk Pro | Campaign 001 Conversion Page
In your html page, add the snippet and call
goog_report_conversion when someone clicks on the
chosen link or button. -->
<script type="text/javascript">
    //<![CDATA[
    goog_snippet_vars = function() {
        var w = window;
        w.google_conversion_id = 1027628032;
        w.google_conversion_label = "uBhoCLT3i3AQgLiB6gM";
        w.google_remarketing_only = false;
    }
    // DO NOT CHANGE THE CODE BELOW.
    goog_report_conversion = function(url) {
        goog_snippet_vars();
        window.google_conversion_format = "3";
        var opt = new Object();
        opt.onload_callback = function() {
            if (typeof(url) != 'undefined') {
                window.location = url;
            }
        }
        var conv_handler = window['google_trackConversion'];
        if (typeof(conv_handler) == 'function') {
            conv_handler(opt);
        }
        fbq('track', 'CompleteRegistration');
    }
    //]]>
</script>
<!-- Google Code for Help Desk Pro | Campaign 001 Conversion Page
In your html page, add the snippet and call
goog_report_conversion when someone clicks on the
chosen link or button. -->
<script type="text/javascript">
    //<![CDATA[
    goog_snippet_vars = function() {
        var w = window;
        w.google_conversion_id = 1027628032;
        w.google_conversion_label = "uBhoCLT3i3AQgLiB6gM";
        w.google_remarketing_only = false;
    }
    // DO NOT CHANGE THE CODE BELOW.
    goog_report_conversion = function(url) {
        goog_snippet_vars();
        window.google_conversion_format = "3";
        var opt = new Object();
        opt.onload_callback = function() {
            if (typeof(url) != 'undefined') {
                window.location = url;
            }
        }
        var conv_handler = window['google_trackConversion'];
        if (typeof(conv_handler) == 'function') {
            conv_handler(opt);
        }
        fbq('track', 'CompleteRegistration');
    }
    //]]>
</script>
<script type="text/javascript"
        src="//www.googleadservices.com/pagead/conversion_async.js">
</script>
<!-- Facebook Pixel Code -->
<!-- <script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '308328899511239');
fbq('track', 'PageView');

</script> -->

<script type="text/javascript"
        src="//www.googleadservices.com/pagead/conversion_async.js">
</script>
 <script>
        $(document).ready(function () {

            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

            //Initialize tooltips
            $('.nav-tabs > li a[title]').tooltip();
            $('.nav-tabs .active a[href="#step1"]').click(function(){
                $('.wizard-inner').css('display','none');
            })
            //Wizard
            if(!$('.nav-tabs .active a[href="#step1"]')){
                $('.wizard-inner').css('display','block');
            }
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

                var $target = $(e.target);

                if ($target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            /*$(".next-step").click(function (e) {
                $('.wizard-inner').show();
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
                window.scrollTo(0, 10);

            });*/

            $(".prev").click(function (e) {

                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                prevTab($active);
                $('.wizard-inner').css('display','block');
            });
        });

        function nextTab(elem) {

            $(elem).next().find('a[data-toggle="tab"]').click();
        }
        function prevTab(elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        }
    </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- <script>
    var input = document.querySelector("#phonenum"),
        errorMsg = document.querySelector("#error-msg"),
        validMsg = document.querySelector("#valid-msg");

    // here, the index maps to the error code returned from getValidationError - see readme
    var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

    // initialise plugin
    var iti = window.intlTelInput(input, {
        utilsScript: "../../build/js/utils.js?1638200991544"
    });

    var reset = function() {
        input.classList.remove("error");
        errorMsg.innerHTML = "";
        errorMsg.classList.add("hide");
        validMsg.classList.add("hide");
    };

    // on blur: validate
    input.addEventListener('blur', function() {
        reset();
        if (input.value.trim()) {
            if (iti.isValidNumber()) {
                validMsg.classList.remove("hide");
            } else {
                input.classList.add("error");
                var errorCode = iti.getValidationError();
                errorMsg.innerHTML = errorMap[errorCode];
                errorMsg.classList.remove("hide");
            }
        }
    });

    // on keyup / change flag: reset
    input.addEventListener('change', reset);
    input.addEventListener('keyup', reset);
</script> -->

<noscript>
    <img height="1" width="1"
         src="https://www.facebook.com/tr?id=308328899511239&ev=PageView
&noscript=1"/>
</noscript>
