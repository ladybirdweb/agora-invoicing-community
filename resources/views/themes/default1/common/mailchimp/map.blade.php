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
                {!! Form::model($model,['url'=>'mail-chimp/mapping','method'=>'patch','files'=>true]) !!}
                {!! Form::submit(Lang::get('message.update'),['class'=>'btn btn-primary pull-right'])!!}
            </div>   
            <div class="box-body">
                <table class="table table-hover">
                    <tr>
                        <th>{{Lang::get('message.agora-fields')}}</th>
                        <th>{{Lang::get('message.mailchimp-fields')}}</th>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.first_name')}}</td>
                        <td>{!! Form::select('first_name',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.last_name')}}</td>
                        <td>{!! Form::select('last_name',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.company')}}</td>
                        <td>{!! Form::select('company',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.mobile')}}</td>
                        <td>{!! Form::select('mobile',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.address')}}</td>
                        <td>{!! Form::select('address',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.town')}}</td>
                        <td>{!! Form::select('town',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.state')}}</td>
                        <td>{!! Form::select('state',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.zip')}}</td>
                        <td>{!! Form::select('zip',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.active')}}</td>
                        <td>{!! Form::select('active',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.role')}}</td>
                        <td>{!! Form::select('role',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>


                </table>
                {!! Form::close() !!}


            </div>

        </div>
        <!-- /.box -->

    </div>


</div>

@stop