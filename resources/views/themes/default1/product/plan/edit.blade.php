@extends('themes.default1.layouts.master')
@section('title')
Edit Plan
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Edit Plan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('plans')}}"><i class="fa fa-dashboard"></i> All Plans</a></li>
            <li class="breadcrumb-item active">Edit Plan</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')
<div class="card card-primary card-outline">



         {!! Form::model($plan,['url'=>'plans/'.$plan->id,'method'=>'patch']) !!}


    <div class="card-body">

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

                            <tr>
                                
                                <td>

                                    <table class="table">
                                        <tr>
                                            <th><b>{!! Form::label('currency',Lang::get('message.currency')) !!}</b></th>
                                            <th>{{Lang::get('message.regular-price')}}</th>
                                            <th>{{Lang::get('message.renew-price')}}</th>

                                        </tr>

                                        @foreach($currency as $key=>$value)
                                        <tr class="form-group {{ $errors->has('add_price.'.$key) ? 'has-error' : '' }}">
                                            <td>

                                                <input type="hidden" name="currency[{{$key}}]" value="{{$key}}">
                                                {!! Form::label('days',$value,['class'=>'required']) !!}

                                            </td>

                                            <td>
                                                @if(key_exists($key,$add_price))
                                                {!! Form::text("add_price[$key]",$add_price[$key],['class' => 'form-control periodChange']) !!}
                                                 @else
                                                 {!! Form::text("add_price[$key]",null,['class' => 'form-control']) !!}
                                                 @endif

                                            </td>
                                            

                                            <td>
                                                @if(key_exists($key,$renew_price))
                                                {!! Form::text("renew_price[$key]",$renew_price[$key],['class' => 'form-control periodChange']) !!}
                                                 @else
                                                 {!! Form::text("renew_price[$key]",null,['class' => 'form-control periodChange']) !!}
                                                 @endif

                                            </td>

                                        </tr>
                                        @endforeach


                                    </table>

                                </td>
                            </tr>  
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
                        {!! Form::number("product_quantity",$productQunatity,['class' => 'form-control','disabled'=>'disabled','id'=>'prodquant','placeholder'=>'Pricing for No. of Products']) !!}
                    
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
        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-sync-alt">&nbsp;</i>{!!Lang::get('message.update')!!}</button>
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
 // console.log(product)

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