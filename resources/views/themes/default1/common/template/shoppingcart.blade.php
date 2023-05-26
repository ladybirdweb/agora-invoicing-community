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
<style>
  .highlight_batch {
    background: green;
    padding: 0px 5px;
    font-size: smaller;
    color: #FFF;
    border-radius: 16px;
    border-bottom-left-radius: 0;
    position: absolute;
    right: -20px;
    top: -25px;
}
    .subscription {
    display: block;
    width: 100%;
    overflow-x: auto;
    }
         .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {display:none;}

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
          border-radius: 50%;
        }
        .scrollit {
            overflow:scroll;
            height:600px;
        }
        .pricing-table .plan .plan-price .price-label {
            text-transform: lowercase;
        }
</style>

<div class="row">

 <div class="col-md-12">
@if($description == "Per Month")
  <div class="row mb-5">
            <div class="col text-center">
              <div class="d-flex justify-content-center align-items-center">
                <div class="text-3 p-relative bottom-7">Yearly</div>
                <div class="px-2">
                  <label class="switch toggle_event_editing">

                    <input data-content-switcher data-content-switcher-content-id="pricingTable1" type="checkbox" class="form-check-input checkbox" checked>
                    <span class="slider round"></span>
                  </label>
                  
                </div>
                <div class="text-3 p-relative bottom-7">Monthly</div>
              </div>
            </div>
          </div>
          @endif
           <h4 style="text-align: center;">{{$tagline}} </h4>
        <div class="pricing-table mb-4">
          
        {!! html_entity_decode($templates) !!}
   
        </div>
    </div>
     <br/>    <br/>    <br/>    <br/>  <br/> <br/>
    
    
 <br/>    <br/>    <br/>    <br/>  <br/> <br/>









</div>

@stop



