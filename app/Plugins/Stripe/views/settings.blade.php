@extends('themes.default1.layouts.master')
@section('title')
Payment Gateway
@stop
@section('content-header')
<h1>
Stripe
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li><a href="{{url('plugin')}}">Plugins</a></li>
        <li class="active">Stripe</li>
      </ol>
@stop
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="box box-primary">

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
                <div id="alertMessage"></div>

                <table class="table">

                    <tr>
                        <td><h3 class="box-title">Stripe Settings</h3></td>
                        

                    </tr>
                              <div class="box-footer">
                       
                        <div class="pull-right">

                            <?php
                            $status=0;
                            $cont = new \App\Plugins\Stripe\Model\StripePayment();
                            ?>
                          
                          
                        </div>
                    </div>  
                    
                   
                    
                    <tr>

                        <td><b>{!! Form::label('api_key','Api Keys',['class'=>'required']) !!}</b></td>
                        <td>
                          <!-- last name -->
                        {!! Form::label('stripe_secret','Stripe Secret Key') !!} 
                        {!! Form::text('stripe_secret',$stripeKeys->stripe_secret,['class' => 'form-control rzp_secret','id'=>'stripe_secret']) !!}
                           <span id="stripe_keycheck"></span>
                         </td>
                       
                    <td>{!! Form::submit(Lang::get('message.update'),['id'=>'key_update','style'=>'margin-top:24px','class'=>'btn btn-primary'])!!}</td>
                    </tr>       
                   
                </table>



            </div>

        </div>
        <!-- /.box -->

    </div>


</div>
<script type="text/javascript">
           
             $('#b_currency_update').on('click',function(){
                 $('#b_currency_update').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait..."); 
                 $('#b_currency_update').attr('disabled',true); 
                $.ajax ({
              url: '{{url("change-base-currency/payment-gateway/stripe")}}',
              type : 'post',
              data: {
               "b_currency": $('#b_currency').val(),
                },
               success: function (data) {
                    $('#alertMessage').show();
                     $("#b_currency_update").attr('disabled',false);  
                    var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+data.update+'.</div>';
                    $('#alertMessage').html(result+ ".");
                    $("#b_currency_update").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
                      setInterval(function(){ 
                        $('#alertMessage').slideUp(3000); 
                    }, 1000);
                  },
                })

             })
             



               //Validate and pass value through ajax
        $("#key_update").on('click',function (){ //When Submit button is checked
        $('#key_update').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait..."); 
        $("#key_update").attr('disabled',true);  
             var rzpstatus = 1;
           if ($('#stripe_key').val() == "") { //if value is not entered
            $('#stripe_keycheck').show();
            $('#stripe_keycheck').html("Please Enter Stripe Key");
            $('#stripe_key').css("border-color","red");
            $('#stripe_keycheck').css({"color":"red","margin-top":"5px"});
            return false;
          } else if ($('#stripe_secret').val() == "") {
            $("#key_update").attr('disabled',false);  
             $('#stripe_secretcheck').show();
            $('#stripe_secretcheck').html("Please Enter Stripe Secret");
            $('#stripe_secret').css("border-color","red");
            $('#stripe_secretcheck').css({"color":"red","margin-top":"5px"});
            return false;
          } 
    
     
    $.ajax ({
      url: '{{url("update-api-key/payment-gateway/stripe")}}',
      type : 'get',
      data: {
       "status": rzpstatus,
       "stripe_key": $('#stripe_key').val(),"stripe_secret" : $('#stripe_secret').val() },
       success: function (data) {
        $("#key_update").attr('disabled',false); 
        $('#stripe_keycheck').hide();
         $('#stripe_secret').css("border-color","");
           $('#key_update').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Update"); 
             
            $('#alertMessage').show();
            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+data.message.message+'.</div>';
            $('#alertMessage').html(result+ ".");
            $("#key_update").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
              setInterval(function(){ 
                $('#alertMessage').slideUp(3000); 
            }, 1000);
          }, error: function(data) {
            $("#key_update").attr('disabled',false);  
            $('#key_update').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Update"); 
              
                $('#stripe_keycheck').show();
                $('#stripe_keycheck').html(data.responseJSON.message);
                 $('#stripe_keycheck').focus();
                $('#stripe_secret').css("border-color","red");
                $('#stripe_keycheck').css({"color":"red","margin-top":"5px"});
          }
    })
  });
             
        
</script>

@stop