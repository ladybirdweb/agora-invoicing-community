@extends('themes.default1.layouts.master')
@section('title')
Create Invoice
@stop
@section('content-header')
    <div class="col-sm-6 md-6">
        <h1>Generate An Invoice</h1>
    </div>
    <div class="col-sm-6 md-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('clients')}}"> All Users</a></li>
            <li class="breadcrumb-item"><a href="{{url('invoices')}}">View Invoices</a></li>
            <li class="breadcrumb-item active">Generate Invoice</li>
        </ol>
    </div><!-- /.col -->

@stop
@section('content')
<div class="col-md-12">
<div class="card card-primary card-outline">

    <div class="card-header">
         @if($user!='')
            {!! Form::open(['url'=>'generate/invoice/'.$user->id,'id'=>'formoid']) !!}
            <input type="hidden" name="user" value="{{$user->id}}">
            <h5>{{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}}, ({{$user->email}}) </h5>
            @else 
            {!! Form::open(['url'=>'generate/invoice','id'=>'formoid']) !!}
            @endif
        <div id="error">
        </div>
        <div id="success">
        </div>
        <div id="fails">
        </div>
 

       
         </div>


    
    <div class="card-body">

        <div class="row">

            

                @if($user=='')
                <?php
                $users = [];
                ?>
                    <link rel="stylesheet" href="{{asset('admin/css/select2.min.css')}}">
                    <script src="{{asset('admin/plugins/select2.full.min.js')}}"></script>
                     <style>
                        .select2-container--default .select2-selection--multiple .select2-selection__choice {
                            background-color: #1b1818 !important;
                    </style>

                <div class="col-sm-4 form-group">
                    {!! Form::label('user',Lang::get('message.clients'),['class'=>'required']) !!}
                     {!! Form::select('user', [Lang::get('User')=>$users],null,['multiple'=>true,'class'=>"form-control select2" ,'id'=>"users",'required','style'=>"width:100%;color:black",'oninvalid'=>"setCustomValidity('Please Select Client')",
                  'onchange'=>"setCustomValidity('')"]) !!}
                 
                </div>
                @endif
                <div class="col-md-4 lg-4 form-group {{ $errors->has('invoice_status') ? 'has-error' : '' }}">
                            <!-- first name -->
                            {!! Form::label('date',Lang::get('message.date'),['class'=>'required']) !!}
                           
                         <div class="input-group date" id="invoice_date" data-target-input="nearest">
                            {!! Form::text('date',null,['class' => 'form-control','id'=>'datepicker','autocomplete'=>'off']) !!}
                            <div class="input-group-append" data-target="#invoice_date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            
                            </div>

                        </div>
                    </div>

                <div class="col-md-4 lg-4 form-group">
                    {!! Form::label('product',Lang::get('message.product'),['class'=>'required']) !!}
                     <select name="product" value= "Choose" onChange="getSubscription(this.value)" id="product" class="form-control" required="required">
                             <option value="">Choose</option>
                           @foreach($products as $key=>$product)
                              <option value={{$key}}>{{$product}}</option>
                          @endforeach
                          </select>
                    <h6 id ="productnamecheck"></h6>
                </div>

          
                <div id="fields1">
                </div>
                 <div id="qty">
                </div>
                 <div id="agents">
                </div>
                <div id="fields">
                </div>

                <div class="col-md-4 form-group">
                    {!! Form::label('price',Lang::get('message.price'),['class'=>'required']) !!}
                    {!! Form::text('price',null,['class'=>'form-control','id'=>'price']) !!}
                      <h6 id ="pricecheck"></h6>
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('code',Lang::get('message.promotion-code')) !!}
                    {!! Form::text('code',null,['class'=>'form-control']) !!}
                </div>
           
                <!-- <div class="col-md-6 form-group">
                    {!! Form::label('send_mail',Lang::get('message.send-mail')) !!}
                    <p>{!! Form::checkbox('client',1) !!} To Client&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!! Form::checkbox('agent',1) !!} To Agent</p>
                </div> -->




            </div>
             <h4> <button name="generate" type="submit" id="generate" class="btn btn-primary pull-right" ><i class="fas fa-sync-alt">&nbsp;</i>{!!Lang::get('message.generate')!!}</button></h4>
             
            {!! Form::close() !!}

        </div>
    </div>
</div>




<script>
    function getPrice(val) {
         var user = document.getElementsByName('user')[0].value;
        var plan = "";
        var product = "";
        if ($('#plan').length > 0) {
            var plan = document.getElementsByName('plan')[0].value;
        }
        if ($('#product').length > 0) {
            var product = document.getElementsByName('product')[0].value;
        }
        //var plan = document.getElementsByName('plan')[0].value;
        //alert(user);

        $.ajax({
            type: "POST",
            url: "{{url('get-price')}}",
            data: {'product': product, 'user': user, 'plan': val},
            //data: 'product=' + val+'user='+user,
            success: function (data) {
                
                var price = data['price'];
                var field = data['field'];
                 var qty = data['quantity'];
                var agents = data['agents'];
                //console.log(field);
                $("#price").val(price);
                $("#fields").replaceWith(field);
                $("#qty").replaceWith(qty);
                $("#agents").replaceWith(agents);
            }
        });
    }

    function getSubscription(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-subscription')}}" + '/' + val,
            success: function (data) {
                var price = data['price'];
                var field = data['field'];
               
                
                $("#price").val(price);
                $("#fields1").replaceWith(field);
                

            }
        });
    }

</script>
<script type='text/javascript'>
    /* attach a submit handler to the form */
    $("#formoid").submit(function (event) {


      //   }
         /* stop form from submitting normally */
        event.preventDefault();

        /* get the action attribute from the <form action=""> element */
        var $form = $(this),
        url = $form.attr('action');
        var user = document.getElementsByName('user')[0].value;
        var plan = "";
        var subscription = 'false';
        var description = "";
        if ($('#plan').length > 0) {
            var plan = document.getElementsByName('plan')[0].value;
           
            subscription = 'true';
        }
        if ($('#description').length > 0) {
            var description = document.getElementsByName('description')[0].value;
        }
        if ($('#domain').length > 0) {
            var domain = document.getElementsByName('domain')[0].value;
            var data = $("#formoid").serialize() + '&domain=' + domain + '&user=' + user;
            if ($('#quantity').length > 0) {
                var quantity = document.getElementsByName('quantity')[0].value;
                var data = $("#formoid").serialize() + '&domain=' + domain + '&quantity=' + quantity + '&user=' + user;
            } else if ($('#agents').length > 0) {
                 var agents = document.getElementsByName('agents')[0].value;
                 var data = $("#formoid").serialize() + '&domain=' + domain + '&agents=' + agents + '&user=' + user;
            } else{
                var data = $("#formoid").serialize() + '&domain=' + domain + '&user=' + user;
            }
        } else {
            if ($('#quantity').length > 0) {
                var quantity = document.getElementsByName('quantity')[0].value;
                var data = $("#formoid").serialize() + '&quantity=' + quantity + '&user=' + user;
            } else {
                var data = $("#formoid").serialize() + '&user=' + user;
            }
        }
        data = data + '&plan=' + plan + '&subscription=' + subscription+'&description='+description;
        $("#generate").html("<i class='fas fa-circle-notch fa-spin'></i>Please Wait...");
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (data) {
                $("#generate").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>Generate");
                if(data.success == true) {
                    $('#fails').hide();
                        $('#error').hide();
                        $('#success').show();
                    var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-check"></i>Success! </strong>'+data.message.success+'!</div>';
                    $('#success').html(result);
                }


            },
            error: function (response) {
                $("#generate").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>Generate");
                if(response.responseJSON.success == false) {
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                    html += '<li>' + response.responseJSON.message[0] + '</li>'
                } else {
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                for (var key in response.responseJSON.errors)
                {
                    // console.log(response.responseJSON.errors)
                    // console.log(response.responseJSON.errors[0]);
                    html += '<li>' + response.responseJSON.errors[key][0] + '</li>'
                }
                 
                }
                
                 html += '</ul></div>';
                 $('#error').show();
                  document.getElementById('error').innerHTML = html;
              

            }
        });

        //console.log(data);
        /* Send the data using post with element id name and name2*/
//      var posting = $.post(url,data);
//
//      /* Alerts the results */
//      posting.done(function( data ) {
//        console.log(data);
//      });
    });
</script>



@stop

@section('datepicker')
<script>
     $('#invoice_date').datetimepicker({
      format: 'L'
    })
   
</script>

<script>
   

        $('#users').select2({
        placeholder: "Search",
        minimumInputLength: 1,
        maximumSelectionLength: 1,
        ajax: {
            url: '{{route("search-email")}}',
            dataType: 'json',
            beforeSend: function(){
                $('.loader').css('display', 'block');
            },
            complete: function() {
                $('.loader').css('display', 'none');
            },
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                      results: $.map(data, function (value) {
                    return {
                        image:value.profile_pic,
                        text:value.first_name+" "+value.last_name,
                        id: value.id,
                        email:value.text
                    }
                
                 })
                  }
            },
            cache: true
        },
           templateResult: formatState,
    });
        function formatState (state) { 
       
       var $state = $( '<div><div style="width: 14%;display: inline-block;"><img src='+state.image+' width=35px" height="35px" style="vertical-align:inherit"></div><div style="width: 80%;display: inline-block;"><div>'+state.text+'</div><div>'+state.email+'</div></div></div>');
        return $state;
    }
</script>
@stop