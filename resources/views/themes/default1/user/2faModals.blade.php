<div class="modal fade" id="2fa-modal1">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Set up Authenticator</h4>
    </div>
    <div class="modal-body">
                
      <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
          {!! Form::label('name',Lang::get('message.varify_password'),['class'=>'required']) !!}
          <input type="text" name="password" id="user_password" placeholder="Enter Password" class="form-control" required="required">
      </div>
      <span id="passerror"></span>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      <button type="button" id="verify_password" class="btn btn-primary">Validate</button>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>

<div class="modal fade" id="2fa-modal2">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Set up Authenticator</h4>
    </div>
    <div class="modal-body">
            <ul>
              <li>Get the Authenticator App from the Play Store.</li>
              <li>In the App select <b>Set up account.</b></li>
              <li>Choose <b>Scan a barcode.</b></li>
            </ul>     
      <div id="barcode">
         
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      <button type="button" id="scan_complete" class="btn btn-primary">Next</button>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>