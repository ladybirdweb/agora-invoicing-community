@extends('themes.default1.layouts.master')
@section('title')
Invoices
@stop
@section('content-header')
<h1>
All Invoices
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Invoices</li>
      </ol>
@stop
@section('content')
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Search</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

        {!! Form::open(['method'=>'get']) !!}

        <div class="row">
            
             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('name','First Name') !!}
                {!! Form::text('name',null,['class' => 'form-control','id'=>'name']) !!}

            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('invoice_no','Invoice No') !!}
                {!! Form::text('invoice_no',null,['class' => 'form-control','id'=>'invoice_no']) !!}

            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('status','Status') !!}
               <select name="status"  class="form-control" id="status">
                    <option value="">Choose</option>
                   <option value="pending">Pending</option>
                  <option value="success">Success</option>
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
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="from" class="form-control from" id="datepicker1">
                </div>

            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('till','Invoice Till') !!}
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="till" class="form-control till" id="datepicker2">
                </div>

            </div>
        </div>
            

            <div class='row'>
                <div class="col-md-6">
                    <!-- {!! Form::submit('Search',['class'=>'btn btn-primary']) !!} -->
                    <button name="Search" type="submit"  class="btn btn-primary" data-loading-text="<i class='fa fa-search fa-spin fa-1x fa-fw'>&nbsp;</i> updating..."><i class="fa fa-search">&nbsp;&nbsp;</i>{!!Lang::get('Search')!!}</button>
                     &nbsp;&nbsp;
                    <!-- {!! Form::submit('Reset',['class'=>'btn btn-danger','id'=>'reset']) !!} -->
                     <button name="Reset" type="submit" id="reset" class="btn btn-danger" data-loading-text="<i class='fa fa-refresh fa-spin fa-1x fa-fw'>&nbsp;</i> updating..."><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('Reset')!!}</button>


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
            <i class="fa fa-check"></i>
            <b>{{Lang::get('message.alert')}}!</b> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('success')}}
        </div>
        @endif
        <!-- fail message -->
        @if(Session::has('fails'))
        <div class="alert alert-danger alert-dismissable">
            <i class="fa fa-ban"></i>
            <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('fails')}}
        </div>
        @endif
        <div id="response"></div>
        <h4>{{Lang::get('message.invoices')}}
           
            <a href="{{url('invoice/generate')}}" class="btn btn-primary pull-right "><i class="fa fa-credit-card"></i>&nbsp; {{Lang::get('message.place-an-order')}}</a></h4>
    </div>



    <div class="box-body">
        <div class="row">

            <div class="col-md-12">
  
                <table id="invoice-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                    <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Selected</button><br /><br />
                    <thead><tr>
                        <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                         <th>Client</th>
                          <th>Invoice Number</th>
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
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">

        $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: true,
            order: [[ 0, "desc" ]],
            ajax: '{!! route('get-invoices',"name=$name&invoice_no=$invoice_no&status=$status&currency_id=$currency_id&from=$from&till=$till") !!}',
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
                 {data: 'checkbox', name: 'checkbox'},
                {data: 'user_id', name: 'user_id'},
                {data: 'number', name: 'number'},
                {data: 'date', name: 'date'},
                {data: 'grand_total', name: 'grand_total'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
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
                      method:"get",
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

 $('#datepicker1').datepicker({
      autoclose: true
    });
 $('#datepicker2').datepicker({
      autoclose: true
    });
</script>
@stop
















