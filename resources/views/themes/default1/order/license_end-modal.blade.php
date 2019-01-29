<div class="modal fade" id="licenseEndsModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="defaultModalLabel">Enter License Expiry Date</h4>
			<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
		</div>

		<div class="modal-body">
      <div id="error1"></div>
			<div id="response3"></div>
			   <input type="hidden" name="orderId" value="" id="order2">
		        <div class="form-group">
                    <!-- name -->
                    {!! Form::label('license',Lang::get('message.license_end'),['class'=>'required']) !!}
                   
                         <div class="input-group date">
                             <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                         </div>
                          <div id="response3"></div>
                     <input name="ends_at" type="text" value="" class="form-control" id="newDate2" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                        </div>
              </div>
        
		</div>
		
		  <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" id="licenseExpSave" class="btn btn-primary" value="{{Lang::get('message.save')}}">
            </div>
	</div>
</div>
</div>
@section('script')

@stop