<div class="modal fade" id="domain">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Domain</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                {!! Form::open(['url'=>'checkout','method'=>'get']) !!}
                
                @foreach($domain as $product)
                
                <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                    <!-- name -->
                    {!! Form::label('domain',Lang::get('message.domain'),['class'=>'required']) !!}
                    {!! Form::text('domain['.$product.']',null,['class' => 'form-control']) !!}

                </div>
                
                @endforeach


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

{!! Form::close()  !!}