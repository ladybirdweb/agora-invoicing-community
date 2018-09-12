@extends('themes.default1.layouts.master')
@section('title')
Github Setting
@stop
@section('content-header')
<h1>
Github Settings
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li class="active">Github Setting</li>
      </ol>
@stop
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
        {!! Form::model($model,['url'=>'github','method'=>'patch']) !!}
        <h4>{{Lang::get('message.github')}}	<button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>

    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">



                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('username',Lang::get('message.username'),['class'=>'required']) !!}
                        {!! Form::text('username',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('password',Lang::get('message.password'),['class'=>'required']) !!}
                        <!-- {!! Form::password('password',null,['class' => 'form-control']) !!} -->
                        <input type= "password" name="password" class="form-control">

                    </div>



                </div>



                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('client_id',Lang::get('message.client_id'),['class'=>'required']) !!}
                        {!! Form::text('client_id',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('client_secret') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('client_secret',Lang::get('message.client_secret'),['class'=>'required']) !!}
                        {!! Form::text('client_secret',null,['class' => 'form-control']) !!}

                    </div>

                </div>



            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}
@stop