@extends('themes.default1.layouts.front.master')
@section('title')
Pricing | Faveo Helpdesk
@stop
@section('page-header')
Pricing
@stop
@section('page-heading')
 <h1>Pricing</h1>
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li class="active">Pricing</li>
@stop
@section('main-class') 
main
@stop


@section('content')


<div class="row">
    @if (count($errors) > 0)
  </br>
</br>
                <div class="alert alert-danger alert-dismissable">


                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.


                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(Session::has('success'))
              </br>
            </br>
                <div class="alert alert-success alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('success')}}
                </div>
                @endif
                <!-- fail message -->
                @if(Session::has('fails'))
              </br>
            </br>
                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('fails')}}
                </div>
                @endif
    <div class="row">
                        
                            
                            <h4 style="font-size: 30px;text-align: center;font-weight: bold;margin-left:400px">Helpdesk Self Hosted Pricing</h4>
                        
                    </div>
   
        {!! html_entity_decode($template) !!}
 
    <br/>    <br/>    <br/>    <br/>  <br/> <br/>





 <br/>    <br/>    <br/>    <br/>  <br/> <br/>





</div>

<script type="text/javascript">
    

    $( document ).ready(function() {
        var printitem= sessionStorage.getItem('successmessage');

         var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+printitem+'!</div>';
         $('#alertMessage1').html(result);
         sessionStorage.removeItem('successmessage');
    
});
</script>


@stop



