@extends('themes.default1.layouts.master')
@section('title')
Edit Product Uploads
@stop
@section('content-header')
<h1>
Edit Product Upload
</h1>
  <ol class="breadcrumb">
    <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('products')}}">All Products</a></li>
        <li class="active">Edit Product Uplaod</li>
      </ol>
@stop
@section('content')
<div class="box box-primary">
    <div class="content-header">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
                    <i class="fa fa-check"></i>
                    <b>{{Lang::get('message.success')}}!</b>
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
         {!! Form::model($model,['url'=>'upload/'.$model->id,'method'=>'patch']) !!}
        <h4>{{Lang::get('message.plan')}} <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button></h4>

    </div>

        <div class="box-body">

        <div class="row">

            <div class="col-md-12">

            {!! Form::hidden('title',$model->id,['class' => 'form-control','disabled'=>'disabled']) !!}

                    <div class="row">
                         <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('product',Lang::get('message.product'),['class'=>'required']) !!}
                        {!! Form::text('product',$selectedProduct,['class' => 'form-control','disabled'=>'disabled']) !!}

                    </div>

                  
                         <div class="col-md-4 form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('title',Lang::get('message.title'),['class'=>'required']) !!}
                        {!! Form::text('title',null,['class' => 'form-control']) !!}

                    </div>

                    <div class="col-md-4 form-group {{ $errors->has('version') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('version',Lang::get('message.version'),['class'=>'required']) !!}
                        {!! Form::text('version',null,['class' => 'form-control','readonly'=>'readonly']) !!}

                    </div>

                    <div class="col-md-12 form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                    
                    {!! Form::label('description',Lang::get('message.description'),['class'=>'required']) !!}
                    {!! Form::textarea('description',null,['class' => 'form-control','id'=>'desc-textarea']) !!}
                     <h6 id= "descheck"></h6>
                     </div>


        


                </div>





            </div>

        </div>

    </div>
  </div>
 <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
   
<script>
    $(function(){


      tinymce.init({
     selector: '#desc-textarea',
     height: 200,
   //  theme: 'modern',
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
});
</script>
 @stop




