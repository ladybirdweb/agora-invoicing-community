@extends('themes.default1.layouts.master')
@section('title')
Invoices
@stop
@section('content-header')
<style type="text/css">
#loader {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    z-index: 9999;
}

#loader i.fa-spinner {
    font-size: 48px;
}

#invoice-table {
    position: relative;
    z-index: 1;
}
</style>
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
            <div class="card card-secondary card-outline collapsed-card">
            <div class="card-header">
        <h3 class="card-title">Advance Search</h3>

                <div class="card-tools">
                   <button type="button" class="btn btn-tool" id="tip-search" title="Expand"> <i id="search-icon" class="fas fa-plus"></i>
                            </button>
                   
                </div>
    </div>
    <!-- /.box-header -->
    <div class="card-body" id="advance-search" style="display:none;">

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
    {!! Form::label('status', 'Status') !!}
    <select name="status" class="form-control" id="status">
        <option value="">Choose</option>
        <option value="pending" @if($request->input('status') === 'pending') selected @endif>Unpaid</option>
        <option value="Partially paid" @if($request->input('status') === 'Partially paid') selected @endif>Partially Paid</option>
        <option value="success" @if($request->input('status') === 'success') selected @endif>Paid</option>
    </select>
</div>


              <div class="col-md-3 form-group">
            {!! Form::label('currency', 'Currency') !!}
            <select name="currency_id" class="form-control" id="currency">
                <option value="">Choose</option>
                @foreach($currencies as $currency)
                    @if($currency === $request->input('currency_id'))
                        <option value="{{ $currency }}" selected>{{ $currency }}</option>
                    @else
                        <option value="{{ $currency }}">{{ $currency }}</option>
                    @endif
                @endforeach
            </select>
        </div>

            
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Invoice From') !!}
                <div class="input-group date" id="invoice_from" data-target-input="nearest">
                    <input type="text" name="from" class="form-control datetimepicker-input" autocomplete="off" value="{{ $request->input('from') }}"  data-target="#invoice_from"/>

                    <div class="input-group-append" data-target="#invoice_from" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>

                </div>



            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('till','Invoice Till') !!}
                <div class="input-group date" id="invoice_till" data-target-input="nearest">
                    <input type="text" name="till" class="form-control datetimepicker-input" autocomplete="off" value="{{ $request->input('till') }}"  data-target="#invoice_till"/>

                    <div class="input-group-append" data-target="#invoice_till" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>

                </div>

            </div>
        </div>
            

            <div class='row'>
                <div class="col-md-6">
                    <!-- {!! Form::submit('Search',['class'=>'btn btn-primary']) !!} -->
                    <button name="Search" type="submit"  class="btn btn-secondary"><i class="fa fa-search">&nbsp;</i>{!!Lang::get('Search')!!}</button>
                     &nbsp;&nbsp;
                    {!! Form::submit('Reset',['class'=>'btn btn-secondary','id'=>'reset']) !!}
                    </div>
            </div>


        </div>
        {!! Form::close() !!}
    </div>
        </div>
    </div>

    <div class="card card-secondary card-outline">

    <div class="card-header">

        <div id="response"></div>
        <h3 class="card-title">{{Lang::get('message.invoices')}} </h3>
            <div class="card-tools">
                <a href="{{url('invoice/generate')}}" class="btn btn-default btn-sm pull-right"><i class="fas fa-credit-card"></i>&nbsp; {{Lang::get('message.place-an-order')}}</a>
            </div>
    </div>



    <div class="card-body table-responsive">
        <div class="row">

            <div class="col-md-12">

                <div id="loader" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i> 
                </div>
                  
                <table id="invoice-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                    <button  value="" class="btn btn-secondary btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Selected</button><br /><br />
                    <thead><tr>
                        <th class="no-sort"><input type="checkbox"  name="select_all" onchange="checking(this)"></th>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <script>
     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'all_invoice';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'all_invoice';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>

    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">


     $(document).ready(function() {
          var invoiceTable = $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: false, // Change stateSave to true
            order: [[{!! $request->sort_field ?: 5 !!}, {!! "'".$request->sort_order."'" ?: "'asc'" !!}]], // Change the default order if needed

            ajax: {
              "url": '{!! route('get-invoices', "name=$name&invoice_no=$invoice_no&status=$status&currency_id=$currency_id&from=$from&till=$till") !!}',
              error: function(xhr) {
                if (xhr.status == 401) {
                  alert('Your session has expired. Please login again to continue.')
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
              {
                targets: 'no-sort',
                orderable: false,
                order: []
              }
            ],
            columns: [
              { data: 'checkbox', name: 'checkbox' },
              { data: 'user_id', name: 'user_id' },
              { data: 'number', name: 'number' },
              { data: 'date', name: 'created_at' },
              { data: 'grand_total', name: 'grand_total' },
              { data: 'status', name: 'status' },
              { data: 'action', name: 'action' }
            ],
            "fnDrawCallback": function(oSettings) {
              $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
              });
              $('.loader').css('display', 'none');

              // Check the URL parameters after DataTables redraws
              var urlParams = new URLSearchParams(window.location.search);
              var hasSearchParams = urlParams.has('name') || urlParams.has('invoice_no') || urlParams.has('status') || urlParams.has('currency_id') || urlParams.has('from') || urlParams.has('till');
              if (hasSearchParams) {
                $("#advance-search").css('display','block');
                $('#tip-search').attr('title', 'Collapse');
                $('#search-icon').removeClass('fa-plus').addClass('fa-minus');
              }
            },
            "fnPreDrawCallback": function(oSettings, json) {
              $('.loader').css('display', 'block');
            }
          });
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

<script>
$(document).ready(function() {
  const initialProductValue = $('#product_id').val();

  const initialFromDateValue = $('#invoice_from').val();
  const initialTillDateValue = $('#invoice_till').val();

  $('#reset').on('click', function() {
    // Reset the input fields
    $('#name').val('');
    $('#invoice_no').val('');
    $('#status').val('');
    $('#currency').val('');

    $('#product_id').val(initialProductValue);

    $('#invoice_from').datetimepicker('clear');
    $('#invoice_till').datetimepicker('clear');
  });
});
</script>


@stop
















