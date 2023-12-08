
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
        <li><a class="text-primary" href="{{url('my-invoices')}}">Home</a></li>
    @else
         <li><a class="text-primary" href="{{url('login')}}">Home</a></li>
    @endif
     <li class="active text-dark">My Invoices</li>
@stop 
<?php $check = App\User::where('id', Auth::id())->value('company');

?>
@section('content')
   <style>
        [type=search] {
            padding-right: 20px;
        }
        #myModal {
            position: absolute;
            top: 65%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
        }
        #invoice-table td {
            padding: 0px; 
            text-align: center;
        }
        td a {
        margin-top: 5px; 
        }
</style>
    @auth
        @php
            $amt = \DB::table('payments')->where('user_id',\Auth::user()->id)->where('payment_method','Credit Balance')->where('payment_status','success')->value('amt_to_credit');
            $formattedValue = currencyFormat($amt, getCurrencyForClient(\Auth::user()->country) , true);

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


            <div class="modal fade" id="credit" tabindex="-1" role="dialog" aria-labelledby="creditLabel" aria-hidden="true">

                <div class="modal-dialog credit-dialog">
                    <div class="modal-content credit-content">
                        <div class="modal-header credit-header">
                            <h4 class="modal-title credit-title">Credit Balance: {!! $formattedValue !!}</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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

           <div class="row pt-2">

            @include('themes.default1.front.clients.navbar')


                <div class="col-lg-9">

                   <div class="tab-pane tab-pane-navigation active" id="invoices" role="tabpanel">

                        <div id="examples" class="container py-4">

                            <div class="row">

                                <div class="col">


                                                <table id="invoice-table" class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Date</th>
                                                    <th>Order No</th>
                                                    <th>Total</th>
                                                    <th>Paid</th>
                                                    <th>Balance</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr> </thead>
                                            </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Required Details</h5>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url('store-basic-details') }}">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label"><b>Company Name</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control required" id="company" name="company">

                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label"><b>Address</b><span style="color: red;">*</span></label>
                            <textarea class="form-control required" id="address" name="address"></textarea>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="button-text"> <i class="fa fa-save">&nbsp;&nbsp;</i>Save</span>
                    </button>
                </div>

            </div>
        </div>
    </div>



    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />

    <script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
    <script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">



        $(document).ready(function() {
            $('#submit').click(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Custom validation logic here
                const company = $('#company').val();
                const address = $('#address').val();

                // Check if the fields are empty
                if (company.trim() === '' || address.trim() === '') {
                    // Display an error message or take appropriate action
                    alert('Company name and address are required.');
                } else {
                    // If fields are not empty, proceed with the form submission
                    var btn = $(this);
                    btn.prop('disabled', true);
                    btn.find('.spinner-border').removeClass('d-none');
                    btn.find('.button-text').text('Saving...');

                    var formData = $('#myModal form').serialize();

                    $.ajax({
                        url: $('#myModal form').attr('action'),
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            btn.prop('disabled', false);
                            btn.find('.spinner-border').addClass('d-none');
                            btn.find('.button-text').text('Save');
                            $('#myModal').modal('hide');
                            localStorage.setItem('isModalFilled', 'true');
                        },
                        error: function(xhr, status, error) {
                            btn.prop('disabled', false);
                            btn.find('.spinner-border').addClass('d-none');
                            btn.find('.button-text').text('Save');
                        }
                    });
                }
            });
        });

    </script>
    <script>
       var status = '';

        $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 1, "asc" ]],
            ajax: {
                "url": '{!! route('get-my-invoices', "status=$request->status") !!}',

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
    @if(!$check)
        <script>
            $(window).on('load', function () {
                const isFirstLogin = !localStorage.getItem('isLoggedIn');
                const isModalFilled = localStorage.getItem('isModalFilled');

                if (isFirstLogin && !isModalFilled) {
                    $('#myModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }

                $('#myModal form').on('submit', function () {
                    localStorage.setItem('isModalFilled', 'true');
                });
            });
  
        </script>

    @endif
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
 