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
                {!! Form::model($twilio,['url'=>'sms/twilio','method'=>'patch','files'=>true]) !!}

                <table class="table">

                    <tr>
                        <td><h3 class="box-title">Twilio Settings</h3></td>
                        <td>{!! Form::submit(Lang::get('message.update'),['class'=>'btn btn-primary pull-right'])!!}</td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('account_sid','Account Sid',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('merchant_id') ? 'has-error' : '' }}">


                                {!! Form::text('account_sid',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('auth_token','Auth Token',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('auth_token') ? 'has-error' : '' }}">


                                {!! Form::text('auth_token',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('from_number','From Number',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('from_number') ? 'has-error' : '' }}">


                                {!! Form::text('from_number',null,['class' => 'form-control']) !!}
        


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