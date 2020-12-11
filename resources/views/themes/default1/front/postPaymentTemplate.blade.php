
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
            $currency = \Auth::user()->currency;
            $date = getDateHtml($invoiceItem->created_at);   
            ?>
            <ul>
                <li class="">
                    Product Name:<strong>{{$invoiceItem->product_name}}</strong>
                </li>
                <li class="">
                    Invoice number:<strong>{{$invoice->number}}</strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    Date: <strong>{!! $date !!}</strong>
                </li>

                <li class="woocommerce-order-overview__email email">
                    Email: <strong>{{\Auth::user()->email}}</strong>
                 </li>
                <li class="woocommerce-order-overview__total total">
                    <?php 
                    $total = $invoiceItem->subtotal;
                    ?>
                    Total:   <strong><span class="amount">{{currencyFormat($total,$code = $currency)}}
                </span></strong>
                </li>

               
            </ul>
            @endforeach
        

          <section>
    
        <h2 style="margin-top:40px ; margin-bottom:10px;">Order Details</h2>
            @foreach($orders as $order)  
            <?php 
            $product = \App\Model\Product\Product::where('id', $order->product)->select('id', 'name')->first();
            $cont = new \App\Http\Controllers\License\LicensePermissionsController();
            $downloadPermission = $cont->getPermissionsForProduct($order->product);
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
            <?php 
            $invoiceTotal = $invoiceItem->regular_price;
            ?>
        <span class="amount">{{currencyFormat($invoiceTotal,$code = $currency)}}</span>   
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
                        <td>{{Session::get('payment_method')}}</td>
                    </tr>
                     <tr>
                        <th scope="row">Total:</th>
                        <?php
                        $orderTotal = $order->price_override;
                        ?>
                        <td><span class="amount">{{currencyFormat($orderTotal,$code = $currency)}}</span></td>
                    </tr>
        </tfoot>
    </table>
      @if($downloadPermission['downloadPermission'] == 1)
      <a href= product/download/{{$order->product}}/{{$invoice->number}} " class="btn btn-sm btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>&nbsp;&nbsp;Download the Latest Version here</a>
      @endif
    @endforeach
    <br>
    
    </section>

    

              </div>
             </div>
           

        
         </div>

        

</div><!-- end main content -->

    
    </div>
        </div>