@extends('themes.default1.layouts.master')
@section('title')
Create Widget
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Create New Widget</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item"><a href="{{url('widgets')}}"><i class="fa fa-dashboard"></i> All Widgets</a></li>
            <li class="breadcrumb-item active">Create Widget</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
<div class="card card-primary card-outline">


        {!! Form::open(['url'=>'widgets','method'=>'post']) !!}



    <div class="card-body">

        <div class="row">

            <div class="col-md-12">

                

                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control','id'=>'name']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('publish') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('publish',Lang::get('message.publish'),['class'=>'required']) !!}
                        {!! Form::select('publish',[1=>'Yes',0=>'No'],null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('allow_tweets') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('allow_tweets',Lang::get('message.allow_tweets'),['class'=>'required']) !!}
                        {!! Form::select('allow_tweets',[1=>'Yes',0=>'No'],null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('allow_mailchimp') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('allow_mailchimp',Lang::get('message.allow_mailchimp'),['class'=>'required']) !!}
                        {!! Form::select('allow_mailchimp',[1=>'Yes',0=>'No'],null,['class' => 'form-control']) !!}

                    </div>
                    
                    <div class="col-md-4 form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('type',Lang::get('message.type'),['class'=>'required']) !!}
                         <select name="type" value= "Choose" class="form-control">
                             <option value="">Choose</option>
                            <option value="footer1">footer1</option>
                             <option value="footer2">footer2</option>
                              <option value="footer3">footer3</option>
                               <option value="footer4">footer4</option>
                          </select>


                    </div>

                   


                </div>
                
                <div class="row">
                    <div class="col-md-12 form-group">

                        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                        <script>
                          tinymce.init({
                                         selector: 'textarea',
                                         height: 200,
                                         theme: 'modern',
                                         relative_urls: true,
                                         remove_script_host: false,
                                         convert_urls: false,
                                         plugins: [
                                          'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                          'searchreplace wordcount visualblocks visualchars code fullscreen',
                                          'insertdatetime media nonbreaking save table contextmenu directionality',
                                          'emoticons template paste textcolor colorpicker textpattern imagetools'
                                          ],
                                         toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                                          toolbar2: 'print preview media | forecolor backcolor emoticons',
                                          image_advtab: true,
                                          templates: [
                                              {title: 'Test template 1', content: 'Test 1'},
                                              {title: 'Test template 2', content: 'Test 2'}
                                          ],
                                          content_css: [
                                              '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                              '//www.tinymce.com/css/codepen.min.css'
                                          ]
                                    });
</script>


                        {!! Form::label('content',Lang::get('message.content'),['class'=>'required']) !!}
                        {!! Form::textarea('content',null,['class'=>'form-control','id'=>'textarea']) !!}

                    </div>


                </div>

            </div>

        </div>
       <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-save">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>

    </div>

</div>


{!! Form::close() !!}

<script>

    $(document).on('input', '#name', function () {
        
         $.ajax({
            type: "get",
            data:{'url':this.value},
            url: "{{url('get-url')}}",
            success: function (data) {
                $("#url").val(data)
            }
        });
    });
    $(document).on('input', '#name', function () {
        
         $.ajax({
            type: "get",
            data:{'slug':this.value},
            url: "{{url('get-url')}}",
            success: function (data) {
                $("#slug").val(data)
            }
        });
    });
</script>
@stop

