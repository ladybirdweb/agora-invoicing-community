@extends('themes.default1.layouts.front.master')
@section('title')
404
@stop
@section('page-header')
404
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">404</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
<section class="page-not-found">
    <div class="row">
        <div style="text-align:right;">
            <div>
                <h1 style="font-size: 6.6em;">404 <i class="fa fa-file"></i></h1>
                <p>We're sorry, but the page you were looking for doesn't exist.</p>
            </div>
        </div>

    </div>
</section>
@stop
