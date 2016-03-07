@extends('themes.default1.layouts.front.master')
@section('title')
forgot password
@stop
@section('page-header')
Forgot Password
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Forgot Password</li>
@stop
@section('main-class') 
main
@stop
@sectio
@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="featured-boxes">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-md-offset-3">
                    <div class="featured-box featured-box-primary align-left mt-xlg">
                        <div class="box-content">
                            <h4 class="heading-primary mb-md">{{Lang::get('message.get-password')}}</h4>
                            {!!  Form::open(['url'=>'password/email', 'method'=>'post']) !!}
                            <div class="row">
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <div class="col-md-12">
                                        <!--<label>Username or E-mail Address</label>-->
                                        <!--<input type="text" value="" class="form-control input-lg">-->
                                        {!! Form::text('email',null,['placeholder'=>Lang::get('message.email'),'class' => 'form-control input-lg']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <a href="{{url('auth/login')}}">{{Lang::get('message.login')}}</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-6">
                                    <input type="submit" value="Submit" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
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