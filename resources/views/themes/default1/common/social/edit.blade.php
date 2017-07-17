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

            <div class="box-body no-padding">
                {!! Form::model($social,['url'=>'social-media/'.$social->id,'method'=>'patch']) !!}

                <table class="table table-condensed">

                    <tr>
                        <td><h3 class="box-title">{{Lang::get('message.social-media')}}</h3></td>
                        <td>{!! Form::submit(Lang::get('message.update'),['class'=>'btn btn-primary pull-right'])!!}</td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">


                                {!! Form::text('name',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-the-name-of-the-social-media')}}</i> </p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('class',Lang::get('message.class'),['class'=>'required']) !!}</b></td>
                        <td>

                            <div class="form-group {{ $errors->has('class') ? 'has-error' : '' }}">


                                {!! Form::text('class',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-the-css-class-of-the-social-media')}}</i> </p>



                        </td>

                    </tr>
                    <tr>
                        <td><b>{!! Form::label('fa_class',Lang::get('message.fa-class'),['class'=>'required']) !!}</b></td>
                        <td>

                            <div class="form-group {{ $errors->has('fa_class') ? 'has-error' : '' }}">


                                {!! Form::text('fa_class',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-the-fa-class-of-the-social-media')}}</i> </p>



                        </td>
                    </tr>
                    <tr>

                        <td><b>{!! Form::label('link',Lang::get('message.link'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('link') ? 'has-error' : '' }}">


                                {!! Form::text('link',null,['class' => 'form-control']) !!}
                                <p><i> {{Lang::get('message.enter-the-link-of-the-social-media')}}</i> </p>


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