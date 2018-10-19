 <div class="modal fade" id="createComment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{Lang::get('message.add_new_comment')}}</h4>
            </div>
            {!! Form::open(['url'=>'comment']) !!} 
            <div class="modal-body">
                
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name',Lang::get('message.comment'),['class'=>'required']) !!}
                    <textarea name="description" class="form-control" required="required" rows="6" cols="50"></textarea>
                    <input type="hidden" name="user_id" value="{{$client->id}}"> 
                    <input type="hidden" name="updated_by_user_id" value="{{\Auth::user()->id}}"> 
                </div>
            </div>
            <div class="modal-footer">
                 <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary " id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
