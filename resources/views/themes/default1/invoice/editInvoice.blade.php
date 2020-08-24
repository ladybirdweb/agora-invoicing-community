@extends('themes.default1.layouts.master')
@section('title')
Edit Invoice
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1> Edit Invoice</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('clients')}}"> All Users</a></li>
            <li class="breadcrumb-item"><a href="{{url('clients/'.$invoice->user_id)}}">View User</a></li>
            <li class="breadcrumb-item active">Edit Invoice</li>
        </ol>
    </div><!-- /.col -->

@stop

@section('content')

<div class="card card-primary card-outline">

    <div class="card-header">
       
        {!! Form::open(['url'=>'invoice/edit/'.$invoiceid,'method'=>'post']) !!}

        <h5>Invoice Number:#{{$invoice->number}}	</h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-12">

                

                <div class="row">

                   <div class="col-md-6 form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('date',Lang::get('message.date'),['class'=>'required']) !!}
                         <div class="input-group date" id="payment" data-target-input="nearest">
                                 <input type="text" id="payment_date" name="date" value="{{$date}}" class="form-control datetimepicker-input" autocomplete="off"  data-target="#payment"/>
                                <div class="input-group-append" data-target="#payment" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>

                                </div>
                              

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
                <button type="submit" class="form-group btn btn-primary pull-right" id="submit"><i class="fa fa-save">&nbsp;</i>{!!Lang::get('message.update')!!}</button>

            </div>

        </div>

    </div>

</div>

 
{!! Form::close() !!}
@stop
@section('datepicker')
<script>
        $(function () {
        $('#payment').datetimepicker({
            format: 'L'
        });
    });
</script>
@stop