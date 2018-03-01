<div class="modal fade" id="download-list">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Product Details</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                {!! Form::open(['method' => 'patch', 'files' => true ,'id' => 'upload-edit-form']) !!}

                <div class="form-group">
                    <label> Product Name </label>
                 
                      <input type="text" name="product" class="form-control" value="" readonly>
                   
                    </div>
               
                 <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                   
                    {!! Form::label('Title',Lang::get('Title'),['class'=>'required']) !!}
                     
                      <input type="text" id="product-title" class="form-control" value="" name="title">
                 </div>



                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('Description',Lang::get('Description'),['class'=>'required']) !!}
                    <input type="text" id="product-description" class="form-control" name="description">
                 </div>
                
                <div class="form-group {{ $errors->has('version') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('Version',Lang::get('Version'),['class'=>'required']) !!}
                    <input type="text" id="product-version" class="form-control" name="version">
                 </div>

                  <div class="form-group {{ $errors->has('version') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('File',Lang::get('File'),['class'=>'required']) !!}
                    <input type="File" class="form-control" name="file" multiple>
                 </div>
                

                 





               

            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" data-id="$model->id" class="btn btn-primary" value="{{Lang::get('message.save')}}">
            </div>
           
            <!-- /Form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> 
 </form>


