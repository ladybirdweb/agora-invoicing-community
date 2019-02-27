@extends('themes.default1.layouts.master')
@section('title')
Create Promotion
@stop
@section('content-header')
<h1>
Create New Promotion
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('promotions')}}">All Promotions</a></li>
        <li class="active">Create Promotion</li>
      </ol>
@stop
@section('content')
<style>
    .tooltip {
    background-color:#000;
    border:1px solid #fff;
    padding:10px 15px;
    width:200px;
    display:none;
    color:#fff;
    text-align:left;
    font-size:12px;
 
    /* outline radius for mozilla/firefox only */
    -moz-box-shadow:0 0 10px #000;
    -webkit-box-shadow:0 0 10px #000;
}
</style>
<div class="row">

    <div class="col-md-12">
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

            </div>
            <div class="box-body">
                {!! Form::open(['url'=>'promotions','id'=>'myform']) !!}

                <div class="box-header">
                    <h3 class="box-title">{{Lang::get('message.promotion')}}</h3>
                    <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
                </div>

                <table class="table table-condensed">



                    <tr>

                        <td><b>{!! Form::label('company',Lang::get('message.code'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        <!-- {!! Form::text('code',null,['class' => 'form-control','id'=>'code']) !!} -->
                                         <input id="code" name="code" type="text" class="form-control" title="Generate Coupon Code"/>
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


                                {!! Form::select('type',[''=>'Select','Types'=>$type],null,['class' => 'form-control',  'title'=>"Type Of Coupon"]) !!}


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('value',Lang::get('message.value')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('value') ? 'has-error' : '' }}">


                                {!! Form::text('value',null,['class' => 'form-control','title'=>'Value of the Coupon']) !!}


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('uses',Lang::get('message.uses')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('uses') ? 'has-error' : '' }}">


                                {!! Form::text('uses',null,['class' => 'form-control','title'=>'No. Of times the coupon can be Used']) !!}


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('applied',Lang::get('message.applied'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('applied') ? 'has-error' : '' }}">


                                {!! Form::select('applied[]',[''=>$product],null,['class' => 'form-control select2','multiple'=>true,'title'=>'Products for which coupon is Applied']) !!}



                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('start',Lang::get('message.start')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('start') ? 'has-error' : '' }}">
                               
                                {!! Form::text('start',null,['class'=>'form-control','id'=>'datepicker1','title'=>'Date from which Coupon is Valid']) !!}

                            </div>
                        </td>

                    </tr>


                    <tr>

                        <td><b>{!! Form::label('expiry',Lang::get('message.expiry')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('expiry') ? 'has-error' : '' }}">


                                {!! Form::text('expiry',null,['class' => 'form-control','id'=>'datepicker2','title'=>'Date on which Coupon Expires']) !!}

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

      $("#myform :input").tooltip({
 
      // place tooltip on the right edge
      position: "center right",
 
      // a little tweaking of the position
      offset: [-2, 10],
 
      // use the built-in fadeIn/fadeOut effect
      effect: "fade",
 
      // custom opacity setting
      opacity: 0.7
 
      });

  
</script>
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