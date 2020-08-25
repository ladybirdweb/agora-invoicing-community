@extends('themes.default1.layouts.master')
@section('title')
Invoices
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>All Invoices</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">All Invoices</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-danger card-outline collapsed-card">
            <div class="card-header">
        <h3 class="card-title">Advance Search</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fas fa-plus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fas fa-times"></i></button>
                </div>
    </div>
    <!-- /.box-header -->
    <div class="card-body">

        {!! Form::open(['method'=>'get']) !!}

        <div class="row">
            
             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('name','First Name') !!}
                {!! Form::text('name',$request->name,['class' => 'form-control','id'=>'name']) !!}

            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('invoice_no','Invoice No') !!}
                {!! Form::text('invoice_no',$request->invoice_no,['class' => 'form-control','id'=>'invoice_no']) !!}

            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('status','Status') !!}
               <select name="status"  class="form-control" id="status">
                    <option value="">Choose</option>
                   <option value="pending">Unpaid</option>
                   <option value="Partially paid">Partially Paid</option>
                  <option value="success">Paid</option>
                 </select>

            </div>
           <div class="col-md-3 form-group">
                {!! Form::label('currency','Currency') !!}
             <select name="currency_id"  class="form-control" id="currency">
             <option value="">Choose</option>
               @foreach($currencies as $key=>$value)
            <option value={{$value}}>{{$value}}</option>
              @endforeach
               </select>
          </div>
            
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Invoice From') !!}
                <div class="input-group date" id="invoice_from" data-target-input="nearest">
                    <input type="text" name="from" class="form-control datetimepicker-input" autocomplete="off"  data-target="#invoice_from"/>

                    <div class="input-group-append" data-target="#invoice_from" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>

                </div>



            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('till','Invoice Till') !!}
                <div class="input-group date" id="invoice_till" data-target-input="nearest">
                    <input type="text" name="till" class="form-control datetimepicker-input" autocomplete="off"  data-target="#invoice_till"/>

                    <div class="input-group-append" data-target="#invoice_till" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>

                </div>

            </div>
        </div>
            

            <div class='row'>
                <div class="col-md-6">
                    <!-- {!! Form::submit('Search',['class'=>'btn btn-primary']) !!} -->
                    <button name="Search" type="submit"  class="btn btn-primary"><i class="fa fa-search">&nbsp;</i>{!!Lang::get('Search')!!}</button>
                     &nbsp;&nbsp;
                    <!-- {!! Form::submit('Reset',['class'=>'btn btn-danger','id'=>'reset']) !!} -->
                     <button name="Reset" type="submit" id="reset" class="btn btn-danger"><i class="fa fa-sync-alt">&nbsp;</i>{!!Lang::get('Reset')!!}</button>


                </div>
            </div>


        </div>
            <script type="text/javascript">
                    $(function () {
                    $('#reset').on('click', function () {
                      
                        $('#name').val('');
                        $('#invoice_no').val('');
                        $('#status').val('');
                        $('#currency').val('');
                        $('.from').val('');
                        $('.till').val('');
                      
                    
                          
                    });
                });
                </script>


        {!! Form::close() !!}
    </div>
        </div>
    </div>

    <div class="card card-primary card-outline">

    <div class="card-header">

        <div id="response"></div>
        <h3 class="card-title">{{Lang::get('message.invoices')}} </h3>
            <div class="card-tools">
                <a href="{{url('invoice/generate')}}" class="btn btn-primary btn-sm pull-right"><i class="fas fa-credit-card"></i>&nbsp; {{Lang::get('message.place-an-order')}}</a>
            </div>
    </div>



    <div class="card-body table-responsive">
        <div class="row">

            <div class="col-md-12">
  
                <table id="invoice-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                    <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Selected</button><br /><br />
                    <thead><tr>
                        <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                         <th>User</th>
                          <th>Invoice No</th>
                           <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
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

        $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: false,
            order: [[ 0, "desc" ]],
            ajax: {
            "url":  '{!! route('get-invoices',"name=$name&invoice_no=$invoice_no&status=$status&currency_id=$currency_id&from=$from&till=$till") !!}',
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
                {data: 'user_id', name: 'user_id'},
                {data: 'number', name: 'number'},
                {data: 'date', name: 'date'},
                {data: 'grand_total', name: 'grand_total'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
            ],
            "fnDrawCallback": function( oSettings ) {
                $('[data-toggle="tooltip"]').tooltip({
                    container : 'body'
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
          
          $('#invoice-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
     }
     

     $(document).on('click','#bulk_delete',function(){
      var id=[];
      if (confirm("Are you sure you want to delete this?"))
        {
            $('.invoice_checkbox:checked').each(function(){
              id.push($(this).val())
            });
            if(id.length >0)
            {
               $.ajax({
                      url:"{!! route('invoice-delete') !!}",
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
@section('datepicker')
<script type="text/javascript">
    $('#invoice_from').datetimepicker({
        format: 'L'
    });
    $('#invoice_till').datetimepicker({
        format: 'L'
    });
</script>
@stop
















