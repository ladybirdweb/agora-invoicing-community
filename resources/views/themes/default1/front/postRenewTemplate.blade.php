<div class="container">
                            
            
            <div >

            <!-- main content -->
            <div >

                            
    <div role="main">
                
            <article>
                
                
                <div class="page-content">
                    <div>
<div>
          <?php
          $currency = \Auth::user()->currency;
          $cont = new \App\Http\Controllers\License\LicensePermissionsController();
            $downloadPermission = $cont->getPermissionsForProduct($product->id);
          ?>
    
        
            <strong>Thank you. Your Subscription has been renewed.</strong>
                <br>
            <ul>

                <li class="">
                    Invoice Number:                    <strong>{{$invoice->number}}</strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    Date:                    <strong>{{$date}}</strong>
                </li>

                                    <li class="woocommerce-order-overview__email email">
                        Email:                        <strong>{{\Auth::user()->email}}</strong>
                    </li>
                
                <li class="woocommerce-order-overview__total total">
                    Total:                    <strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"></span>{{currency_format($invoiceItem->subtotal,$code = $currency)}}</span></strong>
                </li>

                                    <li class="woocommerce-order-overview__payment-method method">
                        Payment method: <strong>Razorpay</strong>
                    </li>
                
            </ul>

        
       
<section>
    
    <h2 style="margin-top:40px ; margin-bottom:10px;">Payment Details</h2>
    
    <table class="table table-bordered  table-striped">
    
        <thead>
            <tr>
                <th class="woocommerce-table__product-name product-name">Product</th>
                <th class="woocommerce-table__product-table product-total">Total</th>
            </tr>
        </thead>
        
        <tbody>
      
            <tr class="woocommerce-table__line-item order_item">

    <td class="woocommerce-table__product-name product-name">
        <strong>{{$invoiceItem->product_name}}</strong> <strong>× {{$invoiceItem->quantity}}</strong>    </td>

    <td class="woocommerce-table__product-total product-total">
        <span class="woocommerce-Price-amount amount">{{currency_format($invoiceItem->subtotal,$code = $currency)}}</span>    </td>

</tr>

        </tbody>
        <tfoot>
                               
                                        <tr>
                        <th scope="row">Payment method:</th>
                        <td>Razorpay</td>
                    </tr>

                                        <tr>
                        <th scope="row">Total:</th>
                        <td><span class="woocommerce-Price-amount amount">{{currency_format($invoiceItem->subtotal,$code = $currency)}}</span></td>
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