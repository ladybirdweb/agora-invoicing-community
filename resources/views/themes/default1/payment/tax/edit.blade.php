@extends('themes.default1.layouts.master')
@section('title')
Edit Tax
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Edit Tax Class</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item"><a href="{{url('tax')}}"><i class="fa fa-dashboard"></i> Tax</a></li>
            <li class="breadcrumb-item active">Edit Tax</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')
<div class="card card-primary card-outline">



        {!! Form::model($tax,['url'=>'tax/'.$tax->id,'method'=>'patch']) !!}



    <div class="card-body table-responsive">

        <div class="row">

            <div class="col-md-12">

                
                

                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control']) !!}

                    </div>
                    <?php
                    $defaultValue = \App\Model\Payment\TaxClass::pluck('name','id')->toArray();
                    ?>
                    <div class="col-md-4 form-group {{ $errors->has('tax_class') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('tax_class',Lang::get('Tax Type'),['class'=>'required']) !!}
                         <select name="tax_classes_id"  id="editTax"  class="form-control">
                            <option value="">Choose</option>
                         @foreach($defaultValue as $key=>$value)
                             <option value="{{$value}}" <?php  if(in_array($value, $taxClassName) ) { echo "selected";} ?>>{{$value}}</option>
                           
                             @endforeach
                              </select>


                    </div>
                   
                    <div class="col-md-4 form-group">
                        <!-- name -->
                        {!! Form::label('status',Lang::get('message.status')) !!}
                        <div class="row">
                            <div class="col-md-6 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                                <!-- name -->
                                {!! Form::label('active',Lang::get('message.active')) !!}
                                {!! Form::radio('active',1) !!}

                            </div>
                            <div class="col-md-6 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                                <!-- name -->
                                {!! Form::label('active',Lang::get('message.inactive')) !!}
                                {!! Form::radio('active',0) !!}

                            </div>
                        </div>

                    </div>


                </div>

                <div class="row">
                      
                    <div class="col-md-4 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                        
                   <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>
                  
                        <!-- name -->
                        {!! Form::label('country',Lang::get('message.country')) !!}
                     {!! Form::select('country',[''=>'Any Country','Countries'=>$countries],null,['class' => 'form-control country hidden']) !!}

                       @if($tax['country']=='IN')
                         <input type='text' name="country1" id= "country2" class="form-control country1" value="IN" disabled>
                         @else
                        
                        {!! Form::select('country',[''=>'Any Country','Countries'=>$countries],null,['class' => 'form-control','id'=>'country']) !!}
                        @endif
                       <input type='text' name="country1" id= "country1" class="form-control country1 hide" value="IN" disabled>
                      </div>
                  
                    <div class="col-md-4 form-group changegststate">
                        <!-- name -->
                        {!! Form::label('state',Lang::get('message.state')) !!}
                         {!! Form::select('state',['Any State'=>'Any State','state'=>$states],null,['class' => 'form-control','id'=>'state-list']) !!}
                      

                    </div>
            
                    
                    <div class="col-md-4 form-group changegstrate">
                        <!-- name -->
                        {!! Form::label('rate',Lang::get('message.rate').' (%)',['class'=>'required']) !!}
                        {!! Form::text('rate',null,['class' => 'form-control']) !!}

                    </div>
               


                </div>
                <button type="submit" id="submit" class="btn btn-primary pull-right" data-loading-text="<i class='fa fa-refresh fa-spin fa-1x fa-fw'>&nbsp;</i> updating..."><i class="fa fa-sync-alt">&nbsp;&nbsp;</i>{!!Lang::get('Update')!!}</button>


            </div>

        </div>

    </div>
   
     <div class="card-body changegst" >
             
             <div class="row">
            
            <div class="col-md-12">
               
                 <table id="tax-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                    
                    <thead><tr>
                            <th>Id</th>
                            <th>State</th>
                            <th>CGST</th>
                            <th>SGST</th>
                            <th>IGST</th>
                            <th>UTGST</th>
                        </tr></thead>

                   </table>
            </div>
        </div>

    </div>
    
<input type="hidden" value="{{$tax['country']}}" id="hiddenvalue">
</div>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#tax-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: true,
             ajax: {
              "url":  '{!! route('get-taxtable') !!}',
                 error: function(xhr) {
                 if(xhr.status == 401) {
                  alert('Your session has expired. Please login again to continue.')
                  window.location.href = '/login';
                 }
              }

              },
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": ' <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>'
            },
            columnDefs: [
                { 
                    targets: 'no-sort', 
                    orderable: false,
                    order: []
                }
            ],
            columns: [
             {data: 'id', name: 'id'},
                {data: 'state', name: 'state'},
                 {data: 'c_gst', name: 'c_gst'},
                {data: 's_gst', name: 's_gst'},
                {data: 'i_gst', name: 'i_gst'},
                {data: 'ut_gst', name: 'ut_gst'}
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>
<script>
    $(document).on("change", "#country", function () {
                var val= $(this).val();
          
        $.ajax({
            type: "GET",
            url: "{{url('get-state')}}/" + val,
            success: function (data) {
               $("#state-list").html(data);
                
            }
        });
    }); 

     $(document).ready(function(){
         var inicial=$('#hiddenvalue').val();
         var taxValue=$('#editTax').val();
         if (inicial != 'IN' || taxValue == 0)
      {
         $(document).find('.changegst').hide();
         // $(document).find('#country').addClass('hide');
         // $(document).find('#countryinvisible').removeClass('hide');
        
         
      }
if (inicial == 'IN'){
$(document).find('.changegststate').hide();
        $(document).find('.changegstrate').hide();

}
         $(document).ready(function(){
             if($('#editTax').val() != 'Others') {
                 $(document).find('.changegst').show();
                 $(document).find('.changegststate').hide();
                 $(document).find('.changegstrate').hide();
                 $(document).find('#country').hide();
                 $(document).find('#country1').hide();
                 $(document).find('#country2').hide();
                 $(document).find('.country').show();
             } else {
                 $(document).find('.changegst').hide();
                 $(document).find('.country').show();
                 $(document).find('.changegststate').show();
                 $(document).find('.changegstrate').show();
                 $('.country1').hide();
                 $(document).find('.country1').hide();
             }
         });


  $('#editTax').on('change', function() {
        var val= $(this).val();
     if (val == 'Others')
      {
         $(document).find('.changegst').hide();
        $(document).find('.country').show();
        $(document).find('.changegststate').show();
        $(document).find('.changegstrate').show();
        $('.country1').hide();
        $(document).find('.country1').hide();
         // $(document).find('#countryinvisible').removeClass('hide');

         
      }
      else {
             $(document).find('.changegst').show();
             $(document).find('.changegststate').hide();
             $(document).find('.changegstrate').hide();
             $(document).find('#country').hide();
             $(document).find('#country1').hide();
             $(document).find('#country2').hide();
             $(document).find('.country').show();
           }

    });
});





</script>

{!! Form::close() !!}
@stop


