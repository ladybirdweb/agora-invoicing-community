@extends('themes.default1.layouts.front.master')
@section('title')
Pricing | Faveo Helpdesk
@stop
@section('page-heading')
 {{$headline}}
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">Pricing</li>
@stop
@section('main-class') 
main
@stop


@section('content')


<div class="row">

   
   
      
        
      
         <div class="col-md-12">
           <h4 style="text-align: center;">{{$tagline}} </h4>
        <div class="pricing-table mb-4">
          
        {!! html_entity_decode($templates) !!}
   
        </div>
    </div>
     <br/>    <br/>    <br/>    <br/>  <br/> <br/>
    
    
 <br/>    <br/>    <br/>    <br/>  <br/> <br/>









</div>

@stop



