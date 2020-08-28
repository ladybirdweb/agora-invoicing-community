@extends('themes.default1.layouts.master')
@section('title')
File Storage
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>File Storage</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">File Storage</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
    <div class="card card-primary card-outline">

    <div class="card-header">

        <div id="response"></div>
        <h5>Set file storage path
          </h5>
    </div>
       <div class="card-body">
             
             

                 <div class="alert  alert-dismissable" style="background: #F3F3F3">
            <div class="row">
                <div class="col-md-12">
              {!! Form::open(['url'=>'file-storage-path','method'=>'post']) !!}

                    <input type="text" class="form-control input-sm"  name="fileuploadpath" id="fileuploadpath" value="{{$fileStorage}}">

                </div>
            </div>
            </div>
                     <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-save">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
                     <div class="col-md-4">
                 {!! Form::close() !!}  
            </div>
        </div>

    </div>

</div>




@stop


