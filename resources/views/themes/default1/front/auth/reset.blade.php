@extends('themes.default1.layouts.front.master')
@section('title')
Reset Password | Faveo Helpdesk
@stop
@section('page-heading')
Reset Your Password
@stop
@section('page-header')
Reset Password
@stop
@section('breadcrumb')
    @if(Auth::check())
        <li><a class="text-primary" href="{{url('my-invoices')}}">Home</a></li>
    @else
         <li><a class="text-primary" href="{{url('login')}}">Home</a></li>
    @endif
     <li class="active text-dark">Reset Password</li>
@stop 
@section('main-class') 
main
@stop
@section('content')


     <div class="container py-4">

            <div class="row justify-content-center">

                <div class="col-md-6 col-lg-6 mb-5 mb-lg-0 pe-5">

                    <p class="text-2">Your password must be more than 6 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.</p>

                    {!!  Form::open(['url'=>'/password/reset', 'method'=>'post']) !!}
                            <input type="hidden" name="token" value="{{ $reset_token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                        <div class="row">

                            <div class="form-group col {{ $errors->has('password') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Password <span class="text-color-danger">*</span></label>

                                <input type="password" value="" class="form-control form-control-lg text-4" placeholder="Password" name='password'<?php if( count($errors) > 0) {?> style="width: 98%;position: relative;left: 5px;"<?php } ?>>


                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">

                                <label class="form-label text-color-dark text-3">Confirm Password <span class="text-color-danger">*</span></label>

                                {!! Form::password('password_confirmation',['placeholder'=>'Retype password','class' => 'form-control form-control-lg text-4']) !!}


                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col">

                                <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase font-weight-bold text-3 py-3" data-loading-text="Loading...">Reset Password</button>

                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
@stop