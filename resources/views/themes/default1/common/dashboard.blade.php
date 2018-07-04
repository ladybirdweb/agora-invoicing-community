@extends('themes.default1.layouts.master')
@section('content')

   <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h4>Total Sales</h4>
              <?php
              $rupeeSum = number_format($totalSalesINR,2);
              $dollarSum = number_format($totalSalesUSD);
              ?>
              <span>INR: &nbsp;  ₹ {{$rupeeSum}}</span><br/>
               <span>USD: &nbsp;  $ {{$dollarSum}} </span>
            </div>

            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
            	<h4>Yearly Sales</h4>
                <?php
              $rupeeYearlySum = number_format($yearlySalesINR,2);
              $dollarYearlySum = number_format($yearlySalesUSD,2);
              ?>
              <span>INR:&nbsp;  ₹ {{$rupeeYearlySum}}   </span><br/>
               <span>USD:&nbsp; $ {{$dollarYearlySum}} </span>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
             </div>
        </div>
        <!-- ./col -->
   <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
            	<h4>Monthly Sales</h4>
               <?php
              $rupeeMonthlySum = number_format($monthlySalesINR,2);
              $dollarMonthlySum = number_format($monthlySalesUSD,2);
              ?>
              <span>INR:&nbsp;  ₹  {{$rupeeMonthlySum}}</span><br/>
              <span>USD:&nbsp; $ {{$dollarMonthlySum}}</span>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
             </div>
        </div>
</div>




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

                  <ul class="users-list clearfix">
                    @foreach($users as $user)
                    <li>
                      <img src="{{$user['profile_pic']}}" alt="User Image">
                      <a class="users-list-name" href="{{url('clients/'.$user['id'])}}">{{$user['first_name']." ".$user['last_name']}}</a>

                      @if ($user['created_at']->toDateString() < $mytime->toDateString())
                      <span class="users-list-date">{{$user['created_at']->format('M j')}}</span>
                      @elseif ($user['created_at']->toDateString() < $yesterday->toDateString())
                       <span class="users-list-date">Yesterday</span>
                      @else
                      <span class="users-list-date">Today</span>
                      @endif
                    </li>
                    @endforeach
                   </ul>

                  <!-- /.users-list -->
                </div>

                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="{{url('clients')}}" class="uppercase">View All Users</a>
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
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
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

                    <a href="#" class="product-title">{{$key}}<strong> &nbsp; &nbsp; x  {{$value}}</strong>

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
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="{{url('products')}}" class="uppercase">View All Products</a>
            </div>
            <!-- /.box-footer -->
          </div>
             </div>
         </div>


         <div class="row">
         	<div class="col-md-6">
         	  <div class="box box-info">
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
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Order No</th>
                    <th>Item</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Total</th>
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
                    <td>
                      <a href="{{url('clients/'.$order->client)}}" class="sparkbar" data-color="#00a65a" data-height="20">{{$clientName->first_name}}{{$clientName->last_name}}</a>
                    </td>
                    <td><span class="label label-success">{{$order->price_override}}</span></td>
                    
                  </tr>
                   @endforeach


                   </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="{{url('invoice/generate')}}" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
              <a href="{{url('orders')}}" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer -->
            </div>
          </div>

         	<div class="col-md-6">
         	  <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Orders Expiring Soon</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
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
                   @endif
                    
                  @foreach($subscriptions as $subscription)
                   <?php
                  $todayDate = Carbon\Carbon::now();
                  $clientName = \App\User::where('id', $subscription->user_id)->select('first_name','last_name')->first();
                  $orderNo = \App\Model\Order\Order::where('id',$subscription->order_id)->value('number');
                  $expiry = $subscription->ends_at;
                  $date = new DateTime($expiry); 
                  $expDate = $date->format('M j, Y ');
                   $product =  \App\Model\Product\Product::where('id',$subscription->product_id)->value('name');
                    $daysLeft = date_diff($todayDate,$expiry)->format('%a days');
                    ?>

                  <tr>
                    <td><a href="{{url('clients/'.$subscription->user_id)}}">{{$clientName->first_name}} {{$clientName->last_name}}</a></td>
                    <td><a href="{{url('orders/'.$subscription->order_id)}}">{{$orderNo}}</a></td>
                    <td>{{$expDate}}</td>
                    <td>{{$daysLeft}}</td>
                     <td>{{$product}}</td>
                  </tr>
                  @endforeach
                  
               
				           </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
            </div>
          </div>
         </div>

         <div class= row>
          <div class="col-md-6">
            <div class="box box-info">
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
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Invoice No.</th>
                    <th>Total</th>
                    <th>Received Amount </th>
                    <th>Pending Amount</th>
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
                    if($invoice->currency == 'INR')
                    $currency = '₹';
                    else
                    $currency = '$'; 
                   $payment = \App\Model\Order\Payment::where('invoice_id',$invoice->id)->select('amount')->get();
                   $c=count($payment);
                   $sum= 0;
                   for($i=0 ;  $i <= $c-1 ; $i++)
                   {
                     $sum = $sum + $payment[$i]->amount;
                   }
                    $pendingAmount = ($invoice->grand_total)-($sum);
                   if($pendingAmount <= 0)
                    $status =$pendingAmount <= 0 ? 'Success' :'Pending';
                   ?>
                  <tr>
                    <td><a href="pages/examples/invoice.html">{{$invoice->number}}</a></td>
                    <td>{{$currency}} {{$invoice->grand_total}}</td>
                    <td>{{$currency}} {{$sum}}</td>
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">{{$currency}} {{$pendingAmount}}</div>
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
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>
             
             <div class="col-md-6">
             <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Browser Usage</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="chart-responsive">
                    <canvas id="pieChart" height="150"></canvas>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                  <ul class="chart-legend clearfix">
                    <li><i class="fa fa-circle-o text-red"></i> Chrome</li>
                    <li><i class="fa fa-circle-o text-green"></i> IE</li>
                    <li><i class="fa fa-circle-o text-yellow"></i> FireFox</li>
                    <li><i class="fa fa-circle-o text-aqua"></i> Safari</li>
                    <li><i class="fa fa-circle-o text-light-blue"></i> Opera</li>
                    <li><i class="fa fa-circle-o text-gray"></i> Navigator</li>
                  </ul>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#">United States of America
                  <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a></li>
                <li><a href="#">India <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a>
                </li>
                <li><a href="#">China
                  <span class="pull-right text-yellow"><i class="fa fa-angle-left"></i> 0%</span></a></li>
              </ul>
            </div>
            <!-- /.footer -->
          </div>
        </div>
         </div>
@stop