<a href="#renew" <?php if(\Cart::getContent()->isNotEmpty()) {?> class="btn  btn-primary btn-xs" data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="Make sure the cart is empty to Renew your product" onclick="return false" <?php } else {?> class="btn  btn-primary btn-xs" <?php } ?> data-toggle="modal" data-target="#renew{{$id}}"><i class="fa fa-refresh"></i>&nbsp;Renew</a>
<div class="modal fade" id="renew{{$id}}" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['url'=>'client/renew/'.$id]) !!}
            <div class="modal-header">
                 <h4 class="modal-title">Renew your order</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                
                <?php

              $plans = App\Model\Payment\Plan::join('products', 'plans.product', '=', 'products.id')
                      ->leftJoin('plan_prices','plans.id','=','plan_prices.plan_id')
                      ->where('plans.product',$productid)
                      ->where('plan_prices.renew_price','!=','0')
                      ->pluck('plans.name', 'plans.id')
                       ->toArray();

              //add more cloud ids until we have a generic way to differentiate
              if(in_array($productid,[117,119])){
                  $plans = array_filter($plans, function ($value) {
                      return stripos($value, 'free') === false;
                  });
              }
                // $plans = App\Model\Payment\Plan::where('product',$productid)->pluck('name','id')->toArray();
                $userid = Auth::user()->id;
                ?>
                <div class="form-group {{ $errors->has('plan') ? 'has-error' : '' }}">
                        <p><b>Current Agents:</b> {{$agents}}</b></p>
                        <p><b>Current Plan:</b> {{$planName}}</p>
                        <!-- first name -->
                        {!! Form::label('plan','Plans',['class'=>'required']) !!}
                    {!! Form::select('plan', ['' => 'Select', 'Plans' => $plans], null, [
    'class' => 'form-control plan-dropdown',
    'onchange' => 'fetchPlanCost(this.value, ' . $agents . ')',
]) !!}

                    {!! Form::hidden('user',$userid) !!}
                    </div>
                @if(in_array($productid,[117,119]))
                    <div class="form-group">
                        {!! Form::label('cost', 'Price per agent:', ['class' => 'col-form-label']) !!}

                        {!! Form::text('cost', null, ['class' => 'form-control priceperagent', 'id' => 'priceperagent', 'readonly'=>'readonly']) !!}
                    </div>

                     <div class="form-group">
                         {!! Form::label('agents', 'Agents:', ['class' => 'col-form-label']) !!}

                         {!! Form::number('agents', $agents, ['class' => 'form-control agents', 'id' => 'agents', 'placeholder' => '']) !!}
                     </div>
                @endif
                     <div class="form-group {{ $errors->has('cost') ? 'has-error' : '' }}">

                         {!! Form::label('cost',Lang::get('message.price'),['class'=>'required']) !!}

                        {!! Form::text('cost',null,['class' => 'form-control price','id'=>'price','readonly'=>'readonly']) !!}

                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left closebutton" id="closebutton" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
                 <button type="submit"  class="btn btn-primary" id="saveRenew" disabled><i class="fa fa-check" >&nbsp;&nbsp;</i>Save</button>
                {!! Form::close()  !!}
            </div>
            <!-- /Form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
  
<script>


        $('.closebutton').on('click', function () {
            location.reload();
        });
        var shouldFetchPlanCost = true; // Disable further calls until needed

   function fetchPlanCost(planId,agents=null) {
       $('#saveRenew').attr('disabled',true);
       if(!shouldFetchPlanCost){
           return
       }
       var user = document.getElementsByName('user')[0].value;
       shouldFetchPlanCost = false
       $.ajax({
           type: "get",
           url: "{{ url('get-renew-cost') }}",
           data: { 'user': user, 'plan': planId },
           success: function (data) {
               if(agents==null){
                   agents = parseInt($('.agents').val() || 0);
               }
               var totalPrice = agents * parseFloat(data);
               var someprice = totalPrice.toFixed(2);
               $.ajax({
                   url: 'processFormat', // Update with the correct URL
                   method: 'GET',
                   data: { totalPrice: someprice },
                   success: function (data) {
                       $('.price').val(data);
                       shouldFetchPlanCost = true;
                   },
               });
               $.ajax({
                   url: 'processFormat', // Update with the correct URL
                   method: 'GET',
                   data: { totalPrice: parseFloat(data)},
                   success: function (data) {
                       $('.priceperagent').val(data);
                       shouldFetchPlanCost = true;
                   },
               });
               $('#saveRenew').attr('disabled',false);
           }
       });
   }

        // Call the fetchPlanCost function when the plan dropdown selection changes
        $('#plan').on('change', function () {
            var selectedPlanId = $(this).val(); // Get the selected plan ID
            fetchPlanCost(selectedPlanId); // Call the function to fetch plan cost
        });

        // Call the fetchPlanCost function initially with the default selected plan
        $(document).ready(function () {
            var initialPlanId = $('#plan').val();
            fetchPlanCost(initialPlanId);
        });

        // Prevent form submission when Enter key is pressed
        $('form').on('keypress', function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });

        $(document).ready(function () {
        $('.agents').on('input', function () {
            var selectedPlanId = $('#renew{{$id}}').find('.plan-dropdown').val();
            if (selectedPlanId) {
                fetchPlanCost(selectedPlanId,$(this).val());
            }
        });

    });

</script>