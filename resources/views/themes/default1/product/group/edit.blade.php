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
                {!! Form::model($group,['url'=>'groups/'.$group->id,'method'=>'patch']) !!}

                <div class="box-header">
                    <h3 class="box-title">{{Lang::get('message.groups')}}</h3>
                    {!! Form::submit(Lang::get('message.save'),['class'=>'btn btn-primary pull-right'])!!}
                </div>

                <table class="table table-condensed">



                    <tr>

                        <td><b>{!! Form::label('company',Lang::get('message.name'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::text('name',null,['class' => 'form-control']) !!}
                                    </div>

                                </div>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('type',Lang::get('message.headline')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('headline') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::text('headline',null,['class' => 'form-control']) !!}
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('tagline',Lang::get('message.tagline')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('tagline') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::text('tagline',null,['class' => 'form-control']) !!}
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('features',Lang::get('message.features'),['class'=>'required']) !!}</b></td>
                        <td>

                            <div class="form-group {{ $errors->has('features.0.name') ? 'has-error' : '' }}">



                                <div class="input_fields_wrap ">
                                    <div class='row form-group'>

                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <input type="text" name="features[][name]" class="form-control" value="{{$features[0]->features}}">
                                            </div>
                                            @for($i=1;$i<count($features);$i++)
                                                <div class="form-group">
                                                    <input type="text" name="features[][name]" class="form-control" value="{{$features[$i]->features}}">
                                                    <a href="#" class="remove_field">Remove</a>
                                                </div>
                                                @endfor
                                        </div>
                                        <div class="col-md-6">
                                            <a href="#" class="add_field_button btn btn-primary">Add More Features</a>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            </div>
                        </td>

                    </tr>


                    <tr>

                        <td><b>{!! Form::label('hidden',Lang::get('message.hidden')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('hidden') ? 'has-error' : '' }}">

                                <?php
                                $hidden = $group->hidden;
                                if ($hidden == 1) {
                                    $value = true;
                                } else {
                                    $value = '';
                                }
                                ?>

                                <p>{!! Form::checkbox('hidden',1,$value) !!}  {{Lang::get('message.check-this-box-if-this-is-a-hidden-group')}}</p>


                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td>

                            <div class="box-header">
                                <h3 class="box-title">{{Lang::get('message.configurable-options')}}</h3>
                            </div>

                        </td>

                        <td>

                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('title',Lang::get('message.title'),['class'=>'required']) !!}</b></td>

                        <td>
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::text('title',$title,['class' => 'form-control']) !!}
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('type',Lang::get('message.type'),['class'=>'required']) !!}</b></td>

                        <td>
                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::select('type',[''=>'select a type','1'=>'dropdown','2'=>'radio'],$type,['class' => 'form-control','id'=>'type']) !!}
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('options',Lang::get('message.options')) !!}</b></td>

                        <td>
                            <div class='row form-group'>
                                <div class="col-md-4">
                                    <b>{!! Form::label('value',Lang::get('message.value'),['class'=>'required']) !!}</b>

                                </div>
                                <div class="col-md-4">
                                    <b>{!! Form::label('price',Lang::get('message.price'),['class'=>'required']) !!}</b>

                                </div>
                            </div>




                            <div class="input_fields_wrap2">


                                <div class='row'>

                                    <div class="col-md-4 form-group">

                                        <input type="text" name="value[][name]" class="form-control" value="{{ $configs[0]->options }}">
                                    </div>
                                    <div class="col-md-4 form-group">

                                        <input type="text" name="price[][name]" class="form-control" value="{{ $configs[0]->price }}">
                                    </div>
                                    <div class="col-md-4 form-group">

                                        <a href="#" class="add_field_button2 btn btn-primary">Add More Options</a>
                                    </div>
                                    @for($i=1;$i<count($configs); $i++)


                                      <div class="form-group">
                                   
                                        <div class="col-md-4">

                                            <input type="text" name="value[][name]" class="form-control" value="{{ $configs[$i]->options }}">
                                        </div>
                                        <div class="col-md-4">

                                            <input type="text" name="price[][name]" class="form-control" value="{{ $configs[$i]->price }}">
                                        </div>
                                        
                                        <a href="#" class="remove_field2">Remove</a>
                                       
                                        
                                   
                                </div>
                                
                                    @endfor

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
<script src="{{asset('plugins/jQuery/jquery.js')}}"></script>

<script>
$(document).ready(function () {
    var max_fields = 10; //maximum input boxes allowed
    var wrapper = $(".input_fields_wrap"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function (e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="row"><div class="col-md-6 form-group"><input type="text" name="features[][name]" class="form-control" /></div><a href="#" class="remove_field">Remove</a></div>'); //add input box
        }
    });

    $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    })
});
</script>

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
                $(wrapper).append('<div class="row"><div class="col-md-4 form-group"><input type="text" name="value[][name]" class="form-control"/></div><div class="col-md-4 form-group"><input type="text" name="price[][name]" class="form-control" /></div><a href="#" class="remove_field2">Remove</a></div>'); //add input box
            }
        });

        $(wrapper).on("click", ".remove_field2", function (e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });
</script>


