@extends('themes.default1.layouts.master')
@section('content')
<link href="{!!asset('plugins/dhtmlxSuite_v50_std/codebase/fonts/font_roboto/roboto.css')!!}" rel="stylesheet" type="text/css" />
<link href="{!!asset('plugins/dhtmlxSuite_v50_std/codebase/dhtmlx.css')!!}" rel="stylesheet" type="text/css" />
<div class="box box-primary">

    <div class="box-header">
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
        <div id="error">
        </div>
        <div id="success">
        </div>
        <div id="fails">
        </div>
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
        @if($user!='')
        {!! Form::open(['url'=>'generate/invoice/'.$user->id,'id'=>'formoid']) !!}
        <input type="hidden" name="user" value="{{$user->id}}">
        <h4>{{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}}, ({{$user->email}}) </h4>
        @else 
        {!! Form::open(['url'=>'generate/invoice','id'=>'formoid']) !!}
        <h4>Place Order</h4>
        @endif
        {!! Form::submit(Lang::get('message.generate'),['class'=>'btn btn-primary pull-right'])!!}
    </div>




    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

                @if($user=='')
                <?php
                $users = \App\User::lists('email');
                ?>

                <div class="col-md-4 form-group">
                    {!! Form::label('user',Lang::get('message.clients')) !!}
                    <body onload="doOnLoad();">
                        <div id="combo_zone"></div>
                    </body>
                </div>
                @endif

                <div class="col-md-4 form-group">
                    {!! Form::label('product',Lang::get('message.product'),['class'=>'required']) !!}
                    {!! Form::select('product',[''=>'Select','Products'=>$products],null,['class'=>'form-control','onchange'=>'getSubscription(this.value)','id'=>'product']) !!}
                </div>
                <div id="fields1">
                </div>
                <div id="fields">
                </div>

                <div class="col-md-4 form-group">
                    {!! Form::label('price',Lang::get('message.price')) !!}
                    {!! Form::text('price',null,['class'=>'form-control']) !!}
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('code',Lang::get('message.promotion-code')) !!}
                    {!! Form::text('code',null,['class'=>'form-control']) !!}
                </div>
                <div class="col-md-6 form-group">
                    {!! Form::label('send_mail',Lang::get('message.send-mail')) !!}
                    <p>{!! Form::checkbox('client',1) !!} To Client&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!! Form::checkbox('agent',1) !!} To Agent</p>
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
                //console.log(field);
                $("#price").val(price);
                $("#fields").replaceWith(field);
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
        /* stop form from submitting normally */
        event.preventDefault();

        /* get the action attribute from the <form action=""> element */
        var $form = $(this),
                url = $form.attr('action');
        var user = document.getElementsByName('user')[0].value;
        var plan = "";
        var subscription = "";
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
            } else {
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
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (data) {
                //var response = JSON.stringify(data.result);
                for (key in data.result) {
                    if (key == 'success') {
                        $('#fails').hide();
                        $('#error').hide();
                        $('#success').show();
                        $('#success').append('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.result[key] + '</div>');
                        //$('#success').remove();

                    }
                    if (key == 'fails') {
                        // $('#fails').remove();
                        $('#fails').hide();
                        $('#success').hide();
                        $('#fails').show();
                        $('#fails').append('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.result[key] + '</div>');
                    }

                }

            },
            error: function (data) {
                var response = JSON.parse(data.responseText);
                $.each(response, function (k, v) {
                    $('#error').hide();
                    $('#fails').hide();
                    $('#success').hide();
                    $('#error').show();
                    $('#error').append('<div class="alert alert-danger"><strong>Whoops!</strong> There were some problems with your input.<br><br><ul><li>' + v + '</li></ul></div>');
                    // $('#error').remove();

                });

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
<script src="{{asset('plugins/dhtmlxSuite_v50_std/codebase/dhtmlx.js')}}" type="text/javascript"></script>
<script>
    var myCombo;
    function doOnLoad() {
        myCombo = new dhtmlXCombo("combo_zone", "user", 230);
        myCombo.enableFilteringMode(true);
        myCombo.load("{{url('get-users')}}", function () {
            myCombo.selectOption(0);
        });
    }
</script>
@stop
