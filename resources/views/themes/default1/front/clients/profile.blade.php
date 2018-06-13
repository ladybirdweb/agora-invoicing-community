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



<div class="row">

    <!--    <div class="col-md-12">
            <p class="lead">
                Check out all the tables options.
            </p>
        </div>-->
</div>
 <div id="alertMessage"></div>
 <div id="error"></div>

    <h2 class="mb-none"> My Profile</h2>
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
                            {!! Form::label('first_name',Lang::get('message.first_name')) !!}
                            {!! Form::text('first_name',null,['class' => 'form-control input-lg ','id'=>'firstName']) !!}
                           <h6 id="firstNameCheck"></h6>
                        </div>
                    </div>
                       <div class="form-row">
                        <div class="form-group col{{ $errors->has('last_name') ? 'has-error' : '' }}">
                            <!-- last name -->
                            {!! Form::label('last_name',Lang::get('message.last_name')) !!}
                            {!! Form::text('last_name',null,['class' => 'form-control input-lg ','id'=>'lastName']) !!}
                             <h6 id="lastNameCheck"></h6>
                        </div>
                    </div>
                      
                         <div class="form-row">
                        <div class="form-group col">
                            <!-- email -->
                            {!! Form::label('email',Lang::get('message.email')) !!}
                             {!! Form::text('email',null,['class' => 'form-control input-lg ','id'=>'Email']) !!}
                            <h6 id="emailCheck"></h6>
                        </div>
                    </div>
                          <div class="form-row">
                        <div class="form-group col {{ $errors->has('company') ? 'has-error' : '' }}">
                            <!-- company -->
                            {!! Form::label('company',Lang::get('message.company')) !!}
                            {!! Form::text('company',null,['class' => 'form-control input-lg','id'=>'Company']) !!}
                            <h6 id="companyCheck"></h6>
                        </div>
                    </div> 
                         
                          <div class="form-row">
                        <div class="form-group col {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                        <label class="required">Country code</label>
                        <!-- <input class="form-control input-lg" id="mobile_code" name="mobile_code" type="text"> -->
                        
                        {!! Form::text('mobile_code',null,['class'=>'form-control input-lg','id'=>'mobile_code']) !!}
                          <h6 id="mobileCodeCheck"></h6>
                    </div>
                </div> 
                         <div class="form-row">
                        <div class="form-group col {{ $errors->has('mobile') ? 'has-error' : '' }}">
                            
                            <!-- mobile -->
                            {!! Form::label('mobile',Lang::get('message.mobile')) !!}
                            {!! Form::text('mobile',null,['class' => 'form-control input-lg','id'=>'mobile']) !!}
                              <h6 id="mobileCheck"></h6>
                        </div>
                    </div>
                        
                         <div class="form-row">
                        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                            <!-- phone number -->
                            {!! Form::label('address',Lang::get('message.address')) !!}
                            {!! Form::textarea('address',null,['class' => 'form-control input-lg','id'=>'Address']) !!}
                               <h6 id="addressCheck"></h6>
                        </div>
                         </div>


                        <div class="form-row">
                            <div class="form-group col-md-6 {{ $errors->has('town') ? 'has-error' : '' }}">
                                <!-- mobile -->
                                {!! Form::label('town',Lang::get('message.town')) !!}
                                {!! Form::text('town',null,['class' => 'form-control input-lg','id'=>'Town']) !!}
                                 <h6 id="townCheck"></h6>
                            </div>
                            <div class="form-group col-md-6 {{ $errors->has('timezone_id') ? 'has-error' : '' }}">
                                <!-- mobile -->
                                {!! Form::label('timezone_id',Lang::get('message.timezone')) !!}
                                {!! Form::select('timezone_id',[''=>'Select','Timezones'=>$timezones],null,['class' => 'form-control input-lg','id'=>'timezone']) !!}
                                <h6 id="timezoneCheck"></h6>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="col-md-6 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                                <!-- name -->
                                {!! Form::label('country',Lang::get('message.country')) !!}
                                 <?php $countries = \App\Model\Common\Country::pluck('country_name', 'country_code_char2')->toArray(); ?>
                                {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control input-lg ','id'=>'Country','onChange'=>'getState(this.value);']) !!}
                               <h6 id="countryCheck"></h6>
                            </div>
                            <div class="col-md-6 form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                                {!! Form::label('state',Lang::get('message.state')) !!}
                              
                                <select name="state" id="stateList" class="form-control input-lg ">
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
                            <!-- mobile -->
                            {!! Form::label('zip',Lang::get('message.zip')) !!}
                            {!! Form::text('zip',null,['class' => 'form-control input-lg','id'=>'Zip']) !!}
                             <h6 id="zipCheck"></h6>
                        </div>
                    </div>

                         <div class="form-row">
                        <div class="form-group {{ $errors->has('profile_pic') ? 'has-error' : '' }}">
                            <!-- profile pic -->
                            {!! Form::label('profile_pic',Lang::get('message.profile-picture')) !!}
                            {!! Form::file('profile_pic',['id'=>'profilePic']) !!}
                            <h6 id="profilePicCheck"></h6>
                              <h6 id="oldpasswordcheck"></h6>
                        </div>
                    </div> 

                        <div class="form-row">
                           <div class="form-group col">
                             <button type="button"  class="btn btn-primary mb-xl next-step float-right" data-loading-text="Loading..." name="update" id="update" onclick="updateProfile()" >Submit</button>
                           
                               
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
                            {!! Form::label('old_password',Lang::get('message.old_password')) !!}
                            {!! Form::password('old_password',['class' => 'form-control input-lg','id'=>'old_password']) !!}
                            <h6 id="oldpasswordcheck"></h6>
                           
                        </div>
                    </div>
                        <!-- new password -->
                        <div class="form-row">
                        <div class="form-group col has-feedback {{ $errors->has('new_password') ? 'has-error' : '' }}">
                            {!! Form::label('new_password',Lang::get('message.new_password')) !!}
                            {!! Form::password('new_password',['class' => 'form-control input-lg','id'=>'new_password']) !!}
                           
                            <h6 id="newpasswordcheck"></h6>
                        </div>
                    </div>
                        <!-- cofirm password -->
                        <div class="form-row">
                        <div class="form-group col has-feedback {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                            {!! Form::label('confirm_password',Lang::get('message.confirm_password')) !!}
                            {!! Form::password('confirm_password',['class' => 'form-control input-lg','id'=>'confirm_password']) !!}
                            <h6 id ="confirmpasswordcheck"></h6>
                           
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12">
                                 <button type="button"  class="btn btn-primary mb-xl next-step float-right" data-loading-text="Loading..." name="update" id="password" onclick="updatePassword()" >Update</button>
                              
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

    </div>



<script>

                  
 function updateProfile() 
                        {  
                            
                          $("#update").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Updating...");
                                    var data = {
                                        "first_name":   $('#firstName').val(),
                                        "last_name" :    $('#lastName').val(),
                                        "email":  $('#Email').val(),
                                        "company":  $('#Company').val(),
                                        "mobile_code": $('#mobile_code').val(),
                                        "mobile": $('#mobile').val(),
                                        "address" : $('#Address').val(),
                                        "town" : $('#Town').val(),
                                        "timezone_id" : $('#timezone').val(),
                                        "country":$('#Country').val(),
                                        "state" : $('#stateList').val(),
                                        "zip"   : $('#Zip').val(),
                                        "profile_pic": $('#profilePic').val(),

                                                             
                                    };
                                    $.ajax({
                                        url: '{{url('my-profile')}}',
                                        type: 'PATCH',
                                        data: data,
                                        success: function (response) {
                                            console.log(response)
                                        
                                        if(response.type == 'success'){
                                             var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                                              $('#error').hide();
                                            $('#alertMessage').html(result);
                                            // $('#alertMessage2').html(result);
                                            $("#update").html("Update");
                                              $('html, body').animate({scrollTop:0}, 1000);
                                          
                                              // response.success("Success");
                                           }  
                                        },
                                        error: function (data) {
                                          console.log(data)
                                             var html = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>'+data.responseJSON.message+' <br><ul>';
                                            $("#update").html("Update");
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
    var telInput = $('#mobile_code');
     let currentCountry="";
    telInput.intlTelInput({
        initialCountry: "auto",
        geoIpLookup: function (callback) {
            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
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


  

    function getState(val) {


        $.ajax({
            type: "GET",
              url: "{{url('get-state')}}/" + val,
            data: 'country_id=' + val,
            success: function (data) {
                $("#stateList").html(data);
            }
        });
    }


    
</script>
@stop


