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
    <style>
        [type=search] {
            padding-right: 20px;
        }
    </style>
    @auth
        @php
            $amt = \DB::table('payments')->where('user_id',\Auth::user()->id)->where('payment_method','Credit Balance')->where('payment_status','success')->value('amt_to_credit');
            $formattedValue = currencyFormat($amt, \Auth::user()->currency , true);

        @endphp
        <button class="btn-credit open-createCreditDialog" style="background-color: white; border: none; margin-left: 960px; margin-bottom: 10px;">
            <i class="fas fa-credit-card"></i> Credits: {!! $formattedValue !!}
        </button>
        <script>
            $(document).ready(function () {
                $(document).on("click", ".open-createCreditDialog", function () {
                    $('#credit').modal('show');
                });
            });
        </script>
    @endauth
    <div class="col-md-12 pull-center">

            <div class="modal fade" id="credit" tabindex="-1" role="dialog" aria-labelledby="creditLabel" aria-hidden="true">

                <div class="modal-dialog credit-dialog">
                    <div class="modal-content credit-content">
                        <div class="modal-header credit-header">
                            <h4 class="modal-title credit-title">Credit Balance: {!! $formattedValue !!}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body credit-body">
                            <ul class="list-group">
                                <h6 class="modal-title">Credit Balance History</h6>
                                <br>
                                @php
                                    $payment_id = \DB::table('payments')->where('user_id',\Auth::user()->id)->where('payment_method','Credit Balance')->where('payment_status','success')->value('id');
                                    $payment_activity=\DB::table('credit_activity')->where('payment_id',$payment_id)->where('role','user')->orderBy('created_at', 'desc')->get();
                                @endphp
                                @if(!$payment_activity->isEmpty())
                                    @foreach($payment_activity as $activity)
                                        <li class="list-group-item">
                                            {!! getDateHtml($activity->created_at) !!}
                                            <br>
                                            {!! $activity->text !!}
                                            <br>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item" style="text-align: center">No activity has been recorded for this credit so far.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <table id="invoice-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

            <thead><tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Order No</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Balance</th>
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
            order: [[ 1, "asc" ]],
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
                {data: 'total', name: 'grand_total'},
                {data: 'paid', name: 'paid'},
                {data: 'balance', name: 'balance'},
                {data: 'status', name: 'status'},
                {data: 'Action', name: 'Action', searchable: true}
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
    <style>

        /* Custom styles */
        .btn-credit {
            font-size: 14px;
            padding: 5px 10px;
        }
        .credit-dialog {
            max-width: 600px;
        }
        .credit-content {
            border: none;
            border-radius: 10px;
        }
        .credit-header {
            border-bottom: none;
        }
        .credit-title {
            font-size: 18px;
        }
        .credit-body {
            max-height: 400px;
            overflow-y: auto;
        }
        .list-group-item {
            border: none;
            padding: 8px 15px;
            margin-bottom: 5px; /* Add margin between list items */
            background-color: #f8f9fa; /* Light gray background */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
    </style>





@stop