@extends('themes.default1.layouts.master')
@section('title')
  Edit Plan
@stop
@section('content-header')

  <h1>
    Edit Plan
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{url('plans')}}">All Plans</a></li>
    <li class="active">Edit Plan</li>
  </ol>
@stop
@section('content')
  <div class="box box-primary">

    <div class="box-header with-border">
      <h2 class="box-title">{{Lang::get('message.plan')}}</h2>
    </div>

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

    </div>
    {!! Form::model($plan,['url'=>'plans/'.$plan->id,'method'=>'patch']) !!}
    <div class="box-body">


      <div class="row">

        <div class="col-md-12">

          <div class="row">
            <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
              <!-- first name -->
              {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
              {!! Form::text('name',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-4 form-group {{ $errors->has('product') ? 'has-error' : '' }}">
              <!-- first name -->
              {!! Form::label('product',Lang::get('message.product'),['class'=>'required']) !!}
              <select name="product" id="planproduct" class="form-control" onchange="myProduct()">
                <option value="">Choose</option>

                @foreach($products as $key=>$product)
                  <option value="{{$key}}"  <?php  if(in_array($product, $selectedProduct) ) { echo "selected";} ?>>{{$product}}</option>

                @endforeach
              </select>



            </div>
            <div class="col-md-4 form-group plandays {{ $errors->has('days') ? 'has-error' : '' }}">
              <!-- last name -->
              {!! Form::label('days','Periods',['class'=>'required']) !!}
              <select name="days" id="plan" class="form-control">
                <option value="">Choose</option>

                @foreach($periods as $key=>$period)
                  <option value="{{$key}}" <?php  if(in_array($period, $selectedPeriods) ) { echo "selected";} ?>>{{$period}}</option>

                @endforeach
              </select>

            </div>

            <div class="col-md-12">


                <table class="table table-responsive table-bordered table-hover" id="dynamic_table">
                  <thead>
                    <tr>
                      <th class="col-sm-3">{{ Lang::get('message.country') }} <span class="text-red">*</span> </th>
                      <th class="col-sm-3">{{ Lang::get('message.currency') }} <span class="text-red">*</span> </th>
                      <th class="col-sm-3">{{ Lang::get('message.regular-price') }} <span class="text-red">*</span> </th>
                      <th class="col-sm-3">
                        {{ Lang::get('message.renew-price') }} <span class="text-red">*</span>
                      </th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach($planPrices as $row)
                      <tr id="row{{$loop->iteration}}" class="form-group {{ $errors->has('add_price.'.$key) ? 'has-error' : '' }}">

                        <td>
                          <select name="country_id[{{ $row['id'] }}]" class="form-control" >
                            @if (0 === $row['country_id'])
                                <option value="0" selected>Default</option>
                            @endif
                            @if (0 !== $row['country_id'])
                              @foreach ($countries as $country)
                                <option value="{{$country['country_id']}}"  @if ($country['country_id'] === $row['country_id'])
                                  {{ 'selected' }}
                                        @endif>
                                  {{ $country['country_name'] }}
                                </option>
                              @endforeach
                            @endif
                          </select>
                        </td>

                        <td>
                          <select name="currency[{{ $row['id'] }}]" class="form-control">
                            @foreach ($currency as $code => $name)
                              <option value="{{ $code }}" @if ($code === $row['currency'])
                                  {{ 'selected' }}
                              @endif>
                                {{ $name }}
                              </option>
                            @endforeach
                          </select>
                        </td>

                        <td>
                          <input type="text" class="form-control" name="add_price[{{ $row['id'] }}]" value="{{ $row['add_price'] }}">
                        </td>

                        <td>
                          <div class="{{ ($row['country_id'] != 0) ? 'input-group' : '' }}">
                            <input type="text" class="form-control" name="renew_price[{{ $row['id'] }}]" value="{{ $row['renew_price'] }}">
                            @if($row['country_id'] != 0)
                              <span class="input-group-addon btn_remove" id="{{$loop->iteration}}"><i class="fa fa-minus"></i></span>
                            @endif
                          </div>
                        </td>

                      </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>

            <div class="col-sm-12" style="margin-bottom: 10px;">
              <button class="btn btn-sm btn-default add-more"><i class="fa fa-plus"></i>&nbsp;{{ trans('message.add_price_for_country') }}</button>
            </div>


            <div class="col-md-4 form-group">
              <!-- last name -->
              {!! Form::label('description','Price Description') !!}
              {!! Form::text("price_description",$priceDescription,['class' => 'form-control' ,'placeholder'=>'Enter Price Description to be Shown on Pricing Page. eg: Yearly,Monthly,One-Time']) !!}
              <h6 id="dayscheck"></h6>

            </div>
            <div class="col-md-4 form-group">
              <!-- last name -->
              {!! Form::label('product_quantity','Product Quantity',['class'=>'required']) !!}
              {!! Form::number("product_quantity",$productQuantity,['class' => 'form-control','disabled'=>'disabled','id'=>'prodquant','placeholder'=>'Pricing for No. of Products']) !!}

            </div>

            <div class="col-md-4 form-group">
              <!-- last name -->
              <label data-toggle="tooltip" data-placement="top" title="If '0' Agents Selected, Plan will be for Unlimited Agents">
                {!! Form::label('agents','No. of Agents',['class'=>'required']) !!}</label>
              {!! Form::number("no_of_agents",$agentQuantity,['class' => 'form-control' ,'disabled'=>'disabled','id'=>'agentquant','placeholder'=>'Pricing for No. of Agents']) !!}

            </div>

          </div>

        </div>


      </div>

    </div>

    <div class="box-footer">
      <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>

    </div>

  </div>



  {!! Form::close() !!}

  <script>

    $( document ).ready(function() {
      var product = document.getElementById('planproduct').value;
      $.ajax({
        type: 'get',
        url : "{{url('get-period')}}",
        data: {'product_id':product},
        success: function (data){
          if(data.subscription != 1 ){
            $('.plandays').hide();
          }
          else{
            $('.plandays').show();
          }
          if(data.agentEnable != 1) {//Check if Product quantity to be sh`own or No. of Agents
            document.getElementById("prodquant").disabled = false;
            document.getElementById("agentquant").disabled = true;

          } else if(data.agentEnable == 1){
            document.getElementById("agentquant").disabled = false;
            document.getElementById("prodquant").disabled = true;
          }
        }
      });

      var i = 1000;
      $(".add-more").click(function (e) {
        e.preventDefault();
        i++;
        $('#dynamic_table tr:last').after(`
        <tr id="row` + i + `">
          <td>
            <select name="country_id[]" class="form-control" >
              <option value="" selected disabled>Choose Country</option>
              @foreach ($countries as $country)
                <option value="{{$country['country_id']}}">
                  {{ $country['country_name'] }}
                </option>
              @endforeach
            </select>
          </td>

          <td>
            <select name="currency[]" class="form-control">
              @foreach ($currency as $code => $name)
                <option value="{{ $code }}">
                  {{ $name }}
                </option>
              @endforeach
            </select>
          </td>

          <td>
            <input type="text" class="form-control" name="add_price[]">
          </td>

          <td>
            <div class="input-group">
              <input type="text" class="form-control" name="renew_price[]">
              <span id="` + i + `" class="input-group-addon btn_remove"><i class="fa fa-minus"></i></span>
            </div>
          </td>

        </tr>`)
      });

      $(document).on('click', '.btn_remove', function () {
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
      });

    });



    function myProduct(){
      var product = document.getElementById('planproduct').value;
      // console.log(product)
      $.ajax({
        type: 'get',
        url : "{{url('get-period')}}",
        data: {'product_id':product},
        success: function (data){
          console.log(data.subscription);

          if(data.subscription != 1 ){
            $('.plandays').hide();
          }
          else{
            $('.plandays').show();
          }
          if(data.agentEnable != 1) {//Check if Product quantity to be shown or No. of Agents
            document.getElementById("prodquant").disabled = false;
            document.getElementById("agentquant").disabled = true;

          } else if(data.agentEnable == 1){
            document.getElementById("agentquant").disabled = false;
            document.getElementById("prodquant").disabled = true;
          }

          var sub = data['subscription'];

        }
      });
    }
  </script>

@stop