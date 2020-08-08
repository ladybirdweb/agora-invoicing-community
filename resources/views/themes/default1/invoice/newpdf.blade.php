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
                                    <img alt="Logo" width="100" height="50" src="{{asset('common/images/'.$logo)}}">
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
                                Phone: {{$set->phone}}<br>
                                Email: {{$set->email}}
                            </address>
                        </div><!-- /.col -->
                         <div class="col-sm-4" style="float:right!important">
                            <b>Invoice   #{{$invoice->number}}</b><br>
                            <br>


                            <b>Order:</b>   #{!! $order !!}
                            <br>


                            <b>GSTIN:</b>  &nbsp; #{{$gst->Gst_No}}
                            <br>


                        </div><!-- /.col -->
                        <div class="col-sm-4">
                            &nbsp;&nbsp;&nbsp;To
                            <address>
                                
                                <strong>{{$user->first_name}} {{$user->last_name}}</strong><br>
                                {{$user->address}}<br>
                                {{$user->town}}<br>
                                @if(key_exists('name',App\Http\Controllers\Front\CartController::getStateByCode($user->state)))
                                {{App\Http\Controllers\Front\CartController::getStateByCode($user->state)['name']}}
                                @endif
                                {{$user->zip}}<br>
                                Country : {{App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}<br>

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
                                        <td> {!! getOrderLink($orderForThisItem->id) !!} </td>
                                       
                                            @elseif($order != '--')
                                            <td>{!! $order !!}</td>
                                            <span>Renewed</span>
                                            @else
                                            <td>--</td>
                                           
                                        @endif
                                        <td>{{$item->product_name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{currencyFormat($item->regular_price,$code=$symbol)}}</td>
                                        <td>
                                           {{$item->tax_name}}
                                        </td>
                                        <td>
                                            {{$item->tax_percentage}}
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
            
                        <div class="col-6"></div>
                        <div class="col-6" style="width: 50%;">
                            <p class="lead">Amount</p>
                              <div class="table-responsive">
                                 <table class="table">
                                     <tr>
                                         <th>Subtotal:</th>
                                         <td>{{currencyFormat($item->regular_price,$code=$symbol)}}</td>
                                     </tr>
                                      @if($invoice->discount != null)
                                  <th>Discount</th>
                                    <td>{{currencyFormat($invoice->discount,$code=$symbol)}}</td>
                                @endif

                                 <?php
                                    $order = \App\Model\Order\Order::where('invoice_item_id',$item->id)->first();
                                    if($order != null) {
                                        $productId = $order->product;
                                    } else {
                                        $productId =  App\Model\Product\Product::where('name',$item->product_name)->pluck('id')->first();
                                    }
                                    
                                    ?>
                                     @if ($item->tax_name != 'null')
                                            
                                       
                                            <tr>
                                                 <?php
                                                $bifurcateTax = bifurcateTax($item->tax_name,$item->tax_percentage,$user->currency, $user->state, $item->regular_price);
                                                ?>
                                                <th>

                                                    <strong>{!! $bifurcateTax['html'] !!}</strong>


                                                </th>
                                                <td>
                                                   
                                                    {!! $bifurcateTax['tax'] !!}

                                                </td>
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
