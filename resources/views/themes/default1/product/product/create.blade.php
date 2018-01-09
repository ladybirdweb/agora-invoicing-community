@extends('themes.default1.layouts.master')
@section('content')
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
        {!! Form::open(['url'=>'products','method'=>'post','files' => true]) !!}
        <h4>{{Lang::get('message.product')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">



                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">{{Lang::get('message.details')}}</a></li>   
                        <li><a href="#tab_2" data-toggle="tab">{{Lang::get('message.price')}}</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Plans</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">

                                <div class="col-md-3 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <!-- first name -->
                                    {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                                    {!! Form::text('name',null,['class' => 'form-control']) !!}

                                </div>
                              
                                <div class="col-md-3 form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('type',Lang::get('message.type'),['class'=>'required']) !!}
                                    
                                   <select name="type" value="Select a Product" class="form-control">
                         <option value="">Select Plan Type</option>
                        @foreach ($types as $key=>$value)


                        <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>

                                </div>

                                <div class="col-md-3 form-group {{ $errors->has('group') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('group',Lang::get('message.group')) !!}
                                   <!--  $products = ProductName::pluck('name');
                                  -->
                                  <select name="group" value="Select a Product" class="form-control">
                         <option value="">Select Product Group</option>
                        @foreach ($products as $key=>$value)


                        <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                                    <!-- {!! Form::select('name',$products,null,['class' => 'form-control']) !!} -->
                                 

                                </div>
                                <div class="col-md-3 form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('category',Lang::get('message.category')) !!}
                                    <select name="category" value="Select a Product" class="form-control">
                         <option value="">Select Category</option>
                        @foreach ($categories as $key=>$value)


                        <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                                   <!--  {!! Form::select('category',['product'=>'Product','addon'=>'Addon','service'=>'Service'],null,['class' => 'form-control']) !!} -->

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                                    <script>
tinymce.init({
    selector: 'textarea',
    plugins: "code",
    toolbar: "code",
    menubar: "tools"
});
                                    </script>


                                    {!! Form::label('description',Lang::get('message.description')) !!}
                                    {!! Form::textarea('description',null,['class' => 'form-control','id'=>'textarea']) !!}

                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li>
                                           
                                        </li>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <li>
                                                    <div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
                                                        <!-- first name -->
                                                        {!! Form::label('file',Lang::get('message.file')) !!}
                                                        {!! Form::file('file') !!}

                                                    </div>  
                                                </li>
                                                <li>
                                                    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                        {!! Form::label('image',Lang::get('message.image')) !!}
                                                        {!! Form::file('image') !!}

                                                    </div>
                                                </li>
                                                <li>
                                                   
                                                </li>
                                            </div>
                                            <div class="col-md-2">
                                                <p>
                                                    <b>OR</b>
                                                </p>
                                            </div>
                                            <div class="col-md-5">
                                                <li>
                                                    <div class="form-group {{ $errors->has('github_owner') ? 'has-error' : '' }}">
                                                        <!-- first name -->
                                                        {!! Form::label('github_owner',Lang::get('message.github-owner')) !!}
                                                        {!! Form::text('github_owner',null,['class'=>'form-control']) !!}

                                                    </div>  
                                                </li>
                                                <li>
                                                    <div class="form-group {{ $errors->has('github_repository') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                        {!! Form::label('github_repository',Lang::get('message.github-repository-name')) !!}
                                                        {!! Form::text('github_repository',null,['class'=>'form-control']) !!}

                                                    </div>
                                                </li>
                                            </div>
                                        </div>

                                        <li>
                                            <div class="form-group {{ $errors->has('require_domain') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('require_domain',Lang::get('message.require_domain')) !!}
                                                <p>{!! Form::checkbox('require_domain',1) !!} {{Lang::get('message.tick-to-show-domain-registration-options')}}</p>

                                            </div>
                                        </li>
                                         <li>
                                            <div class="row">

                                              

                                                <div class="col-md-8 form-group {{ $errors->has('tax_apply') ? 'has-error' : '' }}">
                                                    <!-- last name -->
                                                    {!! Form::label('Status',Lang::get('Status')) !!}
                                                    <p>{!! Form::checkbox('Status',1) !!}  {{Lang::get('Selling')}}</p>

                                                </div>

                                            </div>
                                        </li>

                                        <li>
                                            <div class="form-group {{ $errors->has('shoping_cart_link') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('shoping_cart_link',Lang::get('message.shoping-cart-link')) !!}
                                                {!! Form::text('shoping_cart_link',$cartUrl,['class'=>'form-control']) !!}

                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                    
                                       


                                    </ul>
                                </div>
                                <div class="col-md-6">
                                   
                                </div>

                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <table class="table table-responsive">

                                <tr>
                                    <td><b>{!! Form::label('subscription',Lang::get('message.subscription')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('subscription') ? 'has-error' : '' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! Form::hidden('subscription',0) !!}
                                                    {!! Form::checkbox('subscription',1,true) !!}
                                                    {!! Form::label('subscription',Lang::get('message.subscription')) !!}
                                                </div>

                                                <div class="col-md-6">
                                                    {!! Form::hidden('deny_after_subscription',0) !!}
                                                    {!! Form::checkbox('deny_after_subscription',1,true) !!}
                                                    {!! Form::label('deny_after_subscription',Lang::get('message.deny_after_subscription')) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>{!! Form::label('currency',Lang::get('message.currency')) !!}</b></td>
                                    <td>

                                        <table class="table table-responsive">
                                            <tr>
                                                <th></th>
                                                <th>{{Lang::get('message.regular-price')}}</th>
                                                <th>{{Lang::get('message.sales-price')}}</th>
                                            </tr>
                                            
                                            @foreach($currency as $key=>$value)
                                            
                                            <tr>
                                                <td>

                                                    <input type="hidden" name="currency[{{$key}}]" value="{{$key}}">
                                                    <p>{{$value}}</p>

                                                </td>

                                                <td>

                                                    {!! Form::text('price[$key]',null) !!}

                                                </td>
                                                <td>

                                                    {!! Form::text('sales_price[$key]',null) !!}

                                                </td>
                                            </tr>
                                            @endforeach


                                        </table>

                                    </td>
                                </tr>

                                <tr>
                                    <td><b>{!! Form::label('multiple_qty',Lang::get('message.allow-multiple-quantities')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('multiple_qty') ? 'has-error' : '' }}">

                                            <p>{!! Form::checkbox('multiple_qty',1) !!}  {{Lang::get('message.tick-this-box-to-allow-customers-to-specify-if-they-want-more-than-1-of-this-item-when-ordering')}} </p>

                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>{!! Form::label('auto-terminate',Lang::get('message.auto-terminate')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('auto_terminate') ? 'has-error' : '' }}">

                                            <p>{!! Form::text('auto_terminate',null) !!} {{Lang::get('message.enter-the-number-of-days-after-activation-to-automatically-terminate')}}</p>

                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>{!! Form::label('tax',Lang::get('message.taxes')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('taxes') ? 'has-error' : '' }}">
                                            <div class="row">
                                                @forelse($taxes as $key=>$value)
                                                <div class="col-md-2">
                                                    <b>{{ucfirst($value)}} {!! Form::radio('tax',$key) !!}</b>
                                                </div>
                                                @empty 
                                                <p>No taxes</p>
                                                @endforelse
                                            </div>

                                        </div>
                                    </td>
                                </tr>

                            </table>
                        </div>

                        <!-- /.tab-pane -->

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <h3>
                                Plans &nbsp;<a href="{{url('plans/create')}}" class="btn btn-default">Add new</a>
                            </h3>
                            
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->

            </div>


        </div>

    </div>

</div>


{!! Form::close() !!}
@stop