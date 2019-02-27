<!DOCTYPE html>
<html lang="en">

    <head>
        <title>invoice</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">


    </head>   
    <body>
        <div class="container">
            <div class="content-wrapper">

                <!-- Content Header (Page header) -->
                <?php $set = App\Model\Common\Setting::where("id", "1")->first(); ?>
             
            
                <!-- Main content -->
                <section class="invoice">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> {{ucfirst($set->company)}}
                                <small class="pull-right">Date: {{$invoice->created_at}}</small>
                            </h2>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            From
                            <address>
                                <strong>{{$set->company}}</strong><br>
                                {{$set->address}}<br>
                                Phone: {{$set->phone}}<br/>
                                Email: {{$set->email}}
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                <strong>{{$user->first_name}} {{$user->last_name}}</strong><br>
                                {{$user->address}}<br/>
                                {{$user->town}}<br/>
                                @if(key_exists('name',App\Http\Controllers\Front\CartController::getStateByCode($user->state)))
                                {{App\Http\Controllers\Front\CartController::getStateByCode($user->state)['name']}}
                                @endif
                                {{$user->zip}}<br/>
                                Country : {{App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}<br/>

                                Mobile: @if($user->mobile_code)<b>+</b>{{$user->mobile_code}}@endif{{$user->mobile}}<br/>
                                Email : {{$user->email}}
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Invoice #{{$invoice->number}}</b><br>
                            <br>
                             <?php $gst =  App\Model\Payment\TaxOption::where('id', '1')->first(); ?>
                             <b>GSTIN   #{{$gst->Gst_No}}</b><br/>

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                      
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                   
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Taxes</th>
                                        <th>Tax Rates</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($invoiceItems as $item)
                                    <tr>

                                        <td>{{$item->product_name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->regular_price}}</td>
                                        <td>
                                            <?php $taxes = explode(',', $item->tax_name); ?>
                                            <ul class="list-unstyled">
                                                @forelse($taxes as $tax)
                                                <li>{{$tax}}</li>
                                                @empty 
                                                <li>No Tax</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td>
                                            <?php $taxes = explode(',', $item->tax_percentage); ?>
                                            <ul class="list-unstyled">
                                                @forelse($taxes as $tax)
                                                <li>{{$tax}}</li>
                                                @empty 
                                                <li>No Tax Rates</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td>{{$item->subtotal}}</td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="row">
                    <div class="col pull-right" >
                        <p class="lead">Amount</p>

                        <div class="table-responsive">
                           
                              <?php
                                $tax_name = [];
                                $tax_percentage = [];
                                foreach ($invoiceItems as $key => $item) {

                                    if (str_finish(',', $item->tax_name)) {
                                        $name = ($item->tax_name);
                                       
                                    }
                                    if (str_finish(',', $item->tax_percentage)) {
                                        $rate = substr_replace($item->tax_percentage, '', -1);
                                        
                                    }
                                    $tax_name = explode(',', $name);
                                    $tax_percentage = explode(',', $rate);
                                }
                                ?>
                                 <table class="table  table-striped">
                                     @if($invoice->discount != null)
                                                    <tr>
                                                          
                                                    <th>Discount</th>
                                                    <td>{{currency_format($invoice->discount,$code=$symbol)}}</td>
                                                    </tr>
                                                     @endif
                                @if($tax_name[0] !='null' && $tax_percentage[0] !=null)
                                   <?php 
                                  $order = \App\Model\Order\Order::where('invoice_item_id',$item->id)->first();
                                    if($order != null) {
                                        $productId = $order->product;
                                    } else {
                                          $productId =  App\Model\Product\Product::where('name',$item->product_name)->pluck('id')->first(); 
                                    }
                                   $taxInstance= new \App\Http\Controllers\Front\CartController();
                                    $taxes= $taxInstance->checkTax($productId,$user->state,$user->country);
                                     ?>
                                  @if ($currency['currency'] == 'INR' && $user->country == 'IN'  && $taxes['tax_attributes'][0]['name']!= 'null')
                                    @if($set->state == $user->state)
                                             <tr class="Taxes">
                            <th>
                               <strong>CGST<span>@</span>{{$taxes['tax_attributes'][0]['c_gst']}}%</strong><br/>
                                <strong>SGST<span>@</span>{{$taxes['tax_attributes'][0]['s_gst']}}%</strong><br/>
                               
                            </th>
                            <td>
                                {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['c_gst'],$item->regular_price)}} <br/>
                                {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['s_gst'],$item->regular_price)}} <br/>
                             </td>
                              </tr>
                                    @endif
                                      @if($set->state != $user->state && $taxes['tax_attributes'][0]['ut_gst'] == "NULL")
                                      <tr>
                                      <th>
                                <strong>IGST<span>@</span>{{$taxes['tax_attributes'][0]['i_gst']}}%</strong><br/>
                                  
                            </th>
                            <td>
                                {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['i_gst'],$item->regular_price)}} <br/>
                              
                             </td>
                         </tr>
                                     @endif
                                     <tr>
                                   @if($set->state != $user->state && $taxes['tax_attributes'][0]['ut_gst'] != "NULL")
                                     <th>
                                 <strong>UTGST<span>@</span>{{$taxes['tax_attributes'][0]['ut_gst']}}%</strong><br/>
                                 <strong>CGST<span>@</span>{{$taxes['tax_attributes'][0]['c_gst']}}%</strong><br/>

                        </th>
                            <td>
                               {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['ut_gst'],$item->regular_price)}} <br/>
                                 {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['c_gst'],$item->regular_price)}}


                              
                             </td>
                         </tr>
                                     @endif
                                     @endif
                                      
                                      @if ($currency['currency'] != 'INR')
                                     <tr>
                                        <th>
                                            <strong>{{ucfirst($tax_name[0])}}<span>@</span>{{$tax_percentage[0]}} </strong>
                                        </th>
                                        <td>

                                            <small>{!! $symbol !!}</small>&nbsp;{{App\Http\Controllers\Front\CartController::taxValue($tax_percentage[0],$item->regular_price)}}
                                            
                                        </td>

                                    </tr>
                                    @endif
                                    @endif
                                   

                               
                               
                               
                                    <th>Total:</th>
                                    <td><small>{!! $symbol !!}</small>&nbsp;{{$invoice->grand_total}}</td>
                               
                            </table>
                        </div>
                    </div>
                </div>
                    <!-- this row will not appear when printing -->

                </section>
                <!-- /.content -->

            </div>
        </div>
        <!-- /.content-wrapper -->


        <!-- ./wrapper -->


    </body>
</html>
