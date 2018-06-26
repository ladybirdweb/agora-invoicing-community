@extends('themes.default1.layouts.master')
@section('content')







  <div class="row">
                    <div class="col-md-12">
<div class="box">
	 <div class="box-header with-border">
                               <h3 class="box-title">Activity Log</h3>
                            </div>
	 <div class="box-body">
             
             <div class="row">
            
            <div class="col-md-12">
               
                 <table id="activity-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                     
                    <thead><tr>
                       
                            <th>Log Name</th>
                            <th>Description</th>
                            <th>Subject id</th>
                            <th>Subject type</th>
                            <th>Causer id</th>
                            <th>Causer type</th>
                             <th>Properties</th>
                              <th>Created At</th>
                        </tr></thead>

                   </table>
            </div>
        </div>

    </div>
</div>
</div>
</div>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#activity-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: true,
              order: [[ 0, "desc" ]],
            ajax: '{!! route('get-activity') !!}',
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
            columnDefs: [
                { 
                    targets: 'no-sort', 
                    orderable: false,
                    order: []
                }
            ],
            columns: [
                 {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'subject_id', name: 'subject_id'},
                {data: 'subject_type', name: 'subject_type'},
                {data: 'causer_id', name: 'causer_id'},
                {data: 'causer_type', name: 'causer_type'},
                {data: 'properties', name: 'properties'},
                {data: 'created_at', name: 'created_at'}
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














