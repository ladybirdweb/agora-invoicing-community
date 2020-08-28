@extends('themes.default1.layouts.master')
@section('title')
All Pages
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>All Pages</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">All Pages</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
<div class="card card-primary card-outline">



        <div id="response"></div>
        <div class="card-header">
            <h3 class="card-title">{{Lang::get('message.pages')}}</h3>

            <div class="card-tools">
                <a href="{{url('pages/create')}}" class="btn btn-primary btn-sm pull-right"><span class="fas fa-plus"></span>&nbsp;{{Lang::get('message.create')}}</a>


            </div>
        </div>



    <div class="card-body table-responsive">
        <div class="row">

            <div class="col-md-12">
        
<table id="pages-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                      <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp; Delete Selected</button><br /><br />
                    <thead><tr>
                         <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                         <th>Name</th>
                          <th>Url</th>
                           <th>Created At</th>
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
        $('#pages-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
            "url":  '{!! route('get-pages') !!}',
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
                { 
                    targets: 'no-sort', 
                    orderable: false,
                    order: []
                }
            ],
          
            columns: [
                {data: 'checkbox', name: 'checkbox'},
                {data: 'name', name: 'name'},
                {data: 'url', name: 'Url'},
                {data: 'created_at', name: 'Created At'},
                {data: 'action', name: 'Action'}
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

@section('icheck')
<script>
    function checking(e){
          
          $('#pages-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
     }
     

     $(document).on('click','#bulk_delete',function(){
      var id=[];
      if (confirm("Are you sure you want to delete this?"))
        {
            $('.page_checkbox:checked').each(function(){
              id.push($(this).val())
            });
            if(id.length >0)
            {
               $.ajax({
                      url:"{!! route('pages-delete') !!}",
                      method:"delete",
                      data: $('#check:checked').serialize(),
                      beforeSend: function () {
                $('#gif').show();
                },
                success: function (data) {
                $('#gif').hide();
                $('#response').html(data);
                location.reload();
                }
               })
            }
            else
            {
                alert("Please select at least one checkbox");
            }
        }  

     });
</script>
@stop