@extends('themes.default1.layouts.front.master')
@section('title')
Orders
@stop
@section('page-header')
<br>
Cart
@stop
@section('nav-orders')
active
@stop
@section('page-heading')
 My Orders
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">My Orders</li>
@stop

@section('content')

    <div class="col-md-12 pull-center">

                <table id="order-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">


             

                    <thead><tr>
                            <th>Product Name</th>
                            <th>Order No</th>
                            <th>Version</th>
                            <th>Updates Expiry Date</th>
                            
                            <th>Action</th>
                        </tr></thead>


                </table>
                </div>   
     <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
    <script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $('#order-table').DataTable({
            destroy:true,
            processing: true,
            stateSave: true,
            serverSide: true,
            order: [[ 0, "desc" ]],
            ajax: {
            "url":  '{!! route('get-my-orders') !!}',
               error: function(xhr) {
               if(xhr.status == 401) {
                alert('Your session has expired. Please login again to continue.')
                window.location.href = '/login';
               }
            }

            },
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
    
            columns: [
                {data: 'product_name', name: 'product_name'},
                 {data: 'number', name: 'number'},
                 {data: 'version', name: 'version'},
                {data: 'expiry', name: 'expiry'},
              
                // {data: 'group', name: 'Group'},
                // {data: 'currency', name: 'Currency'},
                {data: 'Action', name: 'Action'}
            ],
            "fnDrawCallback": function( oSettings ) {
                 $(function () {
                  $('[data-toggle="tooltip"]').tooltip({
                    container : 'body'
                  });
                });
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>


@stop











