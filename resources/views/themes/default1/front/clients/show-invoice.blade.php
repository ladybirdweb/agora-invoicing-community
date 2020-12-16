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
             $date = getDateHtml($invoice->date);
            $symbol = $invoice->currency;
            $itemsSubtotal = 0;
            $taxAmt = 0;
        @endphp

        <div>
            @if($set->logo)
                <img alt="Logo" width="100" height="50" src="{{asset('common/images/'.$set->logo)}}" style="margin-top: -2px">
            @endif
             <h4 class="float-right" >Date: {!! $date !!}</h4>
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
                                {{$set->city}}<br/>
                                @if(key_exists('name',getStateByCode($set->state)))
                                {{getStateByCode($set->state)['name']}}
                                @endif
                                {{$set->zip}}<br/>
                                Country : {{getCountryByCode($set->country)}}<br/>
                                Mobile: {{$set->phone}}<br/>
                                Email: {{$set->email}}
                            </address><br>
                             @if($set->gstin)
                            <div class="bill-data text-right">
                                <p class="mb-0">
                                    <span class="text-dark">GSTIN:</span>
                                    <span class="value">#{{$set->gstin}}</span>
                                </p>
                            </div>
                            @endif

                             @if($set->cin_no)
                             <div class="bill-data text-right">
                                <p class="mb-0">
                                    <span class="text-dark">CIN:</span>
                                    <span class="value">#{{$set->cin_no}}</span>
                                </p>
                            </div>
                             @endif
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
                                    @if(key_exists('name',getStateByCode($user->state)))
                                        {{getStateByCode($user->state)['name']}}
                                    @endif
                                    {{$user->zip}}<br/>
                                    Country : {{getCountryByCode($user->country)}}<br/>
                                    Mobile: @if($user->mobile_code)<b>+</b>{{$user->mobile_code}}@endif{{$user->mobile}}<br/>
                                    Email : {{$user->email}}
                                </address>
                                @if($user->gstin)
                            <div class="bill-data">
                                <p class="mb-0">
                                    <span class="text-dark">GSTIN:</span>
                                    <span class="value">#{{$user->gstin}}</span>
                                </p>
                            </div>
                            @endif <br>
                            </div>
                        </div>
                        
                        
                        

                       
                        
                    </div>
                </div>

                <table class="table table-striped table-responsive-md invoice-items">
                    <thead>
                    <tr class="text-dark">
                        <th class="font-weight-semibold">Order No</th>
                        <th class="font-weight-semibold">Product</th>
                        <th class="font-weight-semibold">Price</th>
                        <th class="font-weight-semibold">Quantity</th>
                        
                        <th class="font-weight-semibold">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            @php
                            $taxName[] =  $item->tax_name.'@'.$item->tax_percentage;
                            if ($item->tax_name != 'null') {
                                $taxAmt +=  $item->subtotal;
                             }
                            $orderForThisItem = $item->order()->first();
                            $itemsSubtotal += $item->subtotal;
                            @endphp
                            @if($orderForThisItem)

                            <td> {!! $orderForThisItem->getOrderLink($orderForThisItem->id,'my-order') !!}</td>
                           
                                @elseif($order != '--')
                                <td>{!! $order !!}
                                <span class='badge badge-warning'>Renewed</span></td>
                                @else
                                <td>--</td>
                               
                            @endif
                            <td>{{$item->product_name}}


                            </td>
                             <td>{{currencyFormat(intval($item->regular_price),$code = $symbol)}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{currencyFormat($item->subtotal,$code = $symbol)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="invoice-summary">
                    <div class="row justify-content-end">
                        <div class="col-sm-4">
                            <table class="table h6 text-dark" >
                                 <tr>
                                 <th>Subtotal</th>
                                    <td>{{currencyFormat($itemsSubtotal,$code=$symbol)}}</td>
                                </tr>
                                @if($invoice->discount != null)
                                <tr>
                                    <th>Discount</th>
                                    <td>{{currencyFormat($invoice->discount,$code=$symbol)}}</td>
                                </tr>
                                @endif
                          

                                
                                    <?php
                                    $order = \App\Model\Order\Order::where('invoice_item_id',$item->id)->first();
                                    if($order != null) {
                                        $productId = $order->product;
                                    } else {
                                        $productId =  App\Model\Product\Product::where('name',$item->product_name)->pluck('id')->first();
                                    }
                                    $taxName = array_unique($taxName);
                                    ?>
                                    @foreach($taxName as $tax)
                                    <?php
                                    $taxDetails = explode('@', $tax);
                                    ?>
                                    @if ($taxDetails[0]!= 'null')
                                            
                                       
                                            <tr>
                                                 <?php
                                                $bifurcateTax = bifurcateTax($taxDetails[0],$taxDetails[1],\Auth::user()->currency, \Auth::user()->state, $taxAmt);
                                                ?>
                                                <th>

                                                    <strong>{!! $bifurcateTax['html'] !!}</strong>


                                                </th>
                                                <td>
                                                   
                                                    {!! $bifurcateTax['tax'] !!}

                                                </td>
                                            </tr>
                                     
                                       
                                    @endif
                                    @endforeach
                                @if($invoice->processing_fee != null && $invoice->processing_fee != '0%')
                                <tr>
                                    <th>Processing fee</th>
                                    <td>{{$invoice->processing_fee}}</td>
                                </tr>
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