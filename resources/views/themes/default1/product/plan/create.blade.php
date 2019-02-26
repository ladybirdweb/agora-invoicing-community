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
                        <select name="product" value= "Choose" class="form-control" id="planproduct" onchange="myProduct()">
                             <option value="">Choose</option>
                           @foreach($products as $key=>$product)
                              <option value={{$key}}>{{$product}}</option>
                          @endforeach
                          </select>
                       <!--  {!! Form::select('product',[''=>'Select','Products'=>$products],null,['class' => 'form-control','id'=>'planproduct']) !!} -->
                        <h6 id = "productcheck"></h6>

                    </div>
                      <div class="col-md-4 form-group plandays {{ $errors->has('days') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('days','Periods',['class'=>'required']) !!}
                      <div class="input-group">
                        <select name="days" value= "Choose" class="form-control" id="plandays">
                             <option value="">Choose</option>
                           @foreach($periods as $key=>$period)
                              <option value={{$key}}>{{$period}}</option>
                          @endforeach
                          </select>
                           <span class="input-group-addon" id="period"><i class="fa fa-plus"></i></span>
                        </div>
                           <h6 id="dayscheck"></h6>
                    
                        <!-- {!! Form::select('days',[''=>'Select','Periods'=>$periods],null,['class' => 'form-control','id'=>'plandays']) !!} -->
                         </div>

                  

                    <div class="col-md-12">
                        <table class="table table-responsive">
                           
                               
                                

                                    <table class="table table-responsive">
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
                                                
                                                {!! Form::text("add_price[$key]",null,['class' => 'form-control periodChange' ,'style'=>'text-align:center;','placeholder'=>'Enter Price']) !!}
                                                <h6 id= "currencycheck1"></h6>

                                            </td>
                                            
                                            <td>
                                                
                                                {!! Form::text("renew_price[$key]",null,['class' => 'form-control periodChange','style'=>'text-align:center;','placeholder'=>'Enter Price']) !!}
                                                <h6 id= "currencycheck2"></h6>

                                            </td>


                                         
                                        </tr>
                                        @endforeach

                                      
                                    </table>

                        </table>
                      
                    </div>
                        <div class="col-md-12 form-group">
                        <!-- last name -->
                        {!! Form::label('description','Price Description') !!}
                        {!! Form::text("price_description",null,['class' => 'form-control' ,'placeholder'=>'Enter Price Description to be Shown on Pricing Page. eg: Yearly,Monthly,One-Time']) !!}
                           <h6 id="dayscheck"></h6>
                    
                        <!-- {!! Form::select('days',[''=>'Select','Periods'=>$periods],null,['class' => 'form-control','id'=>'plandays']) !!} -->
                         </div>
                       
                        <div class="col-md-6 form-group">
                        <!-- last name -->
                        {!! Form::label('product_quantity','Product Quantity',['class'=>'required'])!!}
                        {!! Form::number("product_quantity",null,['class' => 'form-control','disabled'=>'disabled','id'=>'prodquant','placeholder'=>'Pricing for No. of Products']) !!}
                    
                         </div>
                        
                        <div class="col-md-6 form-group">
                        <!-- last name -->
                        <label data-toggle="tooltip" data-placement="top" title="If '0' Agents Selected, Plan will be for Unlimited Agents">
                         {!! Form::label('agents','No. of Agents',['class'=>'required']) !!}</label>
                        {!! Form::number("no_of_agents",null,['class' => 'form-control' ,'disabled'=>'disabled','id'=>'agentquant','placeholder'=>'Pricing for No. of Agents']) !!}
                    
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
  /**
   * Add Periods In the same Modal as Create Plan
   *
   * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
   *
   * @date   2019-01-08T10:35:38+0530
   *
   */
  $(function(){
      $("#period").on('click',function(){
        $("#period-modal-show").modal();
      })
      $('.save-periods').on('click',function(){
        $("#submit1").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
        $.ajax ({
          type : 'POST',
          url: "{{url('postInsertPeriod')}}",
          data : { "name": $('#new-period').val(),
                    "days": $('#new-days').val()},
          success:  function(data){
            $('#plandays').append($("<option/>",{
              value : data.id,
              text  : data.name,
            }))
             $('#new-period').val("");
              $('#new-days').val("");
             var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>Period Added Successfully</div>';
              $('#error').hide();
              $('#alertMessage').show();
            $('#alertMessage').html(result+ ".");
            $("#submit1").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
          },
          error: function (error){
            var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<br><br><ul>';
            for (key in error.responseJSON.errors) {
              html += '<li>'+ error.responseJSON.errors[key][0] + '</li>'
            }
             html += '</ul></div>';
             $('#alertMessage').hide();
             $('#error').show();
              document.getElementById('error').innerHTML = html;
              $("#submit1").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
          }

        })
       })
  })

</script>
<script>
   function myProduct(){
         var product = document.getElementById('planproduct').value;
         $.ajax({
            type: 'get',
            url : "{{url('get-period')}}",
            data: {'product_id':product},
           success: function (data){
            if(data.subscription != 1 ){ //Check if Periods to be shown or nor
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
                     }
                 });
             }
</script>


