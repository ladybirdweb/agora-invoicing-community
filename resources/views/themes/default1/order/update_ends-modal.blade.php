<div class="modal fade" id="updateEndsModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="defaultModalLabel">Enter Updates Expiry Date</h4>
			<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
		</div>
		<div class="modal-body">
      <div id="error2"></div>
			 <div id="response2"></div>
			   <input type="hidden" name="orderId" value="" id="order">
		  <div class="form-group">
                    <!-- name -->
                    {!! Form::label('updates',Lang::get('message.update_end'),['class'=>'required']) !!}
                   
                         <div class="input-group date">
                             <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                         </div>
                     <input name="update_ends_at" type="text" value="" class="form-control" id="newDate" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                        </div>
                    
                           <h6 id ="domaincheck"></h6>
                </div>
        
		</div>
		
		  <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" id="updatesSave" class="btn btn-primary" value="{{Lang::get('message.save')}}">
            </div>
	</div>
</div>
</div>
@section('script')
 <script type="text/javascript">
 	
 
      $('#domaincheck').hide();
function validdomaincheck(){
          alert('ds');
            var pattern = new RegExp(/^((?!-))(xn--)?[a-z0-9][a-z0-9-_]{0,61}[a-z0-9]{0,1}\.(xn--)?([a-z0-9\-]{1,61}|[a-z0-9-]{1,30}\.[a-z]{2,})$/);
              if (pattern.test($('#newDomain').val())){
                 $('#domaincheck').hide();
                 $('#newDomain').css("border-color","");
                 return true;
               
              }
              else{
                 $('#domaincheck').show();
               $('#domaincheck').html("Please enter a valid Domain");
                 $('#domaincheck').focus();
                  $('#newDomain').css("border-color","red");
                 $('#domaincheck').css({"color":"red","margin-top":"5px"});
                   domErr = false;
                    return false;
              
      }

    }


</script>
@stop