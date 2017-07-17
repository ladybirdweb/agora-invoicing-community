@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="content-header">
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
        {!! Form::model($page,['url'=>'pages/'.$page->id,'method'=>'patch']) !!}
        <h4>{{Lang::get('message.pages')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

    </div>

    <div class="box-body">

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

                    <div class="col-md-4 form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('slug',Lang::get('message.slug'),['class'=>'required']) !!}
                        {!! Form::text('slug',null,['class' => 'form-control','id'=>'slug']) !!}

                    </div>


                </div>
                <div class="row">

                    <div class="col-md-6 form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('url',Lang::get('message.url'),['class'=>'required']) !!}
                        {!! Form::text('url',null,['class' => 'form-control','id'=>'url']) !!}

                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('parent_page_id') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('parent_page_id',Lang::get('message.parent-page')) !!}
                        {!! Form::select('parent_page_id',[$parents],null,['class' => 'form-control']) !!}

                    </div>




                </div>

                <div class="row">
                    <div class="col-md-12 form-group">

                        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                        <script>
tinymce.init({
    selector: 'textarea',
    plugins: "code",
    toolbar: "code",
    menubar: "tools"
});
                        </script>


                        {!! Form::label('content',Lang::get('message.content'),['class'=>'required']) !!}
                        {!! Form::textarea('content',null,['class'=>'form-control','id'=>'textarea']) !!}

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}

<script>

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
@stop

