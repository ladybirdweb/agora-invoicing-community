<div class="modal fade" id="create-tax-option">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{Lang::get('message.create-tax-class')}}</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                {!! Form::open(['url'=>'taxes/option','id'=>'taxClass']) !!}

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('name',Lang::get('Tax Type'),['class'=>'required']) !!}
                    <?php
                    $taxType = \App\Model\Payment\TaxOption::where('id', '1')->pluck('tax_enable')->toArray();
                    ?>
                    <!-- {!! Form::text('name',null,['class' => 'form-control']) !!} -->
                      <select name="name" id="gst" class="form-control">
                      <option value="Others">Others</option>
                       @if($taxType[0]==1)
                      <option value="Intra State GST">Intra State GST</option>
                      <option value="Inter State GST">Inter State GST</option>
                      <option value="Union Territory GST">Union Territory GST</option>
                        @endif
                      </select>
                </div>
                
                {!! Form::open(['url'=>'tax']) !!}
                 <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    
                    {!! Form::label('tax-name',Lang::get('Tax Name'),['class'=>'required']) !!}
                    {!! Form::text('tax-name',null,['class' => 'form-control','id'=>'taxname']) !!}
                   <h6 id ="namecheck"></h6>
                </div>
           
                 <div class="form-group">
                    <!-- name -->
                    {!! Form::label('status',Lang::get('message.status')) !!}
                    
                </div>

                 <div class="row">
                    <div class="col-md-3 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('active',Lang::get('message.active')) !!}
                        {!! Form::radio('active',1,true) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('active',Lang::get('message.inactive')) !!}
                        {!! Form::radio('active',0) !!}

                    </div>
                </div>
                  <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('country',Lang::get('message.country')) !!}
                    <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); 


                    ?>
                      {!! Form::select('country',[''=>'Any Country','Choose'=>$countries],null,['class' => 'form-control','onChange'=>'getState(this.value);','id'=>'countryvisible']) !!}

                      <!--  {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],['IN'],['class' => 'form-control hide','onChange'=>'getState(this.value);','id'=>'countrynotvisible','disabled'=>'disabled'])!!} -->
                     <input type='text' name="country1" id= "countrynotvisible" class="form-control hide" value="IN" readonly>
                   

                </div>
                  <div class="form-group showwhengst {{ $errors->has('state') ? 'has-error' : '' }}" style="display:block">
                    <!-- name -->
                    {!! Form::label('state',Lang::get('message.state')) !!}
                 

                    <select name="state"  class="form-control" id="statess">
                        <option name="state">Any State</option>
                    </select>

                </div>
                 <div class="form-group showwhengst{{ $errors->has('rate') ? 'has-error' : '' }}" style="display:block" >
                    <!-- name -->
                    {!! Form::label('rate',Lang::get('message.rate').' (%)',['class'=>'required']) !!}
                    {!! Form::text('rate',null,['class' => 'form-control','id'=>'rate']) !!}
                  

                </div>
                  
                


            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary " id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}</button>
            </div>
            {!! Form::close()  !!}
            <!-- /Form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
<script>
  
    function getState(val) {
      $.ajax({
            type: "GET",
            url: "{{url('get-state')}}/" + val,
            success: function (data) {
              $("#statess").html(data);
                
            }
        });
    }

   $(document).ready(function(){
    $('#gst').on('change', function() {
            if ( this.value != 'Others')
      {
         $(document).find('.showwhengst').hide();
         $(document).find('#countryvisible').addClass('hide');
         $(document).find('#countrynotvisible').removeClass('hide');

         
      }
      else
        {
             $(document).find('.showwhengst').show();
             $(document).find('#countrynotvisible').addClass('hide');
             $(document).find('#countryvisible').removeClass('hide');
        }
    });
});
</script>

<script>
     $(document).ready(function(){
        $('#namecheck').hide();
      
      $('#taxClass').submit(function(){
        function tax_nameCheck()
        {
            var tax_name = $('#taxname').val();
            if (tax_name.length == ''){
                   $('#namecheck').show(); 
                   $('#namecheck').html('This field is required'); 
                   $('#namecheck').focus();
                   $('#taxname').css("border-color","red");
                   $('#namecheck').css({"color":"red","margin-top":"5px"});
            }
            else{
                 $('#namecheck').hide();
                 $('#taxname').css("border-color","");
                 return true;
            }
        }

        
        tax_nameCheck();
       
       
        if(tax_nameCheck()){
                return true;
             }
            else{
            return false;
          }
      });

    });
</script>
{!! Form::close()  !!}