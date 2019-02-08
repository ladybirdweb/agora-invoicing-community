@extends('themes.default1.layouts.master')
@section('title')
License Permission
@stop
@section('content-header')
<h1>
License Permissions
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">License Permissions</li>
      </ol>
@stop
@section('content')
    <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
    <div class="box box-primary">

    <div class="box-header">
        @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
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
        <div id="response"></div>
        <h4>{{Lang::get('message.permissions')}}
          </h4>
    </div>
       <div class="box-body">
             
             

                 <div class="alert  alert-dismissable" style="background: #F3F3F3">
            <div class="row">
              {!! Form::open(['url'=>'file-storage-path','method'=>'post']) !!}
            <div class="col-md-2 copy-command1">
                    <span style="font-size: 15px">Set File Storage Path</span>
                </div>
                <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
                <div class="col-md-4">
                  <input type="text" class="form-control input-sm" style=" padding:5px;height:34px" name="fileuploadpath" id="fileuploadpath" value="{{$fileStorage}}" placeholder="{{Lang::get('message.specify-php-executable')}}">
                </div>
                  
                 {!! Form::close() !!}  
            </div>
        </div>

    </div>

</div>




@stop


