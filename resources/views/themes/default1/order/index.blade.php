@extends('themes.default1.layouts.master')
@section('title')
Orders
@stop
@section('content')
@section('content-header')
<h1>
View All Orders
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Orders</li>
      </ol>
@stop
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
                {!! Form::label('order_no','Order No:') !!}
                {!! Form::text('order_no',$request->order_no,['class' => 'form-control','id'=>'order_no']) !!}

            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('product_id','Product') !!}
                {!! Form::select('product_id',[null => 'Select']+ $products, $request->product_id, ['class' => 'form-control','id'=>'product_id']) !!}
            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('expiry','Updates Expiry From') !!}
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="expiry" value="{!! $request->expiry !!}" class="form-control expary" id="datepicker1">
                </div>
            </div>
             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('expiry','Updates Expiry Till') !!}
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="expiryTill" value="{!! $request->expiryTill !!}" class="form-control exparytill" id="datepicker2">
                </div>
         
            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Order From') !!}
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="from" value="{!! $request->from !!}" class="form-control payment_date" id="datepicker3">
                </div>
            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('till','Order Till') !!}
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="till" value="{!! $request->till !!}" class="form-control payment_till" id="datepicker4">
                </div>
            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('domain','Domain') !!}
                {!! Form::text('domain',$request->domain,['class' => 'form-control','id'=>'domain']) !!}

            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('p_un','Paid/Unpaid Products') !!}
                {!! Form::select('p_un',[null => 'Select']+ $paidUnpaidOptions, $request->p_un, ['class' => 'form-control','id'=>'p_un']) !!}
            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('act_inst','Active Installations') !!}
                {!! Form::select('act_inst',[null => 'Select']+ $activeInstallationOptions, $request->act_inst, ['class' => 'form-control','id'=>'act_inst']) !!}
            </div>

             <div class="col-md-3 form-group">
                 {!! Form::label('version_less_than_equal','Version less than or equal to') !!}
                 {!! Form::select('version_less_than_equal',[null => 'Select']+ array_combine($allVersions, $allVersions), $request->version_less_than_equal,
                ['class' => 'form-control','id'=>'version_less_than_equal']) !!}

             </div>

            <div class="col-md-3 form-group">
                {!! Form::label('version_greater_than_equal','Version greater than or equal to') !!}
                {!! Form::select('version_greater_than_equal',[null => 'Select']+ array_combine($allVersions, $allVersions), $request->version_greater_than_equal,
                ['class' => 'form-control','id'=>'version_greater_than_equal']) !!}
            </div>

        </div>

           <div class='row'>
                <div class="col-md-6">
                    <!-- {!! Form::submit('Search',['class'=>'btn btn-primary']) !!} -->
                      <button name="Search" type="submit"  class="btn btn-primary" data-loading-text="<i class='fa fa-search fa-spin fa-1x fa-fw'>&nbsp;</i> updating..."><i class="fa fa-search">&nbsp;&nbsp;</i>{!!Lang::get('Search')!!}</button>
                      &nbsp;
                    <!-- {!! Form::submit('Reset',['class'=>'btn btn-danger','id'=>'reset']) !!} -->
                     <button name="Reset" type="submit" id="reset" class="btn btn-danger" data-loading-text="<i class='fa fa-refresh fa-spin fa-1x fa-fw'>&nbsp;</i> updating..."><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('Reset')!!}</button>


                </div>
            

        </div>
<script type="text/javascript">
                    $(function () {
                    $('#reset').on('click', function () {
                      
                        $('#order_no').val('');
                        $('#product_id').val('');
                        $('.expary').val('');
                        $('.exparytill').val('');
                        $('.payment_date').val('');
                        $('.payment_till').val('');
                        $('#domain').val('');
                        $('#ver').val('');
                          
                    });
                });
                </script>


        {!! Form::close() !!}
    </div>
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
        <h4>{{Lang::get('message.orders')}}
            <!--<a href="{{url('orders/create')}}" class="btn btn-primary pull-right   ">{{Lang::get('message.create')}}</a></h4>-->
    </div>



    <div class="box-body">
        <div class="row">

            <div class="col-md-12">


                <table id="order-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                 <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Selected</button><br /><br />
                    <thead><tr>
                        <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                          <th>Client</th>
                           
                            <th>Order No</th>
                            <th>Product</th>
                            <th>Version</th>
                            <th>Total</th>
                            
                             <th>Status</th>
                              <th>Order Date</th>
                              <th>Expiry</th>
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
        $('#order-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: false,
            order: [[ 7, "desc" ]],
             ajax: '{!! route('get-orders',"order_no=$request->order_no&product_id=$request->product_id&expiry=$request->expiry&expiryTill=$request->expiryTill&from=$request->from&till=$request->till&domain=$request->domain&p_un=p_un&act_ins=$request->act_inst&version_less_than_equal=$request->version_less_than_equal&version_greater_than_equal=$request->version_greater_than_equal" ) !!}',
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
                {data: 'client', name: 'client'},
                {data: 'number', name: 'number'},
                {data: 'product_name', name: 'product_name'},
                {data: 'version', name: 'version'},
                {data: 'price_override', name: 'price_override'},
                {data: 'order_status', name: 'order_status'},
                {data: 'order_date', name: 'order_date'},
                {data: 'update_ends_at', name: 'update_ends_at'},
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
          
          $('#order-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
     }
     

     $(document).on('click','#bulk_delete',function(){
      var id=[];
      if (confirm("Are you sure you want to delete this?"))
        {
            $('.order_checkbox:checked').each(function(){
              id.push($(this).val())
            });
            if(id.length >0)
            {
               $.ajax({
                      url:"{!! route('orders-delete') !!}",
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
     $('#datepicker1').datepicker({
      autoclose: true
    });
    $('#datepicker2').datepicker({
      autoclose: true
    })
    $('#datepicker3').datepicker({
      autoclose: true
    })
    $('#datepicker4').datepicker({
      autoclose: true
    })
</script>
@stop
