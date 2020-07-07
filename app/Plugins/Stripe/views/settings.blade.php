@extends('themes.default1.layouts.master')
@section('title')
Stripe
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Stripe</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item"><a href="{{url('plugin')}}"><i class="fa fa-dashboard"></i> Payment Gateways</a></li>
            <li class="breadcrumb-item active">Stripe</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="card card-primary card-outline">



            <div class="card-body">
                <div id="alertMessage"></div>

                <table class="table">


                    
                   
                    
                    <tr>

                        <td><b>{!! Form::label('api_key','Api Keys',['class'=>'required']) !!}</b></td>
                        <td>
                          <!-- last name -->
                        {!! Form::label('stripe_secret','Stripe Secret Key') !!} 
                        {!! Form::text('stripe_secret',$stripeKeys->stripe_secret,['class' => 'form-control rzp_secret','id'=>'stripe_secret']) !!}
                           <span id="stripe_keycheck"></span>
                         </td>
                       
                    </tr>

                </table>
                <button type="submit" class="btn btn-primary btn-sm" id="key_update"><i class="fa fa-sync-alt"></i>&nbsp;Update</button>



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
        $('#key_update').html("<i class='fas fa-circle-notch fa-spin'></i>Please Wait...");
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
           $('#key_update').html("<i class='fas fa-circle-notch fa-spin'></i>Update");
             
            $('#alertMessage').show();
            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+data.message.message+'.</div>';
            $('#alertMessage').html(result+ ".");
            $("#key_update").html("<i class='fa fa-sync-alt'>&nbsp;</i>Update");
              setInterval(function(){ 
                $('#alertMessage').slideUp(3000); 
            }, 1000);
          }, error: function(data) {
            $("#key_update").attr('disabled',false);  
            $('#key_update').html("<i class='fas fa-circle-notch fa-spin'></i>Update");
              
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