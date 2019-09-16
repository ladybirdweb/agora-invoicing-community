@extends('themes.default1.layouts.master')
@section('title')
Settings
@stop
@section('content-header')
<h1>
Application Settings
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active">Settings</li>
      </ol>
@stop
@section('content')
<style>
    .settingdivblue:hover {
        border: 5px double #3C8DBC;
    }
    .settingdivblue a:hover {
        /*            color: #61C5FF;*/
        /*            background-color: darkgrey;*/
    }
    .settingdivblue a {
        color: #3A83AD;
    }
    .settingiconblue p {
        text-align: center;
        font-size: 17px;
        word-wrap: break-word;
        font-variant: small-caps;
        font-weight: bold;
        line-height: 30px;
    }
    .settingdivblue {
        width: 70px;
        height: 70px;
        margin: 0 auto;
        text-align: center;
        border: 5px solid #C4D8E4;
        border-radius: 100%;
        padding-top: 5px;
    }
</style>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Settings</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <!--/.col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('settings/system') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-laptop fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title">System Settings</p>
                    </div>
                </div>
               

                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('job-scheduler')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-tachometer fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >{!! Lang::get('message.cron') !!}</p>
                    </div>
                </div>

              

                 <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('license-type')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-file fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >License Type</p>
                    </div>
                </div>

                 <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('license-permissions')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-sitemap fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title">License Permissions</p>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('file-storage')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-file-archive-o fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >File Storage</p>
                    </div>
                </div>

                  <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('plugin') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-credit-card fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Payment Gateways</p>
                    </div>
                </div>

                 <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('system-managers')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-users"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >System Managers</p>
                    </div>
                </div>

                 <!--/.col-md-2-->
                  
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- ./box-body -->
</div>
<!-- /.box -->

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{Lang::get('message.log_setting')}}</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <!--/.col-md-2-->
               
               

                 <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('log-viewer') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-bug fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Error Log</p>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('settings/activitylog') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-history fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Activity Log</p>
                    </div>
                </div>

                 <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('settings/maillog') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-envelope-square fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Email Log</p>
                    </div>
                </div>

          <!--        <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('settings/maillog') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-archive fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >{{Lang::get('message.cleanup_log')}}</p>
                    </div>
                </div> -->


               

                 <!--/.col-md-2-->
                  
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- ./box-body -->
</div>



<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Email</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <!--col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('settings/email') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-envelope fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Email Settings</p>
                    </div>
                </div>
                <!--/.col-md-2-->
                 <!--col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('settings/template') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-folder fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Template Settings</p>
                    </div>
                </div>
                <!--/.col-md-2-->
                <!--/.col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('templates')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-file-text fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Templates</p>
                    </div>
                </div>
                <!--/.col-md-2-->
               
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- ./box-body -->
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Api</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <!--col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('github') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-github fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Github</p>
                    </div>
                </div>
                <!--/.col-md-2-->
                <!--col-md-2-->
                <?php
                $mailchimpStatus = \App\Model\Common\StatusSetting::first()->value('mailchimp_status');
                ?>
                @if($mailchimpStatus ==1)
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('mailchimp') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-mail-forward fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Mail Chimp</p>
                    </div>
                </div>
                @endif
                <!--/.col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('apikeys') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-cogs"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Api Keys</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- ./box-body -->
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Common</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <!--/.col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('tax')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-dollar fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Tax</p>
                    </div>
                </div>
                <!--/.col-md-2-->
                <!--/.col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('currency')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-money fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Currency</p>
                    </div>
                </div>
                <!--/.col-md-2-->
                 <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{url('get-country')}}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-flag-checkered"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Country List</p>
                    </div>
                </div>
                
               
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- ./box-body -->
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Widgets</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <!--/.col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('widgets') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-list-alt fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Footer</p>
                    </div>
                </div>
                <!--/.col-md-2-->                                        
                <!--col-md-2-->
                <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('social-media') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-cubes fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Social Media</p>
                    </div>
                </div>
                <!--/.col-md-2--> 

                    <div class="col-md-2 col-sm-6">
                    <div class="settingiconblue">
                        <div class="settingdivblue">
                            <a href="{{ url('chat') }}">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-wechat fa-stack-1x"></i>
                                </span>
                            </a>
                        </div>
                        <p class="box-title" >Analytics/Custom Code</p>
                    </div>
                </div>                                       
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- ./box-body -->
</div>


@stop