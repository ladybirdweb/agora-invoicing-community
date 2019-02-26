@extends('themes.default1.layouts.front.master')
@section('title')
Reset Paswword| Faveo Helpdesk
@stop
@section('page-heading')
 <h1>Reset Your Password</h1>
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
                 <div class="col-lg-6 offset-lg-3">
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
                    <div class="alert alert-danger alert-dismissable" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <strong><i class="fa fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif


                     <div class="featured-box featured-box-primary text-left mt-5">
                        <div class="box-content">
                            
                               
                            <h4 class="heading-primary text-uppercase mb-md">Reset Password</h4>
                            {!!  Form::open(['url'=>'/password/reset', 'method'=>'post']) !!}
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="form-row">
                                <div class="form-group col{{ $errors->has('password') ? 'has-error' : '' }}">
                                   
                                        <p id="passwordHelpBlock" class="form-text text-muted">
                                            Your password must be more than 6 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                                       </p>
                                      

                                        {!! Form::password('password',['placeholder'=>Lang::get('message.password'),'class' => 'form-control input-lg']) !!}
                                        <!--<input type="password" value="" class="form-control input-lg">-->
                                   
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col{{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                   
                                        <!--<a class="pull-right" href="{{url('password/email')}}">({{Lang::get('message.forgot-my-password')}})</a>-->
                                        {!! Form::password('password_confirmation',['placeholder'=>'Retype password','class' => 'form-control input-lg']) !!}
                                        <!--<input type="password" value="" class="form-control input-lg">-->
                                    
                                </div>
                            
                    </div>
                            <div class="row">
                                
                               
                                
                                <div class="col-md-12">
                                     <input type="submit" value="Reset Password" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                                    <!--<input type="submit" value="Login" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">-->
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop