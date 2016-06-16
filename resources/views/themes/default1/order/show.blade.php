@extends('themes.default1.layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Orders</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <div class="row">
                                <div class="col-md-3">
                                    <b>Date: </b>{{$order->created_at}} 
                                </div>
                                <div class="col-md-3">
                                    <b>Invoice No: </b> #{{$invoice->number}}
                                </div>
                                <div class="col-md-3">
                                    <b>Order No: </b>  #{{$order->number}} 

                                </div>
                                <div class="col-md-3">
                                    <b>Status: </b>{{$order->order_status}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <table class="table table-hover">
                                <tbody><tr><td><b>Name:</b></td><td><a href="{{url('clients/'.$user->id)}}">{{ucfirst($user->first_name)}}</a></td></tr>
                                    <tr><td><b>Email:</b></td><td>{{$user->email}}</td></tr>
                                    <tr><td><b>Mobile:</b></td><td>@if($user->mobile_code)<b>+</b>{{$user->mobile_code}}@endif{{$user->mobile}}</td></tr>
                                    <tr><td><b>Address:</b></td><td>{{$user->address}}, 
                                            {{ucfirst($user->town)}}, 
                                            @if(key_exists('name',\App\Http\Controllers\Front\CartController::getStateByCode($user->state)))
                                            {{\App\Http\Controllers\Front\CartController::getStateByCode($user->state)['name']}}
                                            @endif
                                        </td></tr>
                                    <tr><td><b>Country:</b></td><td>{{\App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}</td></tr>

                                </tbody></table>
                        </div>
                        <div class="col-md-6">


                            <table class="table table-hover">
                                <tbody><tr><td><b>Serial Key:</b></td><td>{{$order->serial_key}}</td></tr>
                                    <tr><td><b>Domain Name:</b></td><td contenteditable="true" id="domain">{{$order->domain}}</td></tr>
                                    <?php
                                    $sub = "--";
                                    if ($subscription) {
                                        if ($subscription->ends_at != '' || $subscription->ends_at != '0000-00-00 00:00:00') {
                                            $sub = $subscription->ends_at;
                                        }
                                    }
                                    ?>
                                    <tr><td><b>Subscription End:</b></td><td>{{$sub}}</td></tr>

                                </tbody></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Transcatin list</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! Datatable::table()
                        ->addColumn('Number','Products','Date','Total','Status','Action')
                        ->setUrl('../get-my-invoices/'.$order->id.'/'.$user->id) 
                        ->setOptions([
                        "order"=> [ 1, "desc" ],
                        ])
                        ->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Payment receipts</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! Datatable::table()
                        ->addColumn('<input type="checkbox" class="checkbox-toggle">','Invoice Number','Total','Method','Status','Payment Date')
                        ->setUrl('../get-my-payment/'.$order->id.'/'.$user->id) 
                        ->setOptions([
                        "order"=> [ 1, "desc" ],
                        "dom" => "Bfrtip",
                        "buttons" => [
                        [
                        "text" => "Delete",
                        "action" => "function ( e, dt, node, config ) {
                        e.preventDefault();
                        var answer = confirm ('Are you sure you want to delete from the database?');
                        if(answer){
                        $.ajax({
                        url: '../payment-delete',
                        type: 'GET',
                        data: $('#check:checked').serialize(),

                        beforeSend: function () {
                        $('#gif').show();
                        },
                        success: function (data) {
                        $('#gif').hide();
                        $('#response').html(data);
                        location.reload();
                        }

                        });
                        }
                        }"
                        ]
                        ],

                        ])

                        ->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('icheck')
<script>
    $(function () {


        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });


    });
</script>
@stop
@section('datepicker')
<script>

    $("#domain").blur(function () {
        var value = $(this).text();
                var id = {{$order-> id}};
            $.ajax({
            type: "GET",
                    url: "{{url('change-domain')}}",
                    data: {'domain':value, 'id':id},
                    success: function () {
                        alert('Updated');
                    },
                    error: function () {
                        alert('Invalid URL');
                    }

            });
    });


</script>
@stop
