@extends('themes.default1.layouts.master')
@section('title')
    Localized License
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Faveo Product Upgrade Settings</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}">Settings</a></li>
            <li class="breadcrumb-item active">Faveo Product Upgrade Settings</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')
    <div id="response"></div>
    <div class="card card-secondary card-outline">
        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div id="Plan-upgrade-table_wrapper" class="dataTables_wrapper no-footer">
                        <div id="Plan-upgrade-table_processing" class="dataTables_processing" style="display: none;">
                            <div class="overlay">
                                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                <div class="text-bold pt-2">Loading...</div>
                            </div>
                        </div>
                        <table id="Plan-upgrade-table" class="table display dataTable no-footer" cellspacing="0" width="100%" styleclass="borderless" role="grid" aria-describedby="third-party-app-table_info" style="width: 100%;">
                            <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="Plan-upgrade-table" rowspan="1" colspan="1" aria-label="App name: activate to sort column ascending" style="width: 98px;">Product</th>
                                <th class="sorting" tabindex="0" aria-controls="Plan-upgrade-table" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 46px;">Upgrade Or Downgrade to</th></tr>
                            </thead>
                            <tbody>
                            @foreach($files_array = App\Model\Product\Product::where('hidden',0)
                                                                             ->where('category','!=','')
                                                                             ->where('category','!=','Service')
                                                                             ->where('name','NOT LIKE','%Cloud%')->cursor() as $files)
                            <tr role="row" class="odd">
                                <td>{{$files->name}}</td>
                            <td>
                                <div class="row">
                                    <div class="col-10">
                                       <ul>
                                           @foreach($prods= App\Model\Product\UpgradeSettings::where('product_id',$files->id)->cursor() as $prod)
                                           @foreach($ups = App\Model\Product\Product::where('id',$prod->upgrade_product_id)->cursor() as $up)
                                           <li>{{$up->name}}</li>
                                           @endforeach
                                           @endforeach
                                       </ul>
                                    </div>
                                    <div>
                                <a href="{{url('UpgradePlanSettings/edit/'.$files->id)}}">
                                    <button class="btn btn-secondary btn-sm ml-3" data-toggle="tooltip" title="Edit" data-placement="top"><i class="fas fa-edit"></i></button>
                                </a>
                                    </div>
                                </div>
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
    <script>
        $(document).ready(function() {
            $('#Plan-upgrade-table').DataTable( {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            } );
        } );
    </script>
    <script type="text/javascript">
        "fnDrawCallback": function( oSettings ) {
            $(function () {
                $('[data-toggle="tooltip"]').tooltip({
                    container : 'body'
                });
            });
            $('.loader').css('display', 'none');
        }
        "fnPreDrawCallback": function(oSettings, json) {
            $('.loader').css('display', 'block');
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@stop