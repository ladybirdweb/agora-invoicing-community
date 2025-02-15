 <div class="modal fade" id="create-type">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{Lang::get('message.create-type')}}</h4>
            </div>
            {!! html()->form('POST', 'product-type') !!}
            <div class="modal-body">
                
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! html()->label(Lang::get('message.type-name'))->class('required') !!}
                    <input type="text" name="name" class="form-control" required="required">
                </div>
            </div>
            <div class="modal-footer">
                 <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary " id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}</button>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
</div>
