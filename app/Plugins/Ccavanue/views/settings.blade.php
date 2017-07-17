@extends('themes.default1.layouts.master')
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="box">

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
                <i class="fa fa-ban"></i>
                <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
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

            <div class="box-body">
                {!! Form::model($ccavanue,['url'=>'payment-gateway/ccavanue','method'=>'patch','files'=>true]) !!}

                <table class="table">

                    <tr>
                        <td><h3 class="box-title">Ccavanue Settings</h3></td>
                        <td>{!! Form::submit(Lang::get('message.update'),['class'=>'btn btn-primary pull-right'])!!}</td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('merchant_id','Merchant Id',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('merchant_id') ? 'has-error' : '' }}">


                                {!! Form::text('merchant_id',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('access_code','Access Code',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('access_code') ? 'has-error' : '' }}">


                                {!! Form::text('access_code',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('working_key','Working Key',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('working_key') ? 'has-error' : '' }}">


                                {!! Form::text('working_key',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('redirect_url','Redirect URL',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('redirect_url') ? 'has-error' : '' }}">


                                {!! Form::text('redirect_url',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('cancel_url','Cancel URL',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('cancel_url') ? 'has-error' : '' }}">


                                {!! Form::text('cancel_url',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('ccavanue_url','Ccavanue URL',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('ccavanue_url') ? 'has-error' : '' }}">


                                {!! Form::text('ccavanue_url',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('currencies','Supported Currencies',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('currencies') ? 'has-error' : '' }}">


                                {!! Form::text('currencies',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>

                   
                    
                    {!! Form::close() !!}
                </table>



            </div>

        </div>
        <!-- /.box -->

    </div>


</div>

@stop