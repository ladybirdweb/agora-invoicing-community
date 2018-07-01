@extends('themes.default1.layouts.master')
@section('content')
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total users</span>
                <span class="info-box-number">3545</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-trophy"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Pro Edition</span>
                <span class="info-box-number">24</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-briefcase"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Community Edition</span>
                <span class="info-box-number">1567</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-tags"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Products/Services Registered</span>
                <span class="info-box-number">3</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

    <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h4>Total Sales</h4>
              <span>INR:   25,00,000</span><br/>
               <span>USD:  2,345 </span>
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
              <span>INR:   2,50,000</span><br/>
               <span>USD:  345 </span>
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
              <span>INR:   1,00,000</span><br/>
               <span>USD:  100 </span>
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
                  <h3 class="box-title">Latest Registered Users</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                    <li>
                      <img src="dist/img/user1-128x128.jpg" alt="User Image">
                      <a class="users-list-name" href="#">Alexander Pierce</a>
                      <span class="users-list-date">Today</span>
                    </li>
                    <li>
                      <img src="dist/img/user8-128x128.jpg" alt="User Image">
                      <a class="users-list-name" href="#">Norman</a>
                      <span class="users-list-date">Yesterday</span>
                    </li>
                    <li>
                      <img src="dist/img/user7-128x128.jpg" alt="User Image">
                      <a class="users-list-name" href="#">Jane</a>
                      <span class="users-list-date">12 Jan</span>
                    </li>
                    <li>
                      <img src="dist/img/user6-128x128.jpg" alt="User Image">
                      <a class="users-list-name" href="#">John</a>
                      <span class="users-list-date">12 Jan</span>
                    </li>
                    <li>
                      <img src="dist/img/user2-160x160.jpg" alt="User Image">
                      <a class="users-list-name" href="#">Alexander</a>
                      <span class="users-list-date">13 Jan</span>
                    </li>
                    <li>
                      <img src="dist/img/user5-128x128.jpg" alt="User Image">
                      <a class="users-list-name" href="#">Sarah</a>
                      <span class="users-list-date">14 Jan</span>
                    </li>
                    <li>
                      <img src="dist/img/user4-128x128.jpg" alt="User Image">
                      <a class="users-list-name" href="#">Nora</a>
                      <span class="users-list-date">15 Jan</span>
                    </li>
                    <li>
                      <img src="dist/img/user3-128x128.jpg" alt="User Image">
                      <a class="users-list-name" href="#">Nadia</a>
                      <span class="users-list-date">15 Jan</span>
                    </li>
                  </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="javascript:void(0)" class="uppercase">View All Users</a>
                </div>
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
            </div>

             <div class="col-md-6">
             	         <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> Products Sold  (Past 30 Days)</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/advance.png" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Helpdesk Community <strong> x 6</strong>
                      <span class="label label-warning pull-right">$1800</span></a>
                       <span class="product-description">
                       	<strong> Last Purchase: </strong>
                          18 Oct, 2018, 11:52AM
                        </span>
                   
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/advance.png" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Helpdesk Pro <strong> x 2</strong>
                      <span class="label label-info pull-right">$700</span></a>
                     <span class="product-description">
                       	<strong> Last Purchase: </strong>
                          18 Oct, 2018, 11:52AM
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/advance.png" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Helpdesk Advance <strong> x 1</strong>  <span
                        class="label label-danger pull-right">$350</span></a>
                    <span class="product-description">
                       	<strong> Last Purchase: </strong>
                          18 Sep, 2018, 11:52AM
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/advance.png" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">ServiceDesk Advance <strong> x 1</strong> 
                      <span class="label label-success pull-right">$399</span></a>
                    <span class="product-description">
                       	<strong> Last Purchase: </strong>
                          3 Oct, 2018, 11:52AM
                        </span>
                  </div>
                </li>
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="javascript:void(0)" class="uppercase">View All Products</a>
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