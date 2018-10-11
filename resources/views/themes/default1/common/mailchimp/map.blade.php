@extends('themes.default1.layouts.master')
@section('content-header')
<h1>
Mailchimp Mapping
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="{{url('settings')}}">Settings</a></li>
        <li><a href="{{url('mailchimp')}}"><i class="fa fa-dashboard"></i> Mailchimp Setting</a></li>
        <li class="active">Mailchimp Mapping</li>
        </ol>
@stop
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="box box-primary">

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
                <button type="submit" class="btn btn-primary pull-right" id="submit" ><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
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
                    <tr>
                        <td>{{Lang::get('message.app-title')}}</td>
                        <td>{!! Form::select('source',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>


                </table>
                {!! Form::close() !!}


            </div>



        </div>
        <!-- /.box -->
         <div class="box box-primary">

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
                {!! Form::model($model2,['url'=>'mailchimp-group/mapping','method'=>'patch','files'=>true]) !!}
                <button type="submit" class="btn btn-primary pull-right" id="submit" ><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
               </div>   
              
            <div class="box-body">
                <table class="table table-hover">
                    <tr>
                        <th>{{Lang::get('message.agora-products')}}</th>
                        <th>{{Lang::get('message.mailchimp-product')}}</th>
                    </tr>

                    <tr>
                    <td>{!! Form::select('row[1][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[0]['agora_product_id']],['class'=>'form-control']) !!}</td>
                        <td>{!! Form::select('row[1][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[0]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                    <td>{!! Form::select('row[2][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[1]['agora_product_id']],['class'=>'form-control']) !!}</td>
                        <td>{!! Form::select('row[2][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[1]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                      <td>{!! Form::select('row[3][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[2]['agora_product_id']],['class'=>'form-control']) !!}</td>
                       <td>{!! Form::select('row[3][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[2]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{!! Form::select('row[4][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[3]['agora_product_id']],['class'=>'form-control']) !!}</td>
                        <td>{!! Form::select('row[4][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[3]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                          <td>{!! Form::select('row[5][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[4]['agora_product_id']],['class'=>'form-control']) !!}</td>
                        <td>{!! Form::select('row[5][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[4]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                          <td>{!! Form::select('row[6][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[5]['agora_product_id']],['class'=>'form-control']) !!}</td>
                         <td>{!! Form::select('row[6][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[5]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{!! Form::select('row[7][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[6]['agora_product_id']],['class'=>'form-control']) !!}</td>
                        <td>{!! Form::select('row[7][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[6]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                          <td>{!! Form::select('row[8][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[7]['agora_product_id']],['class'=>'form-control']) !!}</td>
                         <td>{!! Form::select('row[8][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[7]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                         <td>{!! Form::select('row[9][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[8]['agora_product_id']],['class'=>'form-control']) !!}</td>
                         <td>{!! Form::select('row[9][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[8]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                     <tr>
                         <td>{!! Form::select('row[10][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[9]['agora_product_id']],['class'=>'form-control']) !!}</td>
                         <td>{!! Form::select('row[10][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[9]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{!! Form::select('row[11][]',[''=>'Select','Fields'=>$agoraProducts],[$relations[10]['agora_product_id']],['class'=>'form-control']) !!}</td>
                         <td>{!! Form::select('row[11][]',[''=>'Select','Fields'=>$product_group_fields],[$relations[10]['mailchimp_group_cat_id']],['class'=>'form-control']) !!}</td>
                    </tr>
                  

                </table>
                {!! Form::close() !!}


            </div>

            

        </div>

    </div>


</div>

@stop