@extends('themes.default1.layouts.master')
@section('content')
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
        {!! Form::open(['url'=>'licences','method'=>'post']) !!}
        <h4>{{Lang::get('message.licences')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

                

                <div class="row">

                    <div class="col-md-12 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-12 form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('description',Lang::get('message.description')) !!}
                        {!! Form::textarea('description',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-12 form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('price',Lang::get('message.price'),['class'=>'required']) !!}
                        {!! Form::text('price',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-12 form-group {{ $errors->has('number_of_sla') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('number_of_sla',Lang::get('message.number_of_sla')) !!}
                        {!! Form::text('number_of_sla',null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-12 form-group {{ $errors->has('shoping_cart_link') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('shoping_cart_link',Lang::get('message.shoping_cart_link')) !!}
                        {!! Form::text('shoping_cart_link',$cartUrl,['class' => 'form-control']) !!}

                    </div>


                </div>

                
            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}
@stop