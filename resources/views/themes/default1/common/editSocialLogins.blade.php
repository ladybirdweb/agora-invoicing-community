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


<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">Api Keys</h3>
            </div>

            <div class="card-body table-responsive">
                
                <form method="POST" action="{{ url('update-social-login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="id" class="form-label">Client Id</label>
                        <input type="text" class="form-control" id="id" value="{{old('title', $socialLogins->client_id)}}" name="client_id">
                    </div>
                    <input type="hidden" name="type" value="{{old('title', $socialLogins->type)}}">
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Client Secret</label>
                        <input type="text" class="form-control" id="pwd" value="{{old('title', $socialLogins->client_secret)}}" name="client_secret">
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Redirect URL</label>
                        <input type="password" class="form-control" id="pwd" value="{{old('title', $socialLogins->redirect_url)}}" name="redirect_url">
                    </div>


                    <label for="email" class="form-label">Activate Login via Google</label>

                    <div class="form-check" name="status">
                        <input type="radio" class="form-check-input" id="radio1" name="optradio" value="1" checked>Yes
                        <label class="form-check-label" for="radio1"></label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio2" name="optradio" value="0">No
                        <label class="form-check-label" for="radio2"></label>
                    </div>



                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-sync-alt"></i> &nbspUpdate
                    </button>
                </form>
            </div>
        </div>
    </div>
 </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    @stop