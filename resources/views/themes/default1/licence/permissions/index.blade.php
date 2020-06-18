@extends('themes.default1.layouts.master')
@section('title')
License Permission
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>License Permissions</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">License Permissions</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
    <link rel="stylesheet" href="{{asset('admin/plugins/iCheck/all.css')}}">
    <div class="card card-primary card-outline">


       @include('themes.default1.licence.permissions.create')
       <div class="card-body">
             
             <div class="row">
            
            <div class="col-md-12">
               
                 <table id="permissions-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                    <thead><tr>
                            <th>License Type</th>
                            <th>License Permissions</th>
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

        $('#permissions-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: false,
              order: [[ 0, "desc" ]],
            ajax: '{!! route('get-license-permission') !!}',
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
                {data: 'license_type', name: 'license_type'},
                {data: 'permissions', name: 'permissions'},
                {data: 'action', name: 'action'}
            ],
            "fnDrawCallback": function( oSettings ) {
                bindEditButton();
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>
 <script>
      $(function () {
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
  })
  })
    

    //Add Permissions for a License

   
          function bindEditButton() {
              $('.addPermission').on('click',function(){
                  var licenseTypeId = $(this).attr('data-id');
                  var permissions = $(this).attr('data-permission') //All Permission for a particular License
                  $.ajax({
                    url:"{!! route('tick-permission') !!}",
                    data: { 'license': licenseTypeId  },
                    beforeSend: function() {
                      $('input[type="checkbox"].minimal').iCheck('uncheck');
                    },
                success: function (data) {
                    if (data.message =='success') {
                      $('.permission_checkbox').each(function() {
                        var permission = $(this);
                        $.each(data.permissions, function(index, el) {
                          if (el == permission.val()) {
                            permission.iCheck('check');
                          }
                        });
                      });
                    $("#all-permissions").modal('show');
                    }               
                }
              });

              $('#licenseTypeId').val(licenseTypeId);
              $('#permissionssubmit').click(function(){
               var permissionid = [];
                $('.permission_checkbox:checked').each(function(){
                permissionid.push($(this).val());
                });
                if(permissionid.length > 0) {
                 $.ajax({
                        url:"{!! route('add-permission') !!}",
                         method: "get",
                         data: { 'licenseId': licenseTypeId ,'permissionid' : permissionid },
                        beforeSend: function () {
                       $('#permissionresponse').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                     },
                  success: function (data) {
                   if (data.message =='success'){
                     var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> Well Done! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                        $('#permissionresponse').html(result);
                         $('#permissionresponse').css('color', 'green');
                    setTimeout(function(){
                        window.location.reload();
                      },3000);
                    }
                  },
                  error: function(ex) {
                     var myJSON = JSON.parse(ex.responseText);
                       var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                                  for (var key in myJSON)
                                  {
                                      html += '<li>' + myJSON[key][0] + '</li>'
                                  }
                                 html += '</ul></div>';
                                 $('#permissionresponse').show(); 
                                  document.getElementById('permissionresponse').innerHTML = html;
                                     }
                   
                                 })
                              } else {
                                alert('Plaese Select One Option');
                              }
                         });
                      });
                    }

   </script>

@stop


