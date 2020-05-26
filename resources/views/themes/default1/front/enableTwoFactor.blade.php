@extends('themes.default1.layouts.front.master')
@section('title')
Two-factor authentication
@stop
@section('page-heading')
Two-factor authentication
@stop
@section('page-header')
Forgot Password
@stop
@section('breadcrumb')
<li><a href="{{url('login')}}">Login</a></li>
<li class="active">Two-factor authentication</li>
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
                        <b><i class="fa fa-exclamation-triangle"></i>Whoops!</b>
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
                     <div id="alertMessage"></div>
                     <div id="error"></div>
                      
                   <div class="featured-box featured-box-primary text-left mt-5">
                        <div class="box-content">
                          {!!  Form::open(['route'=>'2fa/loginValidate', 'method'=>'post']) !!}
                         <h2 style="text-align: center;">Two-Factor Authentication</h2>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label>Enter Authentication Code <span style="color: red">*</span></label>
                                          <div class="input-group">
                                      <input type="text" name="totp"  id="2fa_code" class="form-control input-lg">
                                     
                                  </div>
                                   <h6 id="codecheck"></h6>
                               
                            
                      
                                <p>Open the two-factor authentication app on your device to view your authentication code and verify your identity.</p>
                              <button type="submit" class="btn btn-primary float-right" >
                                            Verify
                                </button>
                                <b>Having problems?</b><br>
                                 <a href="{{'recovery-code'}}" >Login using recovery code</a>
                                
                                {!! Form::close() !!}
                           
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
