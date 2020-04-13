@extends('themes.default1.layouts.front.myaccount_master')
@section('title')
Invoice
@stop
@section('page-heading')
 <h1>View Invoice </h1>
@stop
@section('breadcrumb')
 @if(Auth::check())
   <li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
   <li><a href="{{url('login')}}">Home</a></li>
    @endif
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
           $symbol = Auth::user()->currency;
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
                                    @if($user->address)
                                    {{$user->address}}<br/>
                                    @endif
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
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr>
                                            <td>{{$item->product_name}}</td>
                                            <td>{{$item->quantity}}</td>
                                            <td>{{currency_format(intval($item->regular_price),$code = $symbol)}}</td>
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
                                          

                                            <td>{{currency_format($item->subtotal,$code = $symbol)}}</td>
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
                                <p class="lead">Amount</p>
                                <div class="table-responsive">
                                    <table class="table">
                                          @if($invoice->discount != null)
                                          <th>Discount</th>
                                            <td>{{currency_format($invoice->discount,$code=$symbol)}}</td>
                                        @endif
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
                                     
                                   @if(count($tax_name)>0 &&$tax_name[0] !='null')
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
 
                                    @if ($currency['currency'] == 'INR' && $user->country == 'IN')
                                    @if($set->state == $user->state)
                          <tr class="Taxes">
                            <th>
                                <strong>CGST<span>@</span>{{$taxes['tax_attributes'][0]['c_gst']}}%</strong><br/>
                                <strong>SGST<span>@</span>{{$taxes['tax_attributes'][0]['s_gst']}}%</strong><br/>
                               
                            </th>
                            <td>
                                <?php
                                $cgst = \App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['c_gst'],$item->regular_price);
                                $sgst = App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['s_gst'],$item->regular_price);
                                ?>
                                {{currency_format($cgst,$code = $symbol)}} <br/>
                                {{currency_format($sgst,$code = $symbol)}} <br/> <br/>
                             </td>
                              </tr>
                                    @endif
                                      @if($set->state != $user->state && $taxes['tax_attributes'][0]['ut_gst'] == "NULL")
                                      <tr>
                                      <th>

                                    <strong>IGST<span>@</span>{{$taxes['tax_attributes'][0]['i_gst']}}%</strong><br/>
                                  
                            </th>
                            <td>
                                <?php
                                $igst =  \App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['i_gst'],$item->regular_price);
                                ?>
                                  {{currency_format($igst,$code = $symbol)}}  <br/>
                              
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
                                <?php
                                $utgst = \App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['ut_gst'],$item->regular_price);
                                $cgst = \App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['c_gst'],$item->regular_price);
                                ?>
                                {{currency_format($utgst,$code = $symbol)}}<br/>
                                 {{currency_format($cgst,$code = $symbol)}}<br/>

                              
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
                                           <?php
                                        //   dd()
                                           if($tax_percentage[0] == "") {
                                              $tax_percentage[0] = 0; 
                                           }
                                           $value = \App\Http\Controllers\Front\CartController::taxValue($tax_percentage[0],$item->regular_price);
                                           ?>
                                            {{currency_format($value,$code = $symbol)}}
                                            
                                        </td>

                                    </tr>
                                    @endif
                                    @endif
                                    
                                  
                               
                               
                                    <th>Total:</th>
                                    <td>{{currency_format($invoice->grand_total,$code = $symbol)}}</td>

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