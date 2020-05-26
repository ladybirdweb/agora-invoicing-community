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
                {!! Form::select('product_id',[null => 'Choose']+ $products, $request->product_id, ['class' => 'form-control','id'=>'product_id']) !!}
            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('expiry','Updates Expiry From') !!}
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input autocomplete="off" type="text" name="expiry" value="{!! $request->expiry !!}" class="form-control datepicker" id="datepicker1">
                </div>
            </div>
             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('expiry','Updates Expiry Till') !!}
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input autocomplete="off" type="text" name="expiryTill" value="{!! $request->expiryTill !!}" class="form-control datepicker" id="datepicker2">
                </div>
         
            </div>

             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Subscription From') !!}
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input autocomplete="off" type="text" name="sub_from" value="{!! $request->sub_from !!}" class="form-control datepicker" id="datepicker5">
                </div>
            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('till','Subcription Till') !!}
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input autocomplete="off" type="text" name="sub_till" value="{!! $request->sub_till !!}" class="form-control datepicker" id="datepicker6">
                </div>
            </div>

              <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('ins_not_ins','Installed/Not Installed') !!}
                {!! Form::select('ins_not_ins',[null => 'Choose']+ $insNotIns, $request->ins_not_ins, ['class' => 'form-control','id'=>'ins_not_ins']) !!}
            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Order From') !!}
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input autocomplete="off" type="text" name="from" value="{!! $request->from !!}" class="form-control datepicker" id="datepicker3">
                </div>
            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('till','Order Till') !!}
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input autocomplete="off" type="text" name="till" value="{!! $request->till !!}" class="form-control datepicker" id="datepicker4">
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
                {!! Form::select('p_un',[null => 'Choose']+ $paidUnpaidOptions, $request->p_un, ['class' => 'form-control','id'=>'p_un']) !!}
            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('act_inst','Active Installations') !!}
                {!! Form::select('act_inst',[null => 'Choose']+ $activeInstallationOptions, $request->act_inst, ['class' => 'form-control','id'=>'act_inst']) !!}
            </div>

             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('inact_inst','Inactive Installations') !!}
                {!! Form::select('inact_inst',[null => 'Choose']+ $inactiveInstallationOptions, $request->inact_inst, ['class' => 'form-control','id'=>'inact_inst']) !!}
            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('renewal','Subscriptions') !!}
                {!! Form::select('renewal',[null => 'Choose']+ $renewal, $request->renewal, ['class' => 'form-control','id'=>'renewal']) !!}
            </div>

            <div class="col-md-3 form-group">
                {!! Form::label('version_from','Version From') !!}
                {!! Form::select('version_from',[null => 'Choose']+ array_combine($allVersions, $allVersions), $request->version_from,
                ['class' => 'form-control','id'=>'version_from']) !!}
            </div>

            <div class="col-md-3 form-group">
                 {!! Form::label('version_till','Version Till') !!}
                 {!! Form::select('version_till',[null => 'Choose']+ array_combine($allVersions, $allVersions), $request->version_till,
                ['class' => 'form-control','id'=>'version_till']) !!}

             </div>

        </div>

           <div class='row'>
                <div class="col-md-6">
                      <button name="Search" type="submit"  class="btn btn-primary" data-loading-text="<i class='fa fa-search fa-spin fa-1x fa-fw'>&nbsp;</i> updating..."><i class="fa fa-search"></i>&nbsp;{!!Lang::get('Search')!!}</button>
                      &nbsp;
                    <a class="btn btn-danger" href="{!! url('/orders') !!}"><i class="fa fa-refresh"></i>&nbsp;{!!Lang::get('Reset')!!}</a>
                </div>
        </div>
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
            // if in request sort field is present, it will take that else default order
            // need to stringify the sort_order, else it will be considered as a javascript variable
            order: [[ {!! $request->sort_field ?: 7 !!}, {!! "'".$request->sort_order."'" ?: "'desc'" !!} ]],
             ajax: '{!! route('get-orders',"order_no=$request->order_no&product_id=$request->product_id&expiry=$request->expiry&expiryTill=$request->expiryTill&from=$request->from&till=$request->till&sub_from=$request->sub_from&sub_till=$request->sub_till&ins_not_ins=$request->ins_not_ins&domain=$request->domain&p_un=$request->p_un&act_ins=$request->act_inst&renewal=$request->renewal&inact_ins=$request->inact_inst&version_from=$request->version_from&version_till=$request->version_till" ) !!}',
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
     $('.datepicker').datepicker({
      autoclose: true
    });
</script>
@stop
