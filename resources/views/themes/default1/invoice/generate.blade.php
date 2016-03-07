@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="box-header">

        <h4>{{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}}, ({{$user->email}}) </h4>

    </div>

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
        <div class="row">

            <div class="col-md-12">

                <div class="col-md-4 form-group">
                    {!! Form::label('address',Lang::get('message.address')) !!}
                    {!! Form::textarea('address',$user->address,['class'=>'form-control']) !!}
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('invoice_date',Lang::get('message.invoice_date')) !!}
                    {!! Form::date('invoice_date',null,['class'=>'form-control']) !!}
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('due_date',Lang::get('message.due_date')) !!}
                    {!! Form::date('due_date',null,['class'=>'form-control']) !!}
                </div>

            </div>
            <div class="col-md-12">
                <div class="input_fields_wrap2">



                    <div class='row form-group'>

                        <div class="col-md-3 ">
                            <b>{!! Form::label('hidden',Lang::get('message.product')) !!}</b>
                            <input type="text" name="product[][name]" class="form-control" value="{{ old('product.0.name') }}">
                        </div>
                        <div class="col-md-3 ">
                            <b>{!! Form::label('hidden',Lang::get('message.quantity')) !!}</b>
                            <input type="text" name="quantity[][name]" class="form-control" value="{{ old('quantity.0.name') }}">
                        </div>
                        <div class="col-md-3 ">
                            <b>{!! Form::label('hidden',Lang::get('message.rate')) !!}</b>
                            <input type="text" name="rate[][name]" class="form-control" value="{{ old('rate.0.name') }}">
                        </div>
                        <div class="col-md-2 ">
                            <b>{!! Form::label('hidden',Lang::get('message.price')) !!}</b>
                            <input type="text" name="price[][name]" class="form-control" value="{{ old('price.0.name') }}">
                        
                        </div>
                        <div class="col-md-1">
                            <br>
                            <a href="#" class="add_field_button2"><i class="fa fa-plus"></i></a>
                        </div>



                    </div>
                </div>
            </div>
        </div>

    </div>

</div>



@stop
<script src="{{asset('plugins/jQuery/jquery.js')}}"></script>



<script>
    $(document).ready(function () {
        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap2"); //Fields wrapper
        var add_button = $(".add_field_button2"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function (e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="row"><div class="col-md-3 form-group"><input type="text" name="product[][name]" class="form-control"/></div><div class="col-md-3 form-group"><input type="text" name="quantity[][name]" class="form-control"/></div><div class="col-md-3 form-group"><input type="text" name="rate[][name]" class="form-control"/></div><div class="col-md-2 form-group"><input type="text" name="price[][name]" class="form-control"/></div><a href="#" class="remove_field"><i class="fa fa-minus"></i></a></div>'); //add input box
            }
        });

        $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });
</script>
