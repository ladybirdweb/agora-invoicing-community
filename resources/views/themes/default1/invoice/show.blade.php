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
                $date = getDateHtml($invoice->date);
                 $symbol = $invoice->currency;
                $itemsSubtotal = 0;
                ?>

                    <!-- title row -->

                    <div class="row">
                        <div class="col-12">
                            <h4>
                                @if($set->logo)
                                    <img alt="Logo" width="100" height="50" src="{{asset('common/images/'.$set->logo)}}" style="margin-top: -2px">
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
                                {{$set->city}}<br/>
                                @if(key_exists('name',getStateByCode($set->state)))
                                {{getStateByCode($set->state)['name']}}
                                @endif
                                {{$set->zip}}<br/>
                                Country : {{getCountryByCode($set->country)}}<br/>
                                Mobile: {{$set->phone}}<br/>
                                Email: {{$set->email}}
                            </address>
                             @if($set->gstin)
                            <b>GSTIN:</b>  &nbsp; #{{$set->gstin}}
                            <br>
                            @endif

                            @if($set->cin_no)
                            <b>CIN:</b>  &nbsp; #{{$set->cin_no}}
                            <br>
                            @endif<br>

                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                <strong>{{$user->first_name}} {{$user->last_name}}</strong><br>
                                {{$user->address}}<br/>
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
                            <b>GSTIN:</b>  &nbsp; #{{$user->gstin}}
                            <br>
                            @endif
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Invoice   #{{$invoice->number}}</b><br>
                           
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
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($invoiceItems as $item)
                                    <tr>
                                        @php
                                        $orderForThisItem = $item->order()->first();
                                        $itemsSubtotal += $item->subtotal;
                                        @endphp
                                        @if($orderForThisItem)

                                        <td> {!! $orderForThisItem->getOrderLink($orderForThisItem->id) !!}</td>
                                        
                                            @elseif($order != '--')

                                            <td>{!! $order !!}</td>
                                            <span>Renewed</span>
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
                        <div class="col-6">

                        </div>
                        <div class="col-6">
                            <div class="table-responsive">
                              
                                       
                                 <table class="table">
                                     <tr>
                                         <th>Subtotal:</th>
                                         <td>{{currencyFormat($itemsSubtotal,$code=$symbol)}}</td>
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
                                                $bifurcateTax = bifurcateTax($item->tax_name,$item->tax_percentage,$user->currency, $user->state, $item->subtotal);
                                                ?>
                                                <th>

                                                    <strong>{!! $bifurcateTax['html'] !!}</strong>


                                                </th>
                                                <td>
                                                   
                                                    {!! $bifurcateTax['tax'] !!}

                                                </td>
                                            </tr>
                                     
                                       
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
    <script>
     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'all_invoice';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'all_invoice';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>
<script>
    $(document).ready(function(){
         $(function () {
          $('[data-toggle="tooltip"]').tooltip({
            container : 'body'
          });
        });
    })
</script>

@stop