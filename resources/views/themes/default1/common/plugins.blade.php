@extends('themes.default1.layouts.master')
@section('title')
Plugins
@stop
@section('content-header')
<h1>
Payment Gateway
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li class="active">Plugins</li>
      </ol>
@stop
@section('content')

<div class="row">

    <div class="col-md-12">
        

            <div class="box box-primary">
    <div class="box-header">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {!!Session::get('success')!!}
        </div>
        @endif
        <!-- failure message -->
        @if(Session::has('fails'))
        <div class="alert alert-danger alert-dismissable">
            <i class="fa fa-ban"></i>
            <b>Alert!</b> Failed.
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {!!Session::get('fails')!!}
        </div>
        @endif
        <h3>Plugins  
            <button type="button" class="btn btn-default" id="Edit_Ticket" data-toggle="modal" data-target="#Edit"><b>Add New</b></button>
        </h3>
        <div class="modal fade" id="Edit">
            <div class="modal-dialog">
                <div class="modal-content">  
                    <div class="modal-header">
                        <h4 class="modal-title">Add Plugin</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['url'=>'post-plugin','files'=>true]) !!}
                        <table>
                            <tr>
                                <td>Plugin :</td>
                                <td><input type="file" name="plugin" class="btn btn-file"></td>

                            </tr>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" id="dismis">Close</button>
                            <input type="submit" class="btn btn-primary pull-right" value="Upload">
                        </div>
                        {!! Form::close() !!}

                    </div><!-- /.modal-content -->
                </div>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>

    <div class="box-body">

        


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
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
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
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>
@stop