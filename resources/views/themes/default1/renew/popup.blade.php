<a href="#renew" class="btn  btn-primary btn-xs" data-toggle="modal" data-target="#renew{{$id}}"><i class="fa fa-refresh"></i>&nbsp;Renew</a>
<div class="modal fade" id="renew{{$id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['url'=>'client/renew/'.$id]) !!}
            <div class="modal-header">
                 <h4 class="modal-title">Renew</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                
                <?php 
           
                $plans = App\Model\Payment\Plan::where('product',$productid)->pluck('name','id')->toArray();
                $userid = Auth::user()->id;
                ?>
                <div class="form-group {{ $errors->has('plan') ? 'has-error' : '' }}">
                        <!-- first name -->
                        {!! Form::label('plan','Plans',['class'=>'required']) !!}
                        {!! Form::select('plan',[''=>'Select','Plans'=>$plans],null,['class' => 'form-control','onchange'=>'getPrice(this.value)']) !!}
                        {!! Form::hidden('user',$userid) !!}
                    </div>

                
                     <div class="form-group {{ $errors->has('cost') ? 'has-error' : '' }}">
                        <!-- last name -->
                        {!! Form::label('cost',Lang::get('message.price'),['class'=>'required']) !!}
                        {!! Form::text('cost',null,['class' => 'form-control price','id'=>'price','readonly'=>'readonly']) !!}

                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
                 <button type="submit"  class="btn btn-primary"><i class="fa fa-check">&nbsp;&nbsp;</i>Save</button>
                {!! Form::close()  !!}
            </div>
            <!-- /Form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
  
<script>
    function getPrice(val) {
        
        var user = document.getElementsByName('user')[0].value;
        $.ajax({
            type: "get",
            url: "{{url('get-renew-cost')}}",
            data: {'user': user, 'plan': val},
            success: function (data) {
                $(".price").val(data);
            }
        });
    }
</script>