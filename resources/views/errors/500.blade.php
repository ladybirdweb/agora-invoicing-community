@extends('themes.default1.layouts.front.master')
@section('title')
500
@stop
@section('page-header')
500
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">500</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
<section class="page-not-found">
    <div class="row">
        <div style="text-align:center;">
            <div class="page-not-found-main">
                <h3>Sorry, Something went wrong <i class="fa fa-bug"></i></h3>
                <p>We're working on it and we'll get it fixed as soon as we can.</p>
                <p><a href="{{url('home')}}">Go Back</a></p>
            </div>
        </div>

    </div>
</section>
@stop