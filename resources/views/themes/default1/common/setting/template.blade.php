@extends('themes.default1.layouts.master')
@section('title')
    Templates
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Template Settings</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">Template</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
<div class="row">

    <div class="col-md-12">
        <div class="card card-primary card-outline">


            <div class="card-body table-responsive">
                {!! Form::model($set,['url'=>'settings/template','method'=>'patch','files'=>true]) !!}

                
                    <tr>
                        <h4 class="box-title">{{Lang::get('Template List')}}</h4>
                    </tr>

                    <tr>

                        <td><b>{!! Form::label('welcome_mail',Lang::get('message.welcome-mail')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('welcome_mail') ? 'has-error' : '' }}">


                                {!! Form::select('welcome_mail',['Templates'=>$template->where('type',1)->pluck('name','id')->toArray()],null,['class'=>'form-control']) !!}
                                <p><i> {{Lang::get('message.choose-welcome-mail-template')}}</i> </p>


                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('order_mail',Lang::get('message.order-mail')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('order_mail') ? 'has-error' : '' }}">


                                {!! Form::select('order_mail',['Templates'=>$template->where('type',7)->pluck('name','id')->toArray()],null,['class'=>'form-control']) !!}
                                <p><i> {{Lang::get('message.choose-order-mail-template')}}</i> </p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('forgot_password',Lang::get('message.forgot-password')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('forgot_password') ? 'has-error' : '' }}">


                                {!! Form::select('forgot_password',['Templates'=>$template->where('type',2)->pluck('name','id')->toArray()],null,['class'=>'form-control']) !!}
                                <p><i> {{Lang::get('message.choose-forgot-password-mail-template')}}</i> </p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('subscription_going_to_end',Lang::get('message.subscription-going-to-end')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('subscription_going_to_end') ? 'has-error' : '' }}">


                                {!! Form::select('subscription_going_to_end',['Templates'=>$template->where('type',4)->pluck('name','id')->toArray()],null,['class'=>'form-control']) !!}
                                <p><i> {{Lang::get('message.choose-subscription-going-to-end-notification-email-template')}}</i> </p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('subscription_over',Lang::get('message.subscription-over')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('subscription_over') ? 'has-error' : '' }}">


                                {!! Form::select('subscription_over',['Templates'=>$template->where('type',5)->pluck('name','id')->toArray()],null,['class'=>'form-control']) !!}
                                <p><i> {{Lang::get('message.choose-mail-template-to-notify-subscription-has-over')}}</i> </p>


                            </div>
                        </td>

                    </tr>
            <!--         <tr>

                        <td><b>{!! Form::label('download','Download') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('download') ? 'has-error' : '' }}">


                                {!! Form::select('download',['Templates'=>$template->where('type',8)->pluck('name','id')->toArray()],null,['class'=>'form-control']) !!}
                                


                            </div>
                        </td>
                        
                    </tr> -->
                    <tr>

                        <td><b>{!! Form::label('invoice',Lang::get('message.invoice')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('invoice') ? 'has-error' : '' }}">


                                {!! Form::select('invoice',['Templates'=>$template->where('type',6)->pluck('name','id')->toArray()],null,['class'=>'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>
                <br>
                <button type="submit" class="btn btn-primary pull-right" id="submit" style="margin-top:-40px;"><i class="fa fa-sync-alt">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
@stop