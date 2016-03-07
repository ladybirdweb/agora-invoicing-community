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
                {!! Form::model($promotion,['url'=>'promotions/'.$promotion->id,'method'=>'patch']) !!}

                <div class="box-header">
                    <h3 class="box-title">{{Lang::get('message.promotion')}}</h3>
                    {!! Form::submit(Lang::get('message.save'),['class'=>'btn btn-primary pull-right'])!!}
                </div>

                <table class="table table-condensed">



                    <tr>

                        <td><b>{!! Form::label('company',Lang::get('message.code'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::text('code',null,['class' => 'form-control','id'=>'code']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="btn btn-primary" onclick="getCode();">Generate Code</a>
                                    </div>
                                </div>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('type',Lang::get('message.type')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">


                                {!! Form::select('type',[''=>'Select','Types'=>$type],null,['class' => 'form-control']) !!}


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('uses',Lang::get('message.uses')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('uses') ? 'has-error' : '' }}">


                                {!! Form::text('uses',null,['class' => 'form-control']) !!}


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('applied',Lang::get('message.applied')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('applied') ? 'has-error' : '' }}">

                                {!! Form::select('applied[]',[''=>$product],$selectedProduct,['class' => 'form-control','multiple'=>true]) !!}

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('start',Lang::get('message.start')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('start') ? 'has-error' : '' }}">

                                {!! Form::date('start',$start,['class'=>'form-control']) !!}

                            </div>
                        </td>

                    </tr>


                    <tr>

                        <td><b>{!! Form::label('expiry',Lang::get('message.expiry')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('expiry') ? 'has-error' : '' }}">


                                {!! Form::date('expiry',$expiry,['class' => 'form-control']) !!}

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('lifetime',Lang::get('message.lifetime')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('lifetime') ? 'has-error' : '' }}">


                                <p>{!! Form::checkbox('lifetime',1) !!}  {{Lang::get('message.discounted-pricing-is-applied-even-on-upgrade-and-downgrade-orders-in-the-future-regardless-of-settings-like-max-uses-expiry-etc')}}</p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('once',Lang::get('message.once')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('once') ? 'has-error' : '' }}">


                                <p>{!! Form::checkbox('once',1) !!}  {{Lang::get('message.apply-only-once-per-order')}}</p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('signups',Lang::get('message.signups')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('signups') ? 'has-error' : '' }}">


                                <p>{!! Form::checkbox('signups',1) !!} {{Lang::get('message.apply-to-new-signups-only') }}</p>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('existing_client',Lang::get('message.existing_client')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('existing_client') ? 'has-error' : '' }}">


                                <p>{!! Form::checkbox('existing_client',1) !!}  {{Lang::get('message.apply-to-existing-clients-only')}}</p>


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

<script>
    function getCode() {


        $.ajax({
            type: "POST",
            url: "{{url('get-code')}}",
            success: function (data) {
                $("#code").val(data)
            }
        });
    }
</script>