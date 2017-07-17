@extends('themes.default1.layouts.front.master')
@section('title')
Cart
@stop
@section('page-header')
Cart
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
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
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

        <h2 class="mb-sm mt-sm"><strong>Contact</strong> Us</h2>
        {!! Form::open(['url'=>'contact-us']) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="required">Your name</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="required">Your email address</label>
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Mobile No</label>
                        <input type="text" value="" data-msg-required="Please enter the mobile No." maxlength="10" class="form-control" name="Mobile" id="mobile" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label class="required">Message</label>
                        <textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message" required></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <input type="submit" value="Send Message" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
                </div>
            </div>
        {!! Form::close() !!}
    </div>
    <div class="col-md-6">

        <h4 class="heading-primary mt-lg">Get in <strong>Touch</strong></h4>
        <p>What can
we help you with?</p>

        <hr>

        <h4 class="heading-primary">The <strong>Office</strong></h4>
        <ul class="list list-icons list-icons-style-3 mt-xlg">
            <li><i class="fa fa-map-marker"></i> <strong>Address:</strong> No:68, 1st floor
                10th Main Indiranagar, 2nd Stage
                Bangalore – 560038
                Karnataka – India</li>
            <li><i class="fa fa-phone"></i> <strong>Phone:</strong> +91 80 3075 2618</li>
            <li><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="mailto:support@ladybirdweb.com">support@ladybirdweb.com</a></li>
        </ul>

        <hr>

        <h4 class="heading-primary">Business <strong>Hours</strong></h4>
        <ul class="list list-icons list-dark mt-xlg">
            <li><i class="fa fa-clock-o"></i> Monday to Friday 09:30AM to 06:30PM</li>
    
        </ul>

    </div>

</div>
@stop