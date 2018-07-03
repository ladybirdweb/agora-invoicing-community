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
              <h3 class="box-title">Recent Orders (Past 30 Days)</h3>

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
                    <th>Order Status</th>
                    <th>Invoice No.</th>
                    <th>Payment Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td><a href="pages/examples/invoice.html">2333456</a></td>
                    <td>Helpdesk Community</td>
                    <td><span class="label label-success">Executed</span></td>
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">3545355</div>
                    </td>
                    <td><span class="label label-success">Success</span></td>
                  </tr>
                   <tr>
                    <td><a href="pages/examples/invoice.html">233454456</a></td>
                    <td>Helpdesk Advance</td>
                    <td><span class="label label-success">Executed</span></td>
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">3532455</div>
                    </td>
                     <td><span class="label label-warning">Pending</span></td>
                  </tr>
                    <tr>
                    <td><a href="pages/examples/invoice.html">233454456</a></td>
                    <td>ServiceDesk Advance</td>
                    <td><span class="label label-success">Executed</span></td>
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">3532455</div>
                    </td>
                    <td><span class="label label-success">Success</span></td>
                  </tr>

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
                  <tr>
                    <td><a href="">Ashutosh Pthak</a></td>
                    <td>1323445</td>
                    <td>8 July, 2018, 12:30PM</td>
                    <td>12</td>
                     <td>Helpdesk Advance</td>
                  </tr>
                  <tr>
                    <td><a href="">Aniel Simmons</a></td>
                    <td>1323445</td>
                    <td>12 August, 2018, 8:30PM</td>
                    <td>8</td>
                     <td>Helpdesk Pro</td>
                  </tr>

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
@stop