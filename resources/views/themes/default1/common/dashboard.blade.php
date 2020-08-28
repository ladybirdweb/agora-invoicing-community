@extends('themes.default1.layouts.master')
@section('title')
Dashboard
@endsection
@section('content')
@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div><!-- /.col -->
@stop
<style>
.scrollit {
    overflow:scroll;
    height:300px;
}
</style>
 {!! Form::open(['url'=>'my-profile,"status=$status' ,'method'=>'get']) !!}
   <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h4>Total Sales</h4>
              @if(($allowedCurrencies2) != null)
              <span>{{$allowedCurrencies2}}: &nbsp;  {{currencyFormat($totalSalesCurrency2,$code=$allowedCurrencies2)}}</span><br/>
              @endif
               <span>{{$allowedCurrencies1}}: &nbsp;  {{currencyFormat($totalSalesCurrency1,$code=$allowedCurrencies1)}} </span>
            </div>

              <div class="icon">
                  <i class="ion ion-bag"></i>
              </div>
              <a href="{{url('invoices?status=success')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h4>Yearly Sales</h4>
                <?php
              $startingDateOfYear = (date('Y-01-01'));
              
              ?>
              @if(($allowedCurrencies2) != null)
              <span>{{$allowedCurrencies2}}:&nbsp;  {{currencyFormat($yearlySalesCurrency2,$code=$allowedCurrencies2)}}   </span><br/>
              @endif
               <span>{{$allowedCurrencies1}}:&nbsp; {{currencyFormat($yearlySalesCurrency1,$code=$allowedCurrencies1)}} </span>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
             <a href="{{url('invoices?status=success&from='.$startingDateOfYear)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h4>Monthly Sales</h4>
               <?php
              $startMonthDate = date('Y-m-01');
              $endMonthDate = date('Y-m-t');
               ?>
               @if(($allowedCurrencies2) != null)
              <span>{{$allowedCurrencies2}}:&nbsp; {{currencyFormat($monthlySalesCurrency2,$code=$allowedCurrencies2)}}</span><br/>
              @endif
              <span>{{$allowedCurrencies1}}:&nbsp; {{currencyFormat($monthlySalesCurrency1,$code=$allowedCurrencies1)}}</span>
             
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{url('invoices?status=success&from='.$startMonthDate. '&till='.$endMonthDate)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>

         <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h4>Pending Payments</h4>
              @if(($allowedCurrencies2) != null)
              <span>{{$allowedCurrencies2}}: &nbsp;  {{currencyFormat($pendingPaymentCurrency2,$code=$allowedCurrencies2)}}</span><br/>
              @endif
               <span>{{$allowedCurrencies1}}: &nbsp; {{currencyFormat($pendingPaymentCurrency1,$code=$allowedCurrencies1)}} </span>
            </div>
            <div class="icon">
             <i class="ion ion-ios-pricetag-outline"></i>
            </div>
             <a href="{{url('invoices?status=pending')}}" class="small-box-footer">More info 
              <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>
        @php
        $startDate = new Carbon\Carbon('-30 days');
        $endDate = Carbon\Carbon::now()->subDay();
        @endphp
         <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h4>Products Installed Rate:&nbsp;{{number_format($getLast30DaysInstallation['rate'], 2, '.', '')}}%</h4>
              <span>Total Subscription (Last 30 days): &nbsp;  {{$getLast30DaysInstallation['total_subscription']}}</span></br>
              <span>Not Installed (Last 30 days): &nbsp;  {{$getLast30DaysInstallation['inactive_subscription']}}</span>
            </div>
            <div class="icon">
             <i class="ion ion-ios-download-outline"></i>
            </div>
               <a href="{{url('orders?ins_not_ins=not_installed&sub_from='.$startDate.'&sub_till='.$endDate)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>
        @php
        $startDate = new Carbon\Carbon('-30 days');
        $endDate = Carbon\Carbon::now();
        @endphp

        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h4>Paid Orders Rate:&nbsp;{{number_format($conversionRate['rate'], 2, '.', '')}}%</h4>
              <span>Total Orders (Last 30 days): &nbsp;  {{$conversionRate['all_orders']}}</span></br>
              <span>Paid Orders (Last 30 days): &nbsp;  {{$conversionRate['paid_orders']}}</span>
            </div>
            <div class="icon">
             <i class="ion ion-ios-cart-outline"></i>
            </div>
              <a href="{{url('orders?p_un=unpaid&from='.$startDate.'&till='.$endDate)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>
</div>
 {!! Form::close() !!}



<div class="row">

    {{-- Recently Registered Users --}}
    @component('mini_views.card', [
           'title'=> 'Recently Registered Users',
           'layout' => 'custom',
           'collection'=> $users,
            'linkLeft'=> ['View All Users' => url('clients')],
           'linkRight'=> ['Create New User' => url('clients/create')]
    ])
        <ul class="users-list clearfix">
            @foreach($users as $user)
                <li>
                    <a class="users-list-name" href="{{url('clients/'.$user['id'])}}"> <img src="{{$user['profile_pic']}}" alt="User Image"></a>
                    <a class="users-list-name" href="{{url('clients/'.$user['id'])}}">{{$user['first_name']." ".$user['last_name']}}</a>

                    @php
                        $mytime = Carbon\Carbon::now();
                        $yesterday = Carbon\Carbon::yesterday();
                        $productSold=[];
                        $displayDate = new DateTime($user['created_at']);
                    @endphp

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
    @endcomponent

    {{-- Recent Invoices(Past 30 Days) --}}
    @component('mini_views.card', [
           'title'=> 'Recent Invoices(Past 30 Days)',
           'layout' => 'table',
           'collection'=> $invoices,
           'columns'=> ['Invoice No', 'Total', 'User', 'Paid', 'Balance', 'Status'],
            'linkLeft'=> ['View All Invoice' => url('invoices')],
           'linkRight'=> ['Generate New Invoice' => url('invoice/generate')]
    ])

        @foreach($invoices as $element)
            <tr>
                <td><a href="{{url('invoices/show?invoiceid='.$element->invoice_id)}}">{{$element->invoice_number}}</a></td>
                <td>{{$element->grand_total}}</td>
                <td><a href="{{'clients/'.$element->user_id}}">{{ $element->client_name }}</a></td>
                <td>{{$element->paid}}  </td>
                <td>
                    <div class="sparkbar" data-color="#00a65a" data-height="20">{{$element->balance}}</div>
                </td>
               <td>{!! $element->status !!}</td>
            </tr>
        @endforeach
    @endcomponent

</div>


 <div class="row">
     {{-- Paid Orders Expired in Last 30 days --}}
     @php
        $currentDate = date('Y-m-d');
        $expiringSubscriptionDate = date('Y-m-d', strtotime('+3 months'));
        $expiredSubscriptionDate = date('Y-m-d', strtotime('-3 months'));
     @endphp

     @component('mini_views.card', [
            'title'=> 'Paid Orders Expired in Last 30 days',
            'layout' => 'table',
            'collection'=> $expiredSubscriptions,
            'columns'=> ['User', 'Order No', 'Expiry', 'Days Passed', 'Product'],
            'linkLeft'=> ['Place New Order' => url('invoice/generate')],
            'linkRight'=> ['View Orders Expired' => url('orders?expiry='.$expiredSubscriptionDate.'&expiryTill='.$currentDate.'&p_un=paid')]
     ])

         @foreach($expiredSubscriptions as $element)
             <tr>
                 <td><a href="{{$element->client_profile_link}}">{{ $element->client_name }}</a></td>
                 <td><a href="{{$element->order_link}}">{{$element->order_number}}</a></td>
                 <td>{!! $element->subscription_ends_at !!}</td>
                 <td>{{$element->days_difference}}</td>
                 <td>{{$element->product_name}}</td>
             </tr>
         @endforeach
     @endcomponent

     {{-- Paid Orders Expiring Soon (Next 30 Days) --}}
     @component('mini_views.card', [
            'title'=> 'Paid Orders Expiring in Next 30 days',
            'layout' => 'table',
            'collection'=> $subscriptions,
            'columns'=> ['User', 'Order No', 'Expiry', 'Days Left', 'Product'],
            'linkLeft'=> ['Place New Order' => url('invoice/generate')],
            'linkRight'=> ['View Orders Expiring Soon' => url('orders?expiry='.$currentDate.'&expiryTill='.$expiringSubscriptionDate.'&p_un=paid')]
     ])

         @foreach($subscriptions as $element)
             <tr>
                 <td><a href="{{$element->client_profile_link}}">{{ $element->client_name }}</a></td>
                 <td><a href="{{$element->order_link}}">{{$element->order_number}}</a></td>
                 <td style="color: red";>{!! $element->subscription_ends_at !!}</td>
                 <td>{{$element->days_difference}}</td>
                 <td>{{$element->product_name}}</td>
             </tr>
         @endforeach
     @endcomponent

 </div>

 <div class="row">

     {{--   Clients With outdated Product Version (Last 30) --}}
     @php
        // NOTE: adding a filter between latest and olderst version for paid products for seeing outdated versions and sorting them in ascending order
        $latestVersion = \App\Model\Product\Subscription::orderBy("version", "desc")->groupBy("version")->skip(1)->value('version');
        $oldestVersion = \App\Model\Product\Subscription::where('version', '!=', null)->where('version', '!=', '')->orderBy("version", "asc")->groupBy("version")->value('version');
     @endphp

     @component('mini_views.card', [
            'title'=> 'Clients With outdated Product Version',
            'layout' => 'table',
            'collection'=> $clientsUsingOldVersion,
            'columns'=> ['User', 'Version', 'Product', 'Expiry'],
            'linkLeft'=> ['View All' => url('orders')."?version_from=$oldestVersion&version_till=$latestVersion&act_inst=paid_ins&sort_field=4&sort_order=asc"],
            'linkRight'=> ['Create New Product' => url('products/create')]
     ])
         @foreach($clientsUsingOldVersion as $element)
             <tr>
                 <td>{!! $element->client_name !!}</td>
                 <td>{!! $element->product_version !!}</td>
                 <td>{!! $element->product_name !!}</td>
                 <td style="color: red;">{!! $element->subscription_ends_at !!}</td>
             </tr>
         @endforeach
     @endcomponent


     {{-- Recent Paid Orders (Last 30 Days) --}}
     @component('mini_views.card', [
            'title'=> 'Recent Paid Orders (Last 30 Days)',
            'layout' => 'table',
            'collection'=> $recentOrders,
            'columns'=> ['Order No', 'Product', 'Date', 'User'],
             'linkLeft'=> ['View All Orders' => url('orders')],
            'linkRight'=> ['Place New Order' => url('invoice/generate')]
     ])

         @foreach($recentOrders as $element)
             <tr>
                 <td><a href="{{url('orders/'.$element->order_id)}}">{{$element->order_number}}</a></td>
                 <td>{{$element->product_name}}</td>
                 <td>{!! $element->order_created_at !!}</td>
                 <td><a href="{{$element->client_profile_link}}" target="_blank" class="sparkbar" data-color="#00a65a" data-height="20">{{$element->client_name}}</a></td>
             </tr>
         @endforeach
     @endcomponent

 </div>

<div class="row">

    {{-- Products Sold  (Last 30 Days) --}}
    @component('mini_views.card', [
           'title'=> 'Products Sold  (Last 30 Days)',
           'layout' => 'list',
           'collection'=> $productSoldInLast30Days,
           'columns'=> ['Order No', 'Item', 'Date', 'Client'],
            'linkLeft'=> ['View All Orders' => url('orders')],
           'linkRight'=> ['Place New Order' => url('invoice/generate')]
    ])

        @foreach($productSoldInLast30Days as $element)
            <li class="item">
                <div class="product-img">
                    <img src="{{$element->product_image}}" alt="Product Image">
                </div>
                <div class="product-info">
                    <a href="#" class="product-title">{{$element->product_name}}<strong> &nbsp; &nbsp;  <td><span class="label label-success">{{$element->order_count}}</span></td></strong>
                    </a>
                    <span class="product-description">
                        <strong> Last Purchase: </strong>
                          {{$element->order_created_at}}
                    </span>

                </div>
            </li>
        @endforeach
    @endcomponent

    {{-- Total Sold Products --}}
    @component('mini_views.card', [
           'title'=> 'Total Sold Products',
           'layout' => 'list',
           'collection'=> $allSoldProducts,
           'linkLeft'=> ['View All Products' => url('products')],
           'linkRight'=> ['Create New Product' => url('products/create')]
    ])
        @foreach($allSoldProducts as $element)
            <li class="item">
                <div class="product-img">
                    <img src="{{$element->product_image}}" alt="Product Image">
                </div>
                <div class="product-info">
                    <a href="#" class="product-title">{{$element->product_name}}<strong> &nbsp; &nbsp;  <td><span class="label label-success">{{$element->order_count}}</span></td></strong>
                    </a>
                    <span class="product-description">
                    <strong> Last Purchase: </strong>
                      {{$element->order_created_at}}
                    </span>
                </div>
            </li>
        @endforeach
    @endcomponent
</div>
@stop