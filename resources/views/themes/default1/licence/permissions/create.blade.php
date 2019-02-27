<div class="modal fade" id="all-permissions">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{Lang::get('message.add-permissions')}}</h4>
            </div>
            <div class="modal-body">              
                <div id="permissionresponse"></div>
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    

                  @foreach($allPermissions as $permission)
                  <input type="checkbox" name="checkme" class="permission_checkbox minimal" value="{{$permission->id}}" id="permissioncheck">&nbsp;
                  {{$permission->permissions}}
                   <br/><br/>
                  @endforeach
                </div>
            </div>
            <div class="modal-footer">
                 <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary " id="permissionssubmit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}</button>
            </div>
            <script>

            </script>
        </div>
    </div>
</div>
