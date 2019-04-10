@extends('themes.default1.layouts.master')
@section('title')
Edit Invoice
@stop
@section('content-header')
<h1>
Edit Invoice
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('clients')}}">All Users</a></li>
        <li><a href="{{url('clients/'.$invoice->user_id)}}">View User</a></li>
        <li class="active">Edit Invoice</li>
      </ol>
@stop
@section('content')

<div class="box box-primary">

    <div class="box-header">
        @if (count($errors) > 0)
                <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
                     <i class="fa fa-check"></i>
                     <b>{{Lang::get('message.success')}}!</b> 
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
        {!! Form::open(['url'=>'invoice/edit/'.$invoiceid,'method'=>'post']) !!}

        <h4>Invoice Number:#{{$invoice->number}}	 <button type="submit" class="form-group btn btn-primary pull-right" id="submit"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>

    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

                

                <div class="row">

                   <div class="col-md-6 form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('date',Lang::get('message.date'),['class'=>'required']) !!}
                      
                         <div class="input-group date">
                             <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                         </div>
                      <input name="date" type="text" value="{{$date}}" class="form-control" id="datepicker">
                        </div>
                      

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('total') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('total',Lang::get('message.invoice-total'),['class'=>'required']) !!}
                        <!-- {!! Form::text('total',null,['class' => 'form-control']) !!} -->
                        <input type="text" name="total" class="form-control" value="{{$invoice->grand_total}}">

                    </div>


                     <div class="col-md-6 form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('status',Lang::get('message.status')) !!}
                         <select name="status"  class="form-control">
                            <option selected="selected">{{$invoice->status}}</option>
                             <option value="">Choose</option>
                          <option value="success">Success</option>
                        <option value="pending">Pending</option>
                         </select>

                    </div>

                    


                </div>


            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}
@stop
@section('datepicker')
<script>
     $('#datepicker').datepicker({
      autoclose: true
    });
</script>
@stop