@extends('themes.default1.layouts.front.master')
@section('title')
Pricing | Faveo Helpdesk
@stop
@section('page-header')
Pricing
@stop
@section('page-heading')
 <h1>Pricing</h1>
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
    @if (count($errors) > 0)
  </br>
</br>
                <div class="alert alert-danger alert-dismissable">


                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.


                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(Session::has('success'))
              </br>
            </br>
                <div class="alert alert-success alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('success')}}
                </div>
                @endif
                <!-- fail message -->
                @if(Session::has('fails'))
              </br>
            </br>
                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('fails')}}
                </div>
                @endif
   
   
      
        
      
         <div class="col-md-12">
          <h1 style="text-align: center;"> <b>{{($headline)}} </b> </h1>
           <h4 style="text-align: center;">{{$tagline}} </h4>
        <div class="pricing-table mb-4">
          
        {!! html_entity_decode($templates) !!}
   
        </div>
    </div>
     <br/>    <br/>    <br/>    <br/>  <br/> <br/>
    
    
 <br/>    <br/>    <br/>    <br/>  <br/> <br/>









</div>

@stop



