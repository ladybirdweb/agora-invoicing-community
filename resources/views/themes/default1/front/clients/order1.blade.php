@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Agora | Orders
@stop
@section('nav-orders')
active
@stop

@section('content')

<h2 class="mb-none"> My Orders</h2>

<div class="col-md-12 pull-center">
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
    
{!! Datatable::table()
    ->addColumn('Id','Product Name','Expiry','Action')
    ->setUrl('get-my-orders') 
    ->setOptions([
                "order"=> [ 2, "desc" ],
                ])
    ->render() !!}

</div>    


@stop