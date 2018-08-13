@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Agora | Profile
@stop
@section('nav-profile')
active
@stop
@section('page-heading')
 <h1>My Account </h1>
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">My Account</li>
<li class="active">Profile</li>
@stop
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<style>
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
.bootstrap-select.btn-group .dropdown-toggle .filter-option {
    color:#555;
}
</style>


              @if(Session::has('success'))
                <div class="alert alert-success">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <strong><i class="far fa-thumbs-up"></i> Well done!</strong>
                   
                    {!!Session::get('success')!!}
                </div>
                @endif
                <!-- fail message -->
                @if(Session::has('fails'))
                 <div class="alert alert-danger alert-dismissable" role="alert">
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.
                   {{Lang::get('message.alert')}}! {{Lang::get('message.failed')}}.
                  
                    {{Session::get('fails')}}
                </div>
                @endif
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
                            <label for "first_name" class="required"><b>First Name</b></label>
                            <!-- <b>{!! Form::label('first_name',Lang::get('message.first_name')) !!}</b> -->
                            {!! Form::text('first_name',null,['class' => 'form-control input-lg ','id'=>'firstName']) !!}
                           <h6 id="firstNameCheck"></h6>
                        </div>
                    </div>
                       <div class="form-row">
                        <div class="form-group col{{ $errors->has('last_name') ? 'has-error' : '' }}">
                            <!-- last name -->
                             <label for "last_name" class="required"><b>Last Name</b></label>
                            {!! Form::text('last_name',null,['class' => 'form-control input-lg ','id'=>'lastName']) !!}
                             <h6 id="lastNameCheck"></h6>
                        </div>
                    </div>
                      
                         <div class="form-row">
                       <div class="form-group col{{ $errors->has('email') ? 'has-error' : '' }}">
                             <label for "email" class="required"><b>Email</b></label>
                            <!-- email -->
                            {!! Form::text('email',null,['class' => 'form-control input-lg ','id'=>'Email']) !!}
                            <h6 id="emailCheck"></h6>
                        </div>
                    </div>
                          <div class="form-row">
                        <div class="form-group col {{ $errors->has('company') ? 'has-error' : '' }}">
                            <!-- company -->
                            <label for "company" class=""><b>Company</b></label>
                            {!! Form::text('company',null,['class' => 'form-control input-lg','id'=>'Company']) !!}
                            <h6 id="companyCheck"></h6>
                        </div>
                    </div> 
                         
                          <div class="form-row">
                        <div class="form-group col {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                        <label class="required"><b>Country code</b></label>
                        <!-- <input class="form-control input-lg" id="mobile_code" name="mobile_code" type="text"> -->
                        {!! Form::text('mobile_code',null,['class'=>'form-control input-lg','id'=>'mobile_code']) !!}
                          <h6 id="mobileCodeCheck"></h6>
                    </div>
                </div> 
                         <div class="form-row">
                        <div class="form-group col {{ $errors->has('mobile') ? 'has-error' : '' }}">
                            
                            <!-- mobile -->
                            <label for"mobile" class="required"><b>Mobile</b></label>
                            {!! Form::text('mobile',null,['class' => 'form-control input-lg','id'=>'mobile']) !!}
                              <h6 id="mobileCheck"></h6>
                        </div>
                    </div>
                        
                         <div class="form-row">
                        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                            <!-- phone number -->
                            <label for"address" class="required"><b>Address</b></label>
                            {!! Form::textarea('address',null,['class' => 'form-control input-lg','id'=>'Address']) !!}
                               <h6 id="addressCheck"></h6>
                        </div>
                         </div>


                        <div class="form-row">
                            <div class="form-group col-md-6 {{ $errors->has('town') ? 'has-error' : '' }}">
                                <!-- mobile -->
                                 <label for"town" class="required"><b>Town</b></label>
                                {!! Form::text('town',null,['class' => 'form-control input-lg','id'=>'Town']) !!}
                                 <h6 id="townCheck"></h6>
                            </div>
                            <div class="form-group col-md-6 {{ $errors->has('timezone_id') ? 'has-error' : '' }}">
                                <!-- mobile -->
                                 <label for"timezone_id" class=""><b>Timezne</b></label>
                                {!! Form::select('timezone_id',[Lang::get('message.choose')=>$timezones],null,['class' => 'form-control input-lg','id'=>'timezone']) !!}

                               <!--  {!! Form::select('timezone_id', [Lang::get('message.choose')=>$timezones],null,['class' => 'form-control selectpicker','data-live-search'=>'true','required','data-live-search-placeholder' => 'Search','data-dropup-auto'=>'false','data-size'=>'10','id'=>'timezone']) !!}
 -->
                                <h6 id="timezoneCheck"></h6>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="col-md-6 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                                <!-- name -->
                              <label for"country" class="required"><b>Country</b></label>
                                 <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>
                                {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control input-lg ','id'=>'country','onChange'=>'getCountryAttr(this.value);']) !!}


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
                            <label for"zip" class="required"><b>Zip/Postal Code</b></label>
                            {!! Form::text('zip',null,['class' => 'form-control input-lg','id'=>'Zip']) !!}
                             <h6 id="zipCheck"></h6>
                        </div>
                    </div>

                         <div class="form-row">
                        <div class="form-group {{ $errors->has('profile_pic') ? 'has-error' : '' }}">
                            <!-- profile pic -->
                             <label for"profile_pic" class=""><b>Profile Picture</b></label>
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
                            <label for"old_password" class="required"><b>Old Password</b></label>
                            {!! Form::password('old_password',['class' => 'form-control input-lg','id'=>'old_password']) !!}
                            <h6 id="oldpasswordcheck"></h6>
                           
                        </div>
                    </div>
                        <!-- new password -->
                        <div class="form-row">
                        <div class="form-group col has-feedback {{ $errors->has('new_password') ? 'has-error' : '' }}">
                            <label for"new_password" class="required"><b>New Password</b></label>
                            {!! Form::password('new_password',['class' => 'form-control input-lg','id'=>'new_password']) !!}
                           
                            <h6 id="newpasswordcheck"></h6>
                        </div>
                    </div>
                        <!-- cofirm password -->
                        <div class="form-row">
                        <div class="form-group col has-feedback {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                            <label for"confirm_password" class="required"><b>Confirm Password</b></label>
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
                                            console.log(response)
                                        
                                        if(response.type == 'success'){
                                             var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                                              $('#error').hide();
                                            $('#alertMessage').html(result);
                                            // $('#alertMessage2').html(result);
                                            $("#password").html("Update");
                                              $('html, body').animate({scrollTop:0}, 1000);
                                          
                                              // response.success("Success");
                                           }  
                                        },
                                        error: function (data) {
                                          console.log(data)
                                             var html = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+data.responseJSON.message+' <br><ul>';
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
<script src="{{asset("lb-faveo/js/intlTelInput.js")}}"></script>
<script type="text/javascript">
       $(document).ready(function(){ 
         var country = $('#country').val();
         console.log(country);
    var telInput = $('#mobile_code');
     let currentCountry="";
    telInput.intlTelInput({
        initialCountry: "auto",
        geoIpLookup: function (callback) {
            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp){
            var countryCode = (resp && resp.country) ? resp.country : "";
                    currentCountry=countryCode.toLowerCase()
                    callback(countryCode);
            });
        },
        separateDialCode: false,
        utilsScript: "{{asset('js/intl/js/utils.js')}}",
    });
    setTimeout(()=>{
         telInput.intlTelInput("setCountry", currentCountry);
    },500)
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
                $("#mobile_code").val(data);
                $("#mobile_code_hidden").val(data);
            }
        });
    }

    
</script>
@stop


