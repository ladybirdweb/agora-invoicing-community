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
                                </div>
                            </div>

                   
                    <div class="control-sidebar-bg"></div>
                </div><!-- ./wrapper -->
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

                            <h2>
                                Transaction list

                            </h2>

                        </section>

                        <table id="showorder-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

                    <thead><tr>
                            <th>Number</th>
                            <th>Products</th>
                            
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr></thead>


                </table>

                     

                    </div>                             
                </div>
            </div>					
        </div>

    </div>
</div>	
       
           <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#showorder-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! Url('get-my-invoices/'.$order->id.'/'.$user->id) !!}',
             // ajax: {{Url('get-my-invoices/'.$order->id.'/'.$user->id)}}
             // url('service-desk/problems/attach/existing/'.$ticket->id
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
    
            columns: [
                {data: 'number', name: 'number'},
                {data: 'products', name: 'products'},
                {data: 'date', name: 'date'},
                {data: 'total', name: 'total'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>








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

                        <table id="showpayment-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

                    <thead><tr>
                            <th>InvoiceNumber</th>
                            <th>Total</th>
                            
                            <th>Method</th>
                            
                            <th>Status</th>
                            <th>Created At</th>

                        </tr></thead>


                </table>

                    
                    </div>                             
                </div>
            </div>					
        </div>

    </div>
</div>	
 <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#showpayment-table').DataTable({
            processing: true,
            serverSide: true,
             ajax: '{!! Url('get-my-payment-client/'.$order->id.'/'.$user->id) !!}',
      
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
    
            columns: [
                {data: 'number', name: 'number'},
                {data: 'total', name: 'total'},
                {data: 'payment_method', name: 'payment_method'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'created_at', name: 'created_at'},
                
              
                
               
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>



@stop