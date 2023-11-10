@extends('themes.default1.layouts.master')
@section('title')
    Cloud Hub
@stop

<style type="text/css">

    /* Styles for DataTable columns */
    table.dataTable thead th:nth-child(1),
    table.dataTable tbody td:nth-child(1),
    table.dataTable thead th:nth-child(2),
    table.dataTable tbody td:nth-child(2),
    table.dataTable thead th:nth-child(7),
    table.dataTable tbody td:nth-child(7) {
        width: 150px;
        white-space: nowrap;
        padding-right: 20px;
    }

    /* Loading spinner */
    #loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /* Table styles */
    #tenant-table {
        width: 100%;
        border-collapse: collapse;
    }

    #tenant-table th, #tenant-table td {
        padding: 12px;
        text-align: left;
    }

    /* Button styles */
    .btn-primary {
        padding: 10px 20px;
        font-size: 16px;
    }


    .select2-container--default .select2-selection--single {
        height: 320px;
    }


</style>

@section('content-header')
    <div class="col-sm-6">
        <h1>Cloud Details</h1>
    </div>

    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('settings') }}"><i class="fa fa-cog"></i> Settings</a></li>
            <li class="breadcrumb-item active">Cloud Hub</li>
        </ol>
    </div>
@stop

@section('content')
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title">Cloud server</h3>
        </div>
        <div class="card-body table-responsive">
            {!! Form::model($cloud, ['route'=> 'cloud-details']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('cloud_central_domain', Lang::get('message.cloud_central_domain'), ['class' => 'required']) !!}
                        {!! Form::text('cloud_central_domain', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('cloud_cname', Lang::get('message.cloud_cname'),['class' => 'required']) !!}
                        {!! Form::text('cloud_cname', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> {!! Lang::get('message.save') !!}
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title">Customise Cloud Popup</h3>
        </div>

        <div class="card-body table-responsive">
            {!! Form::model($cloudPopUp, ['route'=> 'cloud-pop-up']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('cloud_top_message', Lang::get('message.cloud_top_message'), ['class' => 'required']) !!}
                        {!! Form::text('cloud_top_message', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('cloud_label_field', Lang::get('message.cloud_label_field'),['class' => 'required']) !!}
                        {!! Form::text('cloud_label_field', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('cloud_label_radio', Lang::get('message.cloud_label_radio'),['class' => 'required']) !!}
                        {!! Form::text('cloud_label_radio', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> {!! Lang::get('message.save') !!}
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <?php
        $products = \DB::table('products')->get()
    ?>
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title">Cloud Product Configuration</h3>
        </div>
        <div class="card-body">
            {!! Form::model('',['route' => 'cloud-product-store']) !!}
            <div class="row original-fields">
                <div class="col-md-4">
                    {!! Form::label('cloud_product', Lang::get('message.cloud_product'), ['class' => 'required']) !!}
                    <div class="form-group">
                        <!-- Select Field 1 -->
                        <select name="cloud_product" class="form-control">
                            @foreach($products as $product)
                            <option value="{!! $product->id !!}">{{$product->name}}</option>
                            @endforeach
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php
                      $plans = \DB::table('plans')->get();
                    ?>
                    {!! Form::label('cloud_free_plan', Lang::get('message.cloud_free_plan'), ['class' => 'required']) !!}
                    <div class="form-group">
                        <select name="cloud_free_plan" class="form-control">
                            @foreach($plans as $plan)
                            <option value="{!! $plan->id !!}">{!! $plan->name !!}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    {!! Form::label('cloud_product_key', Lang::get('message.cloud_product_key'), ['class' => 'required']) !!}
                    <div class="form-group">
                        <input type="text" name="cloud_product_key" class="form-control">
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
            <div id="loading" style="display: none;">
                <div class="spinner"></div>
            </div>
            <div id="successmsgpop"></div>
            <div id="errorpop"></div>
        <div class="card-body table-responsive">
            <table id="pop-product-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                <thead>
                <tr>
                    <th>Cloud Product</th>
                    <th>Cloud free plan</th>
                    <th>Cloud product key</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


    <script>
        // JavaScript to duplicate the fields
        $(document).ready(function () {
            $('#addFields').on('click', function () {
                var originalFields = $('.original-fields').html();
                $('.duplicate-fields').append(originalFields);
            });
        });
    </script>






    <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">Cloud Data Centers</h3>
            </div>

          <div class="card-body">
            {!! Form::model('',['route' => 'cloud-data-center-store']) !!}
                  <div class="row">
                      <div class="col-md-4">
                          <?php $countries = \App\Model\Common\Country::cursor(); ?>
                          {!! Form::label('cloud_country', Lang::get('message.cloud_country'), ['class' => 'required']) !!}
                          <div class="form-group">
                              <!-- Select Field 1 -->
                              <select id="cloud_countries" name="cloud_countries" class="form-control select2">
                                  @foreach($countries as $country)
                                      <option value="{!! strtolower($country->country_code_char2) !!}">{{$country->nicename}}</option>
                                  @endforeach
                                  <!-- Add more options as needed -->
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <?php
                          $states = \App\Model\Common\State::get();
                          ?>
                          {!! Form::label('cloud_state', Lang::get('message.cloud_state'), ['class' => 'required']) !!}
                          <div class="form-group">
                              <select id="cloud_state" name="cloud_state" class="form-control">

                              </select>
                          </div>
                      </div>

                      <div class="col-md-4">
                          {!! Form::label('cloud_city', Lang::get('message.cloud_city'), ['class' => 'required']) !!}
                          <div class="form-group">
                              <input type="text" name="cloud_city" class="form-control">
                          </div>

                      </div>

                  </div>
                       <div class="row">
                           <div class="col-md-4">
                               <div class="form-group">
                                   <button type="submit" class="btn btn-primary">
                                       <i class="fa fa-save"></i> Save
                                   </button>
                               </div>

                           </div>
                       </div>
                  {!! Form::close() !!}


            <div id="map" style="height: 450px;"></div>
          </div>
        </div>
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <div id="response"></div>
                <h5>Set Cloud Free Trial Button Display Option</h5>
            </div>
            <div class="card-body">
                {!! Form::open(['url' => 'enable/cloud', 'method' => 'POST']) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('debug', Lang::get('Cloud Free Trial')) !!}
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="radio" name="debug" value="true" @if($cloudButton == 1) checked="true" @endif > {{Lang::get('Enable')}}
                            </div>
                            <div class="col-sm-3">
                                <input type="radio" name="debug" value="false" @if($cloudButton == 0) checked="true" @endif > {{Lang::get('Disable')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>
    </div>
    @if($cloud != null)
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">Tenants</h3>
            </div>
            <div id="loading" style="display: none;">
                <div class="spinner"></div>
            </div>
            <div id="successmsg"></div>
            <div id="error"></div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tenant-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Deletion day</th>
                                <th>Tenant</th>
                                <th>Domain</th>
                                <th>Database name</th>
                                <th>Database username</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>

        $(document).ready(function () {
            $(function () {
                $('[data-toggle="tooltip"]').tooltip({
                    container: 'body'
                });
            });

            $('#tenant-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: false,
                order: [[0, "desc"]],
                ajax: {
                    "url": '{!! route('get-tenants') !!}',
                    error: function (xhr) {
                        if (xhr.status == 401) {
                            alert('Your session has expired. Please login again to continue.');
                            window.location.href = '/login';
                        }
                    }
                },
                "oLanguage": {
                    "sLengthMenu": "_MENU_ Records per page",
                    "sSearch": "Search: ",
                    "sProcessing": ' <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>'
                },
                columnDefs: [
                    { orderable: false, targets: 4 }
                ],
                columns: [
                    { data: 'Order', name: 'Order' },
                    { data: 'Deletion day', name: 'Deletion day' },
                    { data: 'tenants', name: 'tenants' },
                    { data: 'domain', name: 'domain' },
                    { data: 'db_name', name: 'db_name' },
                    { data: 'db_username', name: 'db_username' },
                    { data: 'action', name: 'action' },
                ],
                "fnDrawCallback": function (oSettings) {
                    $('.loader').css('display', 'none');
                },
                "fnPreDrawCallback": function (oSettings, json) {
                    $('.loader').css('display', 'block');
                },
            });
        });

        function deleteTenant(id, orderId = "") {
            var id = id;
            var orderId = orderId;
            if (confirm("Are you sure you want to destroy this tenant?")) {
                var loadingElement = document.getElementById("loading");
                loadingElement.style.display = "flex";
                $.ajax({
                    url: "{!! url('delete-tenant') !!}",
                    method: "delete",
                    data: { 'id': id, 'orderId': orderId },
                    success: function (data) {
                        if (data.success === true) {
                            console.log(data.message);
                            var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-check"></i>Success! </strong>' + data.message + '!</div>';
                            $('#successmsg').show();
                            $('#error').hide();
                            $('#successmsg').html(result);
                            setInterval(function () {
                                $('#successmsg').slideUp(5000);
                                location.reload();
                            }, 3000);
                        } else if (data.success === false) {
                            $('#successmsg').hide();
                            $('#error').show();
                            var result = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-ban"></i>Whoops! </strong> Something went wrong<br>' + data.message + '!</div>';
                            $('#error').html(result);
                            setInterval(function () {
                                $('#error').slideUp(5000);
                                location.reload();
                            }, 10000);
                        }
                    },
                    error: function (data) {
                        $('#successmsg').hide();
                        $('#error').show();
                        var result = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-ban"></i>Whoops! </strong> Something went wrong<br>' + data.responseJSON.message + '!</div>';
                        $('#error').html(result);
                        setInterval(function () {
                            $('#error').slideUp(5000);
                            location.reload();
                        }, 10000);
                    },
                    complete: function () {
                        loadingElement.style.display = "none"; // Hide the loading indicator
                    }
                });
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            var map = L.map('map', {
                minZoom: 2, // Set the minimum zoom level to 2
            }).setView([0, 0], 2); // Initialize the map with a center and zoom level.

            // Add an OpenStreetMap tile layer.
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Define an array of region coordinates
            var regions = @json($regions);

            regions.forEach(function (region) {
                var regionLatLng = [region.latitude, region.longitude];
                var marker = L.marker(regionLatLng).addTo(map).bindPopup(region.name);

                var clickCount = 0;

                marker.on('click', function () {
                    clickCount++;

                    if (clickCount === 2) {
                        clickCount = 0;

                        removeLocation(region.name);
                    }
                });

                marker.on('tooltipclose', function () {
                    clickCount = 0;
                });

            });
        });

        // Function to remove a location
        function removeLocation(locationId) {
            // Send AJAX request to remove location
            $.ajax({
                url: "{{ route('remove-location') }}",
                type: 'DELETE',
                data: { location_id: locationId },
                success: function (data) {
                    // Update the map to remove the pin
                    map.eachLayer(function (layer) {
                        if (layer.options && layer.options.locationId === locationId) {
                            map.removeLayer(layer);
                        }
                    });

                }
            });
        }


            $(document).ready(function () {
            $('#pop-product-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: false,
                order: [[0, "desc"]],
                ajax: {
                    url: '{{ route("fetch-data") }}', // Replace with your actual data source URL
                    type: 'GET', // Or 'POST' depending on your server-side implementation
                },
                language: {
                    sLengthMenu: "_MENU_ Records per page",
                    sSearch: "Search: ",
                    sProcessing: ' <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>'
                },
                columnDefs: [
                    { orderable: false, targets: 3 }
                ],
                columns: [
                    { data: 'Cloud Product', name: 'Cloud Product' },
                    { data: 'Cloud free plan', name: 'Cloud free plan' },
                    { data: 'Cloud product key', name: 'Cloud product key' },
                    {data: 'action', name: 'action'},
                ],
                fnDrawCallback: function (oSettings) {
                    $('.loader').css('display', 'none');
                },
                fnPreDrawCallback: function (oSettings, json) {
                    $('.loader').css('display', 'block');
                },
            });
        });

        function popProduct(id) {
            var id = id;
            if (confirm("Are you sure you want to destroy this product configuration?")) {
                var loadingElement = document.getElementById("loading");
                loadingElement.style.display = "flex";
                $.ajax({
                    url: "{!! url('delete-cloud-product') !!}",
                    method: "delete",
                    data: { 'id': id },
                    success: function (data) {
                        if (data.success === true) {
                            var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-check"></i>Success! </strong>' + data.message + '!</div>';
                            $('#successmsgpop').show();
                            $('#errorpop').hide();
                            $('#successmsgpop').html(result);
                            setInterval(function () {
                                $('#successmsgpop').slideUp(5000);
                                location.reload();
                            }, 3000);
                        } else if (data.success === false) {
                            $('#successmsgpop').hide();
                            $('#errorpop').show();
                            var result = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-ban"></i>Whoops! </strong> Something went wrong<br>' + data.message + '!</div>';
                            $('#errorpop').html(result);
                            setInterval(function () {
                                $('#errorpop').slideUp(5000);
                                location.reload();
                            }, 10000);
                        }
                    },
                    error: function (data) {
                        $('#successmsgpop').hide();
                        $('#errorpop').show();
                        var result = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-ban"></i>Whoops! </strong> Something went wrong<br>' + data.responseJSON.message + '!</div>';
                        $('#errorpop').html(result);
                        setInterval(function () {
                            $('#errorpop').slideUp(5000);
                            location.reload();
                        }, 10000);
                    },
                    complete: function () {
                        loadingElement.style.display = "none"; // Hide the loading indicator
                    }
                });
            }
        }

        $(document).ready(function () {
            // Listen for changes in the country dropdown
            $('#cloud_countries').change(function () {
                var country = $(this).val();

                // Make an AJAX request to fetch states based on the selected country
                $.ajax({
                    url: "{{ url('get-state') }}/" + country + "?country_id=" + country,
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        // Update the state dropdown with the retrieved states
                        $('#cloud_state').empty();
                            $('#cloud_state').append(data);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });

        $(document).ready(function () {
            // Add an id to the country dropdown
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2()
            });
        });


    </script>

@stop

