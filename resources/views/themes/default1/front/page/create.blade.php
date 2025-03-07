@extends('themes.default1.layouts.master')
@section('title')
Create Page
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Create New Page</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('pages')}}"><i class="fa fa-dashboard"></i> All Pages</a></li>
            <li class="breadcrumb-item active">Create New Page</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')
<div class="card card-secondary card-outline">



        {!! Form::open(['url'=>'pages','method'=>'post','id'=>'createPage']) !!}




    <div class="card-body">

        <div class="row">

            <div class="col-md-12">

               

                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control','id'=>'name']) !!}
                        @error('name')
                        <span class="error-message"> {{$message}}</span>
                        @enderror
                        <div class="input-group-append">
                        </div>
                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('publish') ? 'has-error' : '' }}">
                        <!-- publish -->
                        {!! Form::label('publish',Lang::get('message.publish'),['class'=>'required']) !!}
                        {!! Form::select('publish',[1=>'Yes',0=>'No'],null,['class' => 'form-control']) !!}
                        @error('publish')
                        <span class="error-message"> {{$message}}</span>
                        @enderror
                        <div class="input-group-append">
                        </div>
                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                        <!-- slug -->
                        {!! Form::label('slug',Lang::get('message.slug'),['class'=>'required']) !!}
                        {!! Form::text('slug',null,['class' => 'form-control','id'=>'slug']) !!}
                        @error('slug')
                        <span class="error-message"> {{$message}}</span>
                        @enderror
                        <div class="input-group-append">
                        </div>
                    </div>


                </div>
                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                        <!-- url -->
                        {!! Form::label('url',Lang::get('message.url'),['class'=>'required']) !!}

                        {!! Form::text('url',null,['class' => 'form-control','id'=>'url','placeholder'=>'https://example.com']) !!}

                        @error('url')
                        <span class="error-message"> {{$message}}</span>
                        @enderror
                        <div class="input-group-append">
                        </div>
                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('parent_page_id') ? 'has-error' : '' }}">
                        <!-- parent_page_id -->
                        {!! Form::label('parent_page_id',Lang::get('message.parent-page')) !!}
                        {!! Form::select('parent_page_id',['0'=>'Choose','Parent Pages'=>$parents],null,['class' => 'form-control']) !!}
                        @error('parent_page_id')
                        <span class="error-message"> {{$message}}</span>
                        @enderror
                    </div>
                   
                    <div class="col-md-4 form-group {{ $errors->has('parent_page_id') ? 'has-error' : '' }}">
                        <!-- type -->
                        {!! Form::label('type',Lang::get('message.page_type')) !!}
                          {!! Form::select('type',['none'=>'None','contactus'=>'Contact Us'],null,['class' => 'form-control']) !!}
                        @error('type')
                        <span class="error-message"> {{$message}}</span>
                        @enderror
                    </div>

                 </div>

                <div class="row">
                    <div class="col-md-12 form-group">

                        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                        <script>
             tinymce.init({
                          selector: 'textarea',
                          height: 500,
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
                        @error('content')
                        <span class="error-message"> {{$message}}</span>
                        @enderror
                        <div class="input-group-append">
                        </div>
                    </div>


                </div>

            </div>

        </div>
        <h4><button type="submit" class="btn btn-primary pull-right" id="submit"><i class="fa fa-save">&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>

    </div>

</div>
{!! Form::close() !!}

<script>
     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'all_new_page';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'all_new_page';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');

        $(document).ready(function() {
        const userRequiredFields = {
            name:@json(trans('message.page_details.add_name')),
            publish:@json(trans('message.page_details.add_publish')),
            slug:@json(trans('message.page_details.add_slug')),
            url:@json(trans('message.page_details.add_url')),
            content:@json(trans('message.page_details.add_content')),
        };

        $('#createPage').on('submit', function (e) {
            const userFields = {
                name:$('#name'),
                publish:$('#publish'),
                slug:$('#slug'),
                url:$('#url'),
                content:$('#textarea'),

            };


            // Clear previous errors
            Object.values(userFields).forEach(field => {
                field.removeClass('is-invalid');
                field.next().next('.error').remove();

            });

            let isValid = true;

            const showError = (field, message) => {
                field.addClass('is-invalid');
                field.next().after(`<span class='error invalid-feedback'>${message}</span>`);
            };

            // Validate required fields
            Object.keys(userFields).forEach(field => {
                if (!userFields[field].val()) {
                    showError(userFields[field], userRequiredFields[field]);
                    isValid = false;
                }
            });

            if(isValid && !isValidURL(userFields.url.val())){
                showError(userFields.url,@json(trans('message.page_details.valid_url')),);
                isValid=false;
            }

            // If validation fails, prevent form submission
            if (!isValid) {
                e.preventDefault();
            }
        });
        // Function to remove error when input'id' => 'changePasswordForm'ng data
        const removeErrorMessage = (field) => {
            field.classList.remove('is-invalid');
            const error = field.nextElementSibling;
            if (error && error.classList.contains('error')) {
                error.remove();
            }
        };

        // Add input event listeners for all fields
        ['name','publish','url','slug','textarea'].forEach(id => {

            document.getElementById(id).addEventListener('input', function () {
                removeErrorMessage(this);

            });
        });
            function isValidURL(string) {
                try {
                    new URL(string);
                    return true;
                } catch (err) {
                    return false;
                }
            }
    });



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
               $("#slug").val(data);
            }
        });
    });
</script>
@stop

