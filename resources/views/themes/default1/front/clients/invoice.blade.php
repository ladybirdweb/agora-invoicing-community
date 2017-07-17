@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Agora | Invoice
@stop
@section('nav-invoice')
active
@stop

@section('content')

<h2 class="mb-none"> My Invoices</h2>

<div class="col-md-12 pull-center">
    {!! Datatable::table()
    ->addColumn('Number','Date','Total','Action')
    ->setUrl('get-my-invoices') 
    ->setOptions([
                "order"=> [ 1, "desc" ],
                ])
    ->render() !!}

</div>    


@stop