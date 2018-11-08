@extends('themes.default1.layouts.master')
@section('title')
Github Setting
@stop
@section('content-header')
<h1>
{!! Lang::get('message.cron-setting') !!}
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li class="active">{!! Lang::get('message.cron-setting') !!}</li>
      </ol>
@stop
<style>
    .select2-container--default .select2-selection--multiple {
    border-radius: 0px;
</style>
@section('content')


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
    <div class="box box-primary">
      <div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
  
               
                    @include('themes.default1.common.cron.cron-new')
               
                <!-- /.tab-pane -->
            
         
        <!-- nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>

</div>




<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
  
     <div class="box box-primary">
        
        <!-- /.box-header -->
       
              {!! Form::open(['url' => 'cron-days', 'method' => 'PATCH','id'=>'Form']) !!}
              <?php 
                   $mailStatus = \App\Model\Common\StatusSetting::pluck('expiry_mail')->first();
                   $activityStatus =\App\Model\Common\StatusSetting::pluck('activity_log_delete')->first();
                  ?>
        <div class="box-header with-border">
         
         <h4>{{Lang::get('message.set_cron_period')}}  
          @if ( $mailStatus || $activityStatus ==1)
          <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
          @else
            <button type="submit" class="btn btn-primary pull-right disabled" id="submit"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
            @endif
        </h4>
     </div>
      <div class="box-body">
          <div class="row">
           
            <!-- /.col -->
            <div class="col-md-6">
             
              <!-- /.form-group -->
              <div class="form-group select2">
                <label>{{Lang::get('message.expiry_mail_sent')}}</label>
                <?php 
                 if (count($selectedDays) > 0) {
                foreach ($selectedDays as $selectedDay) {
                    $saved[$selectedDay->days] = 'true';
                }
               }  else {
                    $saved=[];
                }
                 if (count($saved) > 0) {
                   foreach ($saved as $key => $value) {
                     $savedkey[]=$key;
                   }
                   $saved1=$savedkey?$savedkey:[''];
                       }
                       else{
                        $saved1=[];
                       }
                 ?>
                  
                   @if ($mailStatus == 0)
                    <select id ="days" name="expiryday[]" class="form-control selectpicker"   style="width: 100%; color:black;" disabled>
                      <option value="">{{Lang::get('message.enable_mail_cron')}}</option>
                    </select>
                      @else
                <select id ="days" name="expiryday[]" class="form-control selectpicker"  data-live-search="true" data-live-search-placeholder="Search" multiple="true" style="width: 100%; color:black;">

                    
                    @foreach ($expiryDays as $key=>$value)
                  <option value="{{$key}}" <?php echo (in_array($key, $saved1)) ?  "selected" : "" ;  ?>>{{$value}}</option>
                   @endforeach
                   
                </select>
                @endif
              </div>
              <!-- /.form-group -->
            </div>

             <div class="col-md-6">
              <div class="form-group">
                <label>{{Lang::get('message.log_del_days')}}</label>
                  @if ($activityStatus == 0)
                    <select id ="days" name="expiryday[]" class="form-control selectpicker"   style="width: 100%; color:black;" disabled>
                      <option value="">{{Lang::get('message.enable_activityLog_cron')}}</option>
                    </select>
                      @else
                <select name="logdelday" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" style="width: 100%;">
                    @foreach ($delLogDays as $key=>$value)
                  <option value="{{$key}}" <?php echo (in_array($key, $beforeLogDay)) ?  "selected" : "" ;  ?>>{{$value}}</option>
                  @endforeach
                </select>
                @endif
              </div>
              <!-- /.form-group -->

              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
            {!! Form::close() !!}
        </div>
        <!-- /.box-body -->
       
      </div>
                <!-- /.tab-pane -->
            
         
        <!-- nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>





<script>

</script>
@stop


