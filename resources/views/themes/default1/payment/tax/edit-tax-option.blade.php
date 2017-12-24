<div class="modal fade" id="edit-tax-option">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Tax Class</h4>
            </div>
            <div class="modal-body">
              
                <!-- Form  -->
                {!! Form::open(['method' => 'patch', 'id' => 'tax-edit-form'])!!}
               
                <!--  <form role="form" action="{{url('taxes/')}}" method="GET">
                     {!! csrf_field() !!} -->


                  



                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                    <input type="text"  name="taxname" id="tax-name"  class="form-control">
                    
                </div>

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                   
               
                    {!! Form::label('name',Lang::get('Product Name'),['class'=>'required']) !!}
                                <div class="col-md-2 form-group">
                <!-- first name -->
              
              
                  <select name="product"  class="form-control">
                        @foreach ($products as $product)
                        <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    </select>

            </div>
                   
       
                </div>

                 <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                   
               
                    {!! Form::label('Country',Lang::get('message.country'),['class'=>'required']) !!}
                    <?php $countries = \App\Model\Common\Country::pluck('country_name', 'country_code_char2')->toArray(); ?>
                    {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control','onChange'=>'getState(this.value);']) !!}

                        
       
                </div>

                <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('state',Lang::get('message.state')) !!}
                    <!-- {!! Form::select('state',[],null,['class' => 'form-control','id'=>'state-list']) !!} -->

                    <select name="state"  class="form-control" id="statess">

                        <option>Please Select Country</option>
                    </select>

                </div>

                 <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                    <!-- name -->
                    <label>TimeZone</label>
                    <?php $timezone = \App\Model\Common\Timezone::pluck('location','location')->toArray(); ?>

                    {!! Form::select('timezone',[''=>'Select a Timezone','Timezones'=>$timezone],null,['class' => 'form-control']) !!}

                </div>



                 <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('Rate',Lang::get('message.rate'),['class'=>'required']) !!}
                    <input type="text" id="rate-name" name="rate" value="" class="form-control">
                    
                </div>

                 <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('Start Date',Lang::get('Start Date'),['class'=>'required']) !!}
                   <input type="date" class="form-control" name="sdate">

                </div>

                 <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('End Date',Lang::get('End Date'),['class'=>'required']) !!}
                   <input type="date" class="form-control" name="edate">

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
<script>
    function getState(val) {


        $.ajax({
            type: "GET",
            url: "{{url('get-state')}}/" + val,
            success: function (data) {
                // console.log(data)
                
                    $("#statess").html(data);
                
            }
        });
    }
</script>
<!-- <script>
    $(document).ready(function() {
        $('.ourItem').each(function() {
            $(this).click(function(event){
                var text=$(this).text();
                 // $('#edit-category-option').val(text);
                $('#edit-category-option').val(text);

                console.log (text);
            });
        });


  
</script>
 -->