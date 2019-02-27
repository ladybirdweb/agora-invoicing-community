@extends('themes.default1.layouts.master')
@section('title')
User Profile
@stop
@section('content')
@section('content-header')
<h1>
Edit Profile
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit Profile</li>
      </ol>
@stop
<style>
  
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }
        .bootstrap-select.btn-group .dropdown-menu li a {
    margin-left: -12px !important;
}
 .btn-group>.btn:first-child {
    margin-left: 0;
    background-color: white;

   }
.bootstrap-select.btn-group .dropdown-toggle .filter-option {
    color:#555;
}


</style>
<div class="row">

    <div class="col-md-12">

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

    </div>


    <div class="col-md-6">


        {!! Form::model($user,['url'=>'profile', 'method' => 'PATCH','files'=>true]) !!}


        <div class="box box-primary">

            <div class="content-header">

                <h4>{{Lang::get('message.profile')}}	
                <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>


            </div>

            <div class="box-body">



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

                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                    <!-- first name -->
                    {!! Form::label('first_name',null,['class' => 'required'],Lang::get('message.first_name')) !!}
                    {!! Form::text('first_name',null,['class' => 'form-control']) !!}

                </div>

                <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                    <!-- last name -->
                    {!! Form::label('last_name',null,['class' => 'required'],Lang::get('message.last_name')) !!}
                    {!! Form::text('last_name',null,['class' => 'form-control']) !!}

                </div>
                <div class="form-group {{ $errors->has('user_name') ? 'has-error' : '' }}">
                    <!-- mobile -->
                    {!! Form::label('user_name',null,['class' => 'required'],Lang::get('message.user_name')) !!}
                    {!! Form::text('user_name',null,['class' => 'form-control']) !!}
                </div>


                <div class="form-group">
                    <!-- email -->
                    {!! Form::label('email',null,['class' => 'required'],Lang::get('message.email')) !!}
                     {!! Form::text('email',null,['class' => 'form-control']) !!}
                  
                </div>

                <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                    <!-- company -->
                    {!! Form::label('company',null,['class' => 'required'],Lang::get('message.company')) !!}
                    {!! Form::text('company',null,['class' => 'form-control']) !!}

                </div>
            

                <div class="form-group {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                  {!! Form::label('mobile',null,['class' => 'required'],Lang::get('message.mobile'),['class'=>'required']) !!}
                     {!! Form::hidden('mobile_code',null,['id'=>'mobile_code_hidden']) !!}
                      <input class="form-control"  id="mobile_code" value="{{$user->mobile}}" name="mobile" type="tel">
                       {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                    <!-- {!! Form::text('mobil',null,['class'=>'form-control', 'id'=>'mobile_code']) !!} -->
                </div>
                

                <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                    <!-- phone number -->
                    {!! Form::label('address',null,['class' => 'required'],Lang::get('message.address')) !!}
                    {!! Form::textarea('address',null,['class' => 'form-control']) !!}

                </div>

                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('town') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('town',Lang::get('message.town')) !!}
                        {!! Form::text('town',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('timezone_id') ? 'has-error' : '' }}">
                        <!-- mobile -->
                        {!! Form::label('timezone_id',Lang::get('message.timezone')) !!}
                        <!-- {!! Form::select('timezone_id',[''=>'Select','Timezones'=>$timezones],null,['class' => 'form-control']) !!} -->
                        {!! Form::select('timezone_id', [Lang::get('message.choose')=>$timezones],null,['class' => 'form-control selectpicker','data-live-search'=>'true','required','data-live-search-placeholder' => 'Search','data-dropup-auto'=>'false','data-size'=>'10']) !!}


                    </div>

                </div>

                <div class="row">
                    <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>
                    <div class="col-md-6 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                         {!! Form::label('country',Lang::get('message.country')) !!}

                        {!! Form::select('country',[Lang::get('message.choose')=>$countries],null,['class' => 'form-control selectpicker','id'=>'country','onChange'=>'getCountryAttr(this.value)','data-live-search'=>'true','required','data-live-search-placeholder' => 'Search','data-dropup-auto'=>'false','data-size'=>'10']) !!}
                        <!-- name -->
                       
                     
                        

                    </div>
                    <div class="col-md-6 form-group {{ $errors->has('state') ? 'has-error' : '' }}">
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


                </div>
                <div class="form-group {{ $errors->has('zip') ? 'has-error' : '' }}">
                    <!-- mobile -->
                    {!! Form::label('zip',null,['class' => 'required'],Lang::get('message.zip')) !!}
                    {!! Form::text('zip',null,['class' => 'form-control']) !!}

                </div>

                <div class="form-group {{ $errors->has('profile_pic') ? 'has-error' : '' }}">
                    <!-- profile pic -->
                    {!! Form::label('profile_pic',Lang::get('message.profile-picture')) !!}
                    {!! Form::file('profile_pic') !!}

                </div>

                {!! Form::token() !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">



        {!! Form::model($user,['url'=>'password' , 'method' => 'PATCH']) !!}



        <div class="box box-primary">

            <div class="content-header">

                <h4>{{Lang::get('message.change-password')}}	<button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>

            </div>

            <div class="box-body">
                @if(Session::has('success1'))
                <div class="alert alert-success alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('success1')}}
                </div>
                @endif
                <!-- fail message -->
                @if(Session::has('fails1'))
                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('fails1')}}
                </div>
                @endif
                <!-- old password -->
                <div class="form-group has-feedback {{ $errors->has('old_password') ? 'has-error' : '' }}">
                    {!! Form::label('old_password',null,['class' => 'required'],Lang::get('message.old_password')) !!}
                    {!! Form::password('old_password',['placeholder'=>'Password','class' => 'form-control']) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <!-- new password -->
                <div class="form-group has-feedback {{ $errors->has('new_password') ? 'has-error' : '' }}">
                    {!! Form::label('new_password',null,['class' => 'required'],Lang::get('message.new_password')) !!}
                    {!! Form::password('new_password',['placeholder'=>'New Password','class' => 'form-control']) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <!-- cofirm password -->
                <div class="form-group has-feedback {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                    {!! Form::label('confirm_password',null,['class' => 'required'],Lang::get('message.confirm_password')) !!}
                    {!! Form::password('confirm_password',['placeholder'=>'Confirm Password','class' => 'form-control']) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

            </div>
        </div>
    </div>
</div>


{!! Form::close() !!}
<script>
// get the country data from the plugin
     $(document).ready(function(){
    var country = $('#country').val();
    var telInput = $('#mobile_code');
     let currentCountry="";
    telInput.intlTelInput({
        initialCountry: "auto",
        geoIpLookup: function (callback) {

            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                resp.country = country;

                var countryCode = (resp && resp.country) ? resp.country : "";
                    currentCountry=countryCode.toLowerCase()
                    callback(countryCode);
            });
        },
        separateDialCode: true,
        // utilsScript: "{{asset('js/intl/js/utils.js')}}",
    });
    setTimeout(()=>{
         telInput.intlTelInput("setCountry", currentCountry);
    },500)
    $('.intl-tel-input').css('width', '100%');

    telInput.on('blur', function () {
        if ($.trim(telInput.val())) {
            if (!telInput.intlTelInput("isValidNumber")) {
                telInput.parent().addClass('has-error');
            }
        }
    });
    $('input').on('focus', function () {
        $(this).parent().removeClass('has-error');
    });

    $('form').on('submit', function (e) {
        $('input[name=country_code]').attr('value', $('.selected-dial-code').text());
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
</script>
@stop