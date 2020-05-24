@extends('themes.default1.layouts.master')
@section('title')
Tax
@stop
@section('content-header')
<h1>
Create Tax Options And Tax Classes
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
         <li class="active">Tax</li>
      </ol>
@stop
@section('content')

    <style>


.btn-default.btn-on-1.active{background-color: #006FFC;color: white;}

.btn-default.btn-off-1.active{background-color: #DA4F49;color: white;}
.btn-default.btn-on-2.active{background-color: #006FFC;color: white;}

.btn-default.btn-off-2.active{background-color: #DA4F49;color: white;}
.btn-default.btn-on-3.active{background-color: #006FFC;color: white;}

.btn-default.btn-off-3.active{background-color: #DA4F49;color: white;}

/* Rounded sliders */

</style>
<head>
  <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <!-- <script src="{{asset('bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script> -->
  </head>
  

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
               <i class="fa fa-check"></i>
                <b>{{Lang::get('message.success')}}!</b> 
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


            
                <!--<a href="{{url('currency/create')}}" class="btn btn-primary pull-right   ">{{Lang::get('message.create')}}</a>-->
                <!--<a href="#create" class="btn btn-primary pull-right" data-toggle="modal" data-target="#create">{{Lang::get('message.create')}}</a>-->
                
               
            </h4>
            @include('themes.default1.payment.tax.create-tax-option')

        </div>

      
       <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                   <!--  <div class="header-body">
                        <h4>Options
                            {!! Form::model($options,['url'=>'taxes/option','method'=>'patch']) !!}
                        </h4>
                    </div> -->

                    <table class="table table-responsive">
                    
                          <h4>{{Lang::get('Options')}}  {!! Form::model($options,['url'=>'taxes/option','method'=>'patch']) !!}<button type="submit" class="btn btn-primary pull-right" id="submit" style="margin-top:-30px;"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>
                           
                       
                       
                            <td>
                                {!! Form::label('tax_enable',Lang::get('message.tax-enable')) !!}
                            </td>
                            <td>
                                <label class="switch">
                                     <!-- {!! Form::hidden('tax_enable',0) !!} -->
                                 <!-- {!! Form::checkbox('tax_enable',1) !!} -->
                                 <!-- <input id="toggle-event" type="checkbox" data-toggle="toggle" name="tax_enable"> -->
                                <div class="btn-group"  data-toggle="buttons" >
                                    <label class="btn btn-default btn-on-1 btn-sm ">
                                    <input type="radio" id="chkYes" value="1" name="tax_enable" onchange="getTaxValue(this)">ENABLED</label>
                                    <label class="btn btn-default btn-off-1 btn-sm  ">
                                    <input type="radio" id="chkNo" value="0" name="tax_enable" onchange="getTaxValue(this)">DISABLED</label>
                                    <span class="slider"></span>
                                  </div>
                                    
                              </label>
                               
                            </td>
                        

                        <tr class="form-group gstshow hide">
                              
                                 <td>
                                    {!! Form::label('GSTIN',Lang::get('GSTIN')) !!}
                                </td>

                                 <td>
                                     <input type='text' name="Gst_no"  class="form-control col-md-6" value="{{$gstNo->Gst_No}}" style="width:140px">
                                 </td>
                          
                        </tr>

                        <tr>
                            <td>
                                {!! Form::label('inclusive',Lang::get('message.prices-entered-with-tax')) !!}
                            </td>
                            <td>
                           
                                      <div class="btn-group"  data-toggle="buttons">
                                    <label class="btn btn-default btn-on-2 btn-sm ">
                                    <input type="radio" id="chkYes" value="1" name="inclusive">INCLUSIVE</label>
                                    <label class="btn btn-default btn-off-2 btn-sm">
                                    <input type="radio" id="chkNo" value="0" name="inclusive">EXCLUSIVE</label>
                                    <span class="slider"></span>
                                  </div>
                            </td>
                        </tr>
                       
                       
                        <tr>
                            <td>
                                {!! Form::label('rounding',Lang::get('message.rounding')) !!}
                            </td>
                            <td>
                                    <div class="btn-group"  data-toggle="buttons">
                                    <label class="btn btn-default btn-on-3 btn-sm ">
                                    <input type="radio" id="chkYes" value="1" name="rounding">ENABLED</label>
                                    <label class="btn btn-default btn-off-3 btn-sm">
                                    <input type="radio" id="chkNo" value="0" name="rounding">DISABLED</label>
                                    <span class="slider"></span>
                                  </div>                            </td>
                        </tr>
                        
                    </table>

                    {!! Form::close() !!}

                   

              </div>
                </div>
              </div>
            </div>

             <div class="box box-primary">
              <div class="box-body">
               <h4>{{Lang::get('Tax Classes')}}</h4>
                <div class="col-md-12">
                   <a href="#create-tax-option" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#create-tax-option"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;{{Lang::get('message.create')}}</a>
                    <table id="tax-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                    <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash">&nbsp;&nbsp;</i> Delete Selected</button><br /><br />
                        <thead><tr>
                            <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                             <th>Tax Type</th>
                              <th>Name</th>
                               <th>Country</th>
                              <th>State</th>
                               <th>Rate (%)</th>
                               <th>Action</th>
                            </tr></thead>
                         </table>
                  

                </div>
            </div>


    </div>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
      var btn = {{($options->tax_enable)}};
     if(btn== '1'){
$('.btn-on-1').addClass('active');
$('.gstshow').removeClass("hide");

     }
     else{
$('.btn-off-1').addClass('active');
// $('.gstshow').addClass('hide');
// $('.gstshow').removeAttribute("style");
     }
    var btn1 = {{($options->inclusive)}};
     if(btn1== '1'){
$('.btn-on-2').addClass('active');
// $('.gstshow').removeClass("hide");

     }
     else{
$('.btn-off-2').addClass('active');
// $('.gstshow').addClass('hide');
// $('.gstshow').removeAttribute("style");
     }
       var btn2 = {{($options->rounding)}};
     if(btn2== '1'){
$('.btn-on-3').addClass('active');
// $('.gstshow').removeClass("hide");

     }
     else{
$('.btn-off-3').addClass('active');
// $('.gstshow').addClass('hide');
// $('.gstshow').removeAttribute("style");
     }


   function getTaxValue(x){
      console.log($(x))
        if($(x).val()==1){
              $('.gstshow').removeClass("hide");
        }
        else{
               $('.gstshow').addClass("hide");
        }
   }
     // $('#chkYes').click


            $('#tax-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('get-tax') !!}',
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
                    {data: 'tax_classes_id', name: 'tax_classes_id'},
                    {data: 'name', name: 'name'},
                    {data: 'country', name: 'country'},
                    {data: 'state', name: 'state'},
                    {data: 'rate', name: 'rate'},
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
              $('#tax-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
         }
         

         $(document).on('click','#bulk_delete',function(){
          var id=[];
          if (confirm("Are you sure you want to delete this?"))
            {
                $('.tax_checkbox:checked').each(function(){
                  id.push($(this).val())
                });
                if(id.length >0)
                {
                   $.ajax({
                          url:"{!! route('tax-delete') !!}",
                          method:"get",
                          data: $('#check:checked').serialize(),
                          beforeSend: function () {
                    $('#gif').show();
                    },
                    success: function (data) {
                    $('#gif').show();
                    $('#response').html(data);
                    location.reload();
                    }
                   })
                }
                else
                {
                    alert("Please select at least one checkbox");
                }
            }  

         });



       
    </script>
   
    @stop
   