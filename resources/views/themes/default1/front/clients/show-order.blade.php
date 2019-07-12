@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Orders
@stop
@section('nav-orders')
active
@stop
@section('page-heading')
 <h1>My Account </h1>
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">My Account</li>
<li class="active">Orders</li>
@stop

@section('content')



<style>
    .accordion .card-header a{
        color:currentColor;
    }
        .table td, .table th {
    padding: 0.5rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
    </style>
    <div class="row pb-4">
    <div class="col-lg-12 mb-12 mb-lg-0">
  
    

          <h2>My Orders</h2>

           
                
                    <!-- <div class="content-wrapper"> -->
                        <div class="accordion accordion-modern" id="accordion9">
                        <!-- Content Header (Page header) -->
                        <!-- <section class="content-header"> -->
                        <div class="card card-default">
                            <div class="card-header">
                                        <h4 class="card-title m-0">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion9" href="#collapse9One">
                                                <i class="fas fa-users"></i> Overview
                                            </a>
                                        </h4>
                            </div>
                        
                          
                        <!-- </section> -->
                       <div id="collapse9One" class="collapse show">
                           <div class="card-body">
                                
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
                              

                                 
                                <div class="col">
                                  <div class="card card-default">
                                    <div class="card-header">
                                        <h4 class="card-title m-0">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2Primary" href="#collapse2PrimaryTwo" style="background-color: lightblue;">
                                                User Details
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse2PrimaryTwo" class="collapse">
                                      <table class="table table-hover">
                                            <div class="col-md-6">
                                            <tbody><tr><td><b>Name:</b></td>   <td>{{ucfirst($user->first_name)}}</td></tr>
                                                <tr><td><b>Email:</b></td>     <td>{{$user->email}}</td></tr>
                                                <tr><td><b>Mobile:</b></td><td>@if($user->mobile_code)(<b>+</b>{{$user->mobile_code}})@endif&nbsp;{{$user->mobile}}</td></tr>
                                                <tr><td><b>Address:</b></td>   <td>{{$user->address}}</td></tr>
                                                <tr><td><b>Country:</b></td>   <td>{{\App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}</td></tr>

                                            </tbody> </div>
                                        </table>
                                    </div>
                                </div>
                            </div>



                                       
                                   
                        <div class="col">
                            <div class="accordion accordion-secondary" id="accordion2Secondary">
                                <div class="card card-default">
                                    <div class="card-header">
                                        <h4 class="card-title m-0">
                                           <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2Secondary" href="#collapse2SecondaryTwo">
                                               License Details
                                            </a>
                                        </h4>
                                    </div>

                                   <div id="collapse2SecondaryTwo" class="collapse">
                                       
                                            <table class="table table-hover">
                                            <input type="hidden" name="domainRes" id="domainRes" value={{$allowDomainStatus}}>
                                            <tbody><tr><td><b>License Code:</b></td>         <td>{{$order->serial_key}}</td></tr>
                                                <tr><td><b>Licensed Domain/IP:</b></td>     <td>{{$order->domain}} 
                                                    @if ($licenseStatus == 1)
                                                     @include('themes.default1.front.clients.reissue-licenseModal')
                                                     @include('themes.default1.front.clients.domainRestriction')
                                                <button class='class="btn btn-danger mb-2 pull-right' style="border:none;" id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}">
                                               Reissue License</button>
                                           
                                               @endif
                                                </td>
                                                
                                                
                                            </tr>
                                                 <tr><td><b>Installation Path:</b></td> 
                                                    @if(count($installationDetails['installed_path']) > 0)
                                                    <td>@foreach($installationDetails['installed_path'] as $paths)
                                                        <li>{{$paths}}</li>
                                                        @endforeach
                                                    </td>
                                                    @else
                                                    <td>
                                                    No Active Installation
                                                  </td>
                                                   @endif
                                                    </tr>

                                                <tr><td><b>Installation IP:</b></td> 
                                                @if(count($installationDetails['installed_path']) > 0)    
                                                    <td>
                                                        @foreach($installationDetails['installed_ip'] as $paths)
                                                        <li>{{$paths}}</li>
                                                        @endforeach
                                                    </td>
                                                    @else
                                                     <td>
                                                    --
                                                  </td>
                                                  @endif
                                                </tr>
                                                  

                                                <?php

                                                if (!$subscription || strtotime($subscription->ends_at) < 1) {
                                                    $sub = "--";
                                                } else {
                                                    $date = new DateTime($subscription->ends_at);
                                                    $tz = \Auth::user()->timezone()->first()->name;
                                                    $date->setTimezone(new DateTimeZone($tz));
                                                      
                                                    $sub = $date->format('M j, Y, g:i a ');
                                                    // $sub = $sub2->setTimezone($tz);
                                                }

                                                if (!$subscription || strtotime($subscription->update_ends_at) < 1) {
                                                    $update_sub = "--";
                                                } else {
                                                    $date1 = new DateTime($subscription->update_ends_at);
                                                    $tz = \Auth::user()->timezone()->first()->name;
                                                    $date1->setTimezone(new DateTimeZone($tz));
                                                      
                                                    $update_sub = $date1->format('M j, Y, g:i a ');
                                                    // $sub = $sub2->setTimezone($tz);
                                                }
                                                ?>
                                                <tr><td><b>License Expiry Date:</b></td>   <td>{{$sub}}</td></tr>
                                                <tr><td><b>Update Expiry Date:</b></td>   <td>{{$update_sub}}</td></tr>

                                            </tbody>
                                       
                                        
                                     </table>
                                       
                                    
               
                                 </div>
                             </div>
                         </div>
                                   
                                </div>
                            
                            </div>
                            </div>
                        </div>

                   

                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title m-0">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion9" href="#collapse9Two">
                                    <i class="fas fa-film"></i>  Transaction list
                                </a>
                            </h4>
                        </div>
                        
                        <div id="collapse9Two" class="collapse">
                            <div class="card-body">
                                
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


                  <div class="card card-default">
                    <div class="card-header">
                        <h4 class="card-title m-0">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion9" href="#collapse9Three">
                            <i class="fas fa-bars"></i> Payment receipts
                        </a>
                        </h4>
                    </div>
                        <!-- Content Header (Page header) -->
                       <div id="collapse9Three" class="collapse">
                            <div class="card-body">
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
             if ($('#domainRes').val() == 1) {
                            var oldDomainId = $(this).attr('data-id');
            $("#orderId").val(oldDomainId);
            $("#domainModal").modal();
            $("#domainSave").on('click',function(){
            var id = $('#orderId').val();
            $.ajax ({
                type: 'patch',
                url : "{{url('reissue-license')}}",
                data : {'id':id},
                  beforeSend: function () {
                 $('#response1').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

                },
          
                success: function (data) {
                if (data.message =='success'){
                 var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                  $('#response1').html(result);
                     $('#response1').css('color', 'green');
                setTimeout(function(){
                    window.location.reload();
                },3000);
                  }
               
                }
                
             });
            });
             } else {
            var oldDomainName = $(this).attr('data-name');
            var oldDomainId = $(this).attr('data-id');
            $("#licesnseModal").modal();
           $("#newDomain").val(oldDomainName);
           $("#orderId").val(oldDomainId);
    
        $("#licenseSave").on('click',function(){
      var pattern = new RegExp(/^((?!-))(xn--)?[a-z0-9][a-z0-9-_]{0,61}[a-z0-9]{0,1}\.(xn--)?([a-z0-9\-]{1,61}|[a-z0-9-]{1,30}\.[a-z]{2,})$/);
      var ip_pattern = new RegExp(/^\b((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.|$)){4}\b/);
              if (pattern.test($('#newDomain').val()) || ip_pattern.test($('#newDomain').val())) {
                 $('#domaincheck').hide();
                 $('#newDomain').css("border-color","");
              }
              else{
                 $('#domaincheck').show();
               $('#domaincheck').html("Please enter a valid Domain in the form domain.com or sub.domain.com or enter a valid IP");
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
               
                }, error: function(err) {
                    console.log(err);
                }
                
            });
        });
         }
         });
    </script>



@stop