@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="content-header">
        {!! Form::open(['url'=>'payment/receive/'.$invoice_id,'method'=>'post']) !!}
        <h4>{{Lang::get('message.payment')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

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

                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('invoice_status') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('invoice_status',Lang::get('message.invoice-status'),['class'=>'required']) !!}
                        {!! Form::select('invoice_status',[''=>'Select','pending'=>'Pending','success'=>'Success'],strtolower($invoice_status),['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('payment_method') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('payment_method',Lang::get('message.payment-method')) !!}
                        {!! Form::select('payment_method',[''=>'Select','cash'=>'Cash','check'=>'Check','online payment'=>'Online Payment','ccavanue'=>'ccAvanue','paypal'=>'Paypal'],$payment_method,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('payment_status') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('payment_status',Lang::get('message.payment-status')) !!}
                        {!! Form::select('payment_status',[''=>'Select','pending'=>'Pending','success'=>'Success'],$payment_status,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-4 form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('domain',Lang::get('message.domain')) !!}
                        {!! Form::text('domain',$domain,['class' => 'form-control']) !!}

                    </div>

                    


                </div>


            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}
@stop