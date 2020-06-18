@extends('themes.default1.layouts.master')
@section('title')
Script
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Create Script Code</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item"><a href="{{url('chat')}}"><i class="fa fa-dashboard"></i> Script</a></li>
            <li class="breadcrumb-item active">Create Script</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
<div class="card card-primary card-outline">

        {!! Form::open(['url'=>'chat','method'=>'post']) !!}



    <div class="card-body">

        <div class="row">

            <div class="col-md-12">



                <div class="row">

                    <div class="col-md-12 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control']) !!}

                    </div>

                   

                   


                </div>

                <div class="row">
                    <div class="col-md-12 form-group">

       

                        {!! Form::label('data',Lang::get('message.content'),['class'=>'required']) !!}
                        {!! Form::textarea('script',null,['class'=>'form-control','id'=>'textarea']) !!}

                    </div>


                </div>

            </div>

        </div>
        <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-save">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>

    </div>

</div>


{!! Form::close() !!}
@stop