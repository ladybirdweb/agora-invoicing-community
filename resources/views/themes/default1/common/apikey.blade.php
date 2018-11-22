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
              <h3 class="box-title">Striped Full Width Table</h3>
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
                <tr>
                   <div id="alertMessage"></div>
                  <td>License Manager</td>
                  <td>
                    <label class="switch toggle_event_editing">
                          
                         <input type="checkbox" value="{{$status}}"  name="modules_settings" 
                          class="checkbox" id="License">
                          <span class="slider round"></span>
                    </label>
 
                  </td>

                  <td class="licenseEmptyField">
                  {!! Form::label('lic_api_secret',Lang::get('message.lic_api_secret')) !!}
                        {!! Form::text('license_api_secret',null,['class' => 'form-control secretHide','disabled'=>'disabled','style'=>'width:400px']) !!}
                        <h6 id=""></h6>
                         
                  
                        <!-- last name -->
                        {!! Form::label('lic_api_url',Lang::get('message.lic_api_url')) !!} :
                        {!! Form::text('license_api_url',null,['class' => 'form-control urlHide','disabled'=>'disabled','style'=>'width:400px']) !!}
                        <h6 id=""></h6>
                  </td>
                  <td class="LicenseField hide">
                    
                   
                        <!-- last name -->
                        {!! Form::label('lic_api_secret',Lang::get('message.lic_api_secret')) !!}
                        {!! Form::text('license_api_secret',$licenseSecret,['class' => 'form-control','id'=>'license_api_secret','style'=>'width:400px']) !!}
                         <h6 id="license_apiCheck"></h6>
                         <br/>
                  
                        <!-- last name -->
                        {!! Form::label('lic_api_url',Lang::get('message.lic_api_url')) !!} :
                        {!! Form::text('license_api_url',$licenseUrl,['class' => 'form-control','id'=>'license_api_url','style'=>'width:400px']) !!}
                        <h6 id="license_urlCheck"></h6>
                   
            </td>
                  <td><button type="submit" class="form-group btn btn-primary" onclick="licenseDetails()" id="submit"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></td>
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
             $('.secretHide').val('');
                $('.urlHide').val('');
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
    type : 'post',
    data: {
       "status": checkboxvalue,
       "license_api_secret": $('#license_api_secret').val(),
       "license_api_url" :$('#license_api_url').val(),
      },
      success: function (response) {
            $('#alertMessage').show();
            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+response.message+'.</div>';
            $('#alertMessage').html(result+ ".");
            $("#submit").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
          },

 });
 };

</script>
@stop