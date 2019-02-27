@extends('themes.default1.layouts.master')
@section('title')
Create Group
@stop
@section('content-header')
<h1>
Create Product Group
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('groups')}}">Groups</a></li>
        <li class="active">Create Group</li>
      </ol>
@stop
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="box box-primary">

          

            <div class="box-body">
                {!! Form::open(['url'=>'groups']) !!}
        
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
                    <h3 class="box-title">{{Lang::get('message.groups')}}</h3>
                     <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
                </div>

                <table class="table table-condensed">



                    <tr>

                        <td><b>{!! Form::label('company',Lang::get('message.name'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-10">
                                        {!! Form::text('name',null,['class' => 'form-control','id'=>'name']) !!}
                                    </div>

                                </div>


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('headline',Lang::get('message.headline')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('headline') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-10">
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
                                    <div class="col-md-10">
                                        {!! Form::text('tagline',null,['class' => 'form-control']) !!}
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>
  


                    <tr>

                        <td><b>{!! Form::label('hidden',Lang::get('message.hidden')) !!}</b></td>
                        <td>
                             <p>{!! Form::hidden('hidden',0) !!}</p>
                            <div class="form-group {{ $errors->has('hidden') ? 'has-error' : '' }}">

                               
                                <p>{!! Form::checkbox('hidden',1) !!}  {{Lang::get('message.check-this-box-if-this-is-a-hidden-group')}}</p>


                            </div>
                        </td>

                    </tr>

                   

                     <tr>
                          
                        <td><b>{!! Form::label('design',Lang::get('message.select_design')) !!}</b></td>
                        <td>

                           <div class="form-group">
                            @foreach($pricingTemplates as $template)
                            <div class="col-md-4">
                             <img src='{{ asset("images/$template->image")}}' class="img-thumbnail" style="height: 150;">
                             <br/>
                            <input type="radio" name= 'pricing_templates_id' value='{{$template->id}}' style="text-align: center;">
                            {{$template->name}}

                             <br/><br/>
                        </div>
                   
                            @endforeach
                            </div> 
                        </td>

                    </tr>



                   <!--  <tr>

                        <td>

                            <div class="box-header">
                                <h3 class="box-title">{{Lang::get('message.configurable-options')}}</h3>
                            </div>

                        </td>

                        <td>

                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('hidden',Lang::get('message.title'),['class'=>'required']) !!}</b></td>

                        <td>
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::text('title',null,['class' => 'form-control']) !!}
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('hidden',Lang::get('message.type'),['class'=>'required']) !!}</b></td>

                        <td>
                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">

                                <div class='row'>
                                    <div class="col-md-6">
                                        {!! Form::select('type',[''=>'select a type','1'=>'dropdown','2'=>'radio'],null,['class' => 'form-control','id'=>'type']) !!}
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('hidden',Lang::get('message.options')) !!}</b></td>

                        <td>
                            <div class="input_fields_wrap2">
                            
                                    
                                    
                                        <div class='row form-group'>
                                            
                                            <div class="col-md-4 {{ $errors->has('value.0.name') ? 'has-error' : '' }}">
                                                <b>{!! Form::label('hidden',Lang::get('message.value'),['class'=>'required']) !!}</b>
                                                <input type="text" name="value[][name]" class="form-control" value="{{ old('value.0.name') }}">
                                            </div>
                                            <div class="col-md-4 {{ $errors->has('price.0.name') ? 'has-error' : '' }}">
                                                <b>{!! Form::label('hidden',Lang::get('message.price'),['class'=>'required']) !!}</b>
                                                <input type="text" name="price[][name]" class="form-control" value="{{ old('price.0.name') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <br>
                                                <a href="#" class="add_field_button2 btn btn-primary">Add More Options</a>
                                            </div>
                                            


                                        </div>
                            </div>

                        </td>

                    </tr> -->

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
    $(function(){
       $(document).on('input','#name',function(){

         $.ajax ({
            type: "get",
            data : {'url' : this.value},
            url : "{{url('get-group-url')}}",
            success : function(data) {
                $('#groupslug').val(data);
            }
         })
        }); 
    })

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
            $(wrapper).append('<div class="row"><div class="col-md-4 form-group"><input type="text" name="value[][name]" class="form-control"/></div><div class="col-md-4 form-group"><input type="text" name="price[][name]" class="form-control" /></div><a href="#" class="remove_field">Remove</a></div>'); //add input box
        }
    });

    $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    })
});
</script>


