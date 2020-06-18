@extends('themes.default1.layouts.master')
@section('title')
System Setting
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Company Details</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">System Settings</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="box-header">

            </div>

            <div class="card-body">
                {!! Form::model($set,['url'=>'settings/system','method'=>'patch','files'=>true]) !!}
                <div class="row">
                 <div class="col-md-6">
              

                  

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
             
            </div>
            <div class="col-md-6">
            
                    <tr>

                        <td><b>{!! Form::label('country',Lang::get('message.country') ,['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">


                                <!-- {!! Form::text('country',null,['class' => 'form-control']) !!} -->
                                <!-- <p><i> {{Lang::get('message.country')}}</i> </p> -->
                                  <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>

                     <select name="country" value= "Choose" onChange="getCountryAttr(this.value)" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-dropup-auto="false" data-size="10">
                             <option value="">Choose</option>
                           @foreach($countries as $key=>$country)
                              <option value="{{$key}}" <?php  if(in_array($country, $selectedCountry) ) { echo "selected";} ?>>{{$country}}</option>
                          @endforeach
                          </select>


                    </div>

                           
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('state',Lang::get('message.state') ,['class'=>'required']) !!}</b></td>
                        <td>
                        <select name="state" id="state-list" class="form-control">
                                @if($set->state)
                             <option value="{{$state['id']}}">{{$state['name']}}</option>
                            @endif
                            <option value="">Choose</option>
                            @foreach($states as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach

                        </select>
                        </td>
                    </tr>
                    <br>
                        <tr>

                        <td><b>{!! Form::label('default_currency',Lang::get('message.default-currency') ,['class'=>'required']) !!}</b></td>
                        <td>
                             <?php $currencies = \App\Model\Payment\Currency::where('status',1)->pluck('name','code')->toArray(); 
                             ?>
                         <select name="default_currency" value= "Choose"  class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-dropup-auto="false" data-size="10">
                               <option value="">Choose</option>
                           @foreach($currencies as $key=>$currency)
                              <option value="{{$key}}" <?php  if(in_array($currency, $selectedCurrency) ) { echo "selected";} ?>>{{$currency}}</option>
                          @endforeach

                        </select>
                        </td>
                    </tr>
                    <br>
                      <tr>
                     
                        <td><b>{!! Form::label('logo',Lang::get('message.admin-logo')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">

                                {!! Form::file('admin-logo') !!}
                                <p><i> {{Lang::get('message.enter-the-admin-panel-logo')}}</i> </p>
                                @if($set->admin_logo) 
                                <img src='{{ asset("admin/images/$set->admin_logo")}}' class="img-thumbnail" style="height: 50;">
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
                                <img src='{{asset("common/images/$set->fav_icon")}}' class="img-thumbnail" style="height: 50;">
                                @endif
                            </div>
                        </td>
                       
                    </tr>

                     <tr>

                        <td><b>{!! Form::label('favicon_title',Lang::get('message.fav-title-admin')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('favicon_title') ? 'has-error' : '' }}">


                                {!! Form::text('favicon_title',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>

                     <tr>

                        <td><b>{!! Form::label('favicon_title_client',Lang::get('message.fav-title-client')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('favicon_title_client') ? 'has-error' : '' }}">


                                {!! Form::text('favicon_title_client',null,['class' => 'form-control']) !!}
                                


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
                                <img src='{{asset("common/images/$set->logo")}}' class="img-thumbnail" style="height: 50;">
                                @endif
                            </div>
                        </td>

                    </tr>


            </div>

                </div>
                <button type="submit" class="btn btn-primary" id="submit" ><i class="fa fa-sync-alt">&nbsp;</i>{!!Lang::get('message.update')!!}</button>
                {!! Form::close() !!}
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