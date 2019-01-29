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
                {!! Form::model($paypal,['url'=>'payment-gateway/paypal','method'=>'patch','files'=>true]) !!}

                <table class="table">

                    <tr>
                        <td><h3 class="box-title">Paypal Settings</h3></td>
                        <td>{!! Form::submit(Lang::get('message.update'),['class'=>'btn btn-primary pull-right'])!!}</td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('business','Business Email/ID',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('business') ? 'has-error' : '' }}">


                                {!! Form::text('business',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('cmd','CMD',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('cmd') ? 'has-error' : '' }}">


                                {!! Form::text('cmd',null,['class' => 'form-control']) !!}
        


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
                    
                    <tr>

                        <td><b>{!! Form::label('paypal_url','Paypal URL',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('paypal_url') ? 'has-error' : '' }}">


                                {!! Form::text('paypal_url',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('image_url','Image URL') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('image_url') ? 'has-error' : '' }}">


                                {!! Form::text('image_url',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('success_url','Success URL') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('success_url') ? 'has-error' : '' }}">


                                {!! Form::text('success_url',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('cancel_url','Cancel URL') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('cancel_url') ? 'has-error' : '' }}">


                                {!! Form::text('cancel_url',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('notify_url','Notify URL') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('notify_url') ? 'has-error' : '' }}">


                                {!! Form::text('notify_url',null,['class' => 'form-control']) !!}
        


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