@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="content-header">
        {!! Form::open(['url'=>'plans','method'=>'post']) !!}
        <h4>{{Lang::get('message.plan')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

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

                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control']) !!}

                    </div>
                     <div class="col-md-4 form-group {{ $errors->has('product') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('product',Lang::get('message.product'),['class'=>'required']) !!}
                        {!! Form::select('product',[''=>'Select','Products'=>$products],null,['class' => 'form-control']) !!}

                    </div>

                   <div class="col-md-4 form-group {{ $errors->has('days') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('days','Periods',['class'=>'required']) !!}
                        {!! Form::select('days',[''=>'Select','Periods'=>$periods],null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-12">
                        <table class="table table-responsive">
                            <tr>
                                <td><b>{!! Form::label('currency',Lang::get('message.currency')) !!}</b></td>
                                <td>

                                    <table class="table table-responsive">
                                        <tr>
                                            <th></th>
                                            <th>Add/Month</th>
                                            <th>Renew/Month</th>

                                        </tr>

                                        @foreach($currency as $key=>$value)
                                        <tr class="form-group {{ $errors->has('add_price.'.$key) ? 'has-error' : '' }}">
                                            <td>

                                                <input type="hidden" name="currency[{{$key}}]" value="{{$key}}">
                                                {!! Form::label('days',$value,['class'=>'required']) !!}

                                            </td>

                                            <td>
                                                
                                                {!! Form::text("add_price[$key]",null,['class' => 'form-control']) !!}
                                               

                                            </td>
                                            
                                            <td>
                                                
                                                {!! Form::text("renew_price[$key]",null,['class' => 'form-control']) !!}
                                               

                                            </td>

                                        </tr>
                                        @endforeach


                                    </table>

                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6 form-group {{ $errors->has('allow_tax') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('allow_tax','Allow Tax') !!}
                        {!! Form::checkbox('allow_tax',1) !!}

                    </div>


                </div>





            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}
@stop