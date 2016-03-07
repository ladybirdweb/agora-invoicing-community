@extends('themes.default1.layouts.front.master')
@section('title')
pricing
@stop
@section('page-header')
Pricing
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Pricing</li>
@stop
@section('main-class') 
main
@stop


    @section('content')
    
    <div class="row">

    {!! html_entity_decode($template) !!}
    
    </div>

    @stop
</div>