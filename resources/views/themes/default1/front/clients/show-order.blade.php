@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Agora | Orders
@stop
@section('nav-orders')
active
@stop
@section('page-heading')
 <h1>My Account </h1>
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">My Account</li>
<li class="active">Orders</li>
@stop

@section('content')



<style>
        .table{table-layout:fixed}
        .table td, .table th {
    padding: 0.5rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
    </style>
<div class="col-md-12">
     @include('themes.default1.front.clients.reissue-licenseModal')
    <div class="featured-boxes">

        <div class="row">
            <h2 class="mb-none" style="margin-bottom: 0px;"> My Orders</h2>

            <div class="featured-box featured-box-primary align-left mt-xlg" style="text-align: left;">
                <div class="box-content">
                    <div class="content-wrapper">
                        <!-- Content Header (Page header) -->
                        <section class="content-header">

                            <h2>Overview</h2>

                        </section>
                        <div class="row">
                            <section class="content">
                                
                                    <table class="table">
                                        <tr class="info">
                                           
                                            <td><?php
                                                $date1 = new DateTime($order->created_at);
                                                // $tz = \Auth::user()->timezone()->first()->name;
                                                // $date1->setTimezone(new DateTimeZone($tz));
                                                $date = $date1->format('M j, Y, g:i a ');?>
                                                Date: {{$date}}
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
                              

                                <div id="hide2">
                                    
                                        <table class="table table-hover">
                                            <div class="col-md-6">
                                            <tbody><tr><td><b>Name:</b></td>   <td>{{ucfirst($user->first_name)}}</td></tr>
                                                <tr><td><b>Email:</b></td>     <td>{{$user->email}}</td></tr>
                                                <tr><td><b>Address:</b></td>   <td>{{$user->address}}</td></tr>
                                                <tr><td><b>Country:</b></td>   <td>{{\App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}</td></tr>

                                            </tbody> </div>
                                        </table>
                                   
                                      
                                        <table class="table table-hover">
                                            <div class="col-md-6">
                                            <tbody><tr><td><b>Serial Key:</b></td>         <td>{{$order->serial_key}}</td></tr>
                                                <tr><td><b>Licensed Domain:</b></td>     <td>{{$order->domain}}
                                                <button class='class="btn btn-danger mb-2 pull-right' style="border:none;" id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}"
                                                >
                                Reissue Licesnse</button>
                                                </td>
                                           
                                                 </tr>

                                                <?php
                                                
                                                if (!$subscription || $subscription->ends_at == '' || $subscription->ends_at == '0000-00-00 00:00:00') {
                                                    $sub = "--";
                                                } else {
                                                    $date = new DateTime($subscription->ends_at);
                                                    $tz = \Auth::user()->timezone()->first()->name;
                                                     $date->setTimezone(new DateTimeZone($tz));
                                                      
                                                    $sub = $date->format('M j, Y, g:i a ');
                                                     // $sub = $sub2->setTimezone($tz);


                                                }
                                                ?>
                                                <tr><td><b>Subscription End:</b></td>   <td>{{$sub}}</td></tr>

                                            </tbody>
                                         </div>
                                     </table>
                                   
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

            <div class="featured-box featured-box-primary align-left mt-xlg" style="text-align: left;">
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

            <div class="featured-box featured-box-primary align-left mt-xlg" style="text-align: left;">
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

        $("#reissueLic").click(function(){
            var oldDomainName = $(this).attr('data-name');
            var oldDomainId = $(this).attr('data-id');
            $("#licesnseModal").modal();
           $("#newDomain").val(oldDomainName);
           $("#orderId").val(oldDomainId);
        });
        $("#licenseSave").on('click',function(){
      var pattern = new RegExp(/^((?!-))(xn--)?[a-z0-9][a-z0-9-_]{0,61}[a-z0-9]{0,1}\.(xn--)?([a-z0-9\-]{1,61}|[a-z0-9-]{1,30}\.[a-z]{2,})$/);
              if (pattern.test($('#newDomain').val())){
                 $('#domaincheck').hide();
                 $('#newDomain').css("border-color","");
              }
              else{
                 $('#domaincheck').show();
               $('#domaincheck').html("Please enter a valid Domain");
                 $('#domaincheck').focus();
                  $('#newDomain').css("border-color","red");
                 $('#domaincheck').css({"color":"red","margin-top":"5px"});
                   domErr = false;
                    return false;
              
      }
            var domain = $('#newDomain').val();
            var id = $('#orderId').val();
             
            $.ajax ({
                type: 'patch',
                url : "{{url('change-domain')}}",
                data : {'domain':domain,'id':id},
                  beforeSend: function () {
                 $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

                },
                success: function (data) {
               if (data.message =='success'){
                 var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> Well Done! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                  $('#response').html(result);
                     $('#response').css('color', 'green');
                setTimeout(function(){
                    window.location.reload();
                },3000);
                  }
               
                }
                
            });
        });
    </script>



@stop