@extends('themes.default1.layouts.master')
@section('title')
    {{ trans('message.file_storage') }}
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>{{ trans('message.file_storage') }}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{ trans('message.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> {{ trans('message.settings') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('message.file_storage') }}</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
    <div id="alert-container"></div>
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <div id="response"></div>
            <h5>{{ trans('message.set_file_storage') }}</h5>
        </div>

        <div class="card-body flex">
            <form id="file_form">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label class="required">{{ trans('message.storage_disk') }}</label>
                        <select class="form-control" name="disk" id="disk">
                            <option value="system" {{ $fileStorage->disk == 'system' ? 'selected' : '' }}>System</option>
                            <option value="s3" {{ $fileStorage->disk == 's3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="required">{{ trans('message.storage_path') }}</label>
                        <input class="form-control" name="path" type="text" id="path"
                               value="{{ $fileStorage->local_file_storage_path }}"
                               placeholder="Storage Path">
                    </div>
                </div>

                <!-- Product Configuration Fields -->
                <div id="product_config" style="display: none;">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="required">{{ trans('message.product_storage') }}</label>
                            <select class="form-control" name="product_storage" id="product_storage">
                                <option value="app" {{ $fileStorage->product_storage == 'app' ? 'selected' : '' }}>App Storage</option>
                                <option value="system" {{ $fileStorage->product_storage == 'system' ? 'selected' : '' }}>System Storage</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- S3 Configuration Fields -->
                <div id="s3_config" style="display: none;">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="required">{{ trans('message.s3_bucket') }}</label>
                            <input class="form-control" name="s3_bucket" type="text" id="s3_bucket"
                                   placeholder="Enter S3 Bucket Name" value="{{ $fileStorage->s3_bucket }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="required">{{ trans('message.s3_region') }}</label>
                            <input class="form-control" name="s3_region" type="text" id="s3_region"
                                   placeholder="Enter S3 Region" value="{{ $fileStorage->s3_region }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="required">{{ trans('message.s3_access_key') }}</label>
                            <input class="form-control" name="s3_access_key" type="password" id="s3_access_key"
                                   placeholder="Enter S3 Access Key" value="{{ $fileStorage->s3_access_key }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="required">{{ trans('message.s3_secret_key') }}</label>
                            <input class="form-control" name="s3_secret_key" type="password" id="s3_secret_key"
                                   placeholder="Enter S3 Secret Key" value="{{ $fileStorage->s3_secret_key }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="required">{{ trans('message.s3_endpoint_url') }}</label>
                            <input class="form-control" name="s3_endpoint_url" type="text" id="s3_endpoint_url"
                                   placeholder="Enter S3 Endpoint URL" value="{{ $fileStorage->s3_endpoint_url }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary pull-right" id="submit"
                        data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving...">
                    <i class="fa fa-save">&nbsp;&nbsp;</i>{{ trans('message.save') }}
                </button>
            </form>
        </div>


    </div>

    <script>
        $('ul.nav-sidebar a').filter(function () {
            return this.id == 'setting';
        }).addClass('active');

        // for treeview
        $('ul.nav-treeview a').filter(function () {
            return this.id == 'setting';
        }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');

        $(document).ready(function () {
            var isS3Enabled = $('#disk').val() === 's3';

            var validator = $("#file_form").validate({
                rules: {
                    disk: {
                        required: true
                    },
                    path: {
                        required: true
                    },
                    product_storage: {
                        required: {
                            depends: function () {
                                return !$('#product_storage').prop('disabled');
                            }
                        }
                    },
                    s3_bucket: {
                        required: {
                            depends: function () {
                                return isS3Enabled;
                            }
                        }
                    },
                    s3_region: {
                        required: {
                            depends: function () {
                                return isS3Enabled;
                            }
                        }
                    },
                    s3_access_key: {
                        required: {
                            depends: function () {
                                return isS3Enabled;
                            }
                        }
                    },
                    s3_secret_key: {
                        required: {
                            depends: function () {
                                return isS3Enabled;
                            }
                        }
                    },
                    s3_endpoint_url: {
                        required: {
                            depends: function () {
                                return isS3Enabled;
                            }
                        }
                    }
                },
                messages: {
                    disk: {
                        required: "{{ trans('message.disk_required') }}"
                    },
                    path: {
                        required: "{{ trans('message.path_required') }}"
                    },
                    product_storage: {
                        required: "{{ trans('message.product_storage_required') }}"
                    },
                    s3_bucket: {
                        required: "{{ trans('message.s3_bucket_required') }}"
                    },
                    s3_region: {
                        required:  "{{ trans('message.s3_region_required') }}"
                    },
                    s3_access_key: {
                        required:  "{{ trans('message.s3_access_key_required') }}"
                    },
                    s3_secret_key: {
                        required:  "{{ trans('message.s3_secret_key_required') }}"
                    },
                    s3_endpoint_url: {
                        required:  "{{ trans('message.s3_endpoint_url_required') }}"
                    }
                },
                errorClass: "is-invalid",
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    error.insertAfter(element);
                    error.css('font-weight', 'normal');
                },
                submitHandler: function (form) {
                    let formData = $(form).serialize();

                    $.ajax({
                        url: '{{ url('/file-storage-path') }}',
                        type: 'POST',
                        data: formData,
                        success: function (response) {
                            showAlert('success', response.message);
                        },
                        error: function (error) {
                            if (error.status === 422) {
                                showValidationErrors(error.responseJSON.errors);
                            } else {
                                showAlert('error', error.responseJSON.message);
                            }
                        }
                    });
                }
            });

            function showValidationErrors(errors) {

                $.each(errors, function (field, messages) {
                    validator.showErrors({
                        [field]: messages[0]
                    });
                });

            }

            function showAlert(type, message) {
                let icon = (type === 'success') ? 'fa-check-circle' : 'fa-ban';
                let alertType = (type === 'success') ? 'alert-success' : 'alert-danger';

                $('#alert-container').html(`
        <div class="alert ${alertType} alert-dismissable">
            <i class="fa ${icon}"></i> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
    `);

                setTimeout(function () {
                    $('#alert-container .alert').fadeOut('slow', function () {
                        $(this).remove();
                    });
                }, 5000);
            }

            $('#disk').change(function () {
                isS3Enabled = $(this).val() === 's3';
                $('#s3_config').toggle(isS3Enabled);
                $('#product_config').toggle(!isS3Enabled);
                validator.resetForm();
                $('#file_form').find('.is-invalid').removeClass('is-invalid');
                $('#file_form').find('.invalid-feedback').remove();
            }).trigger('change');

            $('#product_storage, #disk').change(function () {
                let product = $('#disk').val();
                let storage = $('#product_storage').val();
                console.log('pro' + product)
                console.log('sto' + storage)
                if (product === 'system' && storage === 'system') {
                    $('#path').prop('disabled', false);
                } else {
                    $('#path').prop('disabled', true);
                }
            }).trigger('change');

        });
    </script>

@stop

