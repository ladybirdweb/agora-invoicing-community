@extends('themes.default1.layouts.master')
@section('title')
    Announcement
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Announcement</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">Announcement</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
    @include('themes.default1.common.message-create')
    <div id="response"></div>
    <div class="card card-secondary card-outline">
        <div class="card-body table-responsive">

            <div class="row">
                <div class="col-sm-10"><h4>Announcement Activity</h4></div>
                <div class="col-sm-2">
                    <div class="card-tools card-button">
                        <a href="#create-an-announcement" data-toggle="modal" data-target="#create-an-announcement" class="btn btn-default btn-sm"><span class="fa fa-plus"></span></a>

                    </div>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-md-12 col-lg-12">

                    <div id="Localized-license-table_wrapper" class="dataTables_wrapper no-footer">
                        <div id="Localized-license-table_processing" class="dataTables_processing" style="display: none;">
                            <div class="overlay">
                                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                <div class="text-bold pt-2">Loading...</div>
                            </div>
                        </div>
                        <?php $announcement = \App\Model\Common\Announcement::orderBy('updated_at','desc')->get() ?>
                        @foreach($announcement as $announce)
                        <div class="card crdie">
                            <h5 class="card-header">Announcements made by {!! $announce->user !!}!</h5>
                            <div class="card-body">
                                <h5 class="card-title">Message: {!!  $announce->message !!}</h5>
                                @if(!empty($announce->version))
                                    <p class="card-text"> <b>Version:</b> {{$announce->version}} </p>
                                @endif
                                @if(!empty($announce->products))
                                    <p class="card-text"> <b>Product:</b> {{$announce->products}} </p>
                                @endif
                                @if(!empty($announce->from))
                                    <p class="card-text"> <b>From:</b> {{$announce->from}} </p>
                                @endif
                                @if(!empty($announce->till))
                                    <p class="card-text"> <b>Till:</b> {{$announce->till}} </p>
                                @endif
                                @if($announce->is_closeable)
                                    <p class="card-text"> <b>Is the Message closeable?</b> Yes!</p>
                                @endif
                                @if(!$announce->is_closeable)
                                    <p class="card-text"> <b>Is the Message closeable?</b> No!</p>
                                    <p class="card-test"><b>Condition:</b> {!! \App\Model\Common\AnnouncementConditions::where('name',$announce->condition)->value('condition') !!}</p>
                                @endif
                                @if(!empty($announce->reappear))
                                    <p class="card-text"> <b>This message will reappear again after</b> {!! $announce->reappear !!}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        $('ul.nav-sidebar a').filter(function() {
            return this.id == 'setting';
        }).addClass('active');
        // for treeview
        $('ul.nav-treeview a').filter(function() {
            return this.id == 'setting';
        }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
    </script>
    <style>
        .card-button{
            margin-left: 150px;
            margin-bottom: 10px;
        }
        .card-title{
            margin-bottom: 30px;
        }
        .crdie{
            background-color: lightgrey;
            box-shadow: black;
        }
    </style>

@endsection
