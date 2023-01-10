@extends('themes.default1.layouts.master')
@section('title')
    Localized License
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Faveo Product Upgrade Edit</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{url('UpgradePlanSettings')}}">Faveo Product Upgrade Settings</a></li>
            <li class="breadcrumb-item active">Faveo Product Upgrade Edit</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')
    <div class="card card-secondary card-outline">
        <div class="card-body table-responsive">
            <form action={!! url('/UpgradeSettingSave') !!} method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="oldProduct" value="{!! \Illuminate\Support\Facades\Request::segment(3) !!}">
                @foreach($files_array = App\Model\Product\Product::where('hidden',0) ->where('category','!=','')
                                                                                           ->where('category','!=','Service')
                                                                                           ->where('name','NOT LIKE','%Cloud%')
                                                                                           ->where('id', '!=', \Illuminate\Support\Facades\Request::segment(3))
                                                                                           ->get() as $files)
                <input type="checkbox" name="upgradeOrDowngrade[]" value="{!! $files->id !!}"
                       @if(count(App\Model\Product\UpgradeSettings::where('product_id',\Illuminate\Support\Facades\Request::segment(3))
                                                                                           ->where('upgrade_product_id',$files->id)->get())) checked @endif>
                    <strong>{!! $files->name !!}</strong>
                <br>
                    <br>
                @endforeach
                <br>
                <input class="btn btn-primary" type="submit" name="submit" value="Submit">

            </form>

        </div>
    </div>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $('ul.nav-sidebar a').filter(function() {
            return this.id == 'setting';
        }).addClass('active');
        // for treeview
        $('ul.nav-treeview a').filter(function() {
            return this.id == 'setting';
        }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
    </script>
    <script type="text/javascript">
        function upgradeAdd(value){
          console.log(value);
        }
    </script>
    <style>
      .btn{
          margin-left: 94%;
      }
    </style>
@stop