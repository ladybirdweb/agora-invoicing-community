@extends('themes.default1.layouts.master')
@section('content')

<div class="box box-primary">

    <div class="box-header">

        <h4>{{Lang::get('message.tax')}}
            <!--<a href="{{url('currency/create')}}" class="btn btn-primary pull-right   ">{{Lang::get('message.create')}}</a>-->
            <a href="#create" class="btn btn-primary pull-right" data-toggle="modal" data-target="#create">{{Lang::get('message.create')}}</a>
        </h4>
        @include('themes.default1.payment.tax.create')
    </div>

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
        <i class="fa fa-ban"></i>
        <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
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

    <div class="box-body">
        <div class="row">

            <div class="col-md-12">
                {!! Form::model($rule,['url'=>'tax-rule','method'=>'patch']) !!}
                <table class="table table-condensed">

                    <tr>
                        <td><b>{!! Form::label('company',Lang::get('message.tax-enable')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">

                                <p>{!! Form::checkbox('status',1) !!} {{Lang::get('message.tick-this-box-to-enable-tax-support')}}</p>

                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><b>{!! Form::label('company',Lang::get('message.tax-type')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">

                                <div class="row">

                                    <div class="col-md-3">
                                        <p>{!! Form::radio('type','exclusive') !!} {{Lang::get('message.exclusive')}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>{!! Form::radio('type','inclusive') !!} {{Lang::get('message.inclusive')}}</p>
                                    </div>

                                </div>

                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><b>{!! Form::label('company',Lang::get('message.compound-tax')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">

                                <p>{!! Form::checkbox('compound',1) !!} {{Lang::get('message.tick-this-box-to-charge-compound-tax-on-level-2')}}</p>

                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td></td>
                        <td>
                            {!! Form::submit('save',['class'=>'btn btn-primary']) !!}
                        </td>
                    </tr>

                </table>
                {!! Form::close() !!}
            </div>

            
            <div class="col-md-12">
                {!! Datatable::table()
                ->addColumn('<input type="checkbox" class="checkbox-toggle">','Name','Level','Country','State','Rate (%)','Action')
                ->setUrl('get-tax')
                ->setOptions([

                "dom" => "Bfrtip",
                "buttons" => [
                [
                "text" => "Delete",
                "action" => "function ( e, dt, node, config ) {
                    $.ajax({
                        url: 'tax-delete',
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