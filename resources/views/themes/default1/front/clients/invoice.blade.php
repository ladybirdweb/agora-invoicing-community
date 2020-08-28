@extends('themes.default1.layouts.front.master')
@section('title')
Invoice
@stop
@section('nav-invoice')
active
@stop
@section('page-heading')
 My Invoices
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">My Invoices</li>
@stop

@section('content')

<div class="col-md-12 pull-center">

	 <table id="invoice-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

                    <thead><tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Order No</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr></thead>


                </table>

            </div>
            <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />

<script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
<script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 0, "desc" ]],
            ajax: {
            "url":  '{!! route('get-my-invoices') !!}',
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
                {data: 'number', name: 'number'},
                {data: 'date', name: 'date'},
                {data: 'orderNo', name: 'orderNo'},
                {data: 'total', name: 'total'},
                {data: 'status', name: 'status'},
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