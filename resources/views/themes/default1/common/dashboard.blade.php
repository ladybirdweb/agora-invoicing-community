@extends('themes.default1.layouts.master')
@section('title')
Dashboard
@endsection
@section('content')
<style>
.scrollit {
    overflow:scroll;
    height:300px;
}
</style>
 {!! Form::open(['url'=>'my-profile,"status=$status' ,'method'=>'get']) !!}
   <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h4>Total Sales</h4>
              @if(($allowedCurrencies2) != null)
              <span>{{$allowedCurrencies2}}: &nbsp;  {{currency_format($totalSalesCurrency2,$code=$allowedCurrencies2)}}</span><br/>
              @endif
               <span>{{$allowedCurrencies1}}: &nbsp;  {{currency_format($totalSalesCurrency1,$code=$allowedCurrencies1)}} </span>
            </div>

            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
             <a href="{{url('invoices?status=success')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
            	<h4>Yearly Sales</h4>
                <?php
              $startingDateOfYear = (date('Y-01-01'));
              
              ?>
              @if(($allowedCurrencies2) != null)
              <span>{{$allowedCurrencies2}}:&nbsp;  {{currency_format($yearlySalesCurrency2,$code=$allowedCurrencies2)}}   </span><br/>
              @endif
               <span>{{$allowedCurrencies1}}:&nbsp; {{currency_format($yearlySalesCurrency1,$code=$allowedCurrencies1)}} </span>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
             <a href="{{url('invoices?status=success&from='.$startingDateOfYear)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
            	<h4>Monthly Sales</h4>
               <?php
              $startMonthDate = date('Y-m-01');
              $endMonthDate = date('Y-m-t');
               ?>
               @if(($allowedCurrencies2) != null)
              <span>{{$allowedCurrencies2}}:&nbsp; {{currency_format($monthlySalesCurrency2,$code=$allowedCurrencies2)}}</span><br/>
              @endif
              <span>{{$allowedCurrencies1}}:&nbsp; {{currency_format($monthlySalesCurrency1,$code=$allowedCurrencies1)}}</span>
             
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{url('invoices?status=success&from='.$startMonthDate. '&till='.$endMonthDate)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>

         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h4>Pending Payments</h4>
              @if(($allowedCurrencies2) != null)
              <span>{{$allowedCurrencies2}}: &nbsp;  {{currency_format($pendingPaymentCurrency2,$code=$allowedCurrencies2)}}</span><br/>
              @endif
               <span>{{$allowedCurrencies1}}: &nbsp; {{currency_format($pendingPaymentCurrency1,$code=$allowedCurrencies1)}} </span>
            </div>
            <div class="icon">
             <i class="ion ion-ios-cart-outline"></i>
            </div>
             <a href="{{url('invoices?status=pending')}}" class="small-box-footer">More info 
              <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>
</div>
 {!! Form::close() !!}



<div class="row">
      <div class="col-md-6">
              <!-- USERS LIST -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Total Users: <span><strong>{{$count_users}}</strong></span></h3><br/>
                  <h3 class="box-title">Recently Registered Users</h3><br/>
                   <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->


                   <?php
                   $mytime = Carbon\Carbon::now();
                   $yesterday = Carbon\Carbon::yesterday();
                   $productSold=[];
                  ?>
                <div class="box-body no-padding">
                  <div class="scrollit">
                  <ul class="users-list clearfix">
                    @foreach($users as $user)
                    <li>
                     <a class="users-list-name" href="{{url('clients/'.$user['id'])}}"> <img src="{{$user['profile_pic']}}" alt="User Image"></a>
                      <a class="users-list-name" href="{{url('clients/'.$user['id'])}}">{{$user['first_name']." ".$user['last_name']}}</a>
                       <?php $displayDate = new DateTime($user['created_at']) ;
                        ?>
                      @if ($displayDate < $mytime)
                      <span class="users-list-date">{{($displayDate)->format('M j')}}</span>
                      @elseif ($displayDate == $yesterday)
                       <span class="users-list-date">Yesterday</span>
                      @else
                      <span class="users-list-date">Today</span>
                      @endif
                    </li>
                    @endforeach
                   </ul>
                 </div>

                  <!-- /.users-list -->
                </div>

                <!-- /.box-body -->
                 <div class="box-footer clearfix">
              <a href="{{url('clients')}}" class="btn btn-sm btn-info btn-flat pull-left">View All Users</a>
              <a href="{{url('clients/create')}}" class="btn btn-sm btn-default btn-flat pull-right">Create New User</a>
            </div>
              
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
            </div>

             <div class="col-md-6">
             	         <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> Products Sold  (Last 30 Days)</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
              </div>
            </div>
            <!-- /.box-header -->
           <div class="box-body">
              <div class="scrollit">
              <ul class="products-list product-list-in-box">
                   @foreach($arraylists as $key => $value)
                   <?php
                   $dayUtc = Carbon\Carbon::now()->subMonth();
                    $minus30Day = $dayUtc->toDateTimeString();
                   $imgLink= \App\Model\Product\Product::where('name',$key)->value('image');
                   $productId = \App\Model\Product\Product::where('name',$key)->value('id');
                   $dateUtc = \App\Model\Order\Order::where('product',$productId)->orderBy('created_at','desc')->pluck('created_at')->first();
                    $date1 = new DateTime($dateUtc);
                    $date = $date1->format('M j, Y, g:i a ');
                    // $orderPrice = \App\Model\Order\Order::where('product',$productId)->where('created_at', '>',$minus30Day )->orderBy('created_at','desc')->pluck('price_override')->all();
                    // $orderSum = array_sum($orderPrice);
                     ?>
                    <li class="item">
                  <div class="product-img">
                    <img src="{{$imgLink}}" alt="Product Image">
                  </div>
                  <div class="product-info">
                 <a href="#" class="product-title">{{$key}}<strong> &nbsp; &nbsp;  <td><span class="label label-success">{{$value}}</span></td></strong>
                    </a>
                       <span class="product-description">
                       	<strong> Last Purchase: </strong>
                          {{$date}}
                        </span>

                  </div>
                </li>
                  @endforeach

                <!-- /.item -->

                <!-- /.item -->
              </ul>
            </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="{{url('products')}}" class="btn btn-sm btn-info btn-flat pull-left">View All Products</a>
              <a href="{{url('products/create')}}" class="btn btn-sm btn-default btn-flat pull-right">Create New Product</a>
            </div>
            
            <!-- /.box-footer -->
          </div>
             </div>
         </div>


         <div class="row">
         	<div class="col-md-6">
         	  <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Paid Orders (Last 30 Days)</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <div class="scrollit">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Order No</th>
                    <th>Item</th>
                    <th>Date</th>
                    <th>Client</th>
                    <!-- <th>Total</th> -->
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($orders as $order)
                    <?php
                    $clientName = \App\User::where('id',$order->client)->select('first_name','last_name')->first();
                    $productName = \App\Model\Product\Product::where('id',$order->product)->value('name');
                    $dateUtc = $order->created_at;
                     $date1 = new DateTime($dateUtc);
                    $date = $date1->format('M j, Y, g:i a ');
                    ?>
                   <tr>
                       <td><a href="{{url('orders/'.$order->id)}}">{{$order->number}}</a></td>
                    <td>{{$productName}}</td>
                    <td>{{$date}}</td>
                    @if($clientName)
                    <td>
                      <a href="{{url('clients/'.$order->client)}}" class="sparkbar" data-color="#00a65a" data-height="20">{{$clientName->first_name}}{{$clientName->last_name}}</a>
                    </td>
                    @endif
                    <!-- <td><span class="label label-success">{{$order->price_override}}</span></td> -->
                    
                  </tr>
                   @endforeach


                   </tbody>
                </table>
              </div>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
           
              <a href="{{url('orders')}}" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
               <a href="{{url('invoice/generate')}}" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
            </div>
              
           
            <!-- /.box-footer -->
            </div>
          </div>

         	<div class="col-md-6">
         	   <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Paid Orders Expiring Soon (Next 30 Days)</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                 <div class="scrollit">
                <table class="table no-margin">
                  <thead>
                  <tr>
                  	<th>User</th>
                    <th>Order No</th>
                    <th>Expiry</th>
                    <th>Days Left</th>
                    <th>Product</th>
                   </tr>
                  </thead>
                    

                  <tbody>
                    @if(count($subscriptions)==0)
                    <tr>
                      <td></td> <td></td>
                  <td><h5>No Orders Expiring in Next 30 Days</h5></td>
                     <td></td>
                    </tr>
                   @else
                    
                  @foreach($subscriptions as $subscription)


                   <?php
                  $todayDate = Carbon\Carbon::now();
                  $clientName = \App\User::where('id', $subscription->user_id)->select('first_name','last_name')->first();
                  $orderNo = \App\Model\Order\Order::where('id',$subscription->order_id)->value('number');
                  $expiry = $subscription->update_ends_at;
                  $date = new DateTime($expiry); 
                  $tz = \Auth::user()->timezone()->first()->name;
                  $date->setTimezone(new DateTimeZone($tz)); 
                  $expDate = $date->format('M j, Y ');
                  $product =  \App\Model\Product\Product::where('id',$subscription->product_id)->value('name');
                  $daysLeft = date_diff($todayDate,$date)->format('%a days');
                   
                    ?>

                  <tr>
                    <td><a href="{{url('clients/'.$subscription->user_id)}}">{{$clientName->first_name}} {{$clientName->last_name}}</a></td>
                    <td><a href="{{url('orders/'.$subscription->order_id)}}">{{$orderNo}}</a></td>
                    <td>{{$expDate}}</td>
                    <td>{{$daysLeft}}</td>
                     <td>{{$product}}</td>
                  </tr>
                  @endforeach
                  @endif
               
				           </tbody>
                </table>
              </div>
              </div>
              <!-- /.table-responsive -->
            </div>
             <div class="box-footer clearfix">
           
              <a href="{{url('orders?expiry='.$startSubscriptionDate.'&expiryTill='.$endSubscriptionDate.'&p_un=paid')}}" class="btn btn-sm btn-default btn-flat pull-right">View Orders Expiring Soon</a>
              <!-- <a href="{{url('orders?expiryTill='.$endSubscriptionDate)}}" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a> -->
               <a href="{{url('invoice/generate')}}" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
            </div>
              
            <!-- /.box-body -->

            <!-- /.box-footer -->
            </div>
          </div>
         </div>

         <div class= row>
          <div class="col-md-6">
            <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Invoices(Past 30 Days)</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                 <div class="scrollit">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Invoice No.</th>
                    <th>Total</th>
                    <th>Client</th>
                    <th>Paid </th>
                    <th>Balance</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @if(count($invoices)==0)
                    <tr>
                      <td></td> <td></td>
                  <td><h5>No Paid Invoices in Last 30 Days</h5></td>
                     <td></td>
                    </tr>
                   @endif
                    @foreach($invoices as $invoice)
                    <?php
                   $currency  = \App\Model\Payment\Currency::where('code',$invoice->currency)->pluck('code')->first();
                   $payment = \App\Model\Order\Payment::where('invoice_id',$invoice->id)->select('amount')->get();
                   $c=count($payment);
                   $sum= 0;
                   for($i=0 ;  $i <= $c-1 ; $i++)
                   {
                     $sum = $sum + $payment[$i]->amount;
                   }
                    $pendingAmount = ($invoice->grand_total)-($sum);
                    $status =($pendingAmount <= 0) ? 'Success' :'Pending';
                    $clientName = \App\User::where('id',$invoice->user_id)->select('first_name','last_name')->first();
                    ?>
                  <tr>
                    <td><a href="{{url('invoices/show?invoiceid='.$invoice->id)}}">{{$invoice->number}}</a></td>

                    <td>{{currency_format($invoice->grand_total,$code=$currency)}}  </td>
                     <td>{{$clientName->first_name}} {{$clientName->last_name}}</td>
                    <td>{{currency_format($sum,$code=$currency)}}  </td>
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">{{currency_format($pendingAmount,$code=$currency)}}</div>
                    </td>
                   @if ($status == 'Success')
                    <td><span class="label label-success">{{$status}}</span></td>
                   @elseif ($status == 'Pending')
                    <td><span class="label label-danger">{{$status}}</span></td>
                   @endif
                   
                  </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="{{url('invoices')}}" class="btn btn-sm btn-info btn-flat pull-left">View All Invoice</a>
              <a href="{{url('invoice/generate')}}" class="btn btn-sm btn-default btn-flat pull-right">Generate New Invoice</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>
             
             <div class="col-md-6">
             <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Total Sold Products</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="scrollit">
              <ul class="products-list product-list-in-box">
                 @foreach($arrayCountList as $key => $value)
                 <?php
                 $imgLink= \App\Model\Product\Product::where('name',$key)->value('image');
                  $productId = \App\Model\Product\Product::where('name',$key)->value('id');
                  $dateUtc = \App\Model\Order\Order::where('product',$productId)->orderBy('created_at','desc')->pluck('created_at')->first();
                  $date1 = new DateTime($dateUtc);
                  $date = $date1->format('M j, Y, g:i a ');
                 ?>
                <li class="item">
                  <div class="product-img">
                    <img src="{{$imgLink}}" alt="Product Image">
                  </div>
                   <div class="product-info">

                    <a href="#" class="product-title">{{$key}}<strong> &nbsp; &nbsp;  <td><span class="label label-success">{{$value}}</span></td></strong>

                    </a>
                       <span class="product-description">
                        <strong> Last Purchase: </strong>
                          {{$date}}
                        </span>

                  </div>
                </li>
                @endforeach
              </ul>
            </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="{{url('products')}}" class="btn btn-sm btn-info btn-flat pull-left">View All Products</a>
              <a href="{{url('products/create')}}" class="btn btn-sm btn-default btn-flat pull-right">Create New Product</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>
         </div>
@stop