@extends('themes.default1.layouts.master')
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="box">

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
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {!!Session::get('success')!!}
        </div>
        @endif
        <!-- failure message -->
        @if(Session::has('fails'))
        <div class="alert alert-danger alert-dismissable">
            <i class="fa fa-ban"></i>
            <b>Alert!</b> Failed.
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {!!Session::get('fails')!!}
        </div>
        @endif
        <h3>Plugins  
            <button type="button" class="btn btn-default" id="Edit_Ticket" data-toggle="modal" data-target="#Edit"><b>Add New</b></button>
        </h3>
        <div class="modal fade" id="Edit">
            <div class="modal-dialog">
                <div class="modal-content">  
                    <div class="modal-header">
                        <h4 class="modal-title">Add Plugin</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['url'=>'post-plugin','files'=>true]) !!}
                        <table>
                            <tr>
                                <td>Plugin :</td>
                                <td><input type="file" name="plugin" class="btn btn-file"></td>

                            </tr>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" id="dismis">{!! Lang::get('lang.close') !!}</button>
                            <input type="submit" class="btn btn-primary pull-right" value="Upload">
                        </div>
                        {!! Form::close() !!}

                    </div><!-- /.modal-content -->
                </div>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>

    <div class="box-body">

        


        <div class="row">
            <div class="col-md-12">
                
                {!! Datatable::table()
                ->addColumn('Name','Description','Author','Website','Version')       // these are the column headings to be shown
                ->setUrl('getplugin')   // this is the route where data will be retrieved
                ->render() !!}
            </div>
        </div>
    </div>
</div>

        </div>
        <!-- /.box -->

    </div>


</div>

@stop