@extends('themes.default1.layouts.master')
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

            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('order_no','Order No:') !!}
                {!! Form::text('order_no',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('product_id','Product') !!}
                {!! Form::select('product_id',[''=>'Select','Products'=>$products],null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('expiry','Expiry') !!}
                {!! Form::text('expiry',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('from','Order From') !!}
                {!! Form::text('from',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('till','Order Till') !!}
                {!! Form::text('till',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('domain','Domain') !!}
                {!! Form::text('domain',null,['class' => 'form-control']) !!}

            </div>

            <div class="col-md-4 col-md-offset-4">
                <div class="col-md-6">
                    {!! Form::submit('Search',['class'=>'btn btn-primary']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::submit('Reset',['class'=>'btn btn-danger']) !!}
                </div>
            </div>

        </div>



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

                    <thead><tr>
                         <th>Date</th>
                          <th>Client Name</th>
                           
                            <th>Order No</th>
                            <th>Total</th>
                            
                             <th>Status</th>
                              <th>Expiry Date</th>
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
             ajax: '{{Url("get-orders?order_no=$order_no&product_id=$product_id&expiry=$expiry&from=$from&till=$till&domain=$domain")}}',

           
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
                "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
              }],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'client', name: 'client'},
                {data: 'number', name: 'number'},
                {data: 'price_override', name: 'price_override'},
                  {data: 'order_status', name: 'order_status'},
                  {data: 'ends_at', name: 'ends_at'},

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
    $(function () {


        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });


    });
</script>
@stop
@section('datepicker')
<script type="text/javascript">
    $(function () {
        $('#expiry').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
    $(function () {
        $('#from').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
    $(function () {
        $('#till').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
@stop