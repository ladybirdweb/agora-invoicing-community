
   <div class="container">
      <div >
            <div>
                <div id="content" role="main">
                
                <div class="page-content">
                    <div>

    
        
                     <strong>Thank you. Your Payment has been received. A confirmation Mail has been sent to you on your registered
                Email
            </strong><br>
           
            @foreach($invoiceItems as $invoiceItem)
            <?php 
             $date1 = new DateTime($invoiceItem->created_at);
            $tz = \Auth::user()->timezone()->first()->name;
            $date1->setTimezone(new DateTimeZone($tz));
            $date = $date1->format('M j, Y, g:i a ');   
            ?>
            <ul>
                <li class="">
                    Product Name:<strong>{{$invoiceItem->product_name}}</strong>
                </li>
                <li class="">
                    Invoice number:<strong>{{$invoice->number}}</strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    Date: <strong>{{$date}}</strong>
                </li>

                <li class="woocommerce-order-overview__email email">
                    Email: <strong>{{\Auth::user()->email}}</strong>
                 </li>
                
                <li class="woocommerce-order-overview__total total">
                    Total:                    <strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">{{$currency}}</span>{{$invoiceItem->subtotal}}</span></strong>
                </li>

                <li class="woocommerce-order-overview__payment-method method">
                     Payment method: <strong>Razorpay</strong>
                    </li>
                
            </ul>
            @endforeach
        

          <section>
    
        <h2 style="margin-top:40px ; margin-bottom:10px;">Order Details</h2>
            @foreach($orders as $order)  
            <?php 
            $product = \App\Model\Product\Product::where('id', $order->product)->select('id', 'name')->first();
            ?>
            <table class="table table-bordered table-striped">
    
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Total</th>
                    </tr>
                </thead>
        
        <tbody>
            <tr>

                <td>
                    <strong>{{$product->name}} × {{$order->qty}} </strong>
                </td>

        <td class="woocommerce-table__product-total product-total">
        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">{{$currency}}</span> {{$invoiceItem->regular_price}}</span>   
       </td>

            </tr>

        </tbody>
        <tfoot>
                    <tr>
                        <th scope="row">Order No:</th>
                        <td><span class="woocommerce-Price-amount amount"> {{$order->number}}</span></td>
                    </tr>
                                        <tr>
                        <th scope="row">Payment method:</th>
                        <td>Razorpay</td>
                    </tr>
                                        <tr>
                        <th scope="row">Total:</th>
                        <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">{{$currency}}</span> {{$order->price_override}}</span></td>
                    </tr>
        </tfoot>
    </table>
      <a href= product/download/{{$order->product}}/{{$invoice->number}} " class="btn btn-sm btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>&nbsp;&nbsp;Download the Latest Version here</a>
    @endforeach
    <br>
    
            <section class="woocommerce-customer-details">

    
    <h2 style="margin-bottom:20px;">Billing address</h2>

    <strong>
       {{\Auth::user()->first_name}} {{\Auth::user()->last_name}}<br>{{\Auth::user()->address}}<br>{{\Auth::user()->town}} - {{\Auth::user()->zip}}<br> {{$state}} <br>
                   {{\Auth::user()->mobile}} <br><br>
                   
            </strong>

    
</section>
    

</section>

    

              </div>
             </div>
           

        
         </div>

        

</div><!-- end main content -->

    
    </div>
        </div>