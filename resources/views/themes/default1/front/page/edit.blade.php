@extends('themes.default1.layouts.master')
@section('title')
Edit Page
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Edit Page</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('pages')}}"><i class="fa fa-dashboard"></i> Pages</a></li>
            <li class="breadcrumb-item active">Edit Page</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')
<div class="card card-secondary card-outline">

      {!! Form::model($page,['url'=>'pages/'.$page->id,'method'=>'patch','id'=>'createPage']) !!}


    <div class="card-body table-responsive">

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
                        <select name="parent_page_id"  class="form-control">
                            <option value="0">Choose</option>
                            @foreach($parents as $key=>$parent)

                                   <option value="{{$key}}" <?php  if(in_array($parent, $parentName) ) { echo "selected";} ?>>{{$parent}}</option>
                           
                             @endforeach
                        </select>
                        @error('parent_page_id')
                        <span class="error-message"> {{$message}}</span>
                        @enderror
                    </div>

                     <div class="col-md-4 form-group {{ $errors->has('parent_page_id') ? 'has-error' : '' }}">
                        <!-- type -->
                        {!! Form::label('type',Lang::get('message.page_type')) !!}
                          {!! Form::select('type',['none'=>'None','contactus'=>'Contact Us'],null,['class' => 'form-control']) !!} 

                    </div>
                    <?php
                         $defaults = DB::table('frontend_pages')->pluck('name','id')->toArray();
                         ?>
                       <div class="col-md-6 form-group {{ $errors->has('parent_page_id') ? 'has-error' : '' }}">
                        <!-- default_page_id -->
                        {!! Form::label('default_page_id',Lang::get('message.default-page'),['class'=>'required']) !!}
                                   <select name="default_page_id"  class="form-control">
                                     <option value="">My Invoices</option>
                         @foreach($defaults as $key=>$value)
                                   <option value="{{$key}}" <?php  if($key == $selectedDefault)  { echo "selected";} ?>>{{$value}}</option>
                           
                             @endforeach
                              </select>
                           @error('default_page_id')
                           <span class="error-message"> {{$message}}</span>
                           @enderror
                    </div>
                    <div class="col-md-6 form-group {{ $errors->has('parent_page_id') ? 'has-error' : '' }}">
                        <!-- publish_date -->
                        {!! Form::label('publish_date',Lang::get('message.publish-date'),['class'=>'required']) !!}

                        <div class="input-group date" id="publishing_date" data-target-input="nearest">
                        <input type="text" name="created_at" value="{{$publishingDate}}" class="form-control datetimepicker-input" autocomplete="off"  data-target="#publishing_date" id="created_at"/>
                            @error('created_at')
                            <span class="error-message"> {{$message}}</span>
                            @enderror
                        <div class="input-group-append" data-target="#publishing_date"  data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>

                        </div>

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
        <button type="submit" class="btn btn-primary pull-right" id="submit"><i class="fa fa-sync-alt">&nbsp;</i>&nbsp;{!!Lang::get('message.update')!!}</button>
    </div>

</div>
{!! Form::close() !!}

<script>
     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'all_page';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'all_page';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');


      $(document).ready(function() {
          const userRequiredFields = {
              name:@json(trans('message.page_details.add_name')),
              publish:@json(trans('message.page_details.add_publish')),
              slug:@json(trans('message.page_details.add_slug')),
              url:@json(trans('message.page_details.add_url')),
              content:@json(trans('message.page_details.add_content')),
              created_at:@json(trans('message.page_details.publish_date')),

          };

          $('#createPage').on('submit', function (e) {
              const userFields = {
                  name:$('#name'),
                  publish:$('#publish'),
                  slug:$('#slug'),
                  url:$('#url'),
                  content:$('#textarea'),
                  created_at:$('#created_at'),
                  default_page:$('#default_page_id'),

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

              if (isValid && userRequiredFields.default_page.val()==null) {
                  showError(userFields.email, @json(trans('message.page_details.default_page')));
                  isValid = false;
              }

              if(isValid && !isValidURL(userFields.url.val())){
                  showError(userFields.url,@json(trans('message.page_details.valid_url')),);
                  isValid=false;
              }

              // If validation fails, prevent form submission
              if (!isValid) {
                  console.log(3);
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
          ['name','publish','url','slug','content'].forEach(id => {

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
            data: {'url': this.value},
            url: "{{url('get-url')}}",
            success: function (data) {
                $("#url").val(data)
            }
        });
    });
    $(document).on('input', '#name', function () {

        $.ajax({
            type: "get",
            data: {'slug': this.value},
            url: "{{url('get-url')}}",
            success: function (data) {
                $("#slug").val(data)
            }
        });
    });
</script>
@section('icheck')
<script>
  $('#publishing_date').datetimepicker({
        format: 'L'
    });
  </script>
  @stop
@stop

