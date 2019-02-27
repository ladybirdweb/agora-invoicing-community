@extends('themes.default1.layouts.master')
@section('title')
Payment Gateway
@stop
@section('content-header')
<h1>
Paypal
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li><a href="{{url('plugin')}}">Plugins</a></li>
        <li class="active">Paypal</li>
      </ol>
@stop
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="box box-primary">

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
                              <div class="box-footer">
                       
                        <div class="pull-right">

                            <?php
                            $status=0;
                            $cont = new \App\Plugins\Paypal\Model\Paypal();
                            $recentselected = $cont->find(1)->pluck('paypal_url')->first();
                            ?>
                            <div class="btn-group {{$status == '0' ? 'locked_active unlocked_inactive' : 'locked_inactive unlocked_active'}}" id="toggle_event" style="margin-top:-8px">
                                <button type="button"  class="btn {{$status == '1' ? 'btn-sm btn-info' : 'btn-sm btn-default'}}">Live Mode</button>
                                <button type="button"  class="btn  {{$status == '0' ? 'btn-sm btn-info' : 'btn-sm btn-default'}}" >Test Mode</button>
                            </div>
                            @if($recentselected == 'https://www.sandbox.paypal.com/cgi-bin/webscr')
                           <div>Test Mode Active</div>
                            @elseif ($recentselected == 'https://www.paypal.com/cgi-bin/webscr')
                             <div>Live Mode Active</div>
                            @endif
                          
                        </div>
                    </div>  
                    
                    <tr>

                        <td><b>{!! Form::label('business','Business Email/ID',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('business') ? 'has-error' : '' }}">


                                {!! Form::text('business',null,['class' => 'form-control','id'=>'business']) !!}
        


                            </div>
                        </td>

                    </tr>
                    
                    <tr class="hidden">

                        <td><b>{!! Form::label('cmd','CMD',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('cmd') ? 'has-error' : '' }}">


                                {!! Form::text('cmd',null,['class' => 'form-control','id'=>'cmd']) !!}
        


                            </div>
                        </td>

                    </tr>
                    <tr class="hidden">

                        <td><b>{!! Form::label('currencies','Supported Currencies',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('currencies') ? 'has-error' : '' }}">


                                {!! Form::text('currencies',null,['class' => 'form-control','id'=>'currency']) !!}
        


                            </div>
                        </td>

                    </tr>
                    
                    <tr class="hidden">

                        <td><b>{!! Form::label('paypal_url','Paypal URL',['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('paypal_url') ? 'has-error' : '' }}">
                           {!! Form::text('paypal_url',null,['class' => 'form-control','id'=>'paypal_url']) !!}
        


                            </div>
                        </td>

                    </tr>
                    
               <!--      <tr>

                        <td><b>{!! Form::label('image_url','Image URL') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('image_url') ? 'has-error' : '' }}">


                                {!! Form::text('image_url',null,['class' => 'form-control']) !!}
        


                            </div>
                        </td>

                    </tr> -->
                    <tr class="hidden">

                        <td><b>{!! Form::label('success_url','Success URL') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('success_url') ? 'has-error' : '' }}">

                                 <input type="text" name="success_url" class="form-control" id="success_url" value={{url('/payment-gateway/paypal/response')}}>
        


                            </div>
                        </td>

                    </tr>
                    <tr class="hidden">

                        <td><b>{!! Form::label('cancel_url','Cancel URL') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('cancel_url') ? 'has-error' : '' }}">


                        <input type="text" name="cancel_url" class="form-control" id="success_url" value={{url('payment-gateway/paypal/cancel')}}>


                            </div>
                        </td>

                    </tr>
                    <tr class="hidden">

                        <td><b>{!! Form::label('notify_url','Notify URL') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('notify_url') ? 'has-error' : '' }}">

                          <input type="text" name="notify_url" class="form-control" id="notify_url" value={{url('payment-gateway/paypal/notify')}}>


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
             $(document).ready(function(){
             var selected =   $('#paypal_url').val();
               $('#toggle_event').trigger('click'); 
             })
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
                      $('#cmd').val('_xclick');
                      $('#paypal_url').val('https://www.sandbox.paypal.com/cgi-bin/webscr');
                      $('#currency').val('USD,INR,AUD,BRL,CAD,CZK,DKK,EUR,HKD,RUB,HUF,ILS,JPY,MYR,MXN,TWD,NZD,NOK,PHP,PLN,GBP,SGD,SEK,CHF,THB');
                      

                      }
                      else{

                      $('#cmd').val('_xclick');
                      $('#paypal_url').val('https://www.paypal.com/cgi-bin/webscr');
                      $('#currency').val('USD,INR,AUD,BRL,CAD,CZK,DKK,EUR,HKD,RUB,HUF,ILS,JPY,MYR,MXN,TWD,NZD,NOK,PHP,PLN,GBP,SGD,SEK,CHF,THB');
                      }
                 
                     });
</script>

@stop