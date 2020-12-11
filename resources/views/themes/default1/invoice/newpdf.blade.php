<!DOCTYPE html>
<html lang="en">

        
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>PDF Invoice</title>
        <link rel="stylesheet" href="{{asset('admin/css/bootstrap.min.css')}}">
    </head>  
    <style>
        .invoice-col{float:left;width:33.3333333%}
        .float-right{float:right!important}
        .table-striped tbody tr:nth-of-type(odd){background-color:rgba(0,0,0,.05)}
        
        .table-responsive{border:0}
        
        

        body {
            font-family: DejaVu Sans;
        }

    </style> 
        <body>
         <div class="content-wrapper">
        <section class="content">
            

                  <div class="container-fluid">
                     <div class="row">

            <div class="col-12">

                <?php 
                use App\Model\Order\Order;
                $set = App\Model\Common\Setting::where('id', '1')->first(); 
                $date = getDateHtml($invoice->date);
                 $symbol = $invoice->currency;
                $itemsSubtotal = 0;
                 $taxAmt = 0;
                ?>

                    <!-- title row -->

                    <div class="row">
                        <div class="col-12">
                            <h4>
                                @if($set->logo)
                                    <img alt="Logo" width="100" height="50" src="{{asset('common/images/'.$set->logo)}}">
                                    @else
                                    {{ucfirst($set->company)}}
                                @endif
                            <small class="float-right">Date: {!! $date !!}</small>
                            </h4>
                        </div><!-- /.col -->
                    </div>

                    <!-- info row -->
                    <div class="row">
                        <div class="col-sm-4 invoice-col">
                            From
                            <address>

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
                            </address>
                        </div><!-- /.col -->
                         <div class="col-sm-4" style="float:right!important">
                            <b>Invoice   #{{$invoice->number}}</b><br>
                            <br>


                             @if($set->gstin)
                            <b>GSTIN:</b>  &nbsp; #{{$set->gstin}}
                            <br>
                            @endif

                            @if($set->cin_no)
                            <b>CIN:</b>  &nbsp; #{{$set->cin_no}}
                            <br>
                            @endif


                        </div><!-- /.col -->
                        <div class="col-sm-4">
                            &nbsp;&nbsp;&nbsp;To
                            <address>
                                
                                <strong>{{$user->first_name}} {{$user->last_name}}</strong><br>
                                {{$user->address}}<br>
                                {{$user->town}}<br>
                                @if(key_exists('name',getStateByCode($user->state)))
                                {{getStateByCode($user->state)['name']}}
                                @endif
                                {{$user->zip}}<br>
                                Country : {{getCountryByCode($user->country)}}<br>
                                Mobile: @if($user->mobile_code)<b>+</b>{{$user->mobile_code}}@endif{{$user->mobile}}<br>
                                Email : {{$user->email}}
                            </address>
                        </div><!-- /.col -->
                       
                </div>

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order No</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($invoiceItems as $item)
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
                                        <td> {!! Order::getOrderLink($orderForThisItem->id) !!} </td>
                                            @elseif($order != '--')
                                            <td>{!! $order !!} </td>
                                            @else
                                            <td>--</td>
                                           
                                        @endif
                                        <td>{{$item->product_name}}</td>
                                         <td>{{currencyFormat($item->regular_price,$code=$symbol)}}</td>
                                        <td>{{$item->quantity}}</td>
                                       <td> {{currencyFormat($item->subtotal,$code=$symbol)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
            
                        <div class="col-6"></div>
                        <div class="col-6" style="width: 50%;">
                              <div class="table-responsive">
                                 <table class="table">
                                     <tr>
                                         <th>Subtotal:</th>
                                         <td>{{currencyFormat($item->subtotal,$code=$symbol)}}</td>
                                     </tr>
                                

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
                                                $bifurcateTax = bifurcateTax($taxDetails[0],$taxDetails[1],$symbol, \Auth::user()->state, $taxAmt);
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
                                    <tr>
                                    <th>Total:</th>
                                    <td>{{currencyFormat($invoice->grand_total,$code=$symbol)}}</td>
                                </tr>
                               
                            </table>
                           
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                   


            </div>
       

    </div>
</div>
</div>

</section>
 
</div>
        <!-- /.content-wrapper -->
</body>

        <!-- ./wrapper -->


   
</html>
