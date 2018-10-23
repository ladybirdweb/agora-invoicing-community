@extends('themes.default1.layouts.master')
@section('title')
Github Setting
@stop
@section('content-header')
<h1>
{!! Lang::get('message.cron-setting') !!}
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li class="active">{!! Lang::get('message.cron-setting') !!}</li>
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
      

    </div>
<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
  
               
                    @include('themes.default1.common.cron.cron-new')
               
                <!-- /.tab-pane -->
            
         
        <!-- nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>

</div>


{!! Form::close() !!}
@stop