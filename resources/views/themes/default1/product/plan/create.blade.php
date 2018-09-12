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
                        <select name="days" value= "Choose" class="form-control" id="plandays" onchange="myFunction()">
                             <option value="">Choose</option>
                           @foreach($periods as $key=>$period)
                              <option value={{$key}}>{{$period}}</option>
                          @endforeach
                          </select>
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
   function myFunction(){
    var period = document.getElementById('plandays').value;
   if (period == 365){

     period = '/ One-Time' ; 
   }
    else if (period >= 30 && period < 365){
    period = '/ Month' ;
   }
  else if (period > 365){
    period= '/ Year';
  }
  else{
    period= '';
  }
    $('.periodChange').val(period);
  
  }
</script>
<script>
   function myProduct(){
         var product = document.getElementById('planproduct').value;
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

            var sub = data['subscription'];
           
           }
         });
 // console.log(product)
}
</script>


