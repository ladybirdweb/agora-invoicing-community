@extends('themes.default1.layouts.front.master')
@section('title')
Contact Us
@stop
@section('page-header')
Cart
@stop
@section('page-heading')
What can we help you with?
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">Contact Us</li>
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
 $analyticsTag = App\Model\Common\ChatScript::where('google_analytics', 1)->where('on_registration', 1)->value('google_analytics_tag');
?>

<div class="row">
    <div class="col-md-6">

       <h2 class="mb-3 mt-2"><strong>Contact</strong> Us</h2>
        {!! Form::open(['url'=>'contact-us']) !!}
            <div class="form-row">
                <div class="form-group col-lg-6">
                  
                        <label class="required">Your name</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required>
                    </div>
                   <div class="form-group col-lg-6">
                        <label class="required">Your email address</label>
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email" required>
                    </div>
                </div>
           
            <div class="form-row">
                    <div class="form-group col">
                        <label class="required">Mobile No</label>
                        {!! Form::hidden('mobile',null,['id'=>'con_code_hidden']) !!}
                        <input type="tel" value=""  id="connumber" data-msg-required="Please enter the mobile No." maxlength="10" class="form-control" name="Mobile"  required>
                         <span id="convalid-msg" class="hide"></span>
                         <span id="conerror-msg" class="hide"></span>
                        <span id="con_codecheck"></span>
                       
                    
                </div>
                
              
            </div>
           <div class="form-row">
               <div class="form-group col">
                    
                        <label class="required">Message</label>
                        <textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message" required></textarea>
                    </div>
                
            </div>
              <div class="form-row">
                <div class="form-group col">
                    <input type="submit" value="Send Message" id="submit" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
                </div>
            </div>
        {!! Form::close() !!}
    </div>
    <div class="col-md-6">

        <hr>

        <h4 class="heading-primary">Our Office</h4>
       <ul class="list list-icons list-icons-style-3 mt-4">
                                <li><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> {{$set->address}}</li>
                                <li><i class="fas fa-phone"></i> <strong>Phone:</strong> {{$set->phone}}</li>
                                <li><i class="far fa-envelope"></i> <strong>Email:</strong> <a href="mailto:{{$set->company_email}}">{{$set->company_email}}</a></li>
                            </ul>
        <hr>

      <!--  <h4 class="heading-primary">Business <strong>Hours</strong></h4>
                            <ul class="list list-icons list-dark mt-4">
                                <li><i class="far fa-clock"></i> Monday to Friday 09:30AM to 06:30PM IST</li>
                                
                            </ul> -->

    </div>

</div>
@stop
@section('script')

<script type="text/javascript">


// get the country data from the plugin
     $(document).ready(function(){


    var intelInput = $('#connumber');
    conaddressDropdown = $("#country");
    conerrorMsg = document.querySelector("#conerror-msg"),
    convalidMsg = document.querySelector("#convalid-msg");
    var conerrorMap = [ "Invalid number", "Invalid country code", "Number Too short", "Number Too long", "Invalid number"];
    intelInput.intlTelInput({
        initialCountry: "auto",
        geoIpLookup: function (callback) {

            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {

                var countryCode = (resp && resp.concountry) ? resp.concountry : "";
                    currentCountry=countryCode.toLowerCase()
                    callback(countryCode);
            });
        },
        separateDialCode: true,
      utilsScript: "{{asset('js/intl/js/utils.js')}}"
    });
     var reset = function() {
      conerrorMsg.innerHTML = "";
      conerrorMsg.classList.add("hide");
      convalidMsg.classList.add("hide");
    };
    setTimeout(()=>{
         intelInput.intlTelInput("setCountry", currentCountry);
    },500)
     $('.intl-tel-input').css('width', '100%');
    intelInput.on('blur', function () {
        reset();
        if ($.trim(intelInput.val())) {
            if (intelInput.intlTelInput("isValidNumber")) {
              $('#connumber').css("border-color","");
              convalidMsg.classList.remove("hide");
              $('#submit').attr('disabled',false);
            } else {
              var conerrorCode = intelInput.intlTelInput("getValidationError");
             conerrorMsg.innerHTML = conerrorMap[conerrorCode];
             $('#connumber').css("border-color","red");
             $('#conerror-msg').css({"color":"red","margin-top":"5px"});
            conerrorMsg.classList.remove("hide");
             $('#submit').attr('disabled',true);
            }
        }
    });

     conaddressDropdown.change(function() {
     intelInput.intlTelInput("setCountry", $(this).val());
             reset();
             if ($.trim(intelInput.val())) {
            if (intelInput.intlTelInput("isValidNumber")) {
              $('#connumber').css("border-color","");
              conerrorMsg.classList.add("hide");
              $('#submit').attr('disabled',false);
            } else {
              var conerrorCode = intelInput.intlTelInput("getValidationError");
             conerrorMsg.innerHTML = conerrorMap[conerrorCode];
             $('#connumber').css("border-color","red");
             $('#conerror-msg').css({"color":"red","margin-top":"5px"});
             cerrorMsg.classList.remove("hide");
             $('#submit').attr('disabled',true);
            }
        }
    });
    $('input').on('focus', function () {
        $(this).parent().removeClass('has-error');
    });

    $('form').on('submit', function (e) {
        $('input[name=sds]').attr('value', $('.selected-dial-code').text());
    });


           


});
       function getCode(val) {
            $.ajax({
                type: "GET",
                url: "{{url('get-code')}}",
                data: {'country_id':val,'_token':"{{csrf_token()}}"},//'country_id=' + val,
                success: function (data) {
                    // $("#con_code").val(data);
                    $("#con_code_hidden").val(data);
                }
            });
        }
    </script>


 


@stop