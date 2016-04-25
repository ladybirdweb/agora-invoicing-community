@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="box-header">
        @if($user!='')
        {!! Form::open(['url'=>'generate/invoice/'.$user->id]) !!}
        
        <h4>{{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}}, ({{$user->email}}) </h4>
        @else 
        {!! Form::open(['url'=>'generate/invoice']) !!}
        <h4>Place Order</h4>
        @endif
        {!! Form::submit(Lang::get('message.generate'),['class'=>'btn btn-primary pull-right'])!!}
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
                
                @if($user=='')
                <?php 
                $users = \App\User::lists('email','id')->toArray();
                
                ?>
                    <div class="col-md-4 form-group">
                    {!! Form::label('user',Lang::get('message.clients')) !!}
                    {!! Form::select('user',[''=>'Select','Users'=>$users],null,['class'=>'form-control']) !!}
                </div>
                @endif
                
                <div class="col-md-4 form-group">
                    {!! Form::label('product',Lang::get('message.product')) !!}
                    {!! Form::select('product',[''=>'Select','Products'=>$products],null,['class'=>'form-control','onChange'=>'getPrice(this.value);']) !!}
                </div>
                
                <div class="col-md-4 form-group">
                    {!! Form::label('price',Lang::get('message.price')) !!}
                    {!! Form::text('price',null,['class'=>'form-control']) !!}
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('code',Lang::get('message.promotion-code')) !!}
                    {!! Form::text('code',null,['class'=>'form-control']) !!}
                </div>
                
               

            </div>
            {!! Form::close() !!}
            
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    function getPrice(val) {


        $.ajax({
            type: "POST",
            url: "{{url('get-price')}}",
            data: 'product=' + val,
            success: function (data) {
                $("#price").val(data);
            }
        });
    }
</script>

@stop
