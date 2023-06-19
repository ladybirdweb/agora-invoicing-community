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
 @php
$users = DB::table('users')->where('id',Auth::id())->first(); 
@endphp


@section('content')
<!-- modal styling -->
<style>
    [type=search] {
        padding-right: 20px;
    }
    .modal {
        position: absolute;
        top: 65%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 500px;
        height: 500px;
    }
</style>

<!-- success meassage -->
@if(session('success_message'))
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong><i class="far fa-thumbs-up"></i> Well Done! </strong>
    {{ session('success_message') }}
</div>
@endif


<!-- invoice table -->
<div class="col-md-12 pull-center">
    <table id="invoice-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
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
            </tr>
        </thead>
    </table>
</div>
<!-- invoice tabel end -->

<!-- Modal -->
@if(!$users->password)
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
                        <input type="text" class="form-control" id="company" name="company">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label"><b>Address</b><span style="color: red;">*</span></label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
            </div>
            <!--<div class="modal-footer">-->
            <!--    <button type="submit" class="btn btn-primary " id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-save">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}</button>-->
            <!--</div>-->
            <div class="modal-footer">
    <button type="submit" class="btn btn-primary" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving...">
        <i class="fa fa-save">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}
    </button>
</div>
            </form>
        </div>
    </div>
</div>
@endif
<!-- modal end -->

<!-- required js and css -->
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
<script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- save basic details -->
<!-- save basic details -->
<script>
    // $(document).ready(function() {
    //     $('#submit').click(function(event) {
    //         event.preventDefault(); // Prevent the default form submission

    //         var btn = $(this);
    //         btn.button('loading'); 

    //         // Show the spinner icon
    //         btn.html('<i class="fa fa-circle-o-notch fa-spin"></i> Saving...');

    //         // Get the form data
    //         var formData = $('#myModal form').serialize();

    //         // Send the AJAX request
    //         $.ajax({
    //             url: $('#myModal form').attr('action'),
    //             type: 'POST',
    //             data: formData,
    //             success: function(response) {
    //                 // Enable the button and restore the original text
    //                 btn.button('reset');

    //                 // Hide the modal
    //                 $('#myModal').modal('hide');
    //             },
    //             error: function(xhr, status, error) {
    //                 // Enable the button and restore the original text
    //                 btn.button('reset');

    //                 // Handle the error as needed
    //             }
    //         });
    //     });
    // });
</script>
<script>
    $(document).ready(function() {
        $('#submit').click(function(event) {
            event.preventDefault(); // Prevent the default form submission

            var btn = $(this);
            btn.button('loading'); 

            // Show the spinner icon
            btn.html('<i class="fa fa-circle-o-notch fa-spin"></i> Saving...');

            // Get the form data
            var formData = $('#myModal form').serialize();

            // Send the AJAX request
            $.ajax({
                url: $('#myModal form').attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Enable the button and restore the original text
                    btn.button('reset');

                    // Hide the modal
                    $('#myModal').modal('hide');

                    // Show the success message
                    var successMessage = '<div class="alert alert-success alert-dismissable">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span></button>' +
                        '<strong><i class="far fa-thumbs-up"></i> Well Done! </strong>' +
                        response.message +
                        '</div>';
                    $('#content').prepend(successMessage);
                },
                error: function(xhr, status, error) {
                    // Enable the button and restore the original text
                    btn.button('reset');

                    // Handle the error as needed
                }
            });
        });
    });
</script>

<!-- save basic details end -->

<script type="text/javascript">
    $(window).on('load', function() {
        const isFirstLogin = !localStorage.getItem('isLoggedIn');
        const isModalFilled = localStorage.getItem('isModalFilled');
        if (isFirstLogin && !isModalFilled) {
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
        $('#myModal form').on('submit', function() {
            localStorage.setItem('isModalFilled', 'true');
        });
    });
</script>
<!-- save basic details end -->

@stop