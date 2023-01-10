<a href="#upgrade" class="btn  btn-primary btn-xs" data-toggle="modal" data-target="#upgrade{{$id}}"><i class="fa fa-upload"></i>&nbsp;upgrade</a>
<div class="modal fade" id="upgrade{{$id}}" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">

            {!! Form::open(['url'=>'client/upgrade/'.$id]) !!}

            <div class="modal-header">
                <h4 class="modal-title">upgrade</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                {!! Form::label('plan','Plans',['class'=>'required']) !!}
                <div class="form-group {{ $errors->has('plan') ? 'has-error' : '' }}">
                    <?php
                     $upgrade=App\Model\Product\UpgradeSettings::where('product_id',$productid)->pluck('upgrade_product_id')->toArray();
                     $plans = App\Model\Product\Product::whereIn('id', $upgrade)->pluck('name', 'id')->toArray();
                    ?>
                    <?php $userid = Auth::user()->id; ?>

                    <?php
                    $subscription=\App\Model\Product\Subscription::where('id',$id)->where('user_id',$userid)->value('id');
                    ?>

                    {!! Form::select('plan',[''=>'Select','Upgrade Or Downgrade'=>$plans],null,['class' => 'form-control','onchange'=>'getUpgradePrice(this.value,'.$subscription.')']) !!}

                    {!! Form::hidden('user',$userid) !!}


                </div>


                <div class="form-group {{ $errors->has('cost') ? 'has-error' : '' }}">
                    <!-- last name -->

                    {!! Form::label('cost',Lang::get('message.price'),['class'=>'required']) !!}
                    {!! Form::text('cost',null,['class' => 'form-control price','id'=>'price','readonly'=>'readonly']) !!}

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left closebutton" id="closebutton" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
                <button type="submit"  class="btn btn-primary"><i class="fa fa-check">&nbsp;&nbsp;</i>Save</button>
                {!! Form::close()  !!}
            </div>

            <!-- /Form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $('.closebutton').on('click',function(){
        location.reload();
    });

    function getUpgradePrice(val,sub) {
        var user = document.getElementsByName('user')[0].value;
        console.log(sub);
        $.ajax({
            type: "get",
            url: "{{url('get-upgrade-cost')}}",
            data: {'user': user, 'plan': val, 'subscription':sub},
            success: function (data) {
                $(".price").val(data);
            }
        });
    }
</script>