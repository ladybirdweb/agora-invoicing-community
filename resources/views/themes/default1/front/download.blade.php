@extends('themes.default1.layouts.front.master')
@section('title')
Cart
@stop
@section('page-heading')
Faveo Download
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Download</li>
@stop
@section('main-class') "main shop" @stop
@section('content')

<section class="page-not-found">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="page-Download-main">
                
                <h2><span >Download</span>&nbsp;<i class="fa fa fa-download "></i></h2>
                <p>&nbsp;&nbsp;&nbsp;Your download will begin in a moment. If it doesn't, Click <a href="{{$release}}">here</a> to download.</p>
            </div>
        </div>

    </div>
</section>
@stop
@section('end')
<?php 
header("Location: $release"); 
exit;
?>
@stop