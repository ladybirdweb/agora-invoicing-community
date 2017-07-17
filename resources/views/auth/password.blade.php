@extends('themes.default1.layouts.login')

@section('body')

<body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>{{Lang::get('message.faveo')}}</b> {{Lang::get('message.billing')}}</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">{{Lang::get('message.get-password')}}</p>
        <!-- form open -->
        {!!  Form::open(['url'=>'password/email', 'method'=>'post']) !!}
          <!-- Email -->
          <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">

			{!! Form::text('email',null,['placeholder'=>Lang::get('message.email'),'class' => 'form-control']) !!}
			{!! $errors->first('email', '<spam class="help-block">:message</spam>') !!}
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>

          </div>
          <div class="form-group">

                   <a href="{{url('login')}}">{{Lang::get('message.login')}}</a>

          </div>

          <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block btn-flat">{{Lang::get('message.send')}}</button>
          </div><!-- /.col -->

       </div>
    </div>
</body>




@stop