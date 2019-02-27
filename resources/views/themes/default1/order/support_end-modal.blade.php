<div class="modal fade" id="supportEndsModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="defaultModalLabel">Enter Support Expiry Date</h4>
			<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
		</div>

		<div class="modal-body">
      <div id="error3"></div>
			<div id="response4"></div>
			   <input type="hidden" name="orderId" value="" id="order3">
		        <div class="form-group">
                    <!-- name -->
                    {!! Form::label('support',Lang::get('message.support_end'),['class'=>'required']) !!}
                   
                         <div class="input-group date">
                             <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                         </div>
                     <input name="support_ends_at" type="text" value="" class="form-control" id="newDate3" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                        </div>
              </div>
        
		</div>
		
		  <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" id="supportExpSave" class="btn btn-primary" value="{{Lang::get('message.save')}}">
            </div>
	</div>
</div>
</div>
@section('script')

@stop