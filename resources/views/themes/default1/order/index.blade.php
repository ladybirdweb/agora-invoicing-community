@extends('themes.default1.layouts.master')
@section('title')
Orders
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>View All Orders</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">All Orders</li>
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
    <div class="card-body table-responsive">
        {!! Form::open(['method'=>'get']) !!}

        <div class="row">

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('order_no','Order No:') !!}
                {!! Form::text('order_no',$request->order_no,['class' => 'form-control','id'=>'order_no']) !!}

            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('product_id','Product') !!} <br>
                {!! Form::select('product_id',[null => 'Choose']+ $products, $request->product_id, ['class' => 'form-control select2','style'=>'width:265px;','id'=>'product_id']) !!}
            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('expiry','Updates Expiry From') !!}
                <div class="input-group date" id="update_expiry" data-target-input="nearest">
                    <input type="text" name="expiry" class="form-control datetimepicker-input" autocomplete="off" value="{!! $request->expiry !!}" data-target="#update_expiry"/>

                    <div class="input-group-append" data-target="#update_expiry" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>

                </div>

            </div>
             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('expiry','Updates Expiry Till') !!}
                 <div class="input-group date" id="update_expiry_till" data-target-input="nearest">
                     <input type="text" name="expiryTill" class="form-control datetimepicker-input" autocomplete="off" value="{!! $request->expiryTill !!}" data-target="#update_expiry_till"/>

                     <div class="input-group-append" data-target="#update_expiry_till" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                     </div>

                 </div>


            </div>

             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Subscription From') !!}
                 <div class="input-group date" id="subs_from" data-target-input="nearest">
                     <input type="text" name="sub_from" class="form-control datetimepicker-input" autocomplete="off" value="{!! $request->sub_from !!}" data-target="#subs_from"/>


                     <div class="input-group-append" data-target="#subs_from" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                     </div>

                 </div>

            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('till','Subcription Till') !!}
                <div class="input-group date" id="subs_till" data-target-input="nearest">
                    <input type="text" name="sub_till" class="form-control datetimepicker-input" autocomplete="off" value="{!! $request->sub_till !!}" data-target="#subs_till"/>


                    <div class="input-group-append" data-target="#subs_till" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>

                </div>
            </div>

              <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('ins_not_ins','Installed') !!}
                {!! Form::select('ins_not_ins',[null => 'Choose']+ $insNotIns, $request->ins_not_ins, ['class' => 'form-control','id'=>'ins_not_ins']) !!}
            </div>

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Order From') !!}
                <div class="input-group date" id="order_from" data-target-input="nearest">
                    <input type="text" name="from" class="form-control datetimepicker-input" autocomplete="off" value="{!! $request->from !!}" data-target="#order_from"/>


                    <div class="input-group-append" data-target="#order_from" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>

                </div>


            </div>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('till','Order Till') !!}
                <div class="input-group date" id="order_till" data-target-input="nearest">
                    <input type="text" name="till" class="form-control datetimepicker-input" autocomplete="off" value="{!! $request->till !!}" data-target="#order_till"/>


                    <div class="input-group-append" data-target="#order_till" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>

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
                      <button name="Search" type="submit"  class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{!!Lang::get('Search')!!}</button>
                      &nbsp;
                    <a class="btn btn-danger" href="{!! url('/orders') !!}"><i class="fas fa-sync-alt"></i>&nbsp;{!!Lang::get('Reset')!!}</a>
                </div>
        </div>
    </div>
</div>
    </div>
</div>
<div class="card card-primary card-outline">

    <div class="card-header">

        <div id="response"></div>
        <h3 class="card-title">Orders</h3>
            <!--<a href="{{url('orders/create')}}" class="btn btn-primary pull-right   ">{{Lang::get('message.create')}}</a></h4>-->
    </div>



    <div class="card-body table-responsive">
        <div class="row">

            <div class="col-md-12">


                <table id="order-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                 <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Selected</button><br /><br />
                    <thead><tr>
                        <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                          <th>User</th>

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

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    });

        $('#order-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: false,
            ajax: {
            "url":  '{!! route('get-orders',"order_no=$request->order_no&product_id=$request->product_id&expiry=$request->expiry&expiryTill=$request->expiryTill&from=$request->from&till=$request->till&sub_from=$request->sub_from&sub_till=$request->sub_till&ins_not_ins=$request->ins_not_ins&domain=$request->domain&p_un=$request->p_un&act_ins=$request->act_inst&renewal=$request->renewal&inact_ins=$request->inact_inst&version_from=$request->version_from&version_till=$request->version_till" ) !!}',
               error: function(xhr) {
               if(xhr.status == 401) {
                alert('Your session has expired. Please login again to continue.')
                window.location.href = '/login';
               }
            }

            },

            // if in request sort field is present, it will take that else default order
            // need to stringify the sort_order, else it will be considered as a javascript variable
           
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
    $('#update_expiry').datetimepicker({
        format: 'L'
    });
    $('#update_expiry_till').datetimepicker({
        format: 'L'
    });
    $('#subs_from').datetimepicker({
        format: 'L'
    });
    $('#subs_till').datetimepicker({
        format: 'L'
    });
    $('#order_from').datetimepicker({
        format: 'L'
    });
    $('#order_till').datetimepicker({
        format: 'L'
    });
</script>
@stop
