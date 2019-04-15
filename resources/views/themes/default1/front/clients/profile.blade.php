@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Profile
@stop
@section('nav-profile')
active
@stop
@section('page-heading')
 <h1>My Account </h1>
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">My Account</li>
<li class="active">Profile</li>
@stop
@section('content')
<style>
  
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }


        .bootstrap-select.btn-group .dropdown-menu li a {
    margin-left: -12px !important;
}
 .btn-group>.btn:first-child {
    margin-left: 0;
    background-color: white;

   }
   .open>.dropdown-menu {
  display: block;
}
.bootstrap-select.btn-group .dropdown-toggle .filter-option {
    color:#555;
}
</style>
 <link rel="stylesheet" href="{{asset('client/css/selectpicker.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


     <div id= "alertMessage"></div>
     <div id= "error"></div>        
    <h2 class="mb-none" style="margin-bottom:0px;"> My Profile</h2>
    <div class="featured-boxes">

        <div class="row">
            <div class="col-md-6">
                <div class="featured-box featured-box-primary text-left mt-3 mt-md-5">
                    <div class="box-content">

                        <h4 class="heading-primary text-uppercase mb-3">Edit Profile</h4>
                      {!! Form::model($user,['url'=>'my-profile', 'method' => 'PATCH','files'=>true]) !!}
                        <div class="form-row">
                        <div class="form-group col{{ $errors->has('first_name') ? 'has-error' : '' }}">
                            <!-- first name -->
                            <label for "first_name" class="required">First Name</label>
                            <!-- <b>{!! Form::label('first_name',Lang::get('message.first_name')) !!}</b> -->
                            {!! Form::text('first_name',null,['class' => 'form-control input-lg ','id'=>'firstName']) !!}
                           <h6 id="firstNameCheck"></h6>
                        </div>
                    </div>
                       <div class="form-row">
                        <div class="form-group col{{ $errors->has('last_name') ? 'has-error' : '' }}">
                            <!-- last name -->
                             <label for "last_name" class="required">Last Name</label>
                            {!! Form::text('last_name',null,['class' => 'form-control input-lg ','id'=>'lastName']) !!}
                             <h6 id="lastNameCheck"></h6>
                        </div>
                    </div>
                      
                         <div class="form-row">
                       <div class="form-group col{{ $errors->has('email') ? 'has-error' : '' }}">
                             <label for "email" class="required">Email</label>
                            <!-- email -->
                            {!! Form::text('email',null,['class' => 'form-control input-lg ','id'=>'Email']) !!}
                            <h6 id="emailCheck"></h6>
                        </div>
                    </div>
                          <div class="form-row">
                        <div class="form-group col {{ $errors->has('company') ? 'has-error' : '' }}">
                            <!-- company -->
                            <label for "company" class="">Company</label>
                            {!! Form::text('company',null,['class' => 'form-control input-lg','id'=>'Company']) !!}
                            <h6 id="companyCheck"></h6>
                        </div>
                    </div> 
                    <div class="form-row">
                     <div class="form-group col {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                      {!! Form::label('mobile',null,['class' => 'required'],Lang::get('message.mobile'),['class'=>'required']) !!}
                         {!! Form::hidden('mobile_code',null,['id'=>'mobile_code_hidden']) !!}
                          <input class="form-control selected-dial-code"  id="mobile_code" value="{{$user->mobile}}" name="mobile" type="tel">
                           <!-- {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!} -->
                           <span id="valid-msg" class="hide"></span>
                           <span id="error-msg" class="hide"></span>
                        <!-- {!! Form::text('mobil',null,['class'=>'form-control', 'id'=>'mobile_code']) !!} -->
                    </div>


                    </div>
                         
                     
                        
                        
                         <div class="form-row">
                        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                            <!-- phone number -->
                            <label for"address" class="required">Address</label>
                            {!! Form::textarea('address',null,['class' => 'form-control input-lg','id'=>'Address']) !!}
                               <h6 id="addressCheck"></h6>
                        </div>
                         </div>


                        <div class="form-row">
                            <div class="form-group col-md-6 {{ $errors->has('town') ? 'has-error' : '' }}">
                                <!-- mobile -->
                                 <label for"town" class="required">Town</label>
                                {!! Form::text('town',null,['class' => 'form-control input-lg','id'=>'Town']) !!}
                                 <h6 id="townCheck"></h6>
                            </div>
                            <div class="form-group col-md-6 {{ $errors->has('timezone_id') ? 'has-error' : '' }}">
                                <!-- mobile -->
                                 <label for"timezone_id" class="">Timezone</label>
                                {!! Form::select('timezone_id',[Lang::get('message.choose')=>$timezones],null,['class' => 'form-control input-lg','id'=>'timezone']) !!}

                               <!--  {!! Form::select('timezone_id', [Lang::get('message.choose')=>$timezones],null,['class' => 'form-control selectpicker','data-live-search'=>'true','required','data-live-search-placeholder' => 'Search','data-dropup-auto'=>'false','data-size'=>'10','id'=>'timezone']) !!}
 -->
                                <h6 id="timezoneCheck"></h6>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="col-md-6 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                                <!-- name -->
                              <label for"country" class="required">Country</label>
                                 <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>
                                {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control input-lg selectpicker','data-live-search-style'=>"startsWith",'data-live-search'=>'true','data-live-search-placeholder'=>'Search','data-dropup-auto'=>'false','data-size'=>'10','id'=>'country','onChange'=>'getCountryAttr(this.value);']) !!}


                               <h6 id="countryCheck"></h6>
                            </div>
                            <div class="col-md-6 form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                               <label for"state" class=""><b>State</b></label>
                              
                                <select name="state" id="state-list" class="form-control input-lg ">
                                    @if(count($state)>0)
                                    <option value="{{$state['id']}}">{{$state['name']}}</option>
                                    @endif
                                    <option value="">Select State</option>
                                    @foreach($states as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                    
                                </select>
                              <h6 id="stateCheck"></h6>
                            </div>


                        </div>
                         <div class="form-row">
                        <div class="form-group col {{ $errors->has('zip') ? 'has-error' : '' }}">
                            <label for"zip" class="required">Zip/Postal Code</label>
                            {!! Form::text('zip',null,['class' => 'form-control input-lg','id'=>'Zip']) !!}
                             <h6 id="zipCheck"></h6>
                        </div>
                    </div>

                         <div class="form-row">
                        <div class="form-group {{ $errors->has('profile_pic') ? 'has-error' : '' }}">
                            <!-- profile pic -->
                             <label for"profile_pic" class="">Profile Picture</label>
                            {!! Form::file('profile_pic',['id'=>'profilePic']) !!}
                            <h6 id="profilePicCheck"></h6>
                             
                        </div>
                    </div> 

                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right" id="submit" style="margin-top:-30px;"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
                            </div>
                        </div>
                         {!! Form::close() !!}
                      
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="featured-box featured-box-primary text-left mt-3 mt-md-5">
                    <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-3">Change Password</h4>
                        {!! Form::model($user,['url'=>'my-password' , 'method' => 'PATCH']) !!}
                        
                        <!-- old password -->
                        <div class="form-row">
                        <div class="form-group col {{ $errors->has('old_password') ? 'has-error' : '' }}">
                            <label for"old_password" class="required">Old Password</label>
                            {!! Form::password('old_password',['class' => 'form-control input-lg','id'=>'old_password']) !!}
                            <h6 id="oldpasswordcheck"></h6>
                           
                        </div>
                    </div>
                        <!-- new password -->
                        <div class="form-row">
                        <div class="form-group col has-feedback {{ $errors->has('new_password') ? 'has-error' : '' }}">
                            <label for"new_password" class="required">New Password</label>
                            {!! Form::password('new_password',['class' => 'form-control input-lg','id'=>'new_password']) !!}
                           
                            <h6 id="newpasswordcheck"></h6>
                        </div>
                    </div>
                        <!-- cofirm password -->
                        <div class="form-row">
                        <div class="form-group col has-feedback {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                            <label for"confirm_password" class="required">Confirm Password</label>
                            {!! Form::password('confirm_password',['class' => 'form-control input-lg','id'=>'confirm_password']) !!}
                            <h6 id ="confirmpasswordcheck"></h6>
                           
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12">
                                 <button type="button"  class="btn btn-primary float-right" data-loading-text="Loading..." name="update" id="password" onclick="updatePassword()" > <i class="fa fa-refresh"></i>&nbsp;Update</button>
                              
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

                    <script>

                //Password Validation
                   function oldpasswordcheck(){
                    var oldpassword_val = $('#old_password').val();
                    if(oldpassword_val.length == ''){
                        $('#oldpasswordcheck').show();
                        $('#oldpasswordcheck').html("This field is Required");
                        $('#oldpasswordcheck').focus();
                        $('#old_password').css("border-color","red");
                        $('#oldpasswordcheck').css({"color":"red","margin-top":"5px"});
                        // userErr =false;
                       
                       
                     
                    }
                   
                    else{
                         $('#oldpasswordcheck').hide();
                         $('#old_password').css("border-color","");
                         return true;
                    }
                   }
                    
              function newpasswordcheck(){
              var pattern = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/);
              if (pattern.test($('#new_password').val())){
                 $('#newpasswordcheck').hide();
                  $('#new_password').css("border-color","");
                 return true;
               
              }
              else{
                 $('#newpasswordcheck').show();
                $('#newpasswordcheck').html("Password must contain Uppercase/Lowercase/Special Character and Number");
                 $('#newpasswordcheck').focus();
                $('#new_password').css("border-color","red");
                $('#newpasswordcheck').css({"color":"red","margin-top":"5px"});

                   // mail_error = false;
                return false;
                
              }

            }

                 function confirmpasswordcheck(){
        var confirmPassStore= $('#confirm_password').val();
         var passwordStore = $('#new_password').val();
         if(confirmPassStore != passwordStore){
            $('#confirmpasswordcheck').show();
            $('#confirmpasswordcheck').html("Passwords Don't Match");
            $('#confirmpasswordcheck').focus();
             $('#confirm_password').css("border-color","red");
            $('#confirmpasswordcheck').css("color","red");
         
         }
        else{
             $('#confirmpasswordcheck').hide();
             $('#confirm_password').css("border-color","");
               return true;
        }
  }



               function updatePassword()
             {
                 $('#oldpasswordcheck').hide();
                   $('#newpasswordcheck').hide();
                    $('#confirmpasswordcheck').hide();
                    if(oldpasswordcheck() && newpasswordcheck() && confirmpasswordcheck() ){
                $("#password").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Updating...");
                 var data = {
                                        "old_password":   $('#old_password').val(),
                                        "new_password" :    $('#new_password').val(),
                                        "confirm_password":  $('#confirm_password').val(),
                                       
                                                             
                            };
                                $.ajax({
                                        url: '{{url('my-password')}}',
                                        type: 'PATCH',
                                        data: data,
                                        success: function (response) {
                                        if(response.type == 'success'){
                                             var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                                              $('#error').hide();
                                            $('#alertMessage').html(result);
                                            // $('#alertMessage2').html(result);
                                            $("#password").html("Update");
                                              $('html, body').animate({scrollTop:0}, 1000);
                                          
                                              // response.success("Success");
                                           } else {
                                             var result =  '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fas fa-exclamation-triangle"></i>Whoops! Something went wrong..</strong>'+response.message+'!</div>';
                                              $('#error').html(result);
                                            $('#alertMessage').hide();
                                            // $('#alertMessage2').html(result);
                                            $("#password").html("Update");
                                              $('html, body').animate({scrollTop:0}, 1000);
                                           }  
                                        },
                                        error: function (data) {
                                          console.log(data,'sd')
                                             var html = '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+data.responseJSON.message+' <br><ul>';
                                            $("#password").html("Update");
                                              $('html, body').animate({scrollTop:0}, 500);
                                              for (var key in data.responseJSON.errors)
                                            {
                                                html += '<li>' + data.responseJSON.errors[key][0] + '</li>'
                                            }
                                            html += '</ul></div>';
                                           $('#alertMessage').hide(); 
                                            
                                            $('#error').show();
                                             document.getElementById('error').innerHTML = html;
                                           
                                        }
                                    });
                            }
                            else{
                                return false;
                            }
             } 

                                </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{asset('common/js/intlTelInput.js')}}"></script>
<script type="text/javascript">
     $(document).ready(function(){

    var country = $('#country').val();
    var telInput = $('#mobile_code'),
     addressDropdown = $("#country");
     errorMsg = document.querySelector("#error-msg"),
    validMsg = document.querySelector("#valid-msg");
    var errorMap = [ "Invalid number", "Invalid country code", "Number Too short", "Number Too long", "Invalid number"];
     let currentCountry="";
    telInput.intlTelInput({
        initialCountry: "auto",
        geoIpLookup: function (callback) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                resp.country = country;
                var countryCode = (resp && resp.country) ? resp.country : "";
                    currentCountry=countryCode.toLowerCase()
                    callback(countryCode);
            });
        },
          separateDialCode: true,
          utilsScript: "js/intl/js/utils.js"
    });
      var reset = function() {
      errorMsg.innerHTML = "";
      errorMsg.classList.add("hide");
      validMsg.classList.add("hide");
    };
    setTimeout(()=>{
         telInput.intlTelInput("setCountry", currentCountry);
    },500)
    $('.intl-tel-input').css('width', '100%');
    telInput.on('blur', function () {
      reset();
        if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
              $('#mobile_code').css("border-color","");
              validMsg.classList.remove("hide");
              $('#submit').attr('disabled',false);
            } else {
              var errorCode = telInput.intlTelInput("getValidationError");
             errorMsg.innerHTML = errorMap[errorCode];
             $('#mobile_code').css("border-color","red");
             $('#error-msg').css({"color":"red","margin-top":"5px"});
             errorMsg.classList.remove("hide");
             $('#submit').attr('disabled',true);
            }
        }
    });

    addressDropdown.change(function() {
     telInput.intlTelInput("setCountry", $(this).val());
       if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
              $('#mobile_code').css("border-color","");
               errorMsg.classList.add("hide");
              $('#submit').attr('disabled',false);
            } else {
              var errorCode = telInput.intlTelInput("getValidationError");
             errorMsg.innerHTML = errorMap[errorCode];
             $('#mobile_code').css("border-color","red");
             $('#error-msg').css({"color":"red","margin-top":"5px"});
             errorMsg.classList.remove("hide");
             $('#submit').attr('disabled',true);
            }
        }
    });
    $('input').on('focus', function () {
        $(this).parent().removeClass('has-error');
    });

    $('form').on('submit', function (e) {
        $('input[name=mobile]').attr('value', $('.selected-dial-code').text());
    });
});

   function getCountryAttr(val) {
        getState(val);
        getCode(val);
//        getCurrency(val);

    }

  

       function getState(val) {


        $.ajax({
            type: "GET",
              url: "{{url('get-state')}}/" + val,
            data: 'country_id=' + val,
            success: function (data) {
                $("#state-list").html(data);
            }
        });
    }
    function getCode(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-code')}}",
            data: 'country_id=' + val,
            success: function (data) {
                // $("#mobile_code").val(data);
                $("#mobile_code_hidden").val(data);
            }
        });
    }

    
</script>
@stop


