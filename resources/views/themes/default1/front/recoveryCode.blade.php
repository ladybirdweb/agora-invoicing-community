@extends('themes.default1.layouts.front.master')
@section('title')
Two-factory recovery
@stop
@section('page-heading')
Two-factory recovery
@stop
@section('page-header')
Forgot Password
@stop
@section('breadcrumb')
<li><a href="{{url('login')}}">Login</a></li>
<li class="active">Two-factory recovery</li>
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
                     <div id="alertMessage"></div>
                     <div id="error"></div>
                      
                   <div class="featured-box featured-box-primary text-left mt-5">
                        <div class="box-content">
                          {!!  Form::open(['route'=>'verify-recovery-code', 'method'=>'post']) !!}
                         <h2 style="text-align: center;">Two-factor recovery</h2>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label>Enter recovery code <span style="color: red">*</span></label>
                                          <div class="input-group">
                                      <input type="text" name="rec_code" class="form-control input-lg">
                                     
                                  </div>
                               
                             <p>You recovery code can be used only once. Make sure to generate new recovery code from My Profile section once you successfully sign in using your current recovery code.</p>
                              <button type="submit" class="btn btn-primary float-right" >
                                            Verify
                                </button>
                                  <a href="{{'verify-2fa'}}" >Login using Authenticator Passcode</a>
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
