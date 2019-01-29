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

                            <div class="box-footer">
                       
                        <div class="pull-right">

                            <?php
                            $status=0;

                            ?>
                            <div class="btn-group {{$status == '0' ? 'locked_active unlocked_inactive' : 'locked_inactive unlocked_active'}}" id="toggle_event" style="margin-top:-8px">
                                <button type="button"  class="btn {{$status == '1' ? 'btn-sm btn-info' : 'btn-sm btn-default'}}">Active Mode</button>
                                <button type="button"  class="btn  {{$status == '0' ? 'btn-sm btn-info' : 'btn-sm btn-default'}}" >Test Mode</button>
                            </div>

                          
                        </div>
                    </div> 



                        <td>{!! Form::submit(Lang::get('message.update'),['class'=>'btn btn-primary pull-right'])!!}</td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('merchant_id','Merchant Id',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('merchant_id') ? 'has-error' : '' }}">


                                {!! Form::text('merchant_id',null,['class' => 'form-control','id'=>'merchant_id']) !!}
        


                            </div>
                        </td>

                    </tr>
                    
                    <tr>

                        <td><b>{!! Form::label('access_code','Access Code',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('access_code') ? 'has-error' : '' }}">


                                {!! Form::text('access_code',null,['class' => 'form-control','id'=>'access_code']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('working_key','Working Key',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('working_key') ? 'has-error' : '' }}">


                                {!! Form::text('working_key',null,['class' => 'form-control','id'=>'working_key']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('redirect_url','Redirect URL',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('redirect_url') ? 'has-error' : '' }}">


                                {!! Form::text('redirect_url',null,['class' => 'form-control','id'=>'redirect_url']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('cancel_url','Cancel URL',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('cancel_url') ? 'has-error' : '' }}">


                                {!! Form::text('cancel_url',null,['class' => 'form-control','id'=>'cancel_url']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('ccavanue_url','Ccavanue URL',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('ccavanue_url') ? 'has-error' : '' }}">


                                {!! Form::text('ccavanue_url',null,['class' => 'form-control','id'=>'ccavanue_url']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('currencies','Supported Currencies',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('currencies') ? 'has-error' : '' }}">


                                {!! Form::text('currencies',null,['class' => 'form-control','id'=>'currencies']) !!}
        


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
<script type="text/javascript">
    
                $('#toggle_event').click(function() {
                    var settings = 1;
                    var settings = 0;
                    if ($(this).hasClass('locked_inactive')) {
                        settings = 0
                    }
                    if ($(this).hasClass('locked_active')) {
                        settings = 1;
                    }
                    
                    /* reverse locking status */
                    $('#toggle_event button').eq(0).toggleClass('btn-info btn-default');
                    $('#toggle_event button').eq(1).toggleClass('btn-default btn-info');
                    $('#toggle_event').toggleClass('locked_active unlocked_inactive');
                    $('#toggle_event').toggleClass('locked_inactive unlocked_active');

                    if(settings==0){
                      $('#merchant_id').val('15675');
                      $('#access_code').val('AVRO01EK31CB19ORBC');
                      $('#working_key').val('38133DD6258ADF5FC02E9C900DCE12C6');
                      $('#redirect_url').val('https://www.faveohelpdesk.com/pay1/response/');
                      $('#cancel_url').val('https://www.faveohelpdesk.com/pay1/response/');
                      $('#ccavanue_url').val('https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction');
                      $('#currencies').val('INR,USD');

                      }
                      else{

                      $('#merchant_id').val('');
                      $('#access_code').val('');
                      $('#working_key').val('');
                      $('#redirect_url').val('');
                      $('#cancel_url').val('');
                      $('#ccavanue_url').val('');
                      $('#currencies').val('');
                      }

                     });
</script>


               

@stop