@extends('themes.default1.layouts.master')
@section('title')
Invoice
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>View Invoice</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('clients')}}"><i class="fa fa-dashboard"></i> All Users</a></li>
            <li class="breadcrumb-item"><a href="{{url('invoices')}}"><i class="fa fa-dashboard"></i> All Invoices</a></li>
            <li class="breadcrumb-item active">View Invoice</li>
        </ol>
    </div><!-- /.col -->


@stop

@section('content')

    <div class="invoice p-3 mb-3">
        <div class="container-fluid">



        <div class="row">

            <div class="col-12">

                <?php $set = App\Model\Common\Setting::where('id', '1')->first(); 
                 $gst =  App\Model\Payment\TaxOption::where('id', '1')->first(); 
                $date = getDateHtml($invoice->date);
                $logo = \App\Model\Common\Setting::where('id', 1)->value('logo');
                 $symbol = $invoice->currency;
            
                ?>

                    <!-- title row -->

                    <div class="row">
                        <div class="col-12">
                            <h4>
                                @if($logo)
                                    <img alt="Logo" width="100" height="50" src="{{asset('common/images/'.$logo)}}" style="margin-top: -2px">
                                    @else
                                    {{ucfirst($set->company)}}
                                @endif

                                <small class="float-right">Date: {!! $date !!}</small>
                            </h4>
                        </div><!-- /.col -->
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
                            <b>Invoice   #{{$invoice->number}}</b><br>
                            <br>




                            <b>GSTIN:</b>  &nbsp; #{{$gst->Gst_No}}
                            <br>


                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order No</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Taxes</th>
                                        <th>Tax Rates</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($invoiceItems as $item)
                                    <tr>
                                        @php
                                        $orderForThisItem = $item->order()->first();
                                        @endphp
                                        @if($orderForThisItem)
                                        <td>{!! $orderForThisItem->getOrderLink($orderForThisItem->id,'orders') !!}</td>
                                       
                                            @elseif($order)
                                            <td>{!! $order !!}</td>
                                            <span>Renewed</span>
                                            @else
                                            <td>--</td>
                                           
                                        @endif
                                        <td>{{$item->product_name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{currencyFormat($item->regular_price,$code=$symbol)}}</td>
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
                                     
                                        <td> {{currencyFormat($item->subtotal,$code=$symbol)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-6">

                        </div>
                        <div class="col-6">
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
                                 <table class="table">
                                     <tr>
                                         <th style="width:50%">Subtotal:</th>
                                         <td>{{currency_format($item->regular_price,$code=$symbol)}}</td>
                                     </tr>
                                      @if($invoice->discount != null)
                                  <th>Discount</th>
                                    <td>{{currencyFormat($invoice->discount,$code=$symbol)}}</td>
                                @endif

                                @if($tax_name[0] !='null' && $tax_percentage[0] !=null)
                                   <?php $productId =  App\Model\Product\Product::where('name',$item->product_name)->pluck('id')->first(); 
                                   $taxInstance= new \App\Http\Controllers\Front\CartController();
                                    $taxes= $taxInstance->checkTax($productId,$user->state,$user->country);
                                     ?>
                                   @if ($currency['currency'] == 'INR' && $user->country == 'IN' && $taxes['tax_attributes'][0]['name']!= 'null')
                                    @if($set->state == $user->state)
                                             <tr class="Taxes">
                            <th>
                                <strong>CGST<span>@</span>{{$taxes['tax_attributes'][0]['c_gst']}}%</strong><br/>
                                <strong>SGST<span>@</span>{{$taxes['tax_attributes'][0]['s_gst']}}%</strong><br/>
                               
                            </th>
                            <td>
                                <?php
                                $cgst = \App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['c_gst'],$item->regular_price);
                                $sgst = \App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['s_gst'],$item->regular_price);
                                ?>
                                {{currencyFormat($cgst,$code=$symbol)}} <br/>
                                {{currencyFormat($sgst,$code=$symbol)}}<br/>
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
                                  {{currencyFormat($igst,$code=$symbol)}} <br/>
                              
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
                                $cgst = App\Http\Controllers\Front\CartController::taxValue($taxes['tax_attributes'][0]['c_gst'],$item->regular_price)
                                    ?>
                                {{currencyFormat($utgst,$code=$symbol)}} <br/>
                                {{currencyFormat($cgst,$code=$symbol)}}

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
                                            $value = \App\Http\Controllers\Front\CartController::taxValue($tax_percentage[0],$item->regular_price)
                                            ?>
                                             {{currencyFormat($value,$code=$symbol)}}
                                        </td>

                                    </tr>
                                    @endif
                                    @endif
                                    <th>Total:</th>
                                    <td>{{currencyFormat($invoice->grand_total,$code=$symbol)}}</td>
                               
                            </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-6"></div>
                        <div class="col-6">
                            <a href="{{url('pdf?invoiceid='.$invoice->id)}}"><button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button></a>
                        </div>
                    </div>


            </div>
        </div>

</div>
    </div>


@stop