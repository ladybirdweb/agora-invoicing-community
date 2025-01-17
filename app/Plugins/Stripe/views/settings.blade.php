@extends('themes.default1.layouts.master')

@section('title', 'Stripe')

@section('content-header')
    <div class="col-sm-6">
        <h1>Stripe</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('settings') }}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ url('plugin') }}"><i class="fa fa-dashboard"></i> Payment Gateways</a></li>
            <li class="breadcrumb-item active">Stripe</li>
        </ol>
    </div>
@stop

@section('content')
    <div class="col-md-12">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">API Keys</h3>
            </div>
            <div class="card-body">
                <div id="alertMessage"></div>
                <form id="stripeForm">
                    <div class="form-group">
                        <label for="stripe_key" class="required">Stripe Publishable Key</label>
                        <input
                                type="text"
                                id="stripe_key"
                                name="stripe_key"
                                value="{{ $stripeKeys->stripe_key }}"
                                class="form-control"
                                placeholder="Enter Stripe Key">
                        @error('stripe_key')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <small id="stripe_keycheck" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="stripe_secret" class="required">Stripe Secret Key</label>
                        <input
                                type="text"
                                id="stripe_secret"
                                name="stripe_secret"
                                value="{{ $stripeKeys->stripe_secret }}"
                                class="form-control"
                                placeholder="Enter Stripe Secret Key">
                        @error('stripe_secret')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <small id="stripe_secretcheck" class="text-danger"></small>
                    </div>
                    <button type="button" class="btn btn-primary" id="key_update">
                        <i class="fa fa-sync-alt"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#key_update").on('click', function () {
                $('#key_update').html("<i class='fas fa-circle-notch fa-spin'></i> Please Wait...");
                $("#key_update").attr('disabled', true);

                let valid = true;

                // Validation
                if ($('#stripe_key').val().trim() === "") {
                    valid = false;
                    $('#stripe_keycheck').html("Please Enter Stripe Key").show();
                    $('#stripe_key').addClass('is-invalid');
                } else {
                    $('#stripe_keycheck').hide();
                    $('#stripe_key').removeClass('is-invalid');
                }

                if ($('#stripe_secret').val().trim() === "") {
                    valid = false;
                    $('#stripe_secretcheck').html("Please Enter Stripe Secret").show();
                    $('#stripe_secret').addClass('is-invalid');
                } else {
                    $('#stripe_secretcheck').hide();
                    $('#stripe_secret').removeClass('is-invalid');
                }

                if (!valid) {
                    $("#key_update").attr('disabled', false);
                    $('#key_update').html("<i class='fa fa-sync-alt'></i> Update");
                    return false;
                }

                // AJAX Request
                $.ajax({
                    url: '{{ url("update-api-key/payment-gateway/stripe") }}',
                    type: 'GET',
                    data: {
                        "stripe_key": $('#stripe_key').val(),
                        "stripe_secret": $('#stripe_secret').val()
                    },
                    success: function (data) {
                        $('#alertMessage').html(`<div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong><i class="fa fa-check"></i> Success! </strong>${data.message.message}.
                    </div>`).slideDown();
                        $("#key_update").html("<i class='fa fa-sync-alt'></i> Update").attr('disabled', false);
                        setTimeout(function () { $('#alertMessage').slideUp(); }, 3000);
                    },
                    error: function (data) {
                        $('#alertMessage').html(`<div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong><i class="fa fa-ban"></i> Failed! </strong>${data.responseJSON.message}
                    </div>`).slideDown();
                        $("#key_update").html("<i class='fa fa-sync-alt'></i> Update").attr('disabled', false);
                    }
                });
            });
        });
    </script>
@stop