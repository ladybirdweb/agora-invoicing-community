@extends('themes.default1.layouts.front.master')
@section('title')
reset password
@stop
@section('page-header')
Reset Password
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Reset Password</li>
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
                    <div class="featured-box featured-box-primary align-left mt-xlg">
                        <div class="box-content">
                            <h4 class="heading-primary text-uppercase mb-md">Reset Password</h4>
                            {!!  Form::open(['url'=>'/password/reset', 'method'=>'post']) !!}
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="row">
                                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <div class="col-md-12">
                                        <!--<a class="pull-right" href="{{url('password/email')}}">({{Lang::get('message.forgot-my-password')}})</a>-->
                                        {!! Form::password('password',['placeholder'=>Lang::get('message.password'),'class' => 'form-control input-lg']) !!}
                                        <!--<input type="password" value="" class="form-control input-lg">-->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                    <div class="col-md-12">
                                        <!--<a class="pull-right" href="{{url('password/email')}}">({{Lang::get('message.forgot-my-password')}})</a>-->
                                        {!! Form::password('password_confirmation',['placeholder'=>'Retype password','class' => 'form-control input-lg']) !!}
                                        <!--<input type="password" value="" class="form-control input-lg">-->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                               
                                
                                <div class="col-md-12">
                                     <input type="submit" value="Reset Password" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
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