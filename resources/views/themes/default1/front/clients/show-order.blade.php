@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Agora | Orders
@stop
@section('nav-orders')
active
@stop

@section('content')




<div class="col-md-12">

    <div class="featured-boxes">
        <div class="row">

            <div class="featured-box featured-box-primary align-left mt-xlg">
                <div class="box-content">
                    <div class="content-wrapper">
                        <!-- Content Header (Page header) -->
                        <section class="content-header">

                            <h2>My Order</h2>

                        </section>
                        <div class="row">
                            <section class="content">
                                <div class="col-md-12">
                                    <table class="table">
                                        <tr class="info">
                                            <th scope="row">

                                            </th>
                                            <td>
                                                Date: {{$order->created_at}}
                                            </td>
                                            <td>
                                                Invoice No: #{{$invoice->number}}
                                            </td>
                                            <td>
                                                Order No: #{{$order->number}}
                                            </td>
                                            <td>
                                                Status: {{$order->order_status}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>  
                                </div>

                                <div id="hide2">
                                    <div class="col-md-6">
                                        <table class="table table-hover">
                                            <tbody><tr><td><b>Name:</b></td>   <td>{{ucfirst($user->first_name)}}</td></tr>
                                                <tr><td><b>Email:</b></td>     <td>{{$user->email}}</td></tr>
                                                <tr><td><b>Address:</b></td>   <td>{{$user->address}}</td></tr>
                                                <tr><td><b>Country:</b></td>   <td>{{\App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}</td></tr>

                                            </tbody></table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover">
                                            <tbody><tr><td><b>Serial Key:</b></td>         <td>{{$order->serial_key}}</td></tr>
                                                <tr><td><b>Domain Name:</b></td>     <td>{{$order->domain}}</td></tr>
                                                <?php
                                                
                                                if (!$subscription || $subscription->ends_at == '' || $subscription->ends_at == '0000-00-00 00:00:00') {
                                                    $sub = "--";
                                                } else {
                                                    $sub1 = $subscription->ends_at;
                                                     $date = date_create($sub1);
                                                    $sub = date_format($date,'l, F j, Y H:m A');
                                                }
                                                ?>
                                                <tr><td><b>Subscription End:</b></td>   <td>{{$sub}}</td></tr>

                                            </tbody></table>
                                    </div>
                                </div></div>

                    </div>
                    <div class="control-sidebar-bg"></div>
                </div><!-- ./wrapper -->
            </div> 
        </div>
    </div>
</div>					
<div class="col-md-12">

    <div class="featured-boxes">
        <div class="row">

            <div class="featured-box featured-box-primary align-left mt-xlg">
                <div class="box-content">
                    <div class="content-wrapper">
                        <!-- Content Header (Page header) -->
                        <section class="content-header">

                            <h2>
                                Transcation list

                            </h2>

                        </section>
                        <!--<a href="#" class="btn btn-desiable pull-left mb-xl" data-loading-text="Loading...">Invoice</a>--> 

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

<div class="col-md-12">

    <div class="featured-boxes">
        <div class="row">

            <div class="featured-box featured-box-primary align-left mt-xlg">
                <div class="box-content">
                    <div class="content-wrapper">
                        <!-- Content Header (Page header) -->
                        <section class="content-header">
                            <h2>Payment receipts</h2>
                            <!--<a href="shortcodes-pricing-tables.html"  class="btn btn-primary pull-left mb-xl" data-loading-text="Loading...">Payment</a>--> 
                        </section>
                       {!! Datatable::table()
                        ->addColumn('Invoice Number','Total','Method','Status','Created At')
                        ->setUrl('../get-my-payment-client/'.$order->id.'/'.$user->id) 
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



@stop