@extends('themes.default1.layouts.master')
@section('title')
System Managers
@stop
@section('content-header')
<style>
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
      background-color: #1b1818 !important;
  }
</style>
    <div class="col-sm-6">
        <h1>{{ __('message.system_manager') }}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{ __('message.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> {{ __('message.settings') }}</a></li>
            <li class="breadcrumb-item active">{{ __('message.system_manager') }}</li>
        </ol>
    </div><!-- /.col -->
     <link rel="stylesheet" href="{{asset('admin/css/select2.min.css')}}">
     <script src="{{asset('admin/plugins/select2.full.min.js')}}"></script>

@stop
@section('content')
<div class="card card-secondary card-outline">



         <?php
         $users = [];
         ?>

    <div class="card-body table-responsive">

        <div class="row">

            <div class="col-md-12">

            <div id="replaceMessage"></div>
            <div id="error"></div>
                <div class ="row">
                <div class="col-md-4 form-group">
                    {!! Form::label('user',Lang::get('message.system_account_manager'),['class'=>'required']) !!}
                       <select name="manager" value= "Choose" id="existingManager" class="form-control">
                             <option value="">{{ __('message.choose') }}</option>
                           @foreach($accountManagers as $key=>$manager)
                             <option value={{$key}}>{{$manager}}</option>
                          @endforeach
                          </select>
                 
                </div>
               
                <div class="col-md-4 form-group {{ $errors->has('account_manager') ? 'has-error' : '' }}">
                            <!-- first name -->
                            {!! Form::label('replace_with',Lang::get('message.replace_with'),['class'=>'required']) !!}
                        
                           
                            {!! Form::select('account_manager', [Lang::get('User')=>$users],null,['multiple'=>true,'class'=>"form-control select2" ,'id'=>"users",'required','style'=>"width:100%!important;",'oninvalid'=>"setCustomValidity('Please Select Client')", 
                  'onchange'=>"setCustomValidity('')"]) !!}
                       

                </div>

                  <div class="col-md-4 form-group {{ $errors->has('account_manager') ? 'has-error' : '' }}">
                    <br>
                 <button name="generate" type="submit" id="replace" class="btn btn-primary" style="margin-top: 4px;"><i class="fa fa-sync-alt">&nbsp;</i>{!!Lang::get('message.replace')!!}</button>
                 </div>
                </div>
               </div>


               <div class="col-md-12">
                 <div id="replaceMessage1"></div>
                  <div id="error1"></div>
                   <div class="row">
                   <div class="col-md-4 form-group">
                    {!! Form::label('user',Lang::get('message.system_sales_manager'),['class'=>'required']) !!}
                       <select name="sales_manager" value= "Choose" id="existingSalesManager" class="form-control">
                             <option value="">{{ __('message.choose') }}</option>
                           @foreach($salesManager as $key=>$manager)
                             <option value={{$key}}>{{$manager}}</option>
                          @endforeach
                          </select>
                 
                </div>

                <div class="col-md-4 form-group {{ $errors->has('sales_manager') ? 'has-error' : '' }}">
                            <!-- first name -->
                            {!! Form::label('replace_with',Lang::get('message.replace_with'),['class'=>'required']) !!}
                        
                           
                            {!! Form::select('sales_manager', [Lang::get('user')=>$users],null,['multiple'=>true,'class'=>"form-control select2" ,'id'=>"sales",'required','style'=>"width:100%!important",'oninvalid'=>"setCustomValidity('Please Select Client')",
                  'onchange'=>"setCustomValidity('')"]) !!}
                       

                </div>

                  <div class="col-md-4 form-group {{ $errors->has('sales_manager') ? 'has-error' : '' }}">
                    <br>
                 <button name="generate" type="submit" id="replaceSales" class="btn btn-primary" style="margin-top: 4px;"><i class="fa fa-sync-alt">&nbsp;&nbsp;</i>{!!Lang::get('message.replace')!!}</button>
                 </div>

                   </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>

<script>
        $('#users').select2({
        placeholder: "Search",
        minimumInputLength: 1,
        maximumSelectionLength: 1,
        ajax: {
            url: '{{route("search-admins")}}',
            dataType: 'json',
            beforeSend: function(){
                $('.loader').css('display', 'block');
            },
            complete: function() {
                $('.loader').css('display', 'none');
            },
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                      results: $.map(data, function (value) {
                    return {
                        image:value.profile_pic,
                        text:value.first_name+" "+value.last_name,
                        id: value.id,
                        email:value.text
                    }
                
                 })
                  }
            },
            cache: true
        },
           templateResult: formatState,
    });
           function formatState (state) { 
       
       var $state = $( '<div><div style="width: 14%;display: inline-block;"><img src='+state.image+' width=35px" height="35px" style="vertical-align:inherit"></div><div style="width: 80%;display: inline-block;"><div>'+state.text+'</div><div>'+state.email+'</div></div></div>');
        return $state;
    }


      $('#sales').select2({
        placeholder: "Search",
        minimumInputLength: 1,
        maximumSelectionLength: 1,
        ajax: {
            url: '{{route("search-admins")}}',
            dataType: 'json',
            beforeSend: function(){
                $('.loader').css('display', 'block');
            },
            complete: function() {
                $('.loader').css('display', 'none');
            },
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                      results: $.map(data, function (value) {
                    return {
                        image:value.profile_pic,
                        text:value.first_name+" "+value.last_name,
                        id: value.id,
                        email:value.text
                    }
                
                 })
                  }
            },
            cache: true
        },
           templateResult: formatState,
    });
           function formatState (state) { 
       
       var $state = $( '<div><div style="width: 14%;display: inline-block;"><img src='+state.image+' width=35px" height="35px" style="vertical-align:inherit"></div><div style="width: 80%;display: inline-block;"><div>'+state.text+'</div><div>'+state.email+'</div></div></div>');
        return $state;
    }



    $('#replace').click(function(){
      var existingManagerId = $('#existingManager').val();
      var newManagerId = $('#users').val();
      $("#replace").attr('disabled',true);
      $("#replace").html("<i class='fas fa-circle-notch fa-spin'></i>  {{ __('message.please_wait') }}");
      $.ajax ({
                type: 'post',
                url : "{{url('replace-acc-manager')}}",
                data : {'existingAccManager':existingManagerId, 'newAccManager':newManagerId},
          
                success: function (data) {
               if (data.message =='success'){
                $("#replace").attr('disabled',false);
                 var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> {{ __('message.success') }}! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                 $("#replace").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>{{ __('message.replace') }}");
                 $("#replace").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>{{ __('message.replace') }}");
                  $('#replaceMessage').html(result);
                     $('#replaceMessage').css('color', 'green');
                setTimeout(function(){
                    window.location.reload();
                },3000);
                } else {
                  $("#replace").attr('disabled',false);
                  var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>{{ __('message.whoops') }} </strong>{{ __('message.something_wrong') }}<br><br><ul><li>'+data.update+'</li>';
                   html += '</ul></div>';
                   $('#replaceMessage').hide();
                   $('#error').show();
                    document.getElementById('error').innerHTML = html;
                    $("#replace").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>{{ __('message.replace') }}");
                }
                }, error: function(data)  {
                  $("#replace").attr('disabled',false);
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>{{ __('message.whoops') }} </strong>{{ __('message.something_wrong') }}<br><br><ul>';
                    for (key in data.responseJSON.errors) {
                      html += '<li>'+ data.responseJSON.errors[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    $('#replaceMessage').hide();
                    $('#error').show();
                    document.getElementById('error').innerHTML = html;
                    $("#replace").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>{{ __('message.replace') }}");
                  }
               
             });
    })


        $('#replaceSales').click(function(){
      var existingManagerId = $('#existingSalesManager').val();
      var newManagerId = $('#sales').val();
      $("#replaceSales").attr('disabled',true);
      $("#replaceSales").html("<i class='fas fa-circle-notch fa-spin'></i>  {{ __('message.please_wait') }}");
      $.ajax ({
                type: 'post',
                url : "{{url('replace-sales-manager')}}",
                data : {'existingSaleManager':existingManagerId, 'newSaleManager':newManagerId},
          
              success: function (data) {
               if (data.message =='success'){
                 $("#replaceSales").attr('disabled',false);
                 var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> {{ __('message.success') }}! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                 $("#replaceSales").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>Replace");
                  $('#replaceMessage1').html(result);
                     $('#replaceMessage1').css('color', 'green');
                setTimeout(function(){
                    window.location.reload();
                },3000);
                } else {
                  $("#replaceSales").attr('disabled',false);
                  var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>{{ __('message.whoops') }}! </strong>{{ __('message.something_wrong') }}<br><br><ul><li>'+data.update+'</li>';
                   html += '</ul></div>';
                   $('#replaceMessage1').hide();
                   $('#error1').show();
                    document.getElementById('error').innerHTML = html;
                    $("#replaceSales").html("<i class='fas fa-sync-alt'>&nbsp;</i>{{ __('message.replace') }}");
                }
                }, error: function(data)  {
                  $("#replaceSales").attr('disabled',false);
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>{{ __('message.whoops') }} </strong>{{ __('message.something_wrong') }}<br><br><ul>';
                    for (key in data.responseJSON.errors) {
                      html += '<li>'+ data.responseJSON.errors[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    $('#replaceMessage1').hide();
                    $('#error1').show();
                    document.getElementById('error').innerHTML = html;
                    $("#replaceSales").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>{{ __('message.replace') }}");
                  }
               
             });
    })
  </script>
  <script>
     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'setting';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'setting';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>
  @stop