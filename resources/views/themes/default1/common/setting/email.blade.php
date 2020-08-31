@extends('themes.default1.layouts.master')
@section('title')
Email
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Configure Mail</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">Email</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
 <div id="alertMessage"></div>


<div class="card card-primary card-outline">

       
            <div class="card-body">

                  <div class="col-md-12">


                    <tr>
                        <div class="form-group {{ $errors->has('driver') ? 'has-error' : '' }}">
                        <td><b>{!! Form::label('driver',Lang::get('message.driver'),['class'=>'required']) !!}</b></td>
                        <td>



                                {!! Form::select('driver',[''=>'Choose','smtp'=>'SMTP','mail'=>'Php mail'],$set->driver,['class' => 'form-control', 'id'=>'driver']) !!}
                                <p><i> {{Lang::get('message.select-email-driver')}}</i> </p>



                        </td>
                        </div>
                    </tr>
                    <tr>
                        <div class="form-group {{ $errors->has('port') ? 'has-error' : '' }} showWhenSmtpSelected hide">
                        <td><b>{!! Form::label('port',Lang::get('message.port'),['class'=>'required']) !!}</b></td>
                        <td>



                                {!! Form::text('port',$set->port,['class' => 'form-control','id'=>'port']) !!}
                                <p><i> {{Lang::get('message.enter-email-port')}}</i> </p>


                        </td>
                        </div>
                    </tr>
                    <tr>
                        <div class="form-group {{ $errors->has('host') ? 'has-error' : '' }} showWhenSmtpSelected hide">
                        <td><b>{!! Form::label('host',Lang::get('message.host'),['class'=>'required']) !!}</b></td>
                        <td>



                                {!! Form::text('host',$set->host,['class' => 'form-control','id'=>'host']) !!}
                                <p><i> {{Lang::get('message.enter-email-host')}}</i> </p>


                        </td>
                        </div>

                    </tr>
                    <tr>
                        <div class="form-group {{ $errors->has('encryption') ? 'has-error' : '' }} showWhenSmtpSelected hide" >
                        <td><b>{!! Form::label('encryption',Lang::get('message.encryption'),['class'=>'required']) !!}</b></td>
                        <td>


                                {!! Form::select('encryption',[''=>'Choose','ssl'=>'SSL','tls'=>'TLS','starttls'=>'STARTTLS'],$set->encryption,['class' => 'form-control','id'=>'encryption']) !!}
                                <p><i> {{Lang::get('message.select-email-encryption-method')}}</i> </p>


                        </td>
                        </div>

                    </tr>
                    <tr>
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <td><b>{!! Form::label('email',Lang::get('message.email'),['class'=>'required']) !!}</b></td>
                        <td>


                                {!! Form::text('email',$set->email,['class' => 'form-control','id'=>'email']) !!}
                                <p><i> {{Lang::get('message.enter-email')}} ({{Lang::get('message.enter-email-message')}})</i> </p>


                        </td>
                        </div>
                    </tr>
                    <tr>
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <td><b>{!! Form::label('password',Lang::get('message.password'),['class'=>'required']) !!}</b></td>
                        <td>


                                {!! Form::password('password',['class' => 'form-control', 'id'=>'password']) !!}
                                <p><i> {{Lang::get('message.enter-email-password')}}</i> </p>

                            </div>
                        </td>
                    </tr>
                    <br>
                     <button type="submit" class="form-group btn btn-primary pull-right"  id="emailSetting"><i class="fa fa-save">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
                     
                  </div>
</div>
    <script>
        $(document).ready(function(){
            if($('#driver').val() == 'smtp') {
                $('.showWhenSmtpSelected').removeClass("hide");
            } else {
                $('.showWhenSmtpSelected').addClass("hide");
            }
        })

        $('#driver').on('change',function(){
            var driver = $('#driver').val();
            if(driver == 'smtp')
            {
                $('.showWhenSmtpSelected').removeClass("hide");
            } else {
                $('.showWhenSmtpSelected').addClass("hide");
            }
        })

        $('#emailSetting').on('click',function(){



            $("#emailSetting").html("<i class='fas fa-circle-notch fa-spin'></i>Please Wait...");
            $.ajax({

                url : '{{url("settings/email")}}',
                type : 'patch',
                data: {
                     "email" : $('#email').val(),
                     "password" : $('#password').val(),
                     "driver" : $('#driver').val(),
                     "port"  : $('#port').val(),
                     "encryption" : $('#encryption'). val(),
                     "host" : $('#host').val(),
                },
                success: function (response) {
                    $("#emailSetting").html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
                    $('#alertMessage').show();
                    var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+response.message+'.</div>';
                    $('#alertMessage').html(result+ ".");
                    $("#submit").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
                    setInterval(function(){
                        $('#alertMessage').slideUp(3000);
                    }, 1000);
                },error: function(response) {
                    var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                    $("#emailSetting").html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
                    if(response.status == 422) {
                        for (key in response.responseJSON.errors) {
                            html += '<li>' + response.responseJSON.errors[key][0] + '</li>'
                        }

                    } else {
                        html += '<li>' + response.responseJSON.message + '</li>'
                    }
                    html += '</ul></div>';
                    $('#alertMessage').show();
                    document.getElementById('alertMessage').innerHTML = html;
                    // location.reload();
                }


            });
        })
    </script>
@stop