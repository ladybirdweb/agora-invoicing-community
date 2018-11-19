@extends('themes.default1.layouts.master')
@section('title')
Api Key
@stop
@section('content-header')
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<h1>
API Keys
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
         <li class="active">Api Key</li>
      </ol>
@stop
@section('content')
     <section class="content">
         <div class="row">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Striped Full Width Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tr>
                 
                  <th>Options</th>
                  <th>Status</th>
                   <th>Fields</th>
                  <th style="width: 40px">Action</th>
                </tr>
                <tr>
                  
                  <td>License Manager</td>
                  <td>
                    <label class="switch toggle_event_editing">
                            <input type="hidden" name="module_id" class="module_id" value="1" onchange="licenseManagerSwitch(this)" >
                         <input type="checkbox"  value="1" onchange="licenseManagerSwitch(this)" name="modules_settings" 
                          class="modules_settings_value" >
                          <span class="slider round"></span>
                    </label>
 
                  </td>

                  <td style="display:none";>
                    
                   
                        <!-- last name -->
                        {!! Form::label('rzp_secret',Lang::get('message.rzp_secret')) !!}
                        {!! Form::text('rzp_secret',null,['class' => '']) !!}
                         <br/><br/>
                  
                        <!-- last name -->
                        {!! Form::label('rzp_secret',Lang::get('message.rzp_secret')) !!}
                        {!! Form::text('rzp_secret',null,['class' => '']) !!}

                   
               
            </td>
                  <td><span class="badge bg-red">55%</span></td>
                </tr>
                <tr>
                  
                  <td>Clean database</td>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                    </div>
                  </td>
                    <td>
                    
                   
                        <!-- last name -->
                        {!! Form::label('rzp_secret',Lang::get('message.rzp_secret')) !!}
                        {!! Form::text('rzp_secret',null,['class' => '']) !!}

                  
                        <!-- last name -->
                        {!! Form::label('rzp_secret',Lang::get('message.rzp_secret')) !!}
                        {!! Form::text('rzp_secret',null,['class' => '']) !!}

                   
               
            </td>
                  <td><span class="badge bg-yellow">70%</span></td>
                </tr>
                <tr>
                
                  <td>Cron job running</td>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-light-blue">30%</span></td>
                </tr>
                <tr>
                
                  <td>Fix and squish bugs</td>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-green">90%</span></td>
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
      </div>
  </section>
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
     <div  class="box-body">
 
        {!! Form::model($model,['url'=>'apikeys','method'=>'patch']) !!}
          <tr>
         <h3 class="box-title" style="margin-top:0px;margin-left: 10px;">{{Lang::get('message.system-api')}}</h3>
       <button type="submit" class="btn btn-primary pull-right" id="submit" style="margin-top:-40px;
                        margin-right:15px;"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
         </tr>
        





   

       

            <div class="col-md-12">



                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('rzp_key',Lang::get('message.rzp_key')) !!}
                        {!! Form::text('rzp_key',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('rzp_secret',Lang::get('message.rzp_secret')) !!}
                        {!! Form::text('rzp_secret',null,['class' => 'form-control']) !!}

                    </div>



                </div>



                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('apilayer_key',Lang::get('message.apilayer')) !!}
                        {!! Form::text('apilayer_key',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('client_secret') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('zoho_api_key',Lang::get('message.zoho_key')) !!}
                        {!! Form::text('zoho_api_key',null,['class' => 'form-control']) !!}

                    </div>

                </div>

                 <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('msg91_auth_key',Lang::get('message.msg91key')) !!}
                        {!! Form::text('msg91_auth_key',null,['class' => 'form-control']) !!}

                    </div>

                    

                

                    <div class="col-md-6 form-group {{ $errors->has('twitter_consumer_key') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('twitter_consumer_key',Lang::get('message.twitter_consumer_key')) !!}
                        {!! Form::text('twitter_consumer_key',null,['class' => 'form-control']) !!}

                    </div>

                    

               
                    <div class="col-md-6 form-group {{ $errors->has('twitter_consumer_secret') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('twitter_consumer_secret',Lang::get('message.twitter_consumer_secret')) !!}
                        {!! Form::text('twitter_consumer_secret',null,['class' => 'form-control']) !!}

                    </div>

                    

               

                    <div class="col-md-6 form-group {{ $errors->has('twitter_access_token') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('twitter_access_token',Lang::get('message.twitter_access_token')) !!}
                        {!! Form::text('twitter_access_token',null,['class' => 'form-control']) !!}

                    </div>

                    

               
                    <div class="col-md-6 form-group {{ $errors->has('twitter_access_token_secret') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('access_tooken_secret',Lang::get('message.twitter_access_tooken_secret')) !!}
                        {!! Form::text('access_tooken_secret',null,['class' => 'form-control']) !!}

                    </div>

                    

                </div>




            </div>

       

    </div>

</div>


{!! Form::close() !!}
<script>
   function licenseManagerSwitch(x) {
  console.log($(x).val());
   } 
</script>
@stop