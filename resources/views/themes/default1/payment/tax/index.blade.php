@extends('themes.default1.layouts.master')
@section('content')

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


        <h4>{{Lang::get('message.tax')}}
            <!--<a href="{{url('currency/create')}}" class="btn btn-primary pull-right   ">{{Lang::get('message.create')}}</a>-->
            <!--<a href="#create" class="btn btn-primary pull-right" data-toggle="modal" data-target="#create">{{Lang::get('message.create')}}</a>-->
            <a href="#create-tax-option" class="btn btn-primary pull-right" data-toggle="modal" data-target="#create-tax-option">{{Lang::get('message.create')}}</a>
        </h4>
        @include('themes.default1.payment.tax.create-tax-option')

    </div>



    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="header-body">
                    <h4>Options
                        {!! Form::model($options,['url'=>'taxes/option','method'=>'patch']) !!}
                    </h4>
                </div>

                <table class="table table-responsive">
                    <tr>
                        <td>
                            {!! Form::label('tax_enable',Lang::get('message.tax-enable')) !!}
                        </td>
                        <td>
                            {!! Form::hidden('tax_enable',0) !!}
                            <p>{!! Form::checkbox('tax_enable',1) !!}
                                {{Lang::get('message.tick-this-box-to-enable-tax-support')}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('inclusive',Lang::get('message.prices-entered-with-tax')) !!}
                        </td>
                        <td>
                            <p>{!! Form::radio('inclusive',1) !!}
                                {{Lang::get('message.inclusive')}}</p>
                            <p>{!! Form::radio('inclusive',0,true) !!}
                                {{Lang::get('message.exclusive')}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('shop_inclusive',Lang::get('message.display-prices-in-the-shop')) !!}
                        </td>
                        <td>
                            <p>{!! Form::radio('shop_inclusive',1) !!}
                                {{Lang::get('message.inclusive')}}</p>
                            <p>{!! Form::radio('shop_inclusive',0,true) !!}
                                {{Lang::get('message.exclusive')}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('cart_inclusive',Lang::get('message.display-prices-during-cart-and-checkout')) !!}
                        </td>
                        <td>
                            <p>{!! Form::radio('cart_inclusive',1) !!}
                                {{Lang::get('message.inclusive')}}</p>
                            <p>{!! Form::radio('cart_inclusive',0,true) !!}
                                {{Lang::get('message.exclusive')}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('rounding',Lang::get('message.rounding')) !!}
                        </td>
                        <td>
                            {!! Form::hidden('rounding',0) !!}
                            <p>{!! Form::checkbox('rounding',1) !!}
                                {{Lang::get('message.round-tax-at-subtotal')}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>{!! Form::submit('save',['class'=>'btn btn-primary']) !!}</td>

                    </tr>
                </table>

                {!! Form::close() !!}

                <div class="box">
                    <div class="box-header">
                        Classes
                    </div>
                    <div class="box-body">

                        @forelse($classes as $key=>$value)
                        <div class="col-md-2">
                            <a href="#create" data-toggle="modal" data-target="#create{{$key}}">{{ucfirst($value)}}</a>
                        </div>
                        @include('themes.default1.payment.tax.create')
                        @empty 
                        <div class="col-md-2">
                            <a href="#create" data-toggle="modal" data-target="#create-tax-option">Add Class</a>
                        </div>

                        @endforelse
                    </div>
                </div>


            </div>



            <div class="col-md-12">
                <table id="templates-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

                    <thead><tr>
                         <th>Class Name</th>
                          <th>Name</th>
                           <th>Level</th>
                           <th>Country</th>
                          <th>State</th>
                           <th>Rate (%)</th>
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
        $('#tax-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('get-tax') !!}',
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
                {data: 'tax_classes_id', name: 'Class Name'},
                {data: 'name', name: 'Name'},
                {data: 'level', name: 'Level'},
                {data: 'country', name: 'Country'},
                {data: 'rate', name: 'Rate (%)'},
                {data: 'action', name: 'Action'}
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