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
                {!! Datatable::table()
                ->addColumn('<input type="checkbox" class="checkbox-toggle">','Date','Client Name','Order No.','Total','Status','Expiry Date','Action')
                ->setUrl("get-orders?order_no=$order_no&product_id=$product_id&expiry=$expiry&from=$from&till=$till&domain=$domain") 
                ->setOrder([1=>'desc'])
                ->setOptions(

                [
                "dom" => "Bfrtip",
                "buttons" => [
                [
                "text" => "Delete",
                "action" => "function ( e, dt, node, config ) {
                e.preventDefault();
                var answer = confirm ('Are you sure you want to delete from the database?');
                if(answer){
                $.ajax({
                url: 'orders-delete',
                type: 'GET',
                data: $('#check:checked').serialize(),

                beforeSend: function () {
                $('#gif').show();
                },
                success: function (data) {
                $('#gif').hide();
                $('#response').html(data);
                location.reload();
                }

                });
                }
                }"
                ]
                ],

                ])
                ->render() !!}

            </div>
        </div>

    </div>

</div>



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