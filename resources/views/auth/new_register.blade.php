@extends('themes.default1.layouts.front.master')
@section('title')
login
@stop
@section('page-header')
Login
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Login</li>
@stop
@section('main-class') 
main
@stop
// <?php $location = \GeoIP::getLocation(); ?>

@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="featured-boxes">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-md-offset-3">
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
                            <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="featured-box featured-box-primary align-left mt-xlg">
                        <div class="box-content">
                            <form role="form" method="POST" action="{{ url('/auth/register') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.first_name')}}" name="first_name" value="{{ old('first_name') }}"/>
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.last_name')}}" name="last_name" value="{{ old('last_name') }}"/>
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="email" class="form-control" placeholder="{{Lang::get('message.email')}}" name="email" value="{{ old('email') }}"/>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.company')}}" name="company" value="{{ old('company') }}"/>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.mobile')}}" name="mobile" value="{{ old('mobile') }}"/>
                                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.address')}}" name="address" value="{{ old('address') }}"/>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.country')}}" name="country" value="{{ $location['country'] }}"/>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.town')}}" name="town" value="{{ $location['city'] }}"/>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.state')}}" name="state" value="{{ $location['state'] }}"/>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="{{Lang::get('message.zip')}}" name="zip" value="{{ $location['postal_code'] }}"/>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="password" class="form-control" placeholder="{{Lang::get('message.password')}}" name="password" />
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="password" class="form-control" placeholder="{{Lang::get('message.confirm_password')}}" name="password_confirmation" />
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="checkbox icheck">
                                            <label>
                                                <input type="checkbox" name="terms"> {{Lang::get('message.i-agree-to-the')}} <a href="#">{{Lang::get('message.terms')}}</a>
                                            </label>
                                        </div>
                                    </div><!-- /.col -->
                                    <div class="col-xs-4">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{Lang::get('message.register')}}</button>
                                    </div><!-- /.col -->
                                </div>
                            </form>

                            <a href="{{url('auth/login')}}" class="text-center">{{Lang::get('message.i-already-have-a-membership')}}</a>
                        </div><!-- /.form-box -->
                    </div><!-- /.register-box -->
                </div>
            </div>
        </div>
    </div>
</div>
@stop
