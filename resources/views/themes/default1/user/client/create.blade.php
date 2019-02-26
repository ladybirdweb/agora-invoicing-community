@extends('themes.default1.layouts.master')
@section('title')
Create User
@stop
@section('content-header')
<h1>
Create New User
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="{{url('clients')}}">All Users</a></li>
        <li class="active">Create User</li>
      </ol>
@stop
@section('content')
<style>

.bootstrap-select>.dropdown-toggle {
    background-color: white;
}

select.form-control{
    padding-left: 2px;
}
.caret {
    border-top: 6px dashed;
    border-right: 3px solid transparent;
    border-left: 3px solid transparent;
}
.text{
    margin-left: -10px!important;
}
.bootstrap-select.btn-group .dropdown-toggle .filter-option {
    color:#555;
}

</style>
<div class="box box-primary">

    <div class="box-header">
        @if (count($errors) > 0)
      
        <div class="alert alert-danger alert-dismissable">
            <strong>Whoops!</strong> There were some problems with your input.
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
        {!! Form::open(['url'=>'clients','method'=>'post']) !!}
        <h4><button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>


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

                    <div class="col-md-4 form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                        <!-- company -->
                        {!! Form::label('company',Lang::get('message.company'),['class'=>'required']) !!}
                        {!! Form::text('company',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-4 form-group {{ $errors->has('bussiness') ? 'has-error' : '' }}">
                        <!-- company -->
                        {!! Form::label('bussiness','Industry',['class'=>'required']) !!}
                         <!-- {!! Form::select('bussiness',['Choose'=>'Choose',''=>$bussinesses],null,['class' => 'form-control selectpicker','data-live-search'=>'true', 'data-live-search-placeholder'=>'Search' ,'data-dropup-auto'=>'false', 'data-size'=>'10']) !!} -->
                       <select name="bussiness"  class="form-control">
                             <option value="">Choose</option>
                           @foreach($bussinesses as $key=>$bussines)
                           @if (Input::old('bussiness') == $key)
                             <option value={{$key}} selected>{{$bussines}}</option>
                             @else
                            <option value="{{ $key }}">{{ $bussines }}</option>
                          @endif

                          @endforeach
                          </select>
             
                       

                    </div>


                    <div class="col-md-2 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('active',Lang::get('message.active')) !!}
                        <p>{!! Form::radio('active',1,true) !!}&nbsp;Active&nbsp;&nbsp;{!! Form::radio('active',0) !!}&nbsp;Inactive</p>

                    </div>

                      <div class="col-md-2 form-group {{ $errors->has('mobile_verified') ? 'has-error' : '' }}">
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
                        {!! Form::select('position',[''=>'Choose','manager'=>'Manager'],null,['class' => 'form-control']) !!}

                    </div>
                    <?php
                    $type = DB::table('company_types')->pluck('name','short')->toarray();
                    $size = DB::table('company_sizes')->pluck('name','short')->toarray();
                    ?>
                     <div class="col-md-3 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                        <!-- email -->
                        {!! Form::label('company_type','Company Type',['class'=>'required']) !!}
                        <!-- {!! Form::select('company_type',['choose'=>'Choose',''=>$type],null,['class' => 'form-control']) !!} -->

                         <select name="company_type" value= "Choose" class="form-control">
                             <option value="">Choose</option>
                           @foreach($type as $key=>$types)
                              @if (Input::old('company_type') == $key)
                             <option value={{$key}} selected>{{$types}}</option>
                             @else
                             <option value={{$key}}>{{$types}}</option>
                               @endif
                          @endforeach
                          </select>

                    </div>
                     <div class="col-md-3 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                        <!-- email -->
                        {!! Form::label('company_size','Company Size',['class'=>'required']) !!}
                <!-- {!! Form::select('company_size',['choose'=>'Choose',''=>$size],null,['class' => 'form-control']) !!} -->
                          <select name="company_size" value= "Choose" class="form-control">
                             <option value="">Choose</option>
                           @foreach($size as $key=>$sizes)
                              @if (Input::old('company_size') == $key)
                             <option value={{$key}} selected>{{$sizes}}</option>
                             @else
                             <option value={{$key}}>{{$sizes}}</option>
                             @endif
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

                    <div class="col-md-4 form-group {{ $errors->has('town') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('town',Lang::get('message.town')) !!}
                        {!! Form::text('town',null,['class' => 'form-control']) !!}

                    </div>

                    <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray();
                     ?>
                    <div class="col-md-4 form-group select2{{ $errors->has('country') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('country',Lang::get('message.country'),['class'=>'required']) !!}
                      

                     <!--    {!! Form::select('country',['choose'=>'Choose',''=>$countries],null,['class' => 'form-control selectpicker','data-live-search'=>'true','data-live-search-placeholder'=>'Search','data-dropup-auto'=>'false','data-size'=>'10','onChange'=>'getCountryAttr(this.value);']) !!} -->

                          <select name="country" value= "Choose" id="country" onChange="getCountryAttr(this.value)" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-dropup-auto="false" data-size="10">
                             <option value="">Choose</option>
                           @foreach($countries as $key=>$country)
                            @if (Input::old('country') == strtolower($key))

                            <option value={{$key}} selected>{{$country}}</option>
                             @else
                              <option value={{$key}}>{{$country}}</option>
                               @endif
                          @endforeach
                          </select>

                    </div>
                    <?php
                     $selectedstate = \App\Model\Common\State::select('state_subdivision_code','state_subdivision_name')->get();
                    ?>
                    <div class="col-md-4 form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('state',Lang::get('message.state')) !!}
                        <!--{!! Form::select('state',[],null,['class' => 'form-control','id'=>'state-list']) !!}-->
                          <select name="state" id="state-list" class="form-control">
                        @if(old('state') != null)
                             @foreach($selectedstate as $key=>$state)
                             @if (Input::old('state') == $state->state_subdivision_code)
                             <option value="{{old('state')}}" selected>{{$state->state_subdivision_name}}</option>
                             @endif
                             @endforeach
                             @else
                      
                            <option value="">Choose A Country</option>
                            @endif

                        </select>

                    </div>
                    <div class="col-md-4 form-group {{ $errors->has('zip') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('zip',Lang::get('message.zip'),['class'=>'required']) !!}
                        {!! Form::text('zip',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-4 form-group {{ $errors->has('timezone_id') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('timezone_id',Lang::get('message.timezone'),['class'=>'required']) !!}
                         {!! Form::select('timezone_id', [''=>'Choose','Timezones'=>$timezones],null,['class' => 'form-control selectpicker','data-live-search'=>'true','data-live-search-placeholder' => 'Search','data-dropup-auto'=>'false','data-size'=>'10']) !!}


                       <!--   <select name="timezone_id" value= "Choose" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-dropup-auto="false" data-size="10"">
                             <option value="">Choose</option>
                           @foreach($timezones as $key=>$timezone)

                             <option value={{$key}}>{{$timezone}}</option>
                          @endforeach
                          </select> -->

                    </div>
                    <div class="col-md-4 form-group {{ $errors->has('currency') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        <?php $currencies = DB::table('currencies')->where('status',1)->pluck('name','code')->toarray() ?>
                        {!! Form::label('currency',Lang::get('message.currency'),['class'=>'required']) !!}
                        <select name="currency" value= "Choose" class="form-control" id ="currency">
                             <option value="">Choose</option>
                           @foreach($currencies as $key=>$currency)
                              @if (Input::old('currency') == $key)
                            <option value={{$key}} selected>{{$currency}}</option>
                             @else
                             <option value={{$key}}>{{$currency}}</option>
                              @endif
                           @endforeach
                          </select>

                    </div>
                   <!--  <div class="col-md-4 form-group {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                        <label class="required">Country code</label>
                        {!! Form::hidden('mobile_code',null,['id'=>'mobile_code_hidden']) !!}
                        {!! Form::text('mobil',null,['class'=>'form-control','disabled','id'=>'mobile_code']) !!}

                         
                    </div> -->
                   


                    <div class="col-md-4 form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                        <!-- mobile -->

                        {!! Form::label('mobile',Lang::get('message.mobile'),['class'=>'required']) !!}
                        {!! Form::hidden('mobile_code',null,['id'=>'mobile_code_hidden']) !!}
                         <input class="form-control"  id="mobile_code" name="mobile" value="{{ old('mobile') }}" type="tel">
                    </div>


                    <div class="col-md-4 form-group {{ $errors->has('skype') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('skype','Skype') !!}
                        {!! Form::text('skype',null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-4 form-group {{ $errors->has('manager') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('manager','Manager') !!}
                 <!-- {!! Form::select('manager',[''=>'Select','Managers'=>$managers],null,['class' => 'form-control']) !!} -->
                         <select name="manager" value= "Choose" class="form-control">
                             <option value="">Choose</option>
                           @foreach($managers as $key=>$manager)
                             <option value={{$key}}>{{$manager}}</option>
                          @endforeach
                          </select>
                    </div>


                </div>
               
            </div>
        </div>
          {!! Form::close() !!}
    </div>
</div>





<script>
  $(document).ready(function(){
// get the country data from the plugin
var countryData = $.fn.intlTelInput.getCountryData(),
  telInput = $("#mobile_code"),
  addressDropdown = $("#country");
// init plugin
telInput.intlTelInput({
   separateDialCode: true,
  utilsScript: "common/js/utils.js" // just for formatting/placeholders etc
});

// populate the country dropdown
$.each(countryData, function(i, country) {
  addressDropdown.append($("<option></option>").attr("value", country.iso2).text(country.name));
});
// set it's initial value
// var initialCountry = telInput.intlTelInput("getSelectedCountryData").iso2;
// addressDropdown.val(initialCountry);

// listen to the telephone input for changes
telInput.on("countrychange", function(e, countryData) {
  addressDropdown.val(countryData.iso2);
});

// listen to the address dropdown for changes
addressDropdown.change(function() {
  telInput.intlTelInput("setCountry", $(this).val());
});
$('form').on('submit', function (e) {
        $('input[name=mobile_code]').attr('value', $('.selected-dial-code').text());
    });
});

    function getCountryAttr(val) {
        getState(val);
        // getCode(val);
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
                $("#mobile_code").val(data);
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