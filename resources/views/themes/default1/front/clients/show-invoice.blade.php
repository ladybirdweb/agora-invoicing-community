@extends('themes.default1.layouts.front.master')
@section('title')
Invoice
@stop
@section('page-heading')
 View Invoice
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
    <div class="featured-box featured-box-primary text-left mt-5" style="max-width: 900px">

    <section class="box-content">
        @php
            $set = App\Model\Common\Setting::where('id', '1')->first();
            $gst =  App\Model\Payment\TaxOption::where('id', '1')->first();
            $symbol = Auth::user()->currency;
            $logo = \App\Model\Common\Setting::where('id', 1)->value('logo');
        @endphp

        <div>
            @if($logo)
                <img alt="Logo" width="100" height="50" src="{{asset('common/images/'.$logo)}}" style="margin-top: -2px">
            @endif
            <div class="invoice">

                <header class="clearfix">
                    <div class="row" style="border-bottom: 1px solid rgba(0, 0, 0, 0.06);">
                        <div class="col-sm-6 mt-3">
                            <h2 class="h2 mt-0 mb-1 text-dark font-weight-bold">INVOICE</h2>
                            <h4 class="h4 m-0 text-dark font-weight-bold">#{{$invoice->number}}</h4>
                        </div>
                        <div class="col-sm-6 text-right mt-3 mb-3">
                            <address class="ib" style="margin-bottom: 0px">
                                <strong>{{$set->company}}</strong><br>
                                {{$set->address}}<br>
                                {{$set->phone}}<br/>
                                {{$set->email}}
                            </address>
                        </div>
                    </div>
                </header>

                <div class="bill-info">
                    <div class="row" style="margin-top: 25px">
                        <div class="col-md-6">
                            <div class="bill-to">
                                <p class="h5 mb-1 text-dark font-weight-semibold">To:</p>
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bill-data text-right">
                                <p class="mb-0">
                                    <span class="text-dark">GSTIN:</span>
                                    <span class="value">#{{$gst->Gst_No}}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-responsive-md invoice-items">
                    <thead>
                    <tr class="text-dark">
                        <th class="font-weight-semibold">Order No</th>
                        <th class="font-weight-semibold">Product</th>
                        <th class="font-weight-semibold">Quantity</th>
                        <th class="font-weight-semibold">Price</th>
                        <th class="font-weight-semibold">Taxes</th>
                        <th class="font-weight-semibold">Tax Rates</th>
                        <th class="font-weight-semibold">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            @php
                            $orderForThisItem = $item->order()->first();
                            @endphp
                            @if($orderForThisItem)

                            <td> {!! $orderForThisItem->getOrderLink($orderForThisItem->id,'my-order') !!}
                           
                                @elseif($order)
                                <td>{!! $order !!}</td>
                                <span>Renewed</span>
                                @else
                                <td>--</td>
                               
                            @endif
                            <td>{{$item->product_name}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{currencyFormat(intval($item->regular_price),$code = $symbol)}}</td>
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


                            <td>{{currencyFormat($item->subtotal,$code = $symbol)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="invoice-summary">
                    <div class="row justify-content-end">
                        <div class="col-sm-4">
                            <table class="table h6 text-dark"  style="text-align: right">
                                @if($invoice->discount != null)
                                    <th>Discount</th>
                                    <td>{{currencyFormat($invoice->discount,$code=$symbol)}}</td>
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
                                                    {{currencyFormat($cgst,$code = $symbol)}} <br/>
                                                    {{currencyFormat($sgst,$code = $symbol)}} <br/> <br/>
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
                                                    {{currencyFormat($igst,$code = $symbol)}}  <br/>

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
                                                    {{currencyFormat($utgst,$code = $symbol)}}<br/>
                                                    {{currencyFormat($cgst,$code = $symbol)}}<br/>


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
                                            {{currencyFormat($value,$code = $symbol)}}

                                        </td>

                                    </tr>
                                @endif
                                @endif

                                <tr class="h4">
                                    <th>Total</th>
                                    <td>{{currencyFormat($invoice->grand_total,$code = $symbol)}}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <a href="{{url('pdf?invoiceid='.$invoice->id)}}" class="btn btn-default"><i class="fa fa-download"></i> Generate PDF</a>

                @if($invoice->status !='Success')
                    <a href="{{url('paynow/'.$invoice->id)}}" target="_blank" class="btn btn-primary ml-3"><i class="fa fa-credit-card"></i> Pay Now</a>
                @endif
            </div>
        </div>
    </section>
    </div>

@stop