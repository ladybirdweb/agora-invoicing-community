@extends('themes.default1.layouts.front.master')
@section('title')
pricing
@stop
@section('page-header')
Pricing
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Pricing</li>
@stop
@section('main-class') 
main
@stop


@section('content')


<div class="row">
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
    <div class="col-md-2">
        <?php
        $currency = new App\Model\Payment\Currency();
        $currency = $currency->lists('name', 'code')->toArray();
        ?>
        {!! Form::open(['url'=>'home']) !!}
        <div class="col-md-10 form-group {{ $errors->has('currency') ? 'has-error' : '' }}">
            <!-- last name -->
            {!! Form::label('currency',Lang::get('message.currency')) !!}
            {!! Form::select('currency',[''=>'Choose your currency','Currency'=>$currency],null) !!}

        </div>
        <div class="col-md-10 form-group">
            {!! Form::submit('submit',null) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <div class="col-md-10" id="pricing">

        {!! html_entity_decode($template) !!}
    </div>

</div>

@stop
<script>
    function currency(val){
        //alert(val.value);
        $.ajax({
           type: "get",
           url:"{{url('home')}}",
           data:{currency:val.value},
//           success: function () {
//                location.reload();
//            }
        });
    }
</script>
