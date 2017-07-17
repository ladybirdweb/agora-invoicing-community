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
        {!! Form::model($tax,['url'=>'tax/'.$tax->id,'method'=>'patch']) !!}
        <h4>{{Lang::get('message.tax')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

                
                

                <div class="row">

                    <div class="col-md-3 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('tax_class') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('tax_class',Lang::get('message.tax_class'),['class'=>'required']) !!}
                        {!! Form::select('tax_classes_id',[$classes],null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('level') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('level',Lang::get('message.level'),['class'=>'required']) !!}
                        {!! Form::text('level',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-3 form-group">
                        <!-- name -->
                        {!! Form::label('status',Lang::get('message.status')) !!}
                        <div class="row">
                            <div class="col-md-6 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                                <!-- name -->
                                {!! Form::label('active',Lang::get('message.active')) !!}
                                {!! Form::radio('active',1) !!}

                            </div>
                            <div class="col-md-6 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                                <!-- name -->
                                {!! Form::label('active',Lang::get('message.inactive')) !!}
                                {!! Form::radio('active',0) !!}

                            </div>
                        </div>

                    </div>


                </div>

                <div class="row">

                    <div class="col-md-3 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('country',Lang::get('message.country')) !!}
                        <?php $countries = \App\Model\Common\Country::lists('country_name', 'country_code_char2')->toArray(); ?>
                        {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control','onChange'=>'getState(this.value);']) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('state',Lang::get('message.state')) !!}

                        <select name="state" id="state-list" class="form-control">
                            @if(count($state)>0)
                            <option value="{{$state['id']}}">{{$state['name']}}</option>
                            @endif
                            <option value="">Select State</option>
                            @if(count($states)>0)
                            @foreach($states as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                            @endif
                        </select>

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('rate') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('rate',Lang::get('message.rate').' (%)',['class'=>'required']) !!}
                        {!! Form::text('rate',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-3 form-group">
                        <!-- name -->
                        {!! Form::label('compound',Lang::get('message.compound')) !!}
                        <div class="row">
                            <div class="col-md-6 form-group {{ $errors->has('compound') ? 'has-error' : '' }}">
                                <!-- name -->
                                {!! Form::label('compound',Lang::get('message.yes')) !!}
                                {!! Form::radio('compound',1) !!}

                            </div>
                            <div class="col-md-6 form-group {{ $errors->has('compound') ? 'has-error' : '' }}">
                                <!-- name -->
                                {!! Form::label('compound',Lang::get('message.no')) !!}
                                {!! Form::radio('compound',0) !!}

                            </div>
                        </div>

                    </div>


                </div>


            </div>

        </div>

    </div>

</div>

</div>


{!! Form::close() !!}
@stop

<script>
    function getState(val) {


        $.ajax({
            type: "POST",
            url: "{{url('get-state')}}",
            data: 'country_id=' + val,
            success: function (data) {
                $("#state-list").html(data);
            }
        });
    }
</script>