<div class="modal fade" id="create-plan-option">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create Plans</h4>
            </div>
            <div class="modal-body">



   
    {!! Form::open(['url'=>'plans','method'=>'post','id'=> 'plan']) !!}
      <div class="box-body">

        <div class="row">

            <div class="col-md-12">

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

                <div class="row">

                    <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                        {!! Form::text('name',null,['class' => 'form-control','id'=>'planname']) !!}
                        <h6 id="plannamecheck"> </h6>

                    </div>
                     <div class="col-md-4 form-group {{ $errors->has('product') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('product',Lang::get('message.product'),['class'=>'required']) !!}
                        {!! Form::select('product',[''=>'Select','Products'=>$products],null,['class' => 'form-control','id'=>'planproduct']) !!}
                        <h6 id = "productcheck"></h6>

                    </div>
                      <div class="col-md-4 form-group {{ $errors->has('days') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('days','Periods',['class'=>'required']) !!}
                        {!! Form::select('days',[''=>'Select','Periods'=>$periods],null,['class' => 'form-control','id'=>'plandays']) !!}
                       <h6 id="dayscheck"></h6>
                    </div>

                  

                    <div class="col-md-12">
                        <table class="table table-responsive">
                           
                                <td><b>{!! Form::label('currency',Lang::get('message.currency')) !!}</b></td>
                                

                                    <table class="table table-responsive">
                                        <tr>
                                            <th></th>
                                            <th>Add/Month</th>
                                            <th>Renew/Month</th>

                                        </tr>

                                        @foreach($currency as $key=>$value)
                                        <tr class="form-group {{ $errors->has('add_price.'.$key) ? 'has-error' : '' }}">
                                            <td>

                                                <input type="hidden" name="currency[{{$key}}]" value="{{$key}}">
                                                {!! Form::label('days',$value,['class'=>'required']) !!}
                                            
                                            </td>

                                            <td>
                                                
                                                {!! Form::text("add_price[$key]",null,['class' => 'form-control','id'=>'currency1']) !!}
                                                <h6 id= "currencycheck1"></h6>

                                            </td>
                                            
                                            <td>
                                                
                                                {!! Form::text("renew_price[$key]",null,['class' => 'form-control','id'=>'currency2']) !!}
                                                <h6 id= "currencycheck2"></h6>

                                            </td>
                                         
                                        </tr>
                                        @endforeach

                                      
                                    </table>

                        </table>
                      
                    </div>

                    
                    


                </div>





            </div>

        </div>

    </div>
      <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary " id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}</button>
                </div>

</div>

                           
{!! Form::close()  !!}

</div>
</div>
</div>

<script>
     $(document).ready(function(){
        $('#plannamecheck').hide();
      $('#productcheck').hide();
      $('#dayscheck').hide();
      $('#currencycheck').hide();

      $('#plan').submit(function(){
        function plan_nameCheck()
        {
            var plan_name = $('#planname').val();
            if (plan_name.length == ''){
                   $('#plannamecheck').show(); 
                   $('#plannamecheck').html('This field is required'); 
                   $('#plannamecheck').focus();
                   $('#planname').css("border-color","red");
                   $('#plannamecheck').css({"color":"red","margin-top":"5px"});
            }
            else{
                 $('#plannamecheck').hide();
                 $('#planname').css("border-color","");
                 return true;
            }
        }

         function product_check()
        {
            var product_name = $('#planproduct').val();
            if (product_name.length == ''){
                   $('#productcheck').show(); 
                   $('#productcheck').html('This field is required'); 
                   $('#productcheck').focus();
                   $('#planproduct').css("border-color","red");
                   $('#productcheck').css({"color":"red","margin-top":"5px"});
            }
            else{
                 $('#productcheck').hide();
                 $('#planproduct').css("border-color","");
                 return true;
            }
        }

        function days_check()
        {
            var days = $('#plandays').val();
            if (days.length == ''){
                   $('#dayscheck').show(); 
                   $('#dayscheck').html('This field is required'); 
                   $('#dayscheck').focus();
                   $('#plandays').css("border-color","red");
                   $('#dayscheck').css({"color":"red","margin-top":"5px"});
            }
            else{
                 $('#dayscheck').hide();
                 $('#plandays').css("border-color","");
                 return true;
            }
        }

         function currency1_check()
        {
            var currency1 = $('#currency1').val();
            if (currency1.length == ''){
                   $('#currencycheck1').show(); 
                   $('#currencycheck1').html('This field is required'); 
                   $('#currencycheck1').focus();
                   $('#currency1').css("border-color","red");
                   $('#currencycheck1').css({"color":"red","margin-top":"5px"});
            }
            else{
                 $('#currencycheck1').hide();
                 $('#currency1').css("border-color","");
                 return true;
            }
        }

        function currency2_check()
        {
            var currency2 = $('#currency2').val();
            if (currency2.length == ''){
                   $('#currencycheck2').show(); 
                   $('#currencycheck2').html('This field is required'); 
                   $('#currencycheck2').focus();
                   $('#currency2').css("border-color","red");
                   $('#currencycheck2').css({"color":"red","margin-top":"5px"});
            }
            else{
                 $('#currencycheck2').hide();
                 $('#currency2').css("border-color","");
                 return true;
            }
        }
        plan_nameCheck();
        product_check();
        days_check();
        currency1_check();
        currency2_check();

        if(plan_nameCheck() && product_check() && days_check() && currency1_check() &&  currency2_check()){
                return true;
             }
            else{
            return false;
          }
      });

    });
</script>


