<div class="modal fade" id="licesnseModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="defaultModalLabel">Enter Domain</h4>
			<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
		</div>
		<div class="modal-body">
			<div id="response"></div>
			   <input type="hidden" name="orderId" id="orderId">
		  <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('domain',Lang::get('message.domain'),['class'=>'required']) !!}
                    {!! Form::text('domain',null,['class' => 'form-control domainss' ,'id'=>'newDomain']) !!}
                           <h6 id ="domaincheck"></h6>
                </div>
          <div class="form-group">
			<span style="color:red;">*&nbsp By changing the existing licensed domain, all Installation on the current domain will be aborted. </span>
		</div>  
		</div>
		
		  <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" id="licenseSave" class="btn btn-primary" value="{{Lang::get('message.save')}}">
            </div>
	</div>
</div>
</div>