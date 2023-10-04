<div class="container">
    <div>
        <!-- main content -->
        <div>
            <div role="main">
                <article>
                    <div class="page-content">
                        <div>
                            <div>
                                <?php
                                $currency = $invoice->currency;
                                $cont = new \App\Http\Controllers\License\LicensePermissionsController();
                                $downloadPermission = $cont->getPermissionsForProduct($product->id);
                                $date = getDateHtml($date);
                                ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong><i class="far fa-thumbs-up"></i>
                                        <strong>Your Payment has been received. A confirmation Mail has been sent to you on <a>{{\Auth::user()->email}}</a></strong>
                                </div>

                                <section>
                                    <table class="table table-bordered table-hover ">
                                        <thead class="thead-light">
                                            <tr>
                                                <th colspan="4">Order Details</th>

                                            </tr>
                                        </thead>

                                        <tfoot>
                                            <tr>
                                            <tr>
                                                <th scope="row">Product Name:</th>
                                                <td style="font-weight: normal"> {{$invoiceItem->product_name}}<td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Quantity:</th>
                                                <td style="font-weight: normal">{{$invoiceItem->quantity}}</td>
                                            </tr>
                                            <tr>
                                            <tr>


                                                <th scope="row">Order No.:</th>
                                                <td style="font-weight: normal">{!! $order_number !!}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Invoice No.:</th>
                                                <td style="font-weight: normal">{{$invoice->number}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Payment method:</th>
                                                <td style="font-weight: normal">{{Session::get('payment_method')}}</td>
                                            </tr>
                                            <tr>
                                            <tr>
                                                <th scope="row">Date:</th>
                                                <td>{!! $date !!}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">Total:</th>
                                                <td style="font-weight: normal"><span class="woocommerce-Price-amount amount">{{currencyFormat($invoiceItem->subtotal,$code = $currency)}}</span></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <br>
                                </section>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div><!-- end main content -->

    </div>
</div>