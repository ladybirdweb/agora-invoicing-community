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
                                ?>

                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong><i class="far fa-thumbs-up"></i>
                                        <strong>Your Payment has been received. A confirmation Mail has been sent to you on <a>{{\Auth::user()->email}}</a></strong>
                                </div>


                                <section>

                                    <h2 style="margin-top:40px ; margin-bottom:10px;">Payment Details</h2>

                                    <table class="table table-bordered table-hover ">
                                        <thead class="thead-light">
                                            <tr>
                                                <th colspan="4">Order Details</th>

                                            </tr>
                                        </thead>

                                        <tbody>

                                            <tr class="woocommerce-table__line-item order_item">

                                                <td class="woocommerce-table__product-name product-name">
                                                    <strong>{{$invoiceItem->product_name}}</strong> <strong>× {{$invoiceItem->quantity}}</strong>
                                                </td>

                                                <td class="woocommerce-table__product-total product-total">
                                                    <span class="woocommerce-Price-amount amount">{{currencyFormat($invoiceItem->subtotal,$code = $currency)}}</span>
                                                </td>

                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th scope="row">Product Name:</th>
                                                <td>{{$invoiceItem->product_name}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Quantity:</th>
                                                <td>{{$invoiceItem->quantity}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Invoice No.:</th>
                                                <td>{{$invoice->number}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Payment method:</th>
                                                <td>{{Session::get('payment_method')}}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">Total:</th>
                                                <td><span class="woocommerce-Price-amount amount">{{currencyFormat($invoiceItem->subtotal,$code = $currency)}}</span></td>
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