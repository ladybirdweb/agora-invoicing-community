@extends('themes.default1.layouts.master')
@section('title')
Edit User
@stop
@section('content-header')
<h1>
Edit User
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="{{url('clients')}}">All Users</a></li>
        <li class="active">Edit User</li>
      </ol>
@stop
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<style>
    .bootstrap-select.btn-group .dropdown-menu li a {
    margin-left: -10px !important;
}
 .btn-group>.btn:first-child {
    margin-left: 0;
    background-color: white;

    select {
  -webkit-appearance: none;
  -webkit-border-radius: -6px;
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
        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
              <i class="fa fa-check"></i>
            <b>{{Lang::get('message.success')}}!</b>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('success')}}
        </div>
        @endif
        
        @if(Session::has('warning'))
        <div class="alert alert-warning alert-dismissable">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('warning')}}
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
    

    <div class="box-body">
         {!! Form::model($user,['url'=>'clients/'.$user->id,'method'=>'PATCH']) !!}

        <h4>{{Lang::get('message.client')}}<button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button></h4>

        <div class="row">

            <div class="col-md-12">



                <div class="row">

                    <div class="col-md-3 form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('first_name',Lang::get('message.first_name'),['class'=>'required']) !!}
                        {!! Form::text('first_name',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-3 form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('last_name',Lang::get('message.last_name'),['class'=>'required']) !!}
                        {!! Form::text('last_name',null,['class' => 'form-control']) !!}

                    </div>


                    <div class="col-md-3 form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <!-- email -->
                        {!! Form::label('email',Lang::get('message.email'),['class'=>'required']) !!}
                        {!! Form::text('email',null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-3 form-group {{ $errors->has('user_name') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('user_name',Lang::get('message.user_name')) !!}
                        {!! Form::text('user_name',null,['class' => 'form-control']) !!}

                    </div>

                    

                </div>

                <div class="row">

                    <div class="col-md-3 form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                        <!-- company -->
                        {!! Form::label('company',Lang::get('message.company'),['class'=>'required']) !!}
                        {!! Form::text('company',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('bussiness') ? 'has-error' : '' }}">
                        <!-- company -->
                        {!! Form::label('bussiness','Industry',['class'=>'required']) !!}
                        <select name="bussiness"  class="form-control">
                            <option value="">Choose</option>
                         @foreach($bussinesses as $key=>$bussiness)
                         
                        <option value="{{$key}}" <?php  if(in_array($bussiness, $selectedIndustry) ) 
                        { echo "selected";} ?>>{{$bussiness}}</option>
                            @endforeach
                         </select>
                           
                    </div>


                    <div class="col-md-3 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('active',Lang::get('message.email')) !!}
                        <p>{!! Form::radio('active',1,true) !!}&nbsp;Active&nbsp;&nbsp;{!! Form::radio('active',0) !!}&nbsp;Inactive</p>

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('mobile_verified') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('mobile_verified',Lang::get('message.mobile')) !!}
                        <p>{!! Form::radio('mobile_verified',1,true) !!}&nbsp;Active&nbsp;&nbsp;{!! Form::radio('mobile_verified',0) !!}&nbsp;Inactive</p>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                        <!-- email -->
                        {!! Form::label('role',Lang::get('message.role')) !!}
                        {!! Form::select('role',['user'=>'User','admin'=>'Admin'],null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('position') ? 'has-error' : '' }}">
                        <!-- email -->
                        {!! Form::label('position','Position') !!}
                        {!! Form::select('position',['Choose'=>'Choose','manager'=>'Sales Manager','account_manager'=>'Account Manager'],null,['class' => 'form-control']) !!}

                    </div>
                    <?php
                   $types = DB::table('company_types')->pluck('name','short')->toArray();
                    $sizes = DB::table('company_sizes')->pluck('name','short')->toArray();
                    ?>
                     <div class="col-md-3 form-group {{ $errors->has('company_type') ? 'has-error' : '' }}">
                        <!-- email -->
                        {!! Form::label('company_type','Company Type',['class'=>'required']) !!}
                      
                          <select name="company_type"  class="form-control">
                            <option value="">Choose</option>
                         @foreach($types as $key=>$type)
                                   <option value="{{$key}}" <?php  if(in_array($type, $selectedCompany) ) { echo "selected";} ?>>{{$type}}</option>
                           
                             @endforeach
                              </select>

                    </div>
                     <div class="col-md-3 form-group {{ $errors->has('company_size') ? 'has-error' : '' }}">
                        <!-- email -->
                        {!! Form::label('company_size','Company Size',['class'=>'required']) !!}
                        <select name="company_size"  class="form-control">
                            <option value="">Choose</option>
                        @foreach($sizes as $key=>$size)
                        <option value="{{$key}}" <?php  if(in_array($size, $selectedCompanySize) ) { echo "selected";} ?>>{{$size}}</option>
                           
                             @endforeach
                              </select>

                    </div>
                </div>
                <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                    <!-- phone number -->
                    {!! Form::label('address',Lang::get('message.address'),['class'=>'required']) !!}
                    {!! Form::textarea('address',null,['class' => 'form-control']) !!}

                </div>

                <div class="row">

                    <div class="col-md-3 form-group {{ $errors->has('town') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('town',Lang::get('message.town')) !!}
                        {!! Form::text('town',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-3 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('country',Lang::get('message.country')) !!}
                        <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>

                        {!! Form::select('country',[Lang::get('message.choose')=>$countries],null,['class' => 'form-control selectpicker','id'=>'country','onChange'=>'getCountryAttr(this.value)','data-live-search'=>'true','required','data-live-search-placeholder' => 'Search','data-dropup-auto'=>'false','data-size'=>'10']) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('state',Lang::get('message.state')) !!}
                        <!--{!! Form::select('state',[],null,['class' => 'form-control','id'=>'state-list']) !!}-->

                        <select name="state" id="state-list" class="form-control">

                            @if(count($state)>0)
                            <option value="{{$state['id']}}">{{$state['name']}}</option>
                            @endif
                            <option value="">Select State</option>
                            @foreach($states as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-3 form-group {{ $errors->has('zip') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('zip',Lang::get('message.zip'),['class'=>'required']) !!}
                        {!! Form::text('zip',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('timezone_id') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('timezone_id',Lang::get('message.timezone')) !!}
                       
                         {!! Form::select('timezone_id', ['Timezones'=>$timezones],null,['class' => 'form-control selectpicker','data-live-search'=>'true','required','data-live-search-placeholder' => 'Search','data-dropup-auto'=>'false','data-size'=>'10']) !!}

                    </div>
                    <?php 
                   $currencies = DB::table('currencies')->where('status',1)->pluck('name','code')->toArray() ; 
                    ?>
                    <div class="col-md-3 form-group {{ $errors->has('currency') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('currency',Lang::get('message.currency')) !!}
                         <select name="currency" id="plan" class="form-control" onchange="myFunction()">
                            <option value="">Choose</option>
                         @foreach($currencies as $key=>$currency)
                                   <option value="{{$key}}" <?php  if(in_array($currency, $selectedCurrency) ) { echo "selected";} ?>>{{$currency}}</option>
                           
                             @endforeach
                              </select>

                    </div>
                       <div class="col-md-3 form-group {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                  {!! Form::label('mobile',null,['class' => 'required'],Lang::get('message.mobile'),['class'=>'required']) !!}
                     {!! Form::hidden('mobile_code',null,['id'=>'mobile_code_hidden']) !!}
                      <input class="form-control selected-dial-code"  id="mobile_code" value="{{$user->mobile}}" name="mobile" type="tel">
                       <!-- {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!} -->
                       <span id="valid-msg" class="hide"></span>
                       <span id="error-msg" class="hide"></span>
                    <!-- {!! Form::text('mobil',null,['class'=>'form-control', 'id'=>'mobile_code']) !!} -->
                </div>
                   
                  
                    <div class="col-md-3 form-group {{ $errors->has('skype') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('skype','Skype') !!}
                        {!! Form::text('skype',null,['class' => 'form-control']) !!}

                    </div>
                    @if($user->role=='user')
                    <div class="col-md-3 form-group {{ $errors->has('manager') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('manager','Sales Manager') !!}
                        {!! Form::select('manager',[''=>'Select','Managers'=>$managers],null,['class' => 'form-control']) !!}

                    </div>

                     <div class="col-md-3 form-group {{ $errors->has('manager') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('account_manager','Account Manager') !!}
                        {!! Form::select('account_manager',[''=>'Select','Managers'=>$acc_managers],null,['class' => 'form-control']) !!}

                    </div>
                    @endif
                </div>
              
            </div>
        </div>
 {!! Form::close() !!}
    </div>
     
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
    $('.selectpicker').selectpicker({
  style: 'btn-default',
  color: 'white',
  size: 4
});

   
     $(document).ready(function(){

    var country = $('#country').val();
    var telInput = $('#mobile_code'),
     addressDropdown = $("#country");
     errorMsg = document.querySelector("#error-msg"),
    validMsg = document.querySelector("#valid-msg");
    var errorMap = [ "Invalid number", "Invalid country code", "Number Too short", "Number Too long", "Invalid number"];
     let currentCountry="";
    telInput.intlTelInput({
        initialCountry: "auto",
        geoIpLookup: function (callback) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                resp.country = country;
                var countryCode = (resp && resp.country) ? resp.country : "";
                    currentCountry=countryCode.toLowerCase()
                    callback(countryCode);
            });
        },
          separateDialCode: true,
          utilsScript: "../../js/intl/js/utils.js"
    });
      var reset = function() {
      errorMsg.innerHTML = "";
      errorMsg.classList.add("hide");
      validMsg.classList.add("hide");
    };
    setTimeout(()=>{
         telInput.intlTelInput("setCountry", currentCountry);
    },500)
    $('.intl-tel-input').css('width', '100%');
    telInput.on('blur', function () {
      reset();
        if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
              $('#mobile_code').css("border-color","");
              validMsg.classList.remove("hide");
              $('#submit').attr('disabled',false);
            } else {
              var errorCode = telInput.intlTelInput("getValidationError");
             errorMsg.innerHTML = errorMap[errorCode];
             $('#mobile_code').css("border-color","red");
             $('#error-msg').css({"color":"red","margin-top":"5px"});
             errorMsg.classList.remove("hide");
             $('#submit').attr('disabled',true);
            }
        }
    });

    addressDropdown.change(function() {
     telInput.intlTelInput("setCountry", $(this).val());
             if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
              $('#mobile_code').css("border-color","");
              errorMsg.classList.add("hide");
              $('#submit').attr('disabled',false);
            } else {
              var errorCode = telInput.intlTelInput("getValidationError");
             errorMsg.innerHTML = errorMap[errorCode];
             $('#mobile_code').css("border-color","red");
             $('#error-msg').css({"color":"red","margin-top":"5px"});
             errorMsg.classList.remove("hide");
             $('#submit').attr('disabled',true);
            }
        }
    });
    $('input').on('focus', function () {
        $(this).parent().removeClass('has-error');
    });

    $('form').on('submit', function (e) {
        $('input[name=mobile]').attr('value', $('.selected-dial-code').text());
    });
});

</script>

<script>

    function getCountryAttr(val) {
        getState(val);
        getCode(val);
//        getCurrency(val);

    }

    function getState(val) {


        $.ajax({
            type: "GET",
              url: "{{url('get-state')}}/" + val,
            data: 'country_id=' + val,
            success: function (data) {
                $("#state-list").html(data);
            }
        });
    }
    function getCode(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-code')}}",
            data: 'country_id=' + val,
            success: function (data) {
                // $("#mobile_code").val(data);
                $("#mobile_code_hidden").val(data);
            }
        });
    }
    function getCurrency(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-currency')}}",
            data: 'country_id=' + val,
            success: function (data) {
                $("#currency").val(data);
            }
        });
    }
</script>


@stop