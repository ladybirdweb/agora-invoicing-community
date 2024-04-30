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
                            <h4 style="position:relative;top: 2%;">
                                @if($set->logo)
                                <img alt="Logo" width="100" height="50" src="{{asset('storage/images/'.$set->logo)}}">
                                    @else
                                    {{ucfirst($set->company)}}
                                @endif
                            <!--<small  style="height: 50px;color: red;" class="float-right">Date: {!! $date !!}</small>-->
                        <p class="float-right" style=" position: relative;
                          top: 3.5px;left: 3px;">{!! $date !!}</p><p class="float-right">Date:</p>
                            </h4>
                        </div><!-- /.col -->
                    </div>

                    <!-- info row -->
                    <div class="row">
                        <div class="col-sm-4 invoice-col">
                            From
                            <address>

                                <strong style="word-wrap: break-word;">{{$set->company}}</strong><br>
                                {{$set->address}}<br>
                                {{$set->city}}<br/>
                                @if(key_exists('name',getStateByCode($set->state)))
                                {{getStateByCode($set->state)['name']}}
                                @endif
                                {{$set->zip}}<br/>
                                Country : {{getCountryByCode($set->country)}}<br/>
                                Mobile: <b>+</b>{{$set->phone_code}} {{$set->phone}}<br/>
                                Email: {{$set->company_email}}
                            </address>
                            
                            
                             @if($set->gstin)
                            <b>GSTIN:</b>  &nbsp; #{{$set->gstin}}
                            <br>
                            @endif

                            @if($set->cin_no)
                            <b>CIN:</b>  #{{$set->cin_no}}
                            <br>
                            @endif

                        </div><!-- /.col -->
                         <div class="col-sm-4 float-right" style=" position: relative;
                        top: 1px;left: 5%;">
                            <b>Invoice   #{{$invoice->number}}</b><br>
                            <br>



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
                                Mobile: @if($user->mobile_code)<b>+</b>{{$user->mobile_code}} @endif{{$user->mobile}}<br/>
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
                                        <th>Agents</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($invoiceItems as $item)
                                    <tr>
                                        @php
                                            $period_id =\DB::table('plans_periods_relation')->where('plan_id',$item->plan_id)->latest()->value('period_id');
                                             $plan = \DB::table('periods')->where('id',$period_id)->latest()->value('name');

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

                                        <td>{{$item->product_name}} {{$plan}}</td>
                                         <td>{{currencyFormat($item->regular_price,$code=$symbol)}}</td>
                                         <td>{{($item->agents)?$item->agents:'Unlimited'}}</td>
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
                        <div class="col-6" style="width: 50%;margin-left: 50%">
                              <div class="table-responsive">
                                 <table class="table">
                                     <tr>
                                         <th>Subtotal:</th>
                                         <td">{{currencyFormat($item->subtotal,$code=$symbol)}}</td>
                                     </tr>
                                     <?php
                                    $invoice = \DB::table('invoices')->where('id',$item->invoice_id)->first();
                                    ?>

                                     @if($invoice->credits)
                                         <tr>
                                             <th>Discount</th>
                                             <td>{{currencyFormat($invoice->credits,$code=$symbol)}} (Credits)</td>
                                         </tr>
                                     @endif

                                    @if($invoice->discount)
                                <tr>
                                    <th>Discount</th>
                                    <td>{{currencyFormat($invoice->discount,$code=$symbol)}} ({{$invoice->coupon_code}})</td>
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
                                                $bifurcateTax = bifurcateTax($taxDetails[0],$taxDetails[1],$symbol, \Auth::user()->state, $taxAmt);
                                                ?>
                                                 @foreach(explode('<br>', $bifurcateTax['html']) as $index => $part)
                                    <tr>
                                        <th>
                                            <strong>
                                                <?php
                                                $parts = explode('@', $part);
                                                $cgst = $parts[0];
                                                $percentage = $parts[1];
                                                ?>
                                                <span class="font-weight-bold text-color-grey">{{ $cgst }}</span>
                                                <span style="font-weight: normal;color: grey;">({{ $percentage }})</span><br>
                                            </strong>
                                        </th>
                                        <td class="text-color-grey moveright">
                                            <?php
                                            $taxParts = explode('<br>', $bifurcateTax['tax']);
                                            echo $taxParts[$index]; // Output tax amount corresponding to current index
                                            ?>
                                        </td>
                                    </tr>
                                    @endforeach
                                            </tr>
                                     
                                       
                                    @endif
                                     @endforeach
                                    <?php
                                    $feeAmount = intval(ceil($invoice->grand_total * 0.99 / 100));
                                    ?>


                                @if($invoice->processing_fee != null && $invoice->processing_fee != '0%')
                                <tr>
                                    <th class="font-weight-bold text-color-grey">Processing fee <span style="font-weight: normal;">({{$invoice->processing_fee}})</span></th>
                                    <td class="text-color-grey">{{currencyFormat($feeAmount,$code = $symbol)}}</td>
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
