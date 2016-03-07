@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="content-header">
        {!! Form::model($tax,['url'=>'tax/'.$tax->id,'method'=>'patch']) !!}
        <h4>{{Lang::get('message.tax')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

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

                    <div class="col-md-6 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control']) !!}

                    </div>
                    <div class="col-md-6 form-group {{ $errors->has('level') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('level',Lang::get('message.level'),['class'=>'required']) !!}
                        {!! Form::select('level',[1=>1,2=>2],null,['class' => 'form-control']) !!}

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('country',Lang::get('message.country')) !!}
                        <?php $countries = \App\Model\Common\Country::lists('name', 'id')->toArray(); ?>
                        {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control','onChange'=>'getState(this.value);']) !!}

                    </div>
                    <div class="col-md-4 form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('state',Lang::get('message.state')) !!}

                        <select name="state" id="state-list" class="form-control">
                            <?php
                            if (App\Model\Common\State::where('id', $tax->state)->first()) {
                                $state = App\Model\Common\State::where('id', $tax->state)->first()->name;
                                echo "<option value=$tax->state>$state</option>";
                            }
                            ?>
                            <option value="">Select a State</option>
                        </select>

                    </div>
                    <div class="col-md-4 form-group {{ $errors->has('rate') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('rate',Lang::get('message.rate').' (%)',['class'=>'required']) !!}
                        {!! Form::text('rate',null,['class' => 'form-control']) !!}

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