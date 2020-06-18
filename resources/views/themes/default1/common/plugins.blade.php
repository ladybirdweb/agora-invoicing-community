@extends('themes.default1.layouts.master')
@section('title')
Plugins
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Payment Gateway</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">Plugins</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="card card-primary card-outline">



    <div class="card-body table-responsive">

        


        <div class="row">
            <div class="col-md-12">
                 <table id="plugin" class="table display" cellspacing="0" width="100%" styleClass="borderless">

                    <thead><tr>
                         <th>Name</th>
                          <th>Description</th>
                          <th>Author</th>
                          <th>Website</th>
                          <th>Version</th>
                        </tr></thead>
                     </table>
              
            </div>
        </div>
    </div>
</div>

      
        <!-- /.box -->

    </div>


</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#plugin').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('get-plugin') !!}',
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
                "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
              }],

            columns: [
               
                {data: 'name', name: 'name'},
                {data: 'description', name: 'Description'},
                {data: 'author', name: 'Author'},
                {data: 'website', name: 'Website'},
                {data: 'version', name: 'Version'},
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