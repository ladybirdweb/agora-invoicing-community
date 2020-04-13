<div class="container">
                            
            
            <div >

            <!-- main content -->
            <div >

                            
    <div id="content" role="main">
                
           <div class="page-content">
                    <div>

    
        
            <strong>Thank you. Your {{$product->name}} order is confirmed. A confirmation Mail has been sent to you on your registered
                Email
            </strong><br>

            <ul class="">

                <li class="">
                    Invoice number:                    <strong>{{$invoice->number}}</strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    Date:                    <strong>{{$date}}</strong>
                </li>

                                    <li class="woocommerce-order-overview__email email">
                        Email:                        <strong>{{\Auth::user()->email}}</strong>
                    </li>
                
               

                                    <li class="woocommerce-order-overview__payment-method method">
                        Payment method:                        <strong>Razorpay</strong>
                    </li>
                
            </ul>

        
       
<section>
    
    <h2 style="margin-top:40px ; margin-bottom:10px;">Order Details</h2>
    
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
        <strong>{{$product->name}} ×   {{$items[0]->quantity}} </strong>
    </td>

    <td class="woocommerce-table__product-total product-total">
        <span class="woocommerce-Price-amount amount"> {{currency_format($invoice->grand_total,$code=\Auth::user()->currency)}}</span>    </td>

</tr>

        </tbody>
        <tfoot>
                                <tr>
                        <th scope="row">Invoice No:</th>
                        <td><span class="woocommerce-Price-amount amount"> {{$invoice->number}}</span></td>
                    </tr>
                                        <tr>
                        <th scope="row">Payment method:</th>
                        <td>Razorpay</td>
                    </tr>
                                        <tr>
                        <th scope="row">Total:</th>
                            <td><span class="woocommerce-Price-amount amount"> {{currency_format($invoice->grand_total,$code = \Auth::user()->currency)}}</span></td>
                    </tr>
                            </tfoot>
    </table>
    
          

 
                    <br><br>
                     <a href= product/download/{{$product->id}}/{{$invoice->number}} " class="btn btn-sm btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>&nbsp;&nbsp;Download the Latest Version here</a>
          

    

    

</section>

    

</div>
                </div>
           

        
    </div>

        

</div>

    
    </div>
    </div>