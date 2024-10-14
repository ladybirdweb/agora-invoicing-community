@extends('themes.default1.layouts.master')
@section('title')
File Storage
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>File Storage</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">File Storage</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <div id="response"></div>
            <h5>Set file storage path</h5>
        </div>

        <div class="card-body flex">
            <form id="file_form">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label class="required">Storage Disk</label>
                        <select class="form-control" name="disk" id="disk">
                            <option value="system" {{ $fileStorage->disk == 'system' ? 'selected' : '' }}>System</option>
                            <option value="s3" {{ $fileStorage->disk == 's3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="required">Storage Path</label>
                        <input class="form-control" name="path" type="text" id="path" value="{{ $fileStorage->local_file_storage_path }}"
                               placeholder="Storage Path">
                    </div>
                </div>

                <!-- S3 Configuration Fields -->
                <div id="s3_config" style="display: none;">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="required">S3 Bucket</label>
                            <input class="form-control" name="s3_bucket" type="text" id="s3_bucket"
                                   placeholder="Enter S3 Bucket Name" value="{{ $fileStorage->s3_bucket }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="required">S3 Region</label>
                            <input class="form-control" name="s3_region" type="text" id="s3_region"
                                   placeholder="Enter S3 Region" value="{{ $fileStorage->s3_region }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="required">S3 Access Key</label>
                            <input class="form-control" name="s3_access_key" type="text" id="s3_access_key"
                                   placeholder="Enter S3 Access Key" value="{{ $fileStorage->s3_access_key }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="required">S3 Secret Key</label>
                            <input class="form-control" name="s3_secret_key" type="password" id="s3_secret_key"
                                   placeholder="Enter S3 Secret Key" value="{{ $fileStorage->s3_secret_key }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary pull-right" id="submit"
                        data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving...">
                    <i class="fa fa-save">&nbsp;&nbsp;</i>Save
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
                    path: {
                        required: true
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
                    }
                },
                messages: {
                    path: {
                        required: "Storage Path is required"
                    },
                    s3_bucket: {
                        required: "S3 Bucket is required"
                    },
                    s3_region: {
                        required: "S3 Region is required"
                    },
                    s3_access_key: {
                        required: "S3 Access Key is required"
                    },
                    s3_secret_key: {
                        required: "S3 Secret Key is required"
                    }
                },
                errorClass: "is-invalid",
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    error.insertAfter(element);
                },
                submitHandler: function (form) {
                    let formData = $(form).serialize();

                    $.ajax({
                        url: '{{ url('/file-storage-path') }}',
                        type: 'POST',
                        data: formData,
                        beforeSend: function () {
                            $('#submit').html("<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."); // Show loading text
                        },
                        success: function (response) {
                            alert('Settings saved successfully!');
                            $('#submit').html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
                        },
                        error: function (error) {
                            console.log(error)
                            alert('Error saving settings!');
                            $('#submit').html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
                        }
                    });
                }
            });

            $('#disk').change(function () {
                isS3Enabled = $(this).val() === 's3';
                $('#s3_config').toggle(isS3Enabled);
                validator.resetForm();
                $('#file_form').find('.is-invalid').removeClass('is-invalid');
                $('#file_form').find('.invalid-feedback').remove();
            }).trigger('change');
        });
    </script>

@stop

