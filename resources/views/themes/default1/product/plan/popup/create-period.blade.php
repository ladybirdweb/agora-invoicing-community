<div class="modal fade" id="period-modal-show">
  <div class="modal-dialog">
    <div class="modal-content">
     
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Periods</h4>
      </div>
      <div class="modal-body">
         <div id="alertMessage"></div>
         <div id="error"></div>
       <input type="text" name="periods" id="new-period" class="form-control" placeholder="Enter Period"> <br/>
       <input type="text" name="days" id="new-days" class="form-control" placeholder="Enter Days">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" id="submit1" class="btn btn-primary save-periods"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
