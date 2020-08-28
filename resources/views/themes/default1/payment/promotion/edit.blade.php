@extends('themes.default1.layouts.master')
@section('title')
Edit Promotion
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Edit Promotion</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('promotions')}}"><i class="fa fa-dashboard"></i> All Promotions</a></li>
            <li class="breadcrumb-item active">Edit Promotion</li>
        </ol>
    </div><!-- /.col -->


@stop

@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="card card-primary card-outline">

                        

            <div class="card-body table-responsive">
                {!! Form::model($promotion,['url'=>'promotions/'.$promotion->id,'method'=>'patch']) !!}




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
                                        <a href="#" class="btn btn-primary" onclick="getCode();"><i class="fa fa-refresh"></i>&nbsp;Generate Code</a>
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
                                <div class="input-group date" id="startDate" data-target-input="nearest">
                                    <input type="text" name="start" class="form-control datetimepicker-input" autocomplete="off"  value="{{$startDate}}" title="Date from which Coupon is Valid" data-target="#startDate"/>
                                    <div class="input-group-append" data-target="#startDate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>


                    <tr>

                        <td><b>{!! Form::label('expiry',Lang::get('message.expiry')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('expiry') ? 'has-error' : '' }}">

                                <div class="input-group date" id="endDate" data-target-input="nearest">
                                    <input type="text" name="expiry" class="form-control datetimepicker-input" autocomplete="off"  value="{{$expiryDate}}" title="Date from which Coupon Expires" data-target="#endDate"/>
                                    <div class="input-group-append" data-target="#endDate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>
                    
                   
                    

                    {!! Form::close() !!}

                </table>


                <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-save">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>

            </div>

        </div>
        <!-- /.box -->

    </div>


</div>

@stop

<script>
    function getCode() {


        $.ajax({
            type: "GET",
            url: "{{url('get-promotion-code')}}",
            success: function (data) {
                $("#code").val(data)
            }
        });
    }
</script>

<script src="{{asset("plugins/moment-develop/moment.js")}}" type="text/javascript"></script>

@section('datepicker')
<script type="text/javascript">

    $('#startDate').datetimepicker({
        format: 'L'
    });
    $('#endDate').datetimepicker({
        format: 'L'
    });

</script>
@stop