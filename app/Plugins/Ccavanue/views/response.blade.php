@extends('themes.default1.layouts.front.master')
@section('title')
Ccavenue Response
@stop
@section('page-header')
Ccavenue Response
@stop
@section('main-class') "main shop" @stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="featured-box featured-box-primary">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="heading-primary text-uppercase mb-md">Ccavenue Response
                        &nbsp;
                        @if($url)
                        <a href="{{$url}}" class="btn btn-primary">Pay Now</a>
                        @endif
                    </h4>
                    <table class="table table-bordered">
                        @for ($i = 0; $i < $dataSize; $i++) 
                        <?php $information = explode('=', $decryptValues[$i]); ?>

                        <tr><td>{{$information[0]}}</td><td>{{$information[1]}}</td></tr>
                        @endfor
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

