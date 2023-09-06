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
        .planhide{
            display: none;
        }
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
    .strike
    {
        text-decoration: line-through;
        font-weight: normal;
        font-size: 20px;
        color: #212529;
        font-weight: 300;
        display: flex;
        justify-content: center;
        align-items: flex-start;
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


        .cantact
        {
            font-size: 2.7 rem !important;
        }
        .sales
        {
            margin-top: -20px !important;
        }

        .stylePlan{
            display: block;
            width: 100%;
            text-align: center;
        }


</style>
      <?php
     $templates = preg_replace(
        '/<span\s+class="price">Custom Pricing<\/span>/',
        '<span class="price cantact" >Custom Pricing</span>',
        $templates
    );
  ?>
<div class="row">

 <div class="col-md-12">




         @if($status && $status->status == "1")
         
            <div class="row mb-5">
            <div class="col text-center">
              <div class="d-flex justify-content-center align-items-center">
                <div class="text-3 p-relative bottom-7">Monthly</div>
                <div class="px-2">
                  <label class="switch toggle_event_editing">

                    <input data-content-switcher data-content-switcher-content-id="pricingTable1" type="checkbox" id="kol" class="form-check-input checkbox" checked>
                    <span class="slider round"></span>
                  </label>
                  
                </div>
                <div class="text-3 p-relative bottom-7">Yearly</div>
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

<!-- Your HTML template -->
<!-- ... -->

<!-- Your JavaScript code -->
<!-- Your HTML template -->
<!-- ... -->

<!-- Your JavaScript code -->
<script>
  $(document).ready(function() {
    $('.toggle_event_editing input').on('change', function() {
      const toggleValue = $(this).prop('checked');
      if (toggleValue) {
        console.log('Toggle switch is selected.');
        handleSelectedState();
      } else {
        console.log('Toggle switch is unselected.');
        handleUnselectedState();
      }
    });
    function handleSelectedState() {
      const priceDisplay = $('#priceDisplay');
      const yearlyPrice = 'Yearly Price';
      priceDisplay.text(yearlyPrice);
      $.ajax({
        type: 'POST',
        url: "{{ url('store_toggle_state') }}", 
        data: { toggleState: 'selected' },
        success: function(response) {
          console.log('Selected state value sent to the controller successfully.');
        },
        error: function(error) {
          console.error('Error sending selected state value to the controller:', error);
        }
      });
    }
    function handleUnselectedState() {
      const priceDisplay = $('#priceDisplay');
      const monthlyPrice = 'Monthly Price';
      priceDisplay.text(monthlyPrice);
      $.ajax({
        type: 'POST',
        url: "{{ url('store_toggle_state') }}", 
        data: { toggleState: 'unselected' }, 
        success: function(response) {
          console.log('Unselected state value sent to the controller successfully.');
        },
        error: function(error) {
          console.error('Error sending unselected state value to the controller:', error);
        }
      });
    }
  });
</script>
@stop



