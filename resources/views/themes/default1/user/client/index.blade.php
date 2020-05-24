@extends('themes.default1.layouts.master')
@section('title')
Users
@stop
@section('content')
@section('content-header')
<h1>
All Users
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Users</li>
      </ol>
@stop

<style>

.caret {
    border-top: 6px dashed;
    border-right: 3px solid transparent;
    border-left: 3px solid transparent;
}
.bootstrap-select>.dropdown-toggle {
    background-color: white;
}
.bootstrap-select.btn-group .dropdown-toggle .filter-option {
    color:#555;
}
.caret {
    border-top: 6px dashed;
    border-right: 3px solid transparent;
    border-left: 3px solid transparent;
}
</style>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Search</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {!! Form::open(['method'=>'get']) !!}

        <div class="row">

            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('company','Company Name') !!}
                {!! Form::text('company',$request->company,['class' => 'form-control','id'=>'company']) !!}

            </div>
                         <?php
            $countries=DB::table('countries')->pluck('nicename','country_code_char2')->toarray();
            ?>
            <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('country','Country') !!}
                 <select name="country" value= "Choose" onChange="getCountryAttr(this.value)" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-dropup-auto="false" data-size="10">
                 <option value="" style="">Choose</option>
                      @foreach($countries as $key=> $country)
                         @if($key == $request->country)
                            <option value={{$key}} selected>{{$country}}</option>
                         @else
                             <option value={{$key}}>{{$country}}</option>
                         @endif
                      @endforeach
                  </select>
            </div>

          <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('industry','Industries') !!}

<?php $old = ['agriculture_forestry'=>'Agriculture Forestry','safety_security_legal'=>'Safety Security Legal','business_information'=>'Business Information','finance_insurance'=>'Finance Insurance','gaming'=>'Gaming','real_estate_housing'=>'Real Estate Housing','health_services'=>'Health Services','education'=>'Education','food_hospitality'=>'Food Hospitality','personal_services'=>'Personal Services','transportation'=>'Transportation','construction_utilities_contracting'=>'Construction Utilities Contracting','motor_vehicle'=>'Motor Vehicle','animals_pets'=>'Animals & Pets','art_design'=>'Art & Design','auto_transport'=>'Auto & Transport','food_beverage'=>'Food & Beverage','beauty_fashion'=>'Beauty & Fashion','education_childcare'=>'Education & Childcare','environment_green_tech'=>'Environment & Green Tech','events_weddings'=>'Events & Weddings','finance_legal_consulting'=>'Finance, Legal & Consulting','government_municipal'=>'Government & Municipal','home_garden'=>'Home & Garden','internet_technology'=>'Internet & Technology','local_service_providers'=>'Local Service Providers','manufacturing_wholesale'=>'Manufacturing & Wholesale','marketing_advertising'=>'Marketing & Advertising','media_communication'=>'Media & Communication','medical_dental'=>'Medical & Dental','music_bands'=>'Music & Bands','non_profit_charity'=>'Non-Profit & Charity','real_estate'=>'Real Estate','religion'=>'Religion','retail_e-Commerce'=>'Retail & E-Commerce','sports_recreation'=>'Sports & Recreation','travel_hospitality'=>'Travel & Hospitality','other'=>'Other',]; 
 $bussinesses =DB::table('bussinesses')->pluck('name','short')->toarray();
 $acctManagers = DB::table('users')->where('position', 'account_manager')
            ->pluck('first_name', 'id')->toArray();

  $salesManagers = DB::table('users')->where('position', 'manager')
            ->pluck('first_name', 'id')->toArray();
?>
                <!-- {!! Form::select('industry',['Choose',''=>DB::table('bussinesses')->pluck('name','short')->toarray(),'old'=>$old],null,['class' => 'form-control','data-live-search'=>'true','data-live-search-placeholder'=>'Search','data-dropup-auto'=>'false','data-size'=>'10','id'=>'industry']) !!} -->

                 <select name="industry"  class="form-control selectpicker" data-live-search="true",data-live-search-placeholder="Search" data-dropup-auto="false"  data-size="10" id="industry">
                             <option value="">Choose</option>
                            @foreach($bussinesses as $key=>$bussines)
                                @if($key == $request->industry)
                                    <option value={{$key}} selected>{{$bussines}}</option>
                                @else
                                     <option value={{$key}}>{{$bussines}}</option>
                                @endif
                             @endforeach
                          </select>
             
            </div>

             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('Role','Role') !!}
                {!! Form::select('role',[null => 'Choose']+ ['admin'=>'Admin', 'user'=>'user'], $request->role, ['class' => 'form-control','id'=>'role']) !!}
             </div>
             
             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('Position','Position') !!}
                {!! Form::select('position',[null => 'Choose']+ ['manager'=>'Sales Manager', 'account_manager'=>'Account Manager'], $request->position, ['class' => 'form-control','id'=>'position']) !!}
             </div>

               <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('reg_from','Registered From') !!}
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" autocomplete="off" value="{!! $request->reg_from !!}" name="reg_from" class="form-control reg_from" id="datepicker">
                </div>

            </div>

              <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Registered Till') !!}
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" autocomplete="off" value="{!! $request->reg_till !!}" name="reg_till" class="form-control reg_till" id="datepicker1">
                </div>
            </div>

              <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Users for Account Manager') !!}
                  <select name="actmanager"  class="form-control selectpicker" data-live-search="true",data-live-search-placeholder="Search" data-dropup-auto="false"  data-size="10" id="actmanager" >
                             <option value="">Choose</option>
                           @foreach($acctManagers as $key=>$acct)
                                @if($key == $request->actmanager)
                                    <option value={{$key}} selected>{{$acct}}</option>
                                @else
                                    <option value={{$key}}>{{$acct}}</option>
                                @endif
                          @endforeach
                          </select>
            </div>

             <div class="col-md-3 form-group">
                <!-- first name -->
                {!! Form::label('from','Users for Sales Manager') !!}
                  <select name="salesmanager"  class="form-control selectpicker" data-live-search="true",data-live-search-placeholder="Search" data-dropup-auto="false"  data-size="10" id="salesmanager" >
                             <option value="">Choose</option>
                           @foreach($salesManagers as $key=>$sales)
                                @if($key == $request->salesmanager)
                                  <option value={{$key}} selected>{{$sales}}</option>
                                @else
                                  <option value={{$key}}>{{$sales}}</option>
                                @endif
                          @endforeach
                          </select>
            </div>

            
            

</div>
<div class='row'>

            
              
                <div class="col-md-6">
                    <button name="Search" type="submit"  class="btn btn-primary" data-loading-text="<i class='fa fa-search fa-spin fa-1x fa-fw'>&nbsp;</i> updating..."><i class="fa fa-search">&nbsp;</i>{!!Lang::get('Search')!!}</button>
                      &nbsp;
                    <a href="{!! url('clients') !!}" id="reset" class="btn btn-danger"><i class="fa fa-refresh">&nbsp;</i>{!!Lang::get('Reset')!!}</a>
                </div>
           
         
</div>
        {!! Form::close() !!}
    </div>
</div>


<div class="box box-primary">

    <div class="box-header">

        <h4>{{Lang::get('message.users')}}
            <a href="{{url('clients/create')}}" class="btn btn-primary btn-sm pull-right   "><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;{{Lang::get('message.create')}}</a></h4>
    </div>

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
        <i class="fa fa-ban"></i>
        <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
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

    <div class="box-body">
        <div class="row">

            <div class="col-md-12">
                <table id="user-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                 <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class= "fa fa-trash"></i>&nbsp;&nbsp;Delete Selected</button><br /><br />
                    <thead><tr>
                         <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Country</th>
                            <th>Registered on</th>
                             <th>Status</th>
                            <th>Action</th>
                        </tr></thead>
                     </table>
               

            </div>
        </div>

    </div>
<div id="gif"></div>
</div>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  
        $('#user-table').DataTable({
   
            processing: true,
            serverSide: true,
            stateSave: false,
            // if in request sort field is present, it will take that else default order
            // need to stringify the sort_order, else it will be considered as a javascript variable
            order: [[ {!! $request->sort_field ?: 5 !!}, {!! "'".$request->sort_order."'" ?: "'desc'" !!} ]],

            ajax: '{!! route('get-clients',"company=$request->company&country=$request->country&industry=$request->industry&role=$request->role&position=$request->position&reg_from=$request->reg_from&reg_till=$request->reg_till&actmanager=$request->actmanager&salesmanager=$request->salesmanager" ) !!}',

            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
                columnDefs: [
                { 
                    targets: 'no-sort', 
                    orderable: false,
                    order: []
                }
            ],
            columns: [
                {data: 'checkbox', name: 'checkbox'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'mobile', name: 'mobile'},
                {data: 'country', name: 'country'},
                {data: 'created_at', name: 'created_at'},
                {data: 'active', name: 'active'},
                {data: 'action', name: 'action'}
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>


@stop

@section('icheck')
<script>
   function checking(e){
          
          $('#user-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
     }
     

     $(document).on('click','#bulk_delete',function(){
      var id=[];
      if (confirm("Are you sure you want to delete this?"))
        {
            $('.user_checkbox:checked').each(function(){
              id.push($(this).val())
            });
            if(id.length >0)
            {
               $.ajax({
                      url:"{!! Url('clients-delete') !!}",
                      method:"get",
                      data: $('#check:checked').serialize(),
                      beforeSend: function () {
                $('#gif').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                },
                success: function (data) {
                $('#gif').html('');
                $('#response').html(data);
                 setTimeout(function(){
                    window.location.reload();
                },5000);
                }
               })
            }
            else
            {
                alert("Please select at least one checkbox");
            }
        }  

     });
      $('#datepicker').datepicker({
      autoclose: true
    })
        $('#datepicker1').datepicker({
      autoclose: true
    })
</script>
@stop