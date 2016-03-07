@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="content-header">
        {!! Form::open(['url'=>'services','method'=>'post']) !!}
        <h4>{{Lang::get('message.service')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

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

                    <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('product_id',Lang::get('message.product'),['class'=>'required']) !!}
                        {!! Form::select('product_id',[''=>'Select','Products'=>$product->lists('name','id')],null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('plan_id') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('plan_id',Lang::get('message.plan'),['class'=>'required']) !!}
                        {!! Form::select('plan_id',[''=>'Select','Plans'=>$plan->lists('name','id')],null,['class' => 'form-control']) !!}

                    </div>

                </div>

                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <!-- last name -->
                    {!! Form::label('description',Lang::get('message.description')) !!}
                    {!! Form::textarea('description',null,['class' => 'form-control']) !!}

                </div>
                

            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}
@stop