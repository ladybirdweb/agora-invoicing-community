@extends('themes.default1.layouts.master')
@section('title')
System Managers
@stop
@section('content-header')


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<h1>
System Managers
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
         <li class="active">System Managers</li>
      </ol>
@stop
@section('content')
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
        <div id="error">
        </div>
        <div id="success">
        </div>
        <div id="fails">
        </div>
        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
              <i class="fa fa-check"></i>
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


        
         </div>


         <?php
         $users = [];
         ?>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

            <div id="replaceMessage"></div>
            <div id="error"></div>
                <div class="col-md-4 form-group">
                    {!! Form::label('user',Lang::get('message.system_account_manager'),['class'=>'required']) !!}
                       <select name="manager" value= "Choose" id="existingManager" class="form-control">
                             <option value="">Choose</option>
                           @foreach($accountManagers as $key=>$manager)
                             <option value={{$key}}>{{$manager}}</option>
                          @endforeach
                          </select>
                 
                </div>
               
                <div class="col-md-4 form-group {{ $errors->has('account_manager') ? 'has-error' : '' }}">
                            <!-- first name -->
                            {!! Form::label('replace_with',Lang::get('message.replace_with'),['class'=>'required']) !!}
                        
                           
                            {!! Form::select('account_manager', [Lang::get('User')=>$users],null,['multiple'=>true,'class'=>"form-control select2" ,'id'=>"users",'required','style'=>"width:100%!important",'oninvalid'=>"setCustomValidity('Please Select Client')", 
                  'onchange'=>"setCustomValidity('')"]) !!}
                       

                </div>

                  <div class="col-md-4 form-group {{ $errors->has('account_manager') ? 'has-error' : '' }}">
                    <br>
                 <button name="generate" type="submit" id="replace" class="btn btn-primary" style="margin-top: 4px;"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.replace')!!}</button>
                 </div>
               </div>


               <div class="col-md-12">
                 <div id="replaceMessage1"></div>
                  <div id="error1"></div>
                   <div class="col-md-4 form-group">
                    {!! Form::label('user',Lang::get('message.system_sales_manager'),['class'=>'required']) !!}
                       <select name="sales_manager" value= "Choose" id="existingSalesManager" class="form-control">
                             <option value="">Choose</option>
                           @foreach($salesManager as $key=>$manager)
                             <option value={{$key}}>{{$manager}}</option>
                          @endforeach
                          </select>
                 
                </div>
               
                <div class="col-md-4 form-group {{ $errors->has('sales_manager') ? 'has-error' : '' }}">
                            <!-- first name -->
                            {!! Form::label('replace_with',Lang::get('message.replace_with'),['class'=>'required']) !!}
                        
                           
                            {!! Form::select('sales_manager', [Lang::get('User')=>$users],null,['multiple'=>true,'class'=>"form-control select2" ,'id'=>"sales",'required','style'=>"width:100%!important",'oninvalid'=>"setCustomValidity('Please Select Client')", 
                  'onchange'=>"setCustomValidity('')"]) !!}
                       

                </div>

                  <div class="col-md-4 form-group {{ $errors->has('sales_manager') ? 'has-error' : '' }}">
                    <br>
                 <button name="generate" type="submit" id="replaceSales" class="btn btn-primary" style="margin-top: 4px;"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.replace')!!}</button>
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

    <!---------------------------------------------------------------------------------------------------!>

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

<!--------------------------------------------------------------------------------------------------------------!>


    $('#replace').click(function(){
      var existingManagerId = $('#existingManager').val();
      var newManagerId = $('#users').val();
      $("#replace").attr('disabled',true);
      $("#replace").html("<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>Please Wait...");
      $.ajax ({
                type: 'post',
                url : "{{url('replace-acc-manager')}}",
                data : {'existingAccManager':existingManagerId, 'newAccManager':newManagerId},
          
                success: function (data) {
               if (data.message =='success'){
                $("#replace").attr('disabled',false);
                 var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                 $("#replace").html("<i class='fa fa-refresh'>&nbsp;&nbsp;</i>Replace");
                  $('#replaceMessage').html(result);
                     $('#replaceMessage').css('color', 'green');
                setTimeout(function(){
                    window.location.reload();
                },3000);
                } else {
                  $("#replace").attr('disabled',false);
                  var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<br><br><ul><li>'+data.update+'</li>';
                   html += '</ul></div>';
                   $('#replaceMessage').hide();
                   $('#error').show();
                    document.getElementById('error').innerHTML = html;
                    $("#replace").html("<i class='fa fa-refresh'>&nbsp;&nbsp;</i>Replace");
                }
                }, error: function(data)  {
                  $("#replace").attr('disabled',false);
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                    for (key in data.responseJSON.errors) {
                      html += '<li>'+ data.responseJSON.errors[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    $('#replaceMessage').hide();
                    $('#error').show();
                    document.getElementById('error').innerHTML = html;
                    $("#replace").html("<i class='fa fa-refresh'>&nbsp;&nbsp;</i>Replace");
                  }
               
             });
    })

    <!----------------------------------------------------------------------------------------------------!>

        $('#replaceSales').click(function(){
      var existingManagerId = $('#existingSalesManager').val();
      var newManagerId = $('#sales').val();
      $("#replaceSales").attr('disabled',true);
      $("#replaceSales").html("<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>Please Wait...");
      $.ajax ({
                type: 'post',
                url : "{{url('replace-sales-manager')}}",
                data : {'existingSaleManager':existingManagerId, 'newSaleManager':newManagerId},
          
              success: function (data) {
               if (data.message =='success'){
                 $("#replaceSales").attr('disabled',false);
                 var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                 $("#replaceSales").html("<i class='fa fa-refresh'>&nbsp;&nbsp;</i>Replace");
                  $('#replaceMessage1').html(result);
                     $('#replaceMessage1').css('color', 'green');
                setTimeout(function(){
                    window.location.reload();
                },3000);
                } else {
                  $("#replaceSales").attr('disabled',false);
                  var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<br><br><ul><li>'+data.update+'</li>';
                   html += '</ul></div>';
                   $('#replaceMessage1').hide();
                   $('#error1').show();
                    document.getElementById('error').innerHTML = html;
                    $("#replaceSales").html("<i class='fa fa-refresh'>&nbsp;&nbsp;</i>Replace");
                }
                }, error: function(data)  {
                  $("#replaceSales").attr('disabled',false);
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                    for (key in data.responseJSON.errors) {
                      html += '<li>'+ data.responseJSON.errors[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    $('#replaceMessage1').hide();
                    $('#error1').show();
                    document.getElementById('error').innerHTML = html;
                    $("#replaceSales").html("<i class='fa fa-refresh'>&nbsp;&nbsp;</i>Replace");
                  }
               
             });
    })
  </script>
  @stop