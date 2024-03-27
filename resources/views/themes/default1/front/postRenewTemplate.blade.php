        <div class="container py-4">

            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <div class="card border-width-3 border-radius-0 border-color-success">

                        <div class="card-body text-center">
                                <?php
                                $currency = $invoice->currency;
                                $cont = new \App\Http\Controllers\License\LicensePermissionsController();
                                $downloadPermission = $cont->getPermissionsForProduct($product->id);
                                $date = getDateHtml($date);
                                ?>

                            <p class="text-color-dark font-weight-bold text-4-5 mb-0"><i class="fas fa-check text-color-success me-1"></i> Thank You. Your Order has been received.</p>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between py-3 px-4 my-4">

                        <div class="text-center">
                            <span><strong class="text-color-dark">
                                Invoice Number</strong> <br>
                                {!! $invoice->number !!}
                            </span>
                        </div>
                            <div class="text-center mt-4 mt-md-0">
                            <span><strong class="text-color-dark">
                                Status</strong> <br>
                                Success
                            </span>
                        </div>  
                        <div class="text-center mt-4 mt-md-0">
                            <span> <strong class="text-color-dark">
                                Date </strong><br>
                               {!! $date !!}
                            </span>
                        </div>
                        <div class="text-center mt-4 mt-md-0">
                            <span><strong class="text-color-dark">
                                Payment Method </strong><br>
                                {{Session::get('payment_method')}}
                            </span>
                        </div>

                        <div class="text-center mt-4 mt-md-0">
                            <span><strong class="text-color-dark">
                                Total </strong><br>
                                {{currencyFormat($invoice->grand_total,$code = $currency)}}
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
                                    <strong class="d-block text-color-dark line-height-1 font-weight-semibold">{{$invoiceItem->product_name}} <span class="product-qty">x {{$invoiceItem->quantity}}</span></strong>
                                    <ul class="wc-item-meta" style="list-style: none; padding: 0;">
                                        <li style="display: inline-block;"><strong class="wc-item-meta-label">Order Number:</strong> <p style="display: inline;">{{ $order_number }}</p></li>
                                    </ul> 
                                </td>

                                    <td class="text-end align-top">
                                        <span class="amount font-weight-medium text-color-grey">{{currencyFormat($invoiceItem->subtotal,$code = $currency)}}</span><br>

                                    </td>

                                </tr>
                               
                            

                                <tr class="total">
                                    <td>
                                        <strong class="text-color-dark text-3-5">Total</strong>
                                    </td>
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