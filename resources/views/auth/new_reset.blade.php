@extends('themes.default1.layouts.login')
@section('body')
<body class="register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="#"><b>{{Lang::get('message.faveo')}}</b> {{Lang::get('message.billing')}}</a>
      </div>

      <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>
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
        <form role="form" method="POST" action="{{ url('/password/reset') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="token" value="{{ $token }}">

          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation" />
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="row">
            {{-- <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> I agree to the <a href="#">terms</a>
                </label>
              </div>
            </div><!-- /.col --> --}}
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
            </div><!-- /.col -->
          </div>
        </form>

       {{--  <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign up using Google+</a>
        </div>
 --}}
        {{-- <a href="{{url('auth/login')}}" class="text-center">I already have a membership</a> --}}
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->
@stop
