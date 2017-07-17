@extends('themes.default1.layouts.master')
@section('content')
<div class="row">

    <div class="col-md-12">
        <div class="box">
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

                <table class="table table-condensed">

                    <tr>
                        <td><h3 class="box-title">{{Lang::get('message.company')}}</h3></td>
                        <td>{!! Form::submit(Lang::get('message.update'),['class'=>'btn btn-primary pull-right'])!!}</td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('company',Lang::get('message.company'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">


                                {!! Form::text('company',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-the-company-name')}}</i> </p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('website',Lang::get('message.website')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('website') ? 'has-error' : '' }}">


                                {!! Form::text('website',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-the-company-website')}}</i> </p>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('phone',Lang::get('message.phone')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">


                                {!! Form::text('phone',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-the-company-phone-number')}}</i> </p>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('address',Lang::get('message.address'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">

                                {!! Form::textarea('address',null,['class' => 'form-control','size' => '128x10','id'=>'address']) !!}
                                <p><i> {{Lang::get('message.enter-company-address')}}</i> </p>
                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('logo',Lang::get('message.logo')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">

                                {!! Form::file('logo') !!}
                                <p><i> {{Lang::get('message.enter-the-company-logo')}}</i> </p>
                                @if($set->logo) 
                                <img src="{{asset('cart/img/logo/'.$set->logo)}}" class="img-thumbnail" style="height: 100px;">
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
@stop