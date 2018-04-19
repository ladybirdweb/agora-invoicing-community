<div class="modal fade" id="create-tax-option">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create Tax Class</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                {!! Form::open(['url'=>'taxes/option']) !!}

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('name',Lang::get('Tax Class Name'),['class'=>'required']) !!}
                    <!-- {!! Form::text('name',null,['class' => 'form-control']) !!} -->
                      <select name="name" class="form-control">
                      <option>Others</option>
                      <option>Intra State GST</option>
                      <option>Inter State GST</option>
                      <option>Union Territory</option>
  
                      </select>
                </div>
                
                
                 <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    
                    {!! Form::label('tax-name',Lang::get('Tax Name'),['class'=>'required']) !!}
                    {!! Form::text('tax-name',null,['class' => 'form-control']) !!}
                  
                </div>

                
                 <div class="form-group">
                    <!-- name -->
                    {!! Form::label('status',Lang::get('message.status')) !!}
                    
                </div>

                 <div class="row">
                    <div class="col-md-3 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('active',Lang::get('message.active')) !!}
                        {!! Form::radio('active',1,true) !!}

                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                        <!-- name -->
                        {!! Form::label('active',Lang::get('message.inactive')) !!}
                        {!! Form::radio('active',0) !!}

                    </div>
                </div>
                  <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('country',Lang::get('message.country')) !!}
                    <?php $countries = \App\Model\Common\Country::pluck('country_name', 'country_code_char2')->toArray(); ?>
                    {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control','onChange'=>'getState(this.value);']) !!}

                </div>
                  <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('state',Lang::get('message.state')) !!}
                 

                    <select name="state"  class="form-control" id="statess">
                        <option name="state">Please Select Country</option>
                    </select>

                </div>
                 <div class="form-group {{ $errors->has('rate') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('rate',Lang::get('message.rate').' (%)',['class'=>'required']) !!}
                    {!! Form::text('rate',null,['class' => 'form-control']) !!}

                </div>
                  
                


            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="{{Lang::get('message.save')}}">
            </div>
            {!! Form::close()  !!}
            <!-- /Form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
<script>
  <script>
    function getState(val) {
      $.ajax({
            type: "GET",
            url: "{{url('get-state')}}/" + val,
            success: function (data) {
              $("#statess").html(data);
                
            }
        });
    }
</script>
{!! Form::close()  !!}