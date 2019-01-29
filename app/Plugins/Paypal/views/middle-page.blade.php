@extends('themes.default1.layouts.front.master')
@section('title')
Paypal Payment
@stop
@section('page-header')
Paypal Payment
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Paypal Payment</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
<section class="page-not-found">
    <div class="row">
        <div style="text-align:center;">
            <div>
                <img src="{{asset('dist/gif/gifloader.gif')}}" alt="ccavenue-gif" />
                <h3>Transaction is being processed </h3>
                <p>Please wait while your transaction is being processed ... </p>
                <p> (Please do not use "Refresh" or "Back" button)</p>

            </div>
        </div>

    </div>
</section>
@stop
@section('end')
<?php
$controller = new \App\Plugins\Paypal\Controllers\ProcessController();
$controller->postForm($data);
?>
@stop
