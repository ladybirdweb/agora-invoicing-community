@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Agora | Invoice
@stop
@section('page-heading')
 <h1>View Invoice </h1>
@stop
@section('breadcrumb')
<li><a href="{{url('home')}}">Home</a></li>
<li><a href="{{url('my-invoices')}}">My Account</a></li>
<li class="active">Invoice</li> 
@stop
@section('nav-invoice')
active
@stop

@section('content')

    <div class="row">
        <?php $set = App\Model\Common\Setting::where('id', '1')->first(); ?>
           <?php $gst =  App\Model\Payment\TaxOption::where('id', '1')->first(); 
          ?>
            <?php    
            if($invoice->currency == 'INR'){
                $symbol = '₹';
             }
             else{
                $symbol = '$';
             }
                ?>
           
        <div class="featured-box featured-box-primary align-left mt-xlg"  style="text-align: left;">
            <div class="box-content">
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            Invoice
                            <small>#{{$invoice->number}}</small>
                        </h1>


                    </section>



                    <!-- Main content -->
                    <section class="invoice">
                        <!-- title row -->

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
                            </div><!-- /.col -->
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
                            </div><!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice   #{{$invoice->number}}</b><br/>
                               
                                 <b>GSTIN   #{{$gst->Gst_No}}</b><br/>
                                <br/>

                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-xs-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Taxes</th>
                                            <th>Tax Rates</th>
                                             <th>Discount</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
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
                                             <?php
                                        $data=($item->discount)?$item->discount:'No discounts';
                                        ?>
                                        <td>
                                            {{$data}}
                                        </td>
                                            <td>{{$item->subtotal}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-xs-6">

                            </div><!-- /.col -->
                            <div class="col-xs-6">
                                <p class="lead">Subtotal</p>
                                <div class="table-responsive">
                                    <table class="table">
                                         <?php 
                                         $tax_name = [];
                                    $tax_percentage = [];
                                        foreach($items as $key=>$item){
                                            if(str_finish(',', $item->tax_name)){
                                                $name = $item->tax_name;
                     
                                            }
                                            if(str_finish(',', $item->tax_percentage)){
                                                $rate = substr_replace($item->tax_percentage,'',-1);
                                                
                                            }
                                            $tax_name = explode(',',$name);
                                            $tax_percentage = explode(',',$rate);
                                        }
                                        
                                    ?>

                                   
                                     
                                    @if($tax_name[0] !='null')
                                     <?php
                                     $order = \App\Model\Order\Order::where('invoice_item_id',$item->id)->first();
                                    if($order != null) {
                                        $productId = $order->product;
                                    } else {
                                          $productId =  App\Model\Product\Product::where('name',$item->product_name)->pluck('id')->first(); 
                                    }
                                    $taxInstance= new \App\Http\Controllers\Front\CartController();
                                    $taxes= $taxInstance->checkTax($productId);
                                     ?>

                                   @if ($taxes['attributes']['currency'][0]['code']== 'INR' && $user->country == 'IN')
                                    @if($set->state == $user->state)
                                             <tr class="Taxes">
                            <th>
                                <strong>CGST<span>@</span>{{$taxes['attributes']['tax'][0]['c_gst']}}%</strong><br/>
                                <strong>SGST<span>@</span>{{$taxes['attributes']['tax'][0]['s_gst']}}%</strong><br/>
                               
                            </th>
                            <td>
                                {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['attributes']['tax'][0]['c_gst'],$item->regular_price)}} <br/>
                                {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['attributes']['tax'][0]['s_gst'],$item->regular_price)}} <br/>
                             </td>
                              </tr>
                                    @endif
                                      @if($set->state != $user->state && $taxes['attributes']['tax'][0]['ut_gst'] == "NULL")
                                      <tr>
                                      <th>
                                <strong>IGST<span>@</span>{{$taxes['attributes']['tax'][0]['i_gst']}}%</strong><br/>
                                  
                            </th>
                            <td>
                                {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['attributes']['tax'][0]['i_gst'],$item->regular_price)}} <br/>
                              
                             </td>
                         </tr>
                                     @endif
                                     <tr>
                                     @if($set->state != $user->state && $taxes['attributes']['tax'][0]['ut_gst'] != "NULL")
                                     <th>
                                 <strong>UTGST<span>@</span>{{$taxes['attributes']['tax'][0]['ut_gst']}}%</strong><br/>
                                 <strong>CGST<span>@</span>{{$taxes['attributes']['tax'][0]['c_gst']}}%</strong><br/>

                                  
                            </th>
                            <td>
                                {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['attributes']['tax'][0]['ut_gst'],$item->regular_price)}} <br/>
                                 {{$symbol}} {{App\Http\Controllers\Front\CartController::taxValue($taxes['attributes']['tax'][0]['c_gst'],$item->regular_price)}}
                               
                              
                             </td>
                         </tr>
                                     @endif
                                     @endif
                                      
                                        @if ($taxes['attributes']['currency'][0]['code']!= 'INR')
                                     <tr>
                                        <th>
                                            <strong>{{ucfirst($tax_name[0])}}<span>@</span>{{$tax_percentage[0]}}% </strong>
                                        </th>
                                        <td>

                                            <small>{!! $symbol !!}</small>&nbsp;{{App\Http\Controllers\Front\CartController::taxValue($tax_percentage[0],$item->regular_price)}}
                                            
                                        </td>

                                    </tr>
                                    @endif
                                    @endif
                                   
                                        <tr>
                                            <th style="width:50%">Total:</th>
                                            <td>{!! $symbol !!} {{$invoice->grand_total}}</td>
                                        </tr>

                                    </table>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <!-- this row will not appear when printing -->
                           <div class="row no-print">
                            <div class="col-xs-12">	
                                <a href="{{url('pdf?invoiceid='.$invoice->id)}}"><button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button></a>

                                
                                 @if($invoice->status !='Success')
                            <a href="{{url('paynow/'.$invoice->id)}}"><button class="btn btn-primary" style="margin-right: 5px;"><i class="fa fa-credit-card"></i> Pay Now</button></a>
                      @endif
                            </div>
                        </div>
                    </section><!-- /.content -->

                </div><!-- /.content-wrapper -->

                <div class="control-sidebar-bg"></div>
            </div><!-- ./wrapper -->
        </div> 
   
    </div>


@stop