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

                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{Session::get('fails')}}
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Something went wrong<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="featured-box featured-box-primary align-left mt-xlg">
                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">{{Lang::get('message.sign-in-to-start-your-session')}}</h4>
                            {!!  Form::open(['action'=>'Auth\AuthController@postLogin', 'method'=>'post']) !!}
                            <div class="row">
                                <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <div class="col-md-12">
                                        <label>Username or E-mail Address</label>
                                        {!! Form::text('email',null,['placeholder'=>Lang::get('message.email'),'class' => 'form-control input-lg']) !!}

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <div class="col-md-12">
                                        <a class="pull-right" href="{{url('password/email')}}">({{Lang::get('message.forgot-my-password')}})</a>
                                        <label>Password</label>
                                        {!! Form::password('password',['placeholder'=>Lang::get('message.password'),'class' => 'form-control input-lg']) !!}
                                        <!--<input type="password" value="" class="form-control input-lg">-->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="remember-box checkbox">
                                        <label for="rememberme">
                                            <input type="checkbox" name="remember"> {{Lang::get('message.remember-me')}}
                                        </label>
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <a href="{{url('auth/register')}}" class="btn btn-warning">Register</a>
                                    <input type="submit" value="{{Lang::get('message.sign-in')}}" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                                   <!--<input type="submit" value="Login" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">-->
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop