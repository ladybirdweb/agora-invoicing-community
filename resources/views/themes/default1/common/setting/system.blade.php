@extends('themes.default1.layouts.master')
@section('content-header')
<h1>
System Setting

</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li class="active">System Settings</li>
      </ol>
@stop
@section('content')
<div class="row">

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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

            </div>

            <div class="box-body">
                {!! Form::model($set,['url'=>'settings/system','method'=>'patch','files'=>true]) !!}

                <table class="table table-responsive">

                    <tr >
                        <h3 class="box-title">{{Lang::get('Company Details')}}</h3>
                        <button type="submit" class="btn btn-primary pull-right" id="submit"  style="margin-top:-40px;"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
                    </tr>

                    <tr>

                        <td><b>{!! Form::label('company',Lang::get('message.company-name'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">


                                {!! Form::text('company',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('company_email',Lang::get('message.company-email'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('company_email') ? 'has-error' : '' }}">


                                {!! Form::text('company_email',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>
                      <tr>

                        <td><b>{!! Form::label('title',Lang::get('message.app-title')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">


                                {!! Form::text('title',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('website',Lang::get('message.website'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('website') ? 'has-error' : '' }}">


                                {!! Form::text('website',null,['class' => 'form-control']) !!}
                               

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('phone',Lang::get('message.phone'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">


                                {!! Form::text('phone',null,['class' => 'form-control']) !!}
                               

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('address',Lang::get('message.address'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">

                                {!! Form::textarea('address',null,['class' => 'form-control','size' => '128x10','id'=>'address']) !!}
                               
                            </div>
                        </td>

                    </tr>
                     <tr>

                        <td><b>{!! Form::label('City',Lang::get('message.city')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">


                                {!! Form::text('city',null,['class' => 'form-control']) !!}
                                

                            </div>
                        </td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('country',Lang::get('message.country') ,['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">


                                <!-- {!! Form::text('country',null,['class' => 'form-control']) !!} -->
                                <!-- <p><i> {{Lang::get('message.country')}}</i> </p> -->
                                  <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>

                        {!! Form::select('country',['Choose'=>'Choose',''=>$countries],null,['class' => 'form-control','id'=>'country','onChange'=>'getCountryAttr(this.value);']) !!}

                    </div>

                           
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('state',Lang::get('message.state') ,['class'=>'required']) !!}</b></td>
                        <td>
                        <select name="state" id="state-list" class="form-control">
                                @if(count($set->state)>0)
                            <option value="">{{$set->state}}</option>
                            @endif
                            <option value="">Choose A Country</option>

                        </select>
                        </td>
                    </tr>

                      <tr>
                     
                        <td><b>{!! Form::label('logo',Lang::get('message.admin-logo')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">

                                {!! Form::file('admin-logo') !!}
                                <p><i> {{Lang::get('message.enter-the-admin-panel-logo')}}</i> </p>
                                @if($set->admin_logo) 
                                <img src='{{ asset("images/admin-logo/$set->admin_logo")}}' class="img-thumbnail" style="height: 50;">
                                @endif
                            </div>
                        </td>
                       
                    </tr>

                     <tr>

                        <td><b>{!! Form::label('icon',Lang::get('message.fav-icon')) !!}</b></td>

                        <td>
                            <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">

                                {!! Form::file('fav-icon') !!}
                                <p><i> {{Lang::get('message.enter-the-favicon')}}</i> </p>
                                @if($set->fav_icon) 
                                <img src='{{asset("images/favicon/$set->fav_icon")}}' class="img-thumbnail" style="height: 50;">
                                @endif
                            </div>
                        </td>
                       
                    </tr>

                     <tr>

                        <td><b>{!! Form::label('favicon_title',Lang::get('message.fav-title')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('favicon_title') ? 'has-error' : '' }}">


                                {!! Form::text('favicon_title',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('logo',Lang::get('message.client-logo')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">

                                {!! Form::file('logo') !!}
                                <p><i> {{Lang::get('message.enter-the-company-logo')}}</i> </p>
                                @if($set->logo) 
                                <img src='{{asset("cart/img/logo/$set->logo")}}' class="img-thumbnail" style="height: 50;">
                                @endif
                            </div>
                        </td>
                        {!! Form::close() !!}
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
     function getCountryAttr(val) {
        getState(val);
    
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
</script>
@stop