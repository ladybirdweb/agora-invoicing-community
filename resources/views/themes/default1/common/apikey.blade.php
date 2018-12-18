@extends('themes.default1.layouts.master')
@section('title')
Api Key
@stop
@section('content-header')
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<h1>
API Keys
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
         <li class="active">Api Key</li>
      </ol>
@stop
@section('content')
     <section class="content">
         <div class="row">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Api Keys Settings</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            
              <table class="table table-striped ">
                <tr>
                 
                  <th>Options</th>
                  <th>Status</th>
                   <th>Fields</th>
                  <th>Action</th>
                </tr>
                   <div id="alertMessage"></div>
                <tr>
                  
                  <td>Auto PHP Licenser</td>
                  <td>
                    <label class="switch toggle_event_editing">
                          
                         <input type="checkbox" value="{{$status}}"  name="modules_settings" 
                          class="checkbox" id="License">
                          <span class="slider round"></span>
                    </label>
 
                  </td>

                  <td class="licenseEmptyField">
                  {!! Form::label('lic_api_secret',Lang::get('message.lic_api_secret')) !!}
                        {!! Form::text('license_api',null,['class' => 'form-control secretHide','disabled'=>'disabled','style'=>'width:300px']) !!}
                     
                         
                  
                        <!-- last name -->
                        {!! Form::label('lic_api_url',Lang::get('message.lic_api_url')) !!} :
                        {!! Form::text('license_api',null,['class' => 'form-control urlHide','disabled'=>'disabled','style'=>'width:300px']) !!}
                        
                  </td>
                  <td class="LicenseField hide">
                    
                   
                        <!-- last name -->
                        {!! Form::label('lic_api_secret',Lang::get('message.lic_api_secret')) !!}
                        {!! Form::text('license_api_secret',$licenseSecret,['class' => 'form-control','id'=>'license_api_secret','style'=>'width:300px']) !!}
                         <h6 id="license_apiCheck"></h6>
                         <br/>
                  
                        <!-- last name -->
                        {!! Form::label('lic_api_url',Lang::get('message.lic_api_url')) !!} :
                        {!! Form::text('license_api_url',$licenseUrl,['class' => 'form-control','id'=>'license_api_url','style'=>'width:300px']) !!}
                        <h6 id="license_urlCheck"></h6>
                   
            </td>
                  <td><button type="submit" class="form-group btn btn-primary" onclick="licenseDetails()" id="submit"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></td>
                </tr>


                <tr>
                
                  <td>Auto Update</td>
                  <td>
                    <label class="switch toggle_event_editing">
                          
                         <input type="checkbox" value="{{$updateStatus}}"  name="modules_settings" 
                          class="checkbox3" id="update">
                          <span class="slider round"></span>
                    </label>
 
                  </td>

                  <td class="updateEmptyField">
                  {!! Form::label('update_api_secret',Lang::get('message.lic_api_secret')) !!}
                        {!! Form::text('update_api',null,['class' => 'form-control updatesecretHide','disabled'=>'disabled','style'=>'width:300px']) !!}
                     
                         
                  
                        <!-- last name -->
                        {!! Form::label('update_api_url',Lang::get('message.lic_api_url')) !!} :
                        {!! Form::text('update_api',null,['class' => 'form-control updateurlHide','disabled'=>'disabled','style'=>'width:300px']) !!}
                        
                  </td>
                  <td class="updateField hide">
                    
                   
                        <!-- last name -->
                        {!! Form::label('update_api_secret',Lang::get('message.lic_api_secret')) !!}
                        {!! Form::text('update_api_secret',$updateSecret,['class' => 'form-control','id'=>'update_api_secret','style'=>'width:300px']) !!}
                         <h6 id="update_apiCheck"></h6>
                         <br/>
                  
                        <!-- last name -->
                        {!! Form::label('update_api_url',Lang::get('message.lic_api_url')) !!} :
                        {!! Form::text('update_api_url',$updateUrl,['class' => 'form-control','id'=>'update_api_url','style'=>'width:300px']) !!}
                        <h6 id="update_urlCheck"></h6>
                   
               </td>
                  <td><button type="submit" class="form-group btn btn-primary" onclick="updateDetails()" id="submitudpate"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></td>
                </tr>



                 <tr>
                 
                  <td>Google reCAPTCHA</td>
                  <td>
                    <label class="switch toggle_event_editing">
                          
                         <input type="checkbox" value="{{$captchaStatus}}"  name="modules_settings" 
                          class="checkbox2" id="captcha">
                          <span class="slider round"></span>
                    </label>
 
                  </td>

                  <td class="captchaEmptyField">
                  {!! Form::label('nocaptcha_secret',Lang::get('message.nocaptcha_secret')) !!}
                        {!! Form::text('nocaptcha_secret1',null,['class' => 'form-control nocapsecretHide','disabled'=>'disabled','style'=>'width:300px']) !!}
                        <h6 id=""></h6>
                         
                  
                        <!-- last name -->
                        {!! Form::label('nocaptcha_sitekey',Lang::get('message.nocaptcha_sitekey')) !!} :
                        {!! Form::text('nocaptcha_sitekey1',null,['class' => 'form-control siteKeyHide','disabled'=>'disabled','style'=>'width:300px']) !!}
                        <h6 id=""></h6>
                  </td>
                  <td class="captchaField hide">
                    
                   
                        <!-- last name -->
                        {!! Form::label('nocaptcha_secret',Lang::get('message.nocaptcha_secret')) !!}
                        {!! Form::text('nocaptcha_secret',$secretKey,['class' => 'form-control','id'=>'nocaptcha_secret','style'=>'width:300px']) !!}
                         <h6 id="captcha_secretCheck"></h6>
                         <br/>
                  
                        <!-- last name -->
                        {!! Form::label('nocaptcha_sitekey',Lang::get('message.nocaptcha_sitekey')) !!} :
                        {!! Form::text('nocaptcha_sitekey',$siteKey,['class' => 'form-control','id'=>'nocaptcha_sitekey','style'=>'width:300px']) !!}
                        <h6 id="captcha_sitekeyCheck"></h6>
                   
            </td>
                  <td><button type="submit" class="form-group btn btn-primary" onclick="captchaDetails()" id="submit2"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></td>
                </tr>


      
              </table>
            </div>
            <!-- /.box-body -->
          </div>
      </div>
  </section>
<div class="box box-primary">

    <div class="box-header">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <i class="fa fa-check"></i>
            <b>{{Lang::get('message.success')}}!</b>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('success')}}
        </div>
        @endif
        <!-- fail message -->
        @if(Session::has('fails'))
        <div class="alert alert-danger alert-dismissable">
            <i class="fa fa-ban"></i>
            <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('fails')}}
        </div>
        @endif
    </div>
     <div  class="box-body">
 
        {!! Form::model($model,['url'=>'apikeys','method'=>'patch']) !!}
          <tr>
         <h3 class="box-title" style="margin-top:0px;margin-left: 10px;">{{Lang::get('message.system-api')}}</h3>
       <button type="submit" class="btn btn-primary pull-right" id="submit" style="margin-top:-40px;
                        margin-right:15px;"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
         </tr>
        





   

       

            <div class="col-md-12">


               
                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('rzp_key',Lang::get('message.rzp_key')) !!}
                        {!! Form::text('rzp_key',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('rzp_secret',Lang::get('message.rzp_secret')) !!}
                        {!! Form::text('rzp_secret',null,['class' => 'form-control']) !!}

                    </div>



                </div>



                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('apilayer_key',Lang::get('message.apilayer')) !!}
                        {!! Form::text('apilayer_key',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('client_secret') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('zoho_api_key',Lang::get('message.zoho_key')) !!}
                        {!! Form::text('zoho_api_key',null,['class' => 'form-control']) !!}

                    </div>

                </div>

                 <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('msg91_auth_key',Lang::get('message.msg91key')) !!}
                        {!! Form::text('msg91_auth_key',null,['class' => 'form-control']) !!}

                    </div>

                    

                

                    <div class="col-md-6 form-group {{ $errors->has('twitter_consumer_key') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('twitter_consumer_key',Lang::get('message.twitter_consumer_key')) !!}
                        {!! Form::text('twitter_consumer_key',null,['class' => 'form-control']) !!}

                    </div>

                    

               
                    <div class="col-md-6 form-group {{ $errors->has('twitter_consumer_secret') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('twitter_consumer_secret',Lang::get('message.twitter_consumer_secret')) !!}
                        {!! Form::text('twitter_consumer_secret',null,['class' => 'form-control']) !!}

                    </div>

                    

               

                    <div class="col-md-6 form-group {{ $errors->has('twitter_access_token') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('twitter_access_token',Lang::get('message.twitter_access_token')) !!}
                        {!! Form::text('twitter_access_token',null,['class' => 'form-control']) !!}

                    </div>

                    

               
                    <div class="col-md-6 form-group {{ $errors->has('twitter_access_token_secret') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('access_tooken_secret',Lang::get('message.twitter_access_tooken_secret')) !!}
                        {!! Form::text('access_tooken_secret',null,['class' => 'form-control']) !!}

                    </div>

                    

                </div>




            </div>

       

    </div>

</div>


{!! Form::close() !!}
<script>
  //License Manager
  $(document).ready(function(){
      var status = $('.checkbox').val();
     if(status ==1) {
     $('#License').prop('checked', true);
       $('.LicenseField').removeClass("hide");
            $('.licenseEmptyField').addClass("hide");
     } else if(status ==0) {
       $('.LicenseField').addClass("hide");
               $('.licenseEmptyField').removeClass("hide");
              
     }
      });
 $('#license_apiCheck').hide();
   $('#License').change(function () {
        if ($(this).prop("checked")) {
            // checked
           $('#license_api_secret').val();
                $('#license_api_url').val();
            $('.LicenseField').removeClass("hide");
            $('.licenseEmptyField').addClass("hide");
        }
        else{
            $('.LicenseField').addClass("hide");
             $('.nocapsecretHide').val('');
                $('.siteKeyHide').val('');
               $('.licenseEmptyField').removeClass("hide");
               
               
        }
    });

function licenseDetails(){

if ($('#License').prop("checked")) {
          var checkboxvalue = 1;
          if ($('#license_api_secret').val() =="" ) {
             $('#license_apiCheck').show();
             $('#license_apiCheck').html("Please Enter API Secret Key");
              $('#license_api_secret').css("border-color","red");
              $('#license_apiCheck').css({"color":"red","margin-top":"5px"});
              return false;
          }
            if ($('#license_api_url').val() =="" ) {
             $('#license_urlCheck').show();
             $('#license_urlCheck').html("Please Enter API URL");
              $('#license_api_url').css("border-color","red");
              $('#license_urlCheck').css({"color":"red","margin-top":"5px"});
              return false;
          }
         
    }
    else{
          var checkboxvalue = 0;
         }
       $("#submit").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");     
  $.ajax({
    
    url : '{{url("licenseDetails")}}',
    type : 'get',
    data: {
       "status": checkboxvalue,
       "license_api_secret": $('#license_api_secret').val(),
       "license_api_url" :$('#license_api_url').val(),
      },
      success: function (response) {
            $('#alertMessage').show();
            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+response.update+'.</div>';
            $('#alertMessage').html(result+ ".");
            $("#submit").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
                setInterval(function(){ 
                $('#alertMessage').slideUp(3000); 
            }, 1000);
          },


 });
 };



 //Auto Update
    $(document).ready(function(){

      var status = $('.checkbox3').val();
     if(status ==1) {
     $('#update').prop('checked', true);
       $('.updateField').removeClass("hide");
            $('.updateEmptyField').addClass("hide");
     } else if(status ==0) {
       $('.updateField').addClass("hide");
               $('.updateEmptyField').removeClass("hide");
              
     }
      });
 $('#update_apiCheck').hide();
   $('#update').change(function () {
        if ($(this).prop("checked")) {
            // checked
           $('#update_api_secret').val();
                $('#update_api_url').val();
            $('.updateField').removeClass("hide");
            $('.updateEmptyField').addClass("hide");
        }
        else{
            $('.updateField').addClass("hide");
             $('.updatesecretHide').val('');
                $('.updateurlHide').val('');
               $('.updateEmptyField').removeClass("hide");
               
               
        }
    });

function updateDetails(){
if ($('#update').prop("checked")) {
  console.log($('#update_api_secret').val());
          var checkboxvalue = 1;
          if ($('#update_api_secret').val() == '' ) {
             $('#update_apiCheck').show();
             $('#update_apiCheck').html("Please Enter API Secret Key");
              $('#update_api_secret').css("border-color","red");
              $('#update_apiCheck').css({"color":"red","margin-top":"5px"});
              return false;
          }
         if ($('#update_api_url').val() == '' ) {
            alert('df');
             $('#update_urlCheck').show();
             $('#update_urlCheck').html("Please Enter API URL");
              $('#update_api_url').css("border-color","red");
              $('#update_urlCheck').css({"color":"red","margin-top":"5px"});
              return false;
          }
         
    }
    else{
          var checkboxvalue = 0;
         }
       $("#submitudpate").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");     
  $.ajax({
    
    url : '{{url("updateDetails")}}',
    type : 'get',
    data: {
       "status": checkboxvalue,
       "update_api_secret": $('#update_api_secret').val(),
       "update_api_url" :$('#update_api_url').val(),
      },
      success: function (response) {
            $('#alertMessage').show();
            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+response.update+'.</div>';
            $('#alertMessage').html(result+ ".");
            $("#submitudpate").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
                setInterval(function(){ 
                $('#alertMessage').slideUp(3000); 
            }, 1000);
          },


 });
 };





 //Google Recaptcha
   $(document).ready(function(){
      var status = $('.checkbox2').val();
     if(status ==1) {
     $('#captcha').prop('checked', true);
       $('.captchaField').removeClass("hide");
       $('.captchaEmptyField').addClass("hide");
     } else if(status ==0) {
       $('.captchaField').addClass("hide");
               $('.captchaEmptyField').removeClass("hide");
              
     }
      });
 $('#captcha_secretCheck').hide();
   $('#captcha').change(function () {
        if ($(this).prop("checked")) {
            // checked
           $('#nocaptcha_secret').val();
                $('#nocaptcha_sitekey').val();
            $('.captchaField').removeClass("hide");
            $('.captchaEmptyField').addClass("hide");
        }
        else{
            $('.captchaField').addClass("hide");
             $('.secretHide').val('');
                $('.urlHide').val('');
               $('.captchaEmptyField').removeClass("hide");
               
               
        }
    });

function captchaDetails(){
if ($('#captcha').prop("checked")) {
          var checkboxvalue = 1;
          if ($('#nocaptcha_secret').val() =="" ) {
             $('#captcha_secretCheck').show();
             $('#captcha_secretCheck').html("Please Enter Secret Key");
              $('#captcha_secret').css("border-color","red");
              $('#captcha_secretCheck').css({"color":"red","margin-top":"5px"});
              return false;
          }
            if ($('#nocaptcha_sitekey').val() =="" ) {
             $('#captcha_sitekeyCheck').show();
             $('#captcha_sitekeyCheck').html("Please Enter Sitekey");
              $('#nocaptcha_sitekey').css("border-color","red");
              $('#captcha_sitekeyCheck').css({"color":"red","margin-top":"5px"});
              return false;
          }
         
    }
    else{
          var checkboxvalue = 0;
         }
       $("#submit2").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");     
  $.ajax({
    
    url : '{{url("captchaDetails")}}',
    type : 'get',
    data: {
       "status": checkboxvalue,
       "nocaptcha_sitekey": $('#nocaptcha_sitekey').val(),
       "nocaptcha_secret" :$('#nocaptcha_secret').val(),
      },
      success: function (data) {
            $('#alertMessage').show();
            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+data.update+'.</div>';
            $('#alertMessage').html(result+ ".");
            $("#submit2").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
              setInterval(function(){ 
                $('#alertMessage').slideUp(3000); 
            }, 1000);
          },

 });
 };




</script>
@stop