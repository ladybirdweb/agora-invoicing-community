@foreach($invoiceItems as $invoiceItem)
      <?php
      $currency = $invoice->currency;
      $date = getDateHtml($invoiceItem->created_at);
      $apiKey = \App\ApiKey::value('stripe_secret');

      ?>

      @endforeach
        <div class="container py-4">

            <div class="row justify-content-center">
           


                <div class="col-lg-8">

                    <div class="card border-width-3 border-radius-0 border-color-success">

                        <div class="card-body text-center">

                            <p class="text-color-dark font-weight-bold text-4-5 mb-0"><i class="fas fa-check text-color-success me-1"></i> Thank You. Your Order has been received.</p>
                        </div>
                    </div>
             @php
                $cont = new \App\Http\Controllers\License\LicensePermissionsController();
            @endphp
            
            <div class="my-4">
                <div class="d-flex flex-column flex-md-row justify-content-between py-3 px-4">
                    <div class="text-center">
                        <span><strong class="text-color-dark">Order Number</strong></span>
                    </div>
                    <div class="text-center mt-4 mt-md-0">
                        <span><strong class="text-color-dark">Date</strong></span>
                    </div>
                    <div class="text-center mt-4 mt-md-0">
                        <span><strong class="text-color-dark">Total</strong></span>
                    </div>
                    <div class="text-center mt-4 mt-md-0">
                        <span><strong class="text-color-dark">Payment Method</strong></span>
                    </div>
                    <div class="text-center mt-4 mt-md-0">
                        <span><strong class="text-color-dark">File</strong></span>
                    </div>
                    
                </div>
            
                @foreach($orders as $order)
                    @php
                        $product = \App\Model\Product\Product::where('id', $order->product)->select('id', 'name','type')->first();
                        $downloadPermission = $cont->getPermissionsForProduct($order->product);
                        $orderTotal = $order->price_override;
                    @endphp
            
                    <div class="d-flex flex-column flex-md-row justify-content-between py-3 px-4">
                        <div class="text-center">
                            <span>{{$order->number}}</span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                            <span>{!! $date !!}</span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                            <span style="position: absolute;left: 50%;">{{currencyFormat($orderTotal, $currency)}}</span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                            <span>{{Session::get('payment_method')}}</span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                            <span>
                            <a href="{{ url("product/download/$order->product/$invoice->number") }}" class="btn btn-light-scale-2 btn-sm text-dark" data-toggle="tooltip" aria-label="Click here to download" data-bs-original-title="Click here to download"><i class="fa fa-download"> </i></a>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            
               <div class="card border-width-3 border-radius-0 border-color-hover-dark mb-4">

                        <div class="card-body">

                            <h4 class="font-weight-bold text-uppercase text-4 mb-3">Your Order</h4>

                            <table class="shop_table cart-totals mb-0">

                                <tbody>

                                <tr>
                                    <td colspan="2" class="border-top-0">
                                        <strong class="text-color-dark">Product</strong>
                                    </td>
                                </tr>
                                @foreach($orders as $order)
                                <?php
                                 $product = \App\Model\Product\Product::where('id', $order->product)->select('id', 'name','type')->first();
                                ?>

                                <tr>
                                    <td>
                                        <strong class="d-block text-color-dark line-height-1 font-weight-semibold">{{$product->name}} <span class="product-qty">x {{$order->qty}}</span></strong>
                                    </td>
                                     <?php
                                    $orderTotal = $order->price_override;
                                    ?>
                                    <td class="text-end align-top">
                                        <span class="amount font-weight-medium text-color-grey">{{currencyFormat($orderTotal,$code = $currency)}}</span>
                                    </td>
                                </tr>
                               
                                @endforeach


                                <tr class="total">
                                    <td>
                                        <strong class="text-color-dark text-3-5">Total</strong>
                                    </td>
                                    <?php
                                    $orderTotal = $order->price_override;
                                    ?>
                                    <td class="text-end">
                                        <strong class="text-color-dark"><span class="amount text-color-dark text-5">{{currencyFormat($invoice->grand_total,$code = $currency)}}</span></strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            

                  
                </div>
                
            </div>

        </div>

<script>
function deploy(button) {
var orderNumber = button.value;
openModal(orderNumber);
}


function openModal(orderNumber) {
$('#tenant .modal-body').text('Order Number: ' + orderNumber);
$('#tenant').modal('show');
}
</script>



