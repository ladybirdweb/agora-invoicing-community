@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="content-header">
        {!! Form::model($sla,['url'=>'slas/'.$sla->id,'method'=>'patch']) !!}
        <h4>{{Lang::get('message.sla')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

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

                    <div class="col-md-12 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-12 form-group {{ $errors->has('licence_id') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('licence_id',Lang::get('message.licence'),['class'=>'required']) !!}
                        {!! Form::select('licence_id',[$licences],null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-12 form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('description',Lang::get('message.description')) !!}
                        {!! Form::textarea('description',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-12 form-group {{ $errors->has('organization_id') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('organization_id',Lang::get('message.organization'),['class'=>'required']) !!}
                        {!! Form::select('organization_id',[$organizations],null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-12 form-group {{ $errors->has('service_provider_id') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('service_provider_id',Lang::get('message.service_provider'),['class'=>'required']) !!}
                        {!! Form::select('service_provider_id',[$serviceProviders],null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-12 form-group {{ $errors->has('service') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('service',Lang::get('message.service'),['class'=>'required']) !!}
                        {!! Form::select('service',[$services],null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-12 form-group {{ $errors->has('short_note') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('short_note',Lang::get('message.short_note')) !!}
                        {!! Form::textarea('shortnote',null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-12 form-group {{ $errors->has('start_date') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('start_date',Lang::get('message.start_date')) !!}
                        {!! Form::date('start_date',null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-12 form-group {{ $errors->has('end_date') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('end_date',Lang::get('message.end_date')) !!}
                        {!! Form::date('end_date',null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-12 form-group {{ $errors->has('grace_period') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('grace_period',Lang::get('message.grace_period')) !!}
                        {!! Form::text('grace_period',null,['class' => 'form-control']) !!}

                    </div>


                </div>

                
            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}
@stop