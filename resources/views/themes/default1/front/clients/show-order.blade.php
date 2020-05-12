@extends('themes.default1.layouts.front.master')
@section('title')
Orders
@stop
@section('nav-orders')
active
@stop
@section('page-heading')
 View Order
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li><a href= "{{url('my-orders')}}">My Orders</a></li>
<li class="active">View Order</li>
@stop

@section('content')



<style>
    .accordion .card-header a{
        color:currentColor;
    }
    .table td, .table th {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
    </style>
    <div class="row pb-4">
        <div class="col-lg-12 mb-12 mb-lg-0">
            <div class="alert alert-tertiary" style="padding-bottom: 5px; background-color: #49b1bf">
                <div class="row">
                    <div class="col col-md-4">Order No: #{{$order->number}}</div>
                    <div class="col col-md-4">Date: {!! getDateHtml($order->created_at) !!}</div>
                    <div class="col col-md-4">Status: {{$order->order_status}}</div>
                </div>
            </div>

        <div class="row">
            <div class="col col-md-3">
                <div class="tabs tabs-vertical tabs-left">
                    <ul class="nav nav-tabs">
                        <li class="nav-item active">
                            <a class="nav-link" href="#license-details" data-toggle="tab">License Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#user-details" data-toggle="tab">User Details</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#invoice-list" data-toggle="tab">Invoice List</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#payment-receipts" data-toggle="tab">Payment Receipts</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col col-md-9">
                <div class="tab-content" style="overflow-x: auto">
                        <div id="license-details" class="tab-pane active">
                            <h4>License Details</h4>
                            <table class="table">
                                <input type="hidden" name="domainRes" id="domainRes" value={{$allowDomainStatus}}>
                                <tbody>
                                <tr>
                                    <td><b>License Code:</b></td>
                                    <td id="s_key" data-type="serialkey">{{$order->serial_key}}</td>
                                    <td><span class="badge badge-success badge-xs pull-right" id="copied" style="display:none;margin-top:-15px;margin-left:-20px;position: absolute;">Copied</span>
                                        <span data-type="copy" style="font-size: 15px; pointer-events: initial; cursor: pointer; display: block;" id="copyBtn" title="Click to copy to clipboard"><i class="fa fa-clipboard"></i></span>
                                    </td>
                                </tr>
                                @if ($licenseStatus == 1)
                                    <tr>

                                        <td><b>Licensed Domain/IP:</b></td>
                                        <td>{{$order->domain}} </td>

                                        <td>
                                            @include('themes.default1.front.clients.reissue-licenseModal')
                                            @include('themes.default1.front.clients.domainRestriction')
                                            <button class='class="btn btn-danger mb-2' style="border:none;" id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}">
                                                Reissue License</button></td>
                                    </tr>
                                    <tr><td><b>Installation Path:</b></td>
                                        @if($installationDetails)

                                            <td>@foreach($installationDetails['installed_path'] as $paths)
                                                    {{$paths}}<br>
                                                @endforeach
                                            </td>
                                        @else
                                            <td>
                                                No Active Installation
                                            </td>
                                        @endif
                                        <td></td>
                                    </tr>


                                    <tr><td><b>Installation IP:</b></td>
                                        @if($installationDetails)
                                            <td>
                                                @foreach($installationDetails['installed_ip'] as $paths)
                                                    {{$paths}}<br>
                                                @endforeach
                                            </td>
                                        @else
                                            <td>
                                                --
                                            </td>
                                        @endif

                                        <td></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><b>Version:</b></td>
                                    <td>{!! $versionLabel !!}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><b>License Expiry Date:</b></td>
                                    <td>{!! $licdate !!}</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td><b>Update Expiry Date:</b></td>
                                    <td>{!! $date !!}</td>
                                    <td></td>
                                </tr>

                                </tbody>


                            </table>
                        </div>

                        {{-- User details --}}
                        <div id="user-details" class="tab-pane">
                            <h4>User Details</h4>
                            <table class="table">
                                <div class="col-md-6">
                                    <tbody>
                                        <tr><td><b>Name:</b></td>   <td>{{ucfirst($user->first_name)}}</td></tr>
                                        <tr><td><b>Email:</b></td>     <td>{{$user->email}}</td></tr>
                                        <tr><td><b>Mobile:</b></td><td>@if($user->mobile_code)(<b>+</b>{{$user->mobile_code}})@endif&nbsp;{{$user->mobile}}</td></tr>
                                        <tr><td><b>Address:</b></td>   <td>{{$user->address}}</td></tr>
                                        <tr><td><b>Country:</b></td>   <td>{{\App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}</td></tr>
                                    </tbody>
                                </div>
                            </table>
                        </div>


                        {{--  Invoice List --}}
                        <div id="invoice-list" class="tab-pane">
                            <h4>Invoice List</h4>
                            <table id="showorder-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Product</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        {{--  Payment Receipts --}}
                        <div id="payment-receipts" class="tab-pane">
                            <h4>Payment Receipts</h4>
                            <table id="showpayment-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Total</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
            </div>
        </div>
        </div>
    </div>


<script src="{{asset('common/js/licCode.js')}}"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#showorder-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! Url('get-my-invoices/'.$order->id.'/'.$user->id) !!}',
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