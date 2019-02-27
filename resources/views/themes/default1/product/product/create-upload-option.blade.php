<div class="modal fade" id="create-upload-option">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Product Details</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
              <!--   {!! Form::open(['url'=>'upload/save','files' => true]) !!} -->
                  <div id="error"></div>
                <div id="alertMessage1"></div>
                <div class="form-group">
                    <label> Product Name </label>
                 
                      <input type="text" id="productname" name="product" class="form-control" value="{{$product->name}}" readonly>
                 </div>
                
                 <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                   
                    {!! Form::label('Title',Lang::get('Title'),['class'=>'required']) !!}
                    <input type="text" id="producttitle" class="form-control" name="title">
                 </div>
                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                    <!-- last name -->
                {!! Form::label('description',Lang::get('message.description')) !!}
                <textarea class="form-control" id= "textarea3" name="description"></textarea>
               </div>

                <div class="form-group {{ $errors->has('version') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('Version',Lang::get('Version'),['class'=>'required']) !!}
                    <input type="text" class="form-control" id="productver" name="version">
                 </div>

                     
                 
              <div class="form-group {{ $errors->has('version') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('File',Lang::get('File'),['class'=>'required']) !!}
                   <div id="resumable-drop" style="display: none">
                <p><button id="resumable-browse" data-url="{{ url('chunkupload') }}" >Upload</button> or drop here
                    </p>
                </div>
                <ul id="file-upload-list" class="list-unstyled"  style="display: none">

               </ul>
                
              </div>
             <input type="hidden" name="file_ids" id="file_ids" value="">
              <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="submit" class="btn btn-primary" id="uploadVersion"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('Save')!!}</button>
            </div>
           
           <!--  <form id="formsubmitform"> -->
             
                   <!-- {!! Form::close()  !!} -->
                  <!-- </form> -->
                 <br>
                 <!--  <div id="files_list"></div>
                    <p id="loading"></p>
                    <input type="hidden" name="file_ids" id="file_ids" value=""> -->
                    

            </div>
            <!-- /Form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

<script>
    function getStates(val) {


        $.ajax({
            type: "GET",
            url: "{{url('get-state')}}/" + val,
            success: function (data) {
                // console.log(data)
              
                
                    $("#states").html(data);
                
            }
        });
    }
</script>
<script>

</script>
{!! Form::close()  !!}