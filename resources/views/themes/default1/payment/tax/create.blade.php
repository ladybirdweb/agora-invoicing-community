<div class="modal fade" id="create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create Tax</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                {!! Form::open(['url'=>'tax']) !!}

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                    {!! Form::text('name',null,['class' => 'form-control']) !!}

                </div>
                <div class="form-group {{ $errors->has('level') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('level',Lang::get('message.level'),['class'=>'required']) !!}
                    {!! Form::select('level',[1=>1,2=>2],null,['class' => 'form-control']) !!}

                </div>
                <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('country',Lang::get('message.country')) !!}
                    <?php $countries = \App\Model\Common\Country::lists('name', 'id')->toArray(); ?>
                    {!! Form::select('country',[''=>'Select a Country','Countries'=>$countries],null,['class' => 'form-control','onChange'=>'getState(this.value);']) !!}

                </div>
                <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('state',Lang::get('message.state')) !!}
                    <!--{!! Form::select('state',[],null,['class' => 'form-control','id'=>'state-list']) !!}-->
                    <select name="state" id="state-list" class="form-control">
                        <option value="">Select State</option>
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
    function getState(val) {


        $.ajax({
            type: "POST",
            url: "{{url('get-state')}}",
            data: 'country_id=' + val,
            success: function (data) {
                $("#state-list").html(data);
            }
        });
    }
</script>
{!! Form::close()  !!}