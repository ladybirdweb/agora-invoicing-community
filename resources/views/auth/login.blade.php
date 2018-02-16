@extends('themes.default1.layouts.login')
@section('body')

<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>{{Lang::get('message.faveo')}}</b> {{Lang::get('message.billing')}}</a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            @if(Session::has('success'))
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-ban"></i>
                <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
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
            <p class="login-box-msg">{{Lang::get('message.sign-in-to-start-your-session')}}</p>
            <!-- form open -->
            {!!  Form::open(['action'=>'Auth\LoginController@login', 'method'=>'post']) !!}
            <!-- Email -->
            <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">

                {!! Form::text('email',null,['placeholder'=>Lang::get('message.email'),'class' => 'form-control']) !!}
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

            </div>


            <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                {!! Form::password('password',['placeholder'=>Lang::get('message.password'),'class' => 'form-control']) !!}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember"> {{Lang::get('message.remember-me')}}
                        </label>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{Lang::get('message.sign-in')}}</button>
                </div><!-- /.col -->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="{{url('auth/register')}}" class="btn btn-primary btn-block btn-flat">{{Lang::get('message.sign-up')}}</a>
                </div>
            </div>
            </form>


            <a href="{{url('password/email')}}">{{Lang::get('message.forgot-my-password')}}</a><br>

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    @stop
