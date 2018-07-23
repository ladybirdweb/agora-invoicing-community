@extends('themes.default1.layouts.front.master')
@section('title')
Contact Us | Faveo Helpdesk
@stop
@section('page-header')
Cart
@stop
@section('page-heading')
<h1>Contact <span>What can we help you with?</span></h1>
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Contact Us</li>
@stop
@section('main-class') "main shop" @stop
@section('content')   
<style>
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }
</style>

<div class="row">
    <div class="col-md-6">

        @if (count($errors) > 0)
                <div class="alert alert-danger">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

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

       <h2 class="mb-3 mt-2"><strong>Contact</strong> Us</h2>
        {!! Form::open(['url'=>'contact-us']) !!}
            <div class="form-row">
                <div class="form-group col-lg-6">
                  
                        <label class="required">Your name</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required>
                    </div>
                   <div class="form-group col-lg-6">
                        <label class="required">Your email address</label>
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email" required>
                    </div>
                </div>
           
            <div class="form-row">
                    <div class="form-group col">
                        <label>Mobile No</label>
                        <input type="text" value="" data-msg-required="Please enter the mobile No." maxlength="10" class="form-control" name="Mobile" id="mobile" required>
                    
                </div>
            </div>
           <div class="form-row">
               <div class="form-group col">
                    
                        <label class="required">Message</label>
                        <textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message" required></textarea>
                    </div>
                
            </div>
              <div class="form-row">
                <div class="form-group col">
                    <input type="submit" value="Send Message" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
                </div>
            </div>
        {!! Form::close() !!}
    </div>
    <div class="col-md-6">

        <h4 class="heading-primary mt-4">Get in <strong>Touch</strong></h4>
                            <p>What can we help you with?</p>

        <hr>

        <h4 class="heading-primary">The <strong>Office</strong></h4>
       <ul class="list list-icons list-icons-style-3 mt-4">
                                <li><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> No:68, 1st floor 10th Main Indiranagar, 2nd Stage Bangalore – 560038 Karnataka – India</li>
                                <li><i class="fas fa-phone"></i> <strong>Phone:</strong> +91 80 3075 2618</li>
                                <li><i class="far fa-envelope"></i> <strong>Email:</strong> <a href="mailto:support@faveohelpdesk.com">support@faveohelpdesk.com</a></li>
                            </ul>
        <hr>

       <h4 class="heading-primary">Business <strong>Hours</strong></h4>
                            <ul class="list list-icons list-dark mt-4">
                                <li><i class="far fa-clock"></i> Monday to Friday 09:30AM to 06:30PM</li>
                                
                            </ul>

    </div>

</div>
@stop