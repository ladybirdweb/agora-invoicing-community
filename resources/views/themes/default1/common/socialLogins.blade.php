@extends('themes.default1.layouts.master')

@section('title')
    Social Logins
@stop

@section('content-header')
    <div class="col-sm-6">
        <h1>Social Logins</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">Social Logins</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')
<style>
    a.btn:hover {
        -webkit-transform: scale(0.8);
        -moz-transform: scale(0.8);
        -o-transform: scale(0.8);
    }

    a.btn {
        -webkit-transform: scale(0.7);
        -moz-transform: scale(0.7);
        -o-transform: scale(0.7);
        -webkit-transition-duration: 0.5s;
        -moz-transition-duration: 0.5s;
        -o-transition-duration: 0.15s;
    }
</style>

<div id="response">
    <div class="card card-secondary card-outline">
        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div id="Localized-license-table_wrapper" class="dataTables_wrapper no-footer">
                        <div id="Localized-license-table_processing" class="dataTables_processing" style="display: none;">
                            <div class="overlay">
                                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                <div class="text-bold pt-2">Loading...</div>
                            </div>
                        </div>
                        <table id="social-table" class="table display dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="third-party-app-table_info">
                            <thead>
                                <tr>
                                    <th>Provider</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($socialLoginss as $key => $item)
                                <tr>
                                    <td>{{$item->type}}</td>
                                    <td>
                                        @if($item->status == 1)
                                        Active
                                        @else
                                        Deactive
                                        @endif
                                    </td>
                                    <td>
                                        <a href="edit/SocialLogins/{{$item->id}}" class="btn btn-primary a-btn-slide-text">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            <span><strong>Edit</strong></span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#social-table').DataTable({
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
    });
</script>

<script>
    $('ul.nav-sidebar a').filter(function() {
        return this.id == 'setting';
    }).addClass('active');

    $('ul.nav-treeview a').filter(function() {
        return this.id == 'setting';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop
