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
<div class="row">

    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
            <h3  class="card-title">{{Lang::get('message.smtp')}}</h3>
            </div>
            <div class="card-body table-responsive">
                {!! Form::model($set,['url'=>'settings/email','method'=>'patch','files'=>true]) !!}

                  <div class="col-md-12">

                    <tr>

                        <td><b>{!! Form::label('driver',Lang::get('message.driver'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('driver') ? 'has-error' : '' }}">


                                {!! Form::select('driver',[''=>'Choose','smtp'=>'SMTP'],null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.select-email-driver')}}</i> </p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('port',Lang::get('message.port'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('port') ? 'has-error' : '' }}">


                                {!! Form::text('port',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-email-port')}}</i> </p>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('host',Lang::get('message.host'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('host') ? 'has-error' : '' }}">


                                {!! Form::text('host',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-email-host')}}</i> </p>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('encryption',Lang::get('message.encryption'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('encryption') ? 'has-error' : '' }}">

                                {!! Form::text('encryption',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.select-email-encryption-method')}}</i> </p>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('email',Lang::get('message.email'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">

                                {!! Form::text('email',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-email')}} ({{Lang::get('message.enter-email-message')}})</i> </p>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('password',Lang::get('message.password'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">

                                {!! Form::password('password',['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-email-password')}}</i> </p>

                            </div>
                        </td>
                        {!! Form::close() !!}
                    </tr>
                    <br>
                      <button type="submit" class="btn btn-primary pull-right" id="submit" style="margin-top:-40px;"><i class="fa fa-sync-alt">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>

                  </div>

            </div>
        </div>
    </div>
</div>
@stop