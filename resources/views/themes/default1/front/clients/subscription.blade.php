@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Subscriptions
@stop
@section('nav-subscriptions')
active
@stop

@section('content')

<h2 class="mb-none"> Subscriptions Table</h2>

<div class="col-md-12 pull-center">
    {!! Datatable::table()
    ->addColumn('Number','Date','Total','Action')
    ->setUrl('get-my-subscriptions') 
    ->render() !!}
</div>    


@stop