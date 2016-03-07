@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="content-header">
        {!! Form::open(['url'=>'plans','method'=>'post']) !!}
        <h4>{{Lang::get('message.plan')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

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

                    <div class="col-md-4 form-group {{ $errors->has('subscription') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('subscription',Lang::get('message.subscription'),['class'=>'required']) !!}
                        {!! Form::select('subscription',[''=>'Select','Subcriptions'=>$subscription],null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('price',Lang::get('message.price'),['class'=>'required']) !!}
                        {!! Form::text('price',null,['class' => 'form-control']) !!}

                    </div>

                </div>

                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <!-- last name -->
                    {!! Form::label('description',Lang::get('message.description')) !!}
                    {!! Form::textarea('description',null,['class' => 'form-control']) !!}

                </div>
                
                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('expiry') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('expiry',Lang::get('message.expiry')) !!}
                        {!! Form::select('expiry',[],null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('updates') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('updates',Lang::get('message.updates')) !!}
                        {!! Form::select('updates',[],null,['class' => 'form-control']) !!}

                    </div>

                </div>

                

            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}
@stop