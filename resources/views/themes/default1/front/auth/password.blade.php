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
                            <h4 class="heading-primary mb-md">Forgot Password</h4>
                            {!!  Form::open(['url'=>'password/email', 'method'=>'post']) !!}
                            <div class="row">
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <div class="col-md-12">
                                        <!--<label>Username or E-mail Address</label>-->
                                        <!--<input type="text" value="" class="form-control input-lg">-->
                                        {!! Form::text('email',null,['placeholder'=>Lang::get('message.email'),'class' => 'form-control input-lg']) !!}
                                    </div>
                                </div>
                                <!--                                <div class="row">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <a href="{{url('auth/login')}}">{{Lang::get('message.login')}}</a>
                                                                        </div>
                                
                                                                    </div>
                                                                </div>-->
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <a href="{{url('auth/login')}}" class="pull-left mb-xl">{{Lang::get('message.login')}}</a>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Retrieve Password" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
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