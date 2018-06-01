@extends('themes.default1.layouts.front.master')
@section('title')
Forgot Paswword| Faveo Helpdesk
@stop
@section('page-heading')
 <h1>Forgot Password? <span>Reset it Now</span></h1>
@stop
@section('page-header')
Forgot Password
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li><a href="{{url('login')}}">Login</a></li>
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
              <div class="col-lg-6 offset-lg-3">
                    @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissable">
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong><i class="fa fa-thumbs-up"></i> Well done!</strong>
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
                          
                            {!!  Form::open(['url'=>'password/email', 'method'=>'post']) !!}
                         <p>Lost your password? Please enter your email address. You will receive a link to create a new password via email.</p>
                            <div class="form-row">
                                <div class="form-group col">
                                   
                                        <label>Email Address <span style="color: red">*</span></label>
                                          <div class="input-group">
                                      <input type="text" name="email" value="" class="form-control input-lg">
                                      <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                      </div>
                                  </div>
                                        <!-- {!! Form::text('email',null,['class' => 'form-control input-lg']) !!} -->
                                    
                                </div>
                               
                            </div>
                            <div class="clear"></div>
                                <div class="form-row">
                         <div class="form-group col">
                            

                               
                                    <a href="{{url('auth/login')}}" class="pt-left back-login">Click Here To Login</a>
                               
                              
                                   <input type="submit" value="Retrieve Password" class="btn btn-primary float-right mb-5" data-loading-text="Loading...">
                               
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