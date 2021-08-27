@extends('themes.default1.layouts.master')
@section('title')
Api Key
@stop
@section('content-header')
<div class="col-sm-6 md-6">
    <h1>Localized License</h1>
</div>
<div class="col-sm-6 md-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{url('settings')}}"> Settings</a></li>
        <li class="breadcrumb-item active">Api Key</li>
    </ol>
</div><!-- /.col -->

<div class="card-body table-responsive">
             
            <div class="row">
            
            <div class="col-md-12">
               
                 <button value="" class="btn btn-secondary btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Selected</button>
                 <br>
                 <br>
                 <div id="third-party-app-table_wrapper" class="dataTables_wrapper no-footer">
                 	<div class="dataTables_length" id="third-party-app-table_length">
                 		<label>
                 			<select name="third-party-app-table_length" aria-controls="third-party-app-table" class="">
                 				<option value="10">10</option>
                 				<option value="25">25</option>
                 				<option value="50">50</option>
                 				<option value="100">100</option>
                 			</select> 
                 		Records per page</label>
                 	</div>
                 	<div id="third-party-app-table_filter" class="dataTables_filter">
                 		<label>Search: 
                 			<input type="search" class="" placeholder="" aria-controls="third-party-app-table">
                 		</label>
                 	</div>
                 	<div id="third-party-app-table_processing" class="dataTables_processing" style="display: none;"> 
                 		<div class="overlay">
                 			<i class="fas fa-3x fa-sync-alt fa-spin"></i>
                 			<div class="text-bold pt-2">Loading...</div>
                 		</div>
                 	</div>
                 	<table id="third-party-app-table" class="table display dataTable no-footer" cellspacing="0" width="100%" styleclass="borderless" role="grid" aria-describedby="third-party-app-table_info" style="width: 100%;">
                     
                    <thead>
                    	<tr role="row">
                    		<th class="no-sort sorting_desc" style="width: 13px;" rowspan="1" colspan="1" aria-label="">
                    			<input type="checkbox" name="select_all" onchange="checking(this)"></th><th class="sorting" tabindex="0" aria-controls="third-party-app-table" rowspan="1" colspan="1" aria-label="App name: activate to sort column ascending" style="width: 98px;">File name</th>
                    			<th class="sorting" tabindex="0" aria-controls="third-party-app-table" rowspan="1" colspan="1" aria-label="App key: activate to sort column ascending" style="width: 232px;">Public key</th>
                    			<th class="sorting" tabindex="0" aria-controls="third-party-app-table" rowspan="1" colspan="1" aria-label="App secret: activate to sort column ascending" style="width: 77px;">Private Key</th>
                    			<th class="sorting" tabindex="0" aria-controls="third-party-app-table" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 46px;">Action</th></tr></thead>

                   <tbody>
                   	<tr role="row" class="odd">
                   		<td class="sorting_1">
                   			<input type="checkbox" class="type_checkbox" value="2" name="select[]" id="check">
                   		</td> 
                   		<td></td>
                   		<td><p><button data-toggle="modal" data-id="2" data-appname="faveo_app_key" .="" data-appkey="sgBX3dmgjsiRPCsy4qtQcMy1F2r8xqfl" data-secret="FAVEOSECRET" class="btn btn-sm btn-secondary btn-xs editThirdPartyApp" <label="" style="font-weight:500;" data-placement="top" title="Edit">
             <i class="fa fa-edit" style="color:white;"> 
             </i></button>&nbsp;</p>
         </td>
     </tr>
     </tbody>
 </table>
 <div class="dataTables_info" id="third-party-app-table_info" role="status" aria-live="polite">Showing 1 to 2 of 2 entries</div><div class="dataTables_paginate paging_simple_numbers" id="third-party-app-table_paginate"><a class="paginate_button previous disabled" aria-controls="third-party-app-table" data-dt-idx="0" tabindex="-1" id="third-party-app-table_previous">Previous</a>
 	<span><a class="paginate_button current" aria-controls="third-party-app-table" data-dt-idx="1" tabindex="0">1</a></span><a class="paginate_button next disabled" aria-controls="third-party-app-table" data-dt-idx="2" tabindex="-1" id="third-party-app-table_next">Next</a></div></div>
            </div>
        </div>

    </div>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
@stop
@section('content')


  
