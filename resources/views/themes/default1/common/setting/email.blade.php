@extends('themes.default1.layouts.master')
@section('title')
Email
@stop
@section('content-header')
<h1>
Configure Mail
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li class="active">Email</li>
      </ol>
@stop
@section('content')
<div class="row">

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissable">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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

            <div class="box-body">
                {!! Form::model($set,['url'=>'settings/email','method'=>'patch','files'=>true]) !!}

                  <div class="col-md-12">
                    <tr>
                        <h3 style="margin-top:0px;" class="box-title">{{Lang::get('message.smtp')}}</h3>
                       <button type="submit" class="btn btn-primary pull-right" id="submit" style="margin-top:-40px;"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
                    </tr>
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

                    
          </div>
                
            </div>
        </div>
    </div>
</div>
@stop