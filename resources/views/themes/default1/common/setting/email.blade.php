@extends('themes.default1.layouts.master')
@section('content')
<div class="row">

    <div class="col-md-12">
        <div class="box">
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

                <table class="table table-condensed">
                    <tr>
                        <td><h3 class="box-title">{{Lang::get('message.smtp')}}</h3></td>
                        <td>{!! Form::submit(Lang::get('message.update'),['class'=>'btn btn-primary pull-right'])!!}</td>
                    </tr>
                    <tr>

                        <td><b>{!! Form::label('driver',Lang::get('message.driver'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('driver') ? 'has-error' : '' }}">


                                {!! Form::select('driver',['mail'=>'Mail','smtp'=>'SMTP'],null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.select-email-driver')}}</i> </p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('port',Lang::get('message.port')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('port') ? 'has-error' : '' }}">


                                {!! Form::text('port',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-email-port')}}</i> </p>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('host',Lang::get('message.host')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('host') ? 'has-error' : '' }}">


                                {!! Form::text('host',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-email-host')}}</i> </p>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('encryption',Lang::get('message.encryption')) !!}</b></td>
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
                                <p><i> {{Lang::get('message.enter-email')}}</i> </p>

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

                    

                </table>
            </div>
        </div>
    </div>
</div>
@stop