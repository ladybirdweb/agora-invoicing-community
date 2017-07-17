@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Agora | Profile
@stop
@section('nav-profile')
active
@stop

@section('content')

<h2 class="mb-none"> My Profile</h2>

<div class="row">
    <!--    <div class="col-md-12">
            <p class="lead">
                Check out all the tables options.
            </p>
        </div>-->
</div>
<div class="col-md-12 pull-center">
    <div class="featured-boxes">
        <div class="row">
            <div class="col-sm-6">
                <div class="featured-box featured-box-primary align-left mt-xlg">
                    <div class="box-content">

                        <h4 class="heading-primary text-uppercase mb-md">Edit Profile</h4>
                        {!! Form::model($user,['url'=>'my-profile', 'method' => 'PATCH','files'=>true]) !!}
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                            <!-- first name -->
                            {!! Form::label('first_name',Lang::get('message.first_name')) !!}
                            {!! Form::text('first_name',null,['class' => 'form-control']) !!}

                        </div>

                        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                            <!-- last name -->
                            {!! Form::label('last_name',Lang::get('message.last_name')) !!}
                            {!! Form::text('last_name',null,['class' => 'form-control']) !!}

                        </div>


                        <div class="form-group">
                            <!-- email -->
                            {!! Form::label('email',Lang::get('message.email')) !!}
                            <div>
                                {{$user->email}}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                            <!-- company -->
                            {!! Form::label('company',Lang::get('message.company')) !!}
                            {!! Form::text('company',null,['class' => 'form-control']) !!}

                        </div>
                        <div class="form-group {{ $errors->has('mobile_code') ? 'has-error' : '' }}">
                        <label class="required">Country code</label>
                        {!! Form::text('mobile_code',null,['class'=>'form-control']) !!}
                    </div>
                        <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                            
                            <!-- mobile -->
                            {!! Form::label('mobile',Lang::get('message.mobile')) !!}
                            {!! Form::text('mobile',null,['class' => 'form-control']) !!}

                        </div>

                        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                            <!-- phone number -->
                            {!! Form::label('address',Lang::get('message.address')) !!}
                            {!! Form::textarea('address',null,['class' => 'form-control']) !!}

                        </div>


                        <div class="row">
                            <div class="col-md-6  form-group {{ $errors->has('town') ? 'has-error' : '' }}">
                                <!-- mobile -->
                                {!! Form::label('town',Lang::get('message.town')) !!}
                                {!! Form::text('town',null,['class' => 'form-control']) !!}

                            </div>
                            <div class="col-md-6 form-group {{ $errors->has('timezone_id') ? 'has-error' : '' }}">
                                <!-- mobile -->
                                {!! Form::label('timezone_id',Lang::get('message.timezone')) !!}
                                {!! Form::select('timezone_id',[''=>'Select','Timezones'=>$timezones],null,['class' => 'form-control']) !!}

                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                                <!-- name -->
                                {!! Form::label('country',Lang::get('message.country')) !!}
                                 <?php $countries = \App\Model\Common\Country::lists('country_name', 'country_code_char2')->toArray(); ?>
                                {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control','onChange'=>'getState(this.value);']) !!}

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
                            {!! Form::label('zip',Lang::get('message.zip')) !!}
                            {!! Form::text('zip',null,['class' => 'form-control']) !!}

                        </div>


                        <div class="form-group {{ $errors->has('profile_pic') ? 'has-error' : '' }}">
                            <!-- profile pic -->
                            {!! Form::label('profile_pic',Lang::get('message.profile-picture')) !!}
                            {!! Form::file('profile_pic') !!}

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" value="Update" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="featured-box featured-box-primary align-left mt-xlg">
                    <div class="box-content">
                        <h4 class="heading-primary text-uppercase mb-md">Change Password</h4>
                        {!! Form::model($user,['url'=>'my-password' , 'method' => 'PATCH']) !!}

                        <!-- old password -->
                        <div class="form-group has-feedback {{ $errors->has('old_password') ? 'has-error' : '' }}">
                            {!! Form::label('old_password',Lang::get('message.old_password')) !!}
                            {!! Form::password('old_password',['placeholder'=>'Password','class' => 'form-control']) !!}
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <!-- new password -->
                        <div class="form-group has-feedback {{ $errors->has('new_password') ? 'has-error' : '' }}">
                            {!! Form::label('new_password',Lang::get('message.new_password')) !!}
                            {!! Form::password('new_password',['placeholder'=>'New Password','class' => 'form-control']) !!}
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <!-- cofirm password -->
                        <div class="form-group has-feedback {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                            {!! Form::label('confirm_password',Lang::get('message.confirm_password')) !!}
                            {!! Form::password('confirm_password',['placeholder'=>'Confirm Password','class' => 'form-control']) !!}
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" value="Update" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>    
<script>
    function getState(val) {


        $.ajax({
            type: "POST",
            url: "{{url('get-state')}}",
            data: 'country_id=' + val,
            success: function (data) {
                $("#state-list").html(data);
            }
        });
    }
</script>

@stop