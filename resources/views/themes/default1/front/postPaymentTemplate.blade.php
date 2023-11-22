  @foreach($invoiceItems as $invoiceItem)
      <?php
      $currency = $invoice->currency;
      $date = getDateHtml($invoiceItem->created_at);
      ?>


      @endforeach
        <div class="container py-4">

            <div class="row justify-content-center">
           


                <div class="col-lg-8">

                    <div class="card border-width-3 border-radius-0 border-color-success">

                        <div class="card-body text-center">

                            <p class="text-color-dark font-weight-bold text-4-5 mb-0"><i class="fas fa-check text-color-success me-1"></i> Thank You. Your Order has been received.</p><a>{{\Auth::user()->email}}</a>
                        </div>
                    </div>
                           @foreach($orders as $order)
                  <?php
                  $product = \App\Model\Product\Product::where('id', $order->product)->select('id', 'name','type')->first();
                  $cont = new \App\Http\Controllers\License\LicensePermissionsController();
                  $downloadPermission = $cont->getPermissionsForProduct($order->product);
                  ?>



                    <div class="d-flex flex-column flex-md-row justify-content-between py-3 px-4 my-4">

                        <div class="text-center">
                            <span>
                                Order Number <br>
                                <strong class="text-color-dark">{{$order->number}}</strong>
                            </span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                            <span>
                                Date <br>
                                <strong class="text-color-dark">{!! $date !!}</strong>
                            </span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                            <span>
                                Email <br>
                                <strong class="text-color-dark">{{\Auth::user()->email}}</strong>
                            </span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                              <?php
                              $orderTotal = $order->price_override;
                              ?>
                            <span>
                                Total <br>
                                <strong class="text-color-dark">{{currencyFormat($orderTotal,$code = $currency)}}</strong>
                            </span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                            <span>
                                Payment Method <br>
                                <strong class="text-color-dark">{{Session::get('payment_method')}}</strong>
                            </span>
                        </div>
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

                                <tr>
                                    <td>
                                        <strong class="d-block text-color-dark line-height-1 font-weight-semibold">{{$product->name}} <span class="product-qty">x{{$order->qty}}</span></strong>
                                    </td>
                                     <?php
                                    $orderTotal = $order->price_override;
                                    ?>
                                    <td class="text-end align-top">
                                        <span class="amount font-weight-medium text-color-grey">{{currencyFormat($orderTotal,$code = $currency)}}</span>
                                    </td>
                                </tr>


                                <tr class="total">
                                    <td>
                                        <strong class="text-color-dark text-3-5">Total</strong>
                                    </td>
                                    <?php
                                    $orderTotal = $order->price_override;
                                    ?>
                                    <td class="text-end">
                                        <strong class="text-color-dark"><span class="amount text-color-dark text-5">{{currencyFormat($orderTotal,$code = $currency)}}</span></strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($downloadPermission['downloadPermission'] == 1 && $product->type != '4')

                        <a href="{{ url("product/download/$order->product/$invoice->number") }}" class="btn btn-dark btn-modern text-uppercase text-3 py-3" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>&nbsp;&nbsp;Download the Latest Version here</a>
                        @else

                  @endif
                  @endforeach
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

