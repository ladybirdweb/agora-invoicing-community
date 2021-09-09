@extends('themes.default1.layouts.master')
@section('title')
Tenants
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Cloud Details</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">Tenants</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')


<div class="card card-secondary card-outline">

    <div class="card-header">
            <h3 class="card-title">Cloud server</h3>
    </div>
    
    <div class="card-body table-responsive">
            {!! Form::model($cloud, ['route'=> 'cloud-details']) !!}
            <div class="col-md-12">

                <div class ="row">
                    <div class="col-md-4 form-group">
                         <i class='fa fa-info-circle' style='cursor: help; font-size: small; color: rgb(60, 141, 188)'<label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="The server domain where all the api calls for creating, deleting tenants would happen">
                        </label></i>
                        {!! Form::label('cloud_central_domain',Lang::get('message.cloud_central_domain'),['class'=>'required']) !!}
                        {!! Form::text('cloud_central_domain',null,['class' => 'form-control']) !!}
                     
                    </div>
                   
                    <div class="col-md-4 form-group">
                         <i class='fa fa-info-circle' style='cursor: help; font-size: small; color: rgb(60, 141, 188)'<label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="The server where call for creating cron for each tenant would happen.">
                        </label></i>
                        {!! Form::label('cron_server_url',Lang::get('message.cron_server_url'),['class'=>'required']) !!}
                        {!! Form::text('cron_server_url',null,['class' => 'form-control']) !!}   

                    </div>

                    <div class="col-md-4 form-group">
                         <i class='fa fa-info-circle' style='cursor: help; font-size: small; color: rgb(60, 141, 188)'<label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="The key to verify if the request for cron creation is coming from valid source. There should be check for this key on cron server before setting up cron.">
                        </label></i>


                        {!! Form::label('cron_server_key',Lang::get('message.cron_server_key'),['class'=>'required']) !!}
                        {!! Form::text('cron_server_key',null,['class' => 'form-control']) !!}   

                    </div>

                     
                </div>
                
            </div>
             <div class="col-md-12">

             <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-save">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
         </div>
            
            {!! Form::close() !!}



    </div>
</div>
  



    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title">Tenants</h3>

          
        </div>

        <div id="success"></div>
        <div id="error"></div>
       <div class="card-body table-responsive">
             
             <div class="row">
            
            <div class="col-md-12">
               
                 <table id="tenant-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                     
                    <thead><tr>
                       
                            <th>Tenant</th>
                            <th>Domain</th>
                            <th>Database name</th>
                            <th>Database username</th>
                            <th>Action</th>
                        </tr></thead>

                   </table>
            </div>
        </div>

    </div>

</div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({
            container : 'body'
        });
    });
})

        $('#tenant-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: false,
              order: [[ 0, "desc" ]],
               ajax: {
                 "url":  '{!! route('get-tenants') !!}',

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
                "sProcessing": ' <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>'
            },
            columnDefs: [
             { orderable: false, targets:0 }
          ],
            columns: [
                {data: 'tenants', name: 'tenants'},
                {data: 'domain', name: 'domain'},
                {data: 'db_name', name: 'db_name'},
                {data: 'db_username', name: 'db_username'},
                {data: 'action', name: 'action'},
            ],
            "fnDrawCallback": function( oSettings ) {
              // deleteTenant();
                $('.loader').css('display', 'none');
                // deleteTenant();
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>

<script>

  function deleteTenant(id) {
     var id = id;
      if (confirm("Are you sure you want to destroy this tenant?")) {
         $.ajax({
          url: "{!! url('delete-tenant') !!}",
          method:"delete",
          data: {'id':id},
          success: function (data) {
            if(data.success = true) {
              var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-check"></i>Success! </strong>'+data.message+'!</div>';
              $('#success').show();
              $('#error').hide();
              $('#success').html(result);
              setInterval(function(){ 
                $('#success').slideUp(5000);
                  location.reload();
            }, 3000);

            } else if(data.success = false) {
              $('#success').hide();
              $('#error').show();
              var result =  '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-ban"></i>Whoops! </strong> Something went wrong<br>'+data.message+'!</div>';
              $('#error').html(result);
              setInterval(function(){ 
                $('#error').slideUp(5000); 
           location.reload(); },10000);

            }
             
          },error: function(data) {
            $('#success').hide();
              $('#error').show();
              var result =  '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-ban"></i>Whoops! </strong> Something went wrong<br>'+data.responseJSON.message+'!</div>';
              $('#error').html(result);
              setInterval(function(){ 
                $('#error').slideUp(5000); location.reload();
            }, 10000);
          }

        })
      }
   
  
  }
  
     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'setting';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'setting';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>

@stop


