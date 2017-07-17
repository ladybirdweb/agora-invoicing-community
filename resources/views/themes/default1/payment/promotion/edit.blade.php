@extends('themes.default1.layouts.master')
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="box">

                        
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

            </div>

            <div class="box-body">
                {!! Form::model($promotion,['url'=>'promotions/'.$promotion->id,'method'=>'patch']) !!}

                <div class="box-header">
                    <h3 class="box-title">{{Lang::get('message.promotion')}}</h3>
                    {!! Form::submit(Lang::get('message.save'),['class'=>'btn btn-primary pull-right'])!!}
                </div>

                <table class="table table-condensed">



                    <tr>

                        <td><b>{!! Form::label('company',Lang::get('message.code'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::text('code',null,['class' => 'form-control','id'=>'code']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="btn btn-primary" onclick="getCode();">Generate Code</a>
                                    </div>
                                </div>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('type',Lang::get('message.type'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">


                                {!! Form::select('type',[''=>'Select','Types'=>$type],null,['class' => 'form-control']) !!}


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('value',Lang::get('message.value')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('value') ? 'has-error' : '' }}">


                                {!! Form::text('value',null,['class' => 'form-control']) !!}


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('uses',Lang::get('message.uses')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('uses') ? 'has-error' : '' }}">


                                {!! Form::text('uses',null,['class' => 'form-control']) !!}


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('applied',Lang::get('message.applied'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('applied') ? 'has-error' : '' }}">

                                {!! Form::select('applied[]',[''=>$product],$selectedProduct,['class' => 'form-control','multiple'=>true]) !!}

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('start',Lang::get('message.start')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('start') ? 'has-error' : '' }}">

                                {!! Form::text('start',null,['class'=>'form-control']) !!}

                            </div>
                        </td>

                    </tr>


                    <tr>

                        <td><b>{!! Form::label('expiry',Lang::get('message.expiry')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('expiry') ? 'has-error' : '' }}">


                                {!! Form::text('expiry',null,['class' => 'form-control']) !!}

                            </div>
                        </td>

                    </tr>
                    
                   
                    

                    {!! Form::close() !!}
                </table>



            </div>

        </div>
        <!-- /.box -->

    </div>


</div>

@stop

<script>
    function getCode() {


        $.ajax({
            type: "POST",
            url: "{{url('get-code')}}",
            success: function (data) {
                $("#code").val(data)
            }
        });
    }
</script>

<script src="{{asset("plugins/moment-develop/moment.js")}}" type="text/javascript"></script>

<script type="text/javascript">
    $(function () {
        $('#start').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
    $(function () {
        $('#expiry').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>