 <div class="modal fade" id="edit-type">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{Lang::get('message.edit-license-type')}}</h4>
            </div>
           {!! Form::open(['method' => 'patch', 'id' =>  'type-edit-form'])!!}
            <div class="modal-body">
                
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name',Lang::get('message.license-type-name'),['class'=>'required']) !!}
                    <input type="text" name="name" id="tname" class="form-control" required="required">
                </div>
            </div>
            <div class="modal-footer">
                 <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary " id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
