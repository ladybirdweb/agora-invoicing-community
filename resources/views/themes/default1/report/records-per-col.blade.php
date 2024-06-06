`@extends('themes.default1.layouts.master')
@section('title')
Report settings
@stop
@section('content-header')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <div class="col-sm-6">
        <h1>Report settings</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">Report settings</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title">Settings</h3>
        </div>
          {!! Form::open(['url' => 'add_records', 'method' => 'post']) !!}
        <div class="card-body table-responsive">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('records', Lang::get('Records per export'), ['class' => 'required']) !!}
                         <i class="fas fa-question-circle" data-toggle="tooltip" title="add a records per sheer and the limit maxiumn is 3000."></i>
                          
                        {!! Form::text('records', $settings->records ?? null, ['class' => 'form-control']) !!}

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> {!! Lang::get('message.save') !!}
                    </button>
                </div>
            </div>
        </div>
          {!! Form::close() !!}
    </div>




<script>
      $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});


     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'add_col';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'add_col';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>
@stop


