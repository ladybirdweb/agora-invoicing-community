<a href="#renew" <?php if(\Cart::getContent()->isNotEmpty()) {?> class="btn  btn-primary btn-xs" data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="Make sure the cart is empty to Renew your product" onclick="return false" <?php } else {?> class="btn  btn-primary btn-xs" <?php } ?> data-toggle="modal" data-target="#renew{{$id}}"><i class="fa fa-refresh"></i>&nbsp;Renew</a>
<div class="modal fade" id="renew{{$id}}" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">

            {!! Form::open(['url'=>'client/renew/'.$id]) !!}
            <div class="modal-header">
                 <h4 class="modal-title">Renew your order</h4>
            </div>
            <div class="modal-body">

                <label>Current number of agents: {{$agents}}</label>
                <label>Current Plan: {{$planName}}</label>
                <!-- Form  -->
                
                <?php

              $plans = App\Model\Payment\Plan::join('products', 'plans.product', '=', 'products.id')
                      ->leftJoin('plan_prices','plans.id','=','plan_prices.plan_id')
                      ->where('plans.product',$productid)
                      ->where('plan_prices.renew_price','!=','0')
                      ->pluck('plans.name', 'plans.id')
                       ->toArray();

                $planIds = array_keys($plans);

                $renewalPrices = \App\Model\Payment\PlanPrice::whereIn('plan_id', $planIds)->where('currency',getCurrencyForClient(\Auth::user()->country))
                    ->latest()
                    ->pluck('renew_price', 'plan_id')
                    ->toArray();

                foreach ($plans as $planId => $planName) {
                    if (isset($renewalPrices[$planId])) {
                        if(in_array($productid,[117,119])) {
                            $plans[$planId] .= " (Renewal price-per agent: " . currencyFormat($renewalPrices[$planId], getCurrencyForClient(\Auth::user()->country), true) . ")";
                        }
                        else{
                            $plans[$planId] .= " (Renewal price: " . currencyFormat($renewalPrices[$planId], getCurrencyForClient(\Auth::user()->country), true) . ")";
                        }
                    }
                }
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
                        <!-- first name -->
                        {!! Form::label('plan','Plans',['class'=>'required']) !!}
                        {!! Form::select('plan',['' => 'Select'] + $plans, null, ['class' => 'form-control plan-dropdown', 'onchange' => 'fetchPlanCost(this.value, ' . $agents . ')',]) !!}

                    {!! Form::hidden('user',$userid) !!}
                    </div>
                @if(in_array($productid,[117,119]))
                     <div class="form-group">
                         {!! Form::label('agents', 'Agents:', ['class' => 'col-form-label']) !!}

                         {!! Form::number('agents', $agents, ['class' => 'form-control agents', 'id' => 'agents', 'placeholder' => '']) !!}
                     </div>
                @endif
                <div class="form-group {{ $errors->has('cost') ? 'has-error' : '' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label style="display: inline-block;">Price to be paid:</label>
                        </div>
                        <div class="col-md-6">
                            <span id="price" class="price" style="display: inline-block; margin-left: -141px;"></span>
                        </div>
                    </div>
                </div>
                <div class="overlay" style="display: none;"></div> <!-- Add this line -->

                <div class="loader-wrapper" style="display: none; background: white;" >
                    <i class="fas fa-spinner fa-spin" style="font-size: 40px;"></i>

                </div>

            </div>
            <div class="modal-footer" style="margin-top: -23px;">
                <button type="button" class="btn btn-default pull-left closebutton" id="closebutton" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
                 <button type="submit"  class="btn btn-primary" id="saveRenew" disabled><i class="fa fa-check" >&nbsp;&nbsp;</i>Renew</button>
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
       // Show the loader and disable modal body
       // Show the loader and disable modal body
       $('.loader-wrapper').show();
       $('.overlay').show(); // Show the overlay
       $('.modal-body').css('pointer-events', 'none');

       if(!shouldFetchPlanCost){
           return
       }
       var user = document.getElementsByName('user')[0].value;
       shouldFetchPlanCost = false
       agents=$('.agents').val();
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
                       $('.price').text(data);
                       shouldFetchPlanCost = true;
                   },
               });
               $('.loader-wrapper').hide();
               $('.overlay').hide(); // Hide the overlay
               $('.modal-body').css('pointer-events', 'auto');
               $('#saveRenew').attr('disabled', false);
               $("#agents").prop("disabled", false);
           }
       });
   }

        // Call the fetchPlanCost function when the plan dropdown selection changes
        $('#plan').on('change', function () {
            var selectedPlanId = $(this).val(); // Get the selected plan ID
            var agts = $('.agents').val();
            fetchPlanCost(selectedPlanId,agts); // Call the function to fetch plan cost
        });

        // Call the fetchPlanCost function initially with the default selected plan
        $(document).ready(function () {
            var initialPlanId = $('#plan').val();
            var agt = $('.agents').val();
            fetchPlanCost(initialPlanId,agt);
        });

        // Prevent form submission when Enter key is pressed
        $('form').on('keypress', function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });

        $(document).ready(function () {
        $('.agents').on('input', function () {
            $("#agents").prop("disabled", true);
            var selectedPlanId = $('#renew{{$id}}').find('.plan-dropdown').val();
            if (selectedPlanId) {
                fetchPlanCost(selectedPlanId,$(this).val());
                $("#agents").prop("disabled", false);

            }
            $("#agents").prop("disabled", false);
        });

    });

</script>
<style>
    .loader-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 300px;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent;
        z-index: 9998; /* Below the loader */
    }


</style>
