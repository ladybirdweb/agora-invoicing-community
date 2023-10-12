@extends('themes.default1.layouts.front.master')
@section('title')
    Orders
@stop
@section('nav-orders')
    active
@stop
@section('page-heading')
    View Order
@stop
@section('breadcrumb')
    <style>
        option
        {
            font-size: 15px;
        }
        .modal-backdrop {
            /* bug fix - no overlay */
            display: none;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {display:none;}

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

.slider.round:before {
  border-radius: 50%;
}
.scrollit {
    overflow:scroll;
    height:600px;
}

    .horizontal-images {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .horizontal-images img {
            height: auto;
            width: 12%;
            margin-right: 5px;
        }
        .custom-close {
        position: absolute;
        top: -20px;
        right: -20px;
        width: 30px;
        height: 30px;
        background-color: red;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 20px;
      }
      .alert.alert-danger .close {
        position: absolute;
        top: 0;
        right: 0;
}
</style>
    @if(Auth::check())
        <li><a href="{{url('my-invoices')}}">Home</a></li>
    @else
        <li><a href="{{url('login')}}">Home</a></li>
    @endif
    <li><a href= "{{url('my-orders')}}">My Orders</a></li>
    <li class="active">View Order</li>
@stop
<?php

$cartSubtotalWithoutCondition = 0;

use Razorpay\Api\Api;
$merchant_orderid= generateMerchantRandomString();

function generateMerchantRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$rzp_key = app\ApiKey::where('id', 1)->value('rzp_key');
$rzp_secret = app\ApiKey::where('id', 1)->value('rzp_secret');
$apilayer_key = app\ApiKey::where('id', 1)->value('apilayer_key');
$api = new Api($rzp_key, $rzp_secret);
$displayCurrency = \Auth::user()->currency;
$symbol = \Auth::user()->currency;
if ($symbol == 'INR'){


    $exchangeRate= '';


    $orderData = [
        'receipt'         => 3456,
        'amount'          => round(1.00*100), // 2000 rupees in paise

        'currency'        => \Auth::user()->currency,
        'payment_capture' => 0 // auto capture

    ];


} else {

    $url = "http://apilayer.net/api/live?access_key=$apilayer_key";
    $exchange = json_decode(file_get_contents($url));

    $exchangeRate = $exchange->quotes->USDINR;
    $displayAmount =$exchangeRate * round(1.00*100) ;


    $orderData = [
        'receipt'         => 3456,
        'amount'          =>  round(1.00*100), // 2000 rupees in paise

        'currency'        => \Auth::user()->currency,
        'payment_capture' => 0 // auto capture

    ];
}
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorpayOrderId;
$displayAmount = $amount = $orderData['amount'];



$data = [


    "key"               => $rzp_key,
    "name"              => 'Faveo Helpdesk',
    "currency"          => 'INR',
    "prefill"=> [
        "contact"=>    \Auth::user()->mobile_code .\Auth::user()->mobile,
        "email"=>      \Auth::user()->email,
    ],
    "description"       =>  'Order for Invoice No' .-$invoice->number,
    "notes"             => [
        "First Name"         => \Auth::user()->first_name,
        "Last Name"         =>  \Auth::user()->last_name,
        "Company Name"      => \Auth::user()->company,
        "Address"           =>  \Auth::user()->address,
        "Email"             =>  \Auth::user()->email,
        "Country"           =>  \Auth::user()->country,
        "State"             => \Auth::user()->state,
        "City"              => \Auth::user()->town,
        "Zip"               => \Auth::user()->zip,
        "Currency"          => \Auth::user()->currency,
        "Amount Paid"   => '1',
        "Exchange Rate"   =>  $exchangeRate,



        "merchant_order_id" =>  $merchant_orderid,
    ],
    "theme"             => [
        "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = 'USD';
    $data['display_amount']    ='1';

}
$json = json_encode($data);


$currency = \Auth::user()->currency;



$gateways = \App\Http\Controllers\Common\SettingsController::checkPaymentGateway(\Auth::user()->currency);
// $processingFee = \DB::table(strtolower($gateways))->where('currencies',\Auth::user()->currency)->value('processing_fee');

$planid = \App\Model\Payment\Plan::where('product',$product->id)->value('id');
$price = $order->price_override;





?>

@section('content')
    @include('themes.default1.front.clients.reissue-licenseModal')
    @include('themes.default1.front.clients.domainRestriction')
    <div class="row pb-4">
        <div class="col-lg-12 mb-12 mb-lg-0">
            <div class="alert alert-tertiary" style="padding-bottom: 5px; background-color: #49b1bf">
                <div class="row">
                    <div class="col col-md-3">Order No: #{{$order->number}}</div>
                    <div class="col col-md-3">Date: {!! getDateHtml($order->created_at) !!}</div>
                    <div class="col col-md-3">Status: {{$order->order_status}}</div>
                    <div class="col col-md-3">Expiry Date: {!! getDateHtml($subscription->update_ends_at) !!}</div>

                </div>
            </div>

            <!-- Modal for Localized License domain-->

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Please Enter Your Domain That You Wish To Host</h5>
                        </div>
                        <div class="modal-body">
                            <form method="GET" action="{{url('uploadFile')}}">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Domain Name:</label>
                                    <input type="text" class="form-control" id="recipient-name" placeholder="https://faveohelpdesk.com/public" name="domain" value="" onkeydown="return event.key != 'Enter';">
                                    {{Form::hidden('orderNo', $order->number)}}
                                    {{Form::hidden('userId',$user->id)}}
                                    <br>
                                    <div class="modal-footer">
                                        <button type="button" id="close" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;Close</button>
                                        @if((!Storage::disk('public')->exists('faveo-license-{'.$order->number.'}.txt')) || $order->is_downloadable==0)
                                            <button type="submit" id="domainSave" class="done btn btn-primary" {{$order->where('number',$order->number)->update(['is_downloadable'=> 1])}}><i class="fas fa-save"></i>&nbsp;Done</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="alertMessage-2"></div>

            @php
                if($order->order_status!='Terminated'){
                    $navigations = [
                         ['id'=>'license-details', 'name'=>'License Details','active'=>1, 'slot'=>'license','icon'=>'fas fa-file'],
                         //['id'=>'user-details', 'name'=>'User Details', 'slot'=>'user','icon'=>'fas fa-users'],
                         ['id'=>'invoice-list', 'name'=>'Invoice List', 'slot'=>'invoice','icon'=>'fas fa-credit-card'],
                         ['id'=>'payment-receipts', 'name'=>'Payment Receipts', 'slot'=>'payment','icon'=>'fas fa-briefcase'],
                    ];
                    }
                else{
                     $navigations = [
                         ['id'=>'license-details', 'name'=>'Order Terminated','active'=>1, 'slot'=>'license','icon'=>'fas fa-warning'],
                         ['id'=>'user-details', 'name'=>'User Details', 'slot'=>'user','icon'=>'fas fa-users'],
                         ['id'=>'invoice-list', 'name'=>'Invoice List', 'slot'=>'invoice','icon'=>'fas fa-credit-card'],
                         ['id'=>'payment-receipts', 'name'=>'Payment Receipts', 'slot'=>'payment','icon'=>'fas fa-briefcase'],
                    ];
                }
                    if($product->type == '4' && $order->order_status!='Terminated'){
                        $navigations[]=['id'=>'Cloud-Settings', 'name' => 'Cloud Settings','slot'=>'cloud','icon'=>'fas fa-cloud'];
                    }

                    if ($price == '0' && $product->type != '4' && $order->order_status!='Terminated') {
                        $navigations[] = ['id'=>'auto-renewals', 'name'=>'Auto Renewal', 'slot'=>'autorenewal','icon'=>'fas fa-bell'];
                    }
                    elseif($price != '0' && $order->order_status!='Terminated')
                    {
                      $navigations[] = ['id'=>'auto-renewals', 'name'=>'Auto Renewal', 'slot'=>'autorenewal','icon'=>'fas fa-bell'];
                    }
            @endphp

            @component('mini_views.navigational_view', [
              'navigations' => $navigations
            ])


                @if($order->order_status != 'Terminated')

                    @slot('license')

                        <table class="table">
                            <?php
                                $terminatedOrderId = \DB::table('terminated_order_upgrade')->where('upgraded_order_id',$order->id)->value('terminated_order_id');
                                $terminatedOrderNumber = \App\Model\Order\Order::where('id',$terminatedOrderId)->value('number');
                                ?>
                         @if(!empty($terminatedOrderId))
                                <p class="order-links">
                                    This order <b>{{$order->number}}</b>
                                    has been generated because this order was terminated: <a class="order-link" href="{{$terminatedOrderId}}">{{$terminatedOrderNumber}}</a>.
                                </p>
                         @endif

                            <input type="hidden" name="domainRes" id="domainRes" value={{$allowDomainStatus}}>
                            <tbody>
                            <tr>
                                <td><b>License Code:</b></td>
                                <td id="s_key" data-type="serialkey">{{$order->serial_key}}</td>

                                <td><span data-type="copy" style="font-size: 15px; pointer-events: initial; cursor: pointer; display: block;" id="copyBtn" title="Click to copy to clipboard"><i class="fa fa-clipboard"></i></span><span class="badge badge-success badge-xs pull-right" id="copied1" style="display:none;margin-top:-40px;margin-left:-20px;position: absolute;">Copied</span></td>
                            </tr>
                            @if ($licenseStatus == 1)
                                <tr>
                                    <td><b>Licensed Domain/IP:</b></td>
                                    <td>{{$order->domain}} </td>
                                    <td>
                                        @if($product->type != '4' && $price != '0')
                                            <button class="btn btn-danger mb-2 btn-sm"  id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}" {{!Storage::disk('public')->exists('faveo-license-{'.$order->number.'}.txt') || $order->license_mode!='File' ? "enabled" : "disabled"}}>
                                                Reissue License</button></td>
                                    @elseif($product->type != '4' && $price == '0')
                                        <button class="btn btn-danger mb-2 btn-sm"  id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}" {{!Storage::disk('public')->exists('faveo-license-{'.$order->number.'}.txt') || $order->license_mode!='File' ? "enabled" : "disabled"}}>
                                            Reissue License</button></td>
                                    @elseif($product->type == '4' && $price != '0')
                                        <button class="btn btn-danger mb-2 btn-sm"  id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}" {{!Storage::disk('public')->exists('faveo-license-{'.$order->number.'}.txt') || $order->license_mode!='File' ? "enabled" : "disabled"}}>
                                            Reissue License</button></td>
                                    @endif
                                </tr>
                            @endif

                            <tr>
                                <td><b>License Expiry Date:</b></td>
                                <td>{!! $licdate !!}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td><b>Update Expiry Date:</b></td>
                                <td>{!! $date !!}</td>
                                <td></td>
                            </tr>

                            @if($order->license_mode=='File')
                                <tr>
                                    <td><b>Localized License:</b></td>
                                    <td>
                                        <button class="btn btn-primary mb-2 btn-sm" id="defaultModalLabel" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" {{!Storage::disk('public')->exists('faveo-license-{'.$order->number.'}.txt') || $order->is_downloadable==0 ? "enabled" : "disabled"}}>Download License File</button>
                                    </td>
                                    <td><a href="{{url('downloadPrivate/'.$order->number)}}"><button class="btn btn-primary mb-2 btn-sm">Download License Key</button></a>
                                        <i class="fa fa-info ml-2" title="It is mandatory to download both files inorder for licensing to work. Please place these files in Public\Script\Signature in faveo." {!!tooltip('Edit')!!} </i>
                                    </td>
                                </tr>
                            @endif

                            </tbody>

                        </table>
                        <script src="{{asset('common/js/licCode.js')}}"></script>


                        <table id="installationDetail-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">


                            <thead>
                            <tr>

                                <th>Installation Path</th>
                                @if($product->type != '4')
                                    <th>Installation IP</th>
                                @endif
                                <th>Current Version </th>
                                <th>  Last Active</th>

                            </tr></thead>
                            <tbody>
                            @foreach($installationDetails['installed_path'] as $key => $ins)
                                <?php
                                $Latestversion = DB::table('product_uploads')->where('product_id', $order->product)->latest()->value('version');

                                $productversion = DB::table('installation_details')->where('installation_path',$installationDetails['installed_path'])->first();

                                if($productversion) {

                                    $date = getTimeInLoggedInUserTimeZone($productversion->updated_at, 'M j, Y');
                                    $dateTime = getTimeInLoggedInUserTimeZone($productversion->updated_at);
                                }

                                $active = !empty($ins)?true:false;



                                ?>
                                <tr>
                                    <td><a href="https://{{$ins}}" target="_blank">{{$ins}}</a></td>
                                    @if($product->type != '4')
                                        <td>{{$installationDetails['installed_ip'][$key]}}</td>
                                    @endif
                                    @if($productversion)
                                        @if($productversion < $Latestversion)
                                            <td><span class='.'"'.$badge.' '.$badge.'-warning" <label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="Outdated Version">
                                                </label>{{$productversion->version}}</span></td>
                                        @else
                                            <td><span class='.'"'.$badge.' '.$badge.'-success" <label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="Latest Version">
                                                </label>{{$productversion->version}}</span></td>
                                        @endif

                                    @endif
                                    @if($productversion)
                                        <td><label data-toggle='tooltip' style='font-weight:500;' data-placement='top' title='{{$dateTime}}'>{{$date}}</label></td>
                                    @endif
                                    @if($active == true)
                                        <td><span class='badge badge-primary' style='background-color:darkcyan !important;' <label data-toggle='tooltip' style='font-weight:500;' data-placement='top' title='Installation is Active'>
                                            </label>Active</span></td>
                                    @else
                                        <td><span class='badge badge-info' <label data-toggle='tooltip' style='font-weight:500;background-color:crimson;' data-placement='top' title='Installation inactive for more than 30 days'>
                                            </label>Inactive</span></td>
                                    @endif


                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        <script>
                            $('ul.nav-sidebar a').filter(function() {
                                return this.id == 'all_order';
                            }).addClass('active');

                            // for treeview
                            $('ul.nav-treeview a').filter(function() {
                                return this.id == 'all_order';
                            }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
                        </script>
                        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

                        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

                    @endslot
                @else
                    @slot('license')
                        <?php
                        $idOrdert  = \DB::table('terminated_order_upgrade')->where('terminated_order_id',$order->id)->get();
                        foreach ($idOrdert as $ordt) {
                            $newOrders[] = \App\Model\Order\Order::where('id', $ordt->upgraded_order_id)->get();
                        }
                        ?>

                        @foreach($newOrders as $newOrder)
                            <div class="termination-message">
                                <p class="termination-notice"><b>Important: Termination Notice</b></p>
                                <p class="termination-description">
                                    The order you had placed has been terminated. Consequently, the features and licenses associated with this order are no longer accessible.
                                </p>
                                <p class="order-links">
                                    The terminated order: <b>{{$order->number}}</b>
                                    has been upgraded to the new order: <a class="order-link" href="{{$newOrder[0]->id}}">{{$newOrder[0]->number}}</a>.
                                </p>
                            </div>

                        @endforeach


                    @endslot
                @endif
                @slot('user')
                    <table class="table">
                        <div class="col-md-6">
                            <tbody>
                            <tr><td><b>Name:</b></td>   <td>{{ucfirst($user->first_name)}}</td></tr>
                            <tr><td><b>Email:</b></td>     <td>{{$user->email}}</td></tr>
                            <tr><td><b>Mobile:</b></td><td>@if($user->mobile_code)(<b>+</b>{{$user->mobile_code}})@endif&nbsp;{{$user->mobile}}</td></tr>
                            <tr><td><b>Address:</b></td>   <td>{{$user->address}}</td></tr>
                            <tr><td><b>Country:</b></td>   <td>{{getCountryByCode($user->country)}}</td></tr>
                            </tbody>
                        </div>
                    </table>
                @endslot
                @slot('invoice')
                    <table id="showorder-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                        <thead>
                        <tr>
                            <th>Number</th>
                            <th>Product</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                @endslot
                @slot('payment')
                    <table id="showpayment-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                        <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Total</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                    </table>
                @endslot
                @slot('upgrade')
                    <table id="showpayment-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

                        <thead>
                        <tr>
                            <th>Cloud upgrade policies</th>
                            <th>Action</th>

                        </tr>
                        <?php
                        $plans = App\Model\Payment\Plan::where('name','LIKE','%Multitenant%')->pluck('name','id')->toArray();
                        $agentCalculation  = ltrim(substr($order->serial_key, 12),'0');
                        ?>
                        <tr class="mdm">
                            <td>Do you want to shift to a better plan and increase or decrease your agents?
                                <div class="row">
                                    <div class="col-md-6 form-group {{ $errors->has('plan') ? 'has-error' : '' }}">
                                        <label>
                                            <select name="plan" value= "Choose a plan" id="plan_upgrade_agents" onchange="getPrice(this.value)" class="form-control" required>
                                                @foreach($plans as $key=>$plan)
                                                    <option value={{$key}}>{{$plan}}</option>
                                                @endforeach
                                            </select>
                                        </label>

                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" value="{{$agentCalculation}}" id="number_agents" class="form-control" placeholder="Choose number of agents" required>
                                    </div>
                                </div>
                            </td>
                            <td><button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="cloudPlanUpgrade" onclick="performThisAgentUpgrade()">
                                    Lets do this!
                                </button></td>
                        </tr>
                        </thead>
                    </table>
                @endslot


                @slot('autorenewal')

                    <div class="row">
                        @if($gateways)

                            <div class="col-8">


                                <h6 style="margin-top: 8px;">Status of Auto Renewal</h6>


                            </div>
                            <div class="col-4">
                                <label class="switch toggle_event_editing">


                                    <label class="switch toggle_event_editing">
                                        <input type="checkbox" value="{{$statusAutorenewal}}"  name="is_subscribed"
                                               class="renewcheckbox" id="renew">
                                        <span class="slider round"></span>
                                        <input type="hidden" name="" id="order" value="{{$id}}">


                                    </label>
                                    @else
                                        <h6 style="margin-top: 8px;">Please enable the Payment gateways</h6>
                                @endif

                            </div>
                    </div>

                    <!--    <label style="font-size: 1.3em;font-weight: 100;color: #0088CC;letter-spacing: -0.7px;">Payment Log</label>

                           <table id="showAutopayment-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                                   <thead>
                                   <tr>
                                       <th>Order No</th>
                                       <th>Total</th>
                                       <th>Status</th>
                                       <th>Payment Date</th>
                                       <th>Action</th>
                                   </tr>
                                   </thead>
                               </table> -->
                    @if($statusAutorenewal == 1)
                        <div class="row">

                            <div class="col-8" id="updateButton">
                                <button type="button" class="btn btn-primary" id="cardUpdate" checked>Update CardDetails</button><br>
                                <h6 style="margin-top: 8px;">Click here to Update your Card Details</h6>

                                <!-- <h6 style="margin-top: 8px;">Click and Update your Card Details</h6> -->


                            </div>

                        </div>
                    @endif


                @endslot

                @slot('cloud')
                    <p style="margin-left: 530px;"><b>Plan Expiry:</b> {!! getDateHtml($subscription->ends_at) !!}</p>
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Change Cloud Domain Card -->
                            <div class="col-lg-6 mb-6">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <a href="#" data-toggle="modal" data-target="#cloudDomainModal" class="stretched-link clickable-link">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-globe mr-2" style="color: black; margin-top: 0.1em;"></i>
                                                <div>
                                                    <?php
                                                    $installation_path=\App\Model\Order\InstallationDetail::where('order_id',$id)
                                                        ->where('installation_path','!=','billing.faveocloud.com')->latest()->value('installation_path');
                                                    ?>

                                                    <h5 class="mb-1">Change Cloud Domain</h5>
                                                    <h6 class="mb-1"><i>Current domain: {{$installation_path}}</i></h6>
                                                    <p class="card-text mb-0">Click here to start customising your cloud domain. Please note that there will be a short 5-minute downtime while we work our magic</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Increase or Decrease Agents Card -->
                            <div class="col-lg-6 mb-6">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <a href="#" data-toggle="modal" data-target="#numberOfAgentsModal" class="stretched-link clickable-link">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-users mr-2" style="color: black; margin-top: 0.1em;"></i>
                                                <div>
                                                    <?php $latestAgents   = ltrim(substr($order->serial_key, 12),'0');
                                                    ?>
                                                    <h5 class="mb-1">Increase/Decrease Agents</h5>
                                                    <h6 class="mb-1"><i>Current number of agents: {{$latestAgents}}</i></h6>
                                                    <p class="card-text mb-0">Update your agent count by clicking here. Upgrades incur costs, and downgrades in between billing cycles aren't refunded.</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Upgrade or Downgrade Cloud Plan Card -->

                        </div>

                    </div>
                        <?php
                        $invoice_ids = \App\Model\Order\OrderInvoiceRelation::where('order_id', $id)->pluck('invoice_id')->toArray();
                        $invoice_id = \App\Model\Order\Invoice::whereIn('id', $invoice_ids)->latest()->value('id');
                        $planIdOld = \App\Model\Order\InvoiceItem::where('invoice_id', $invoice_id)->value('plan_id');
                        $planName = \App\Model\Payment\Plan::where('id',$planIdOld)->value('name');
                        $ExistingPlanPirce= \App\Model\Payment\PlanPrice::where('plan_id',$planIdOld)->where('currency',getCurrencyForClient(\Auth::user()->country))->latest()->value('add_price');
                        ?>

                    @if(strpos($planName,'free')==false)

                    <div class="row">
                        <div class="col-lg-6 mb-6">
                            <div class="card o-0 border-0">
                                <div class="card-body">
                                    <a href="#" data-toggle="modal" data-target="#cloudPlanModal" class="stretched-link clickable-link">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-cloud-upload-alt mr-2" style="color: black; margin-top: 0.1em;"></i>
                                            <div>



                                                <h5 class="mb-1">Upgrade/Downgrade Cloud Plan</h5>
                                                <h6 class="mb-1"><i>Current Plan: {{$planName}}</i></h6>
                                                <p class="card-text mb-0">Click here to change your cloud plan. Upgrades may cost extra. Downgrades auto-credited based on billing balance for future use in credits.</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                        @else
                            <h6 class="mb-1"><i>Current Plan: {{$planName}}</i></h6>
                        @endif










                @endslot


            @endcomponent
        </div>
    </div>



    <!-- Cloud Domain Change Modal -->
    <div class="modal fade" id="cloudDomainModal" tabindex="-1" role="dialog" aria-labelledby="cloudDomainModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="cloudDomainModalLabel">Change Cloud Domain</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="success-domain"></div>
                    <div id="failure-domain"></div>
                    <div class="section-box">
                        <p><i>If you wish to purchase a domain, you can <a href="https://store.ladybirdwebhost.com/" target="_blank">Click here.</a> And after the domain is set up, you will have to point your CNAME to our cloud <a href="https://docs.faveohelpdesk.com/docs/helper/cname/" target="_blank">Learn more.</a></i></p>
                    </div>

                    <label id="clouduserdomainfill" style="margin-left: 14px;"><strong></strong></label>
                    <div class="form-group">
                        <div class="col-12">
                            <label for="clouddomain" class="col-form-label">Enter your new domain name:</label>
                        </div>
                        <div class="row" style="margin-left: 14px; margin-right: 2px;">
                            <input type="text" class="form-control col col-2 rounded-1" value="https://" disabled="true" style="background-color: lightslategray; color:white;">
                            <input type="text" class="form-control col-10" id="clouduserdomain" autocomplete="off" placeholder="billing.custom.com" required>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            var orderId = {{$id}};
                            $.ajax({
                                data: {'orderId' : orderId},
                                url: '{{url("/api/takeCloudDomain")}}',
                                method: 'POST',
                                dataType: 'json',
                                success: function(data) {
                                    $('#clouduserdomainfill').html('Current cloud domain: <a href="' + data.data + '">' + data.data + '</a>');
                                },
                                error: function(error) {
                                    console.error('Error:', error);
                                }
                            });
                        });
                    </script>

                    <div class="overlay" style="display: none;"></div> <!-- Add this line -->

                    <div class="loader-wrapper" style="display: none; background: white; height: 100%;" >
                        <i class="fas fa-spinner fa-spin" style="font-size: 40px;"></i>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="changeDomain"><i class="fa fa-globe">&nbsp;&nbsp;</i>Change Domain</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Number of Agents Modal -->
    <div class="modal fade" id="numberOfAgentsModal" tabindex="-1" role="dialog" aria-labelledby="numberOfAgentsModalLabel" aria-hidden="true">
        <?php $latestAgents   = ltrim(substr($order->serial_key, 12),'0');
        ?>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="numberOfAgentsModalLabel" style="text-transform: none">Change Number of Agents</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="response-agent"></div>
                    <div id="failure-agent"></div>
                    <label class="mb-1" style="margin-left: 14px;">Current number of agents: {{$latestAgents}}</label>
                    <div class="form-group">
                        <div class="col-12">
                            <?php
                            $ExistingPlanPirce= \App\Model\Payment\PlanPrice::where('plan_id',$planIdOld)->where('currency',getCurrencyForClient(\Auth::user()->country))->latest()->value('add_price');
                            ?>
                            <p>Price per agent: <span id="ll" class="ll">{!! currencyFormat($ExistingPlanPirce,getCurrencyForClient(\Auth::user()->country),true) !!}</span></p>
                        </div>
                        <div class="col-12"  style="margin-top: -14px;">
                            {!! Form::label('number', 'Choose your desired number of agents:', ['class' => 'col-form-label']) !!}
                            <div class="quantity">
                                {!! Form::number('number', null, ['class' => 'form-control', 'id' => 'numberAGt', 'min' => '1', 'placeholder' => '']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">

                        <div class="col-12">
                            <p>Price to be paid: <span id="pricetopay" class="pricetopay"></span></p>
                        </div>
                    </div>
                    <div class="overlay" style="display: none;"></div> <!-- Add this line -->

                    <div class="loader-wrapper" style="display: none; background: white;" >
                        <i class="fas fa-spinner fa-spin" style="font-size: 40px;"></i>

                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -23px;">
                    <button type="button" class="btn btn-primary" id="agentNumber" disabled><i class="fa fa-users">&nbsp;&nbsp;</i>  Update Agents
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cloud Plan Modal -->
    <div class="modal fade" id="cloudPlanModal" tabindex="-1" role="dialog" aria-labelledby="cloudPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="cloudPlanModalLabel" style="text-transform: none;">Upgrade or downgrade your cloud plan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="response-upgrade"></div>
                    <div id="failure-upgrade"></div>
                    <?php
                    // Retrieve the plans data as before
                    $plans = App\Model\Payment\Plan::join('products', 'plans.product', '=', 'products.id')
                        ->leftJoin('plan_prices','plans.id','=','plan_prices.plan_id')
                        ->where('plans.product','!=',$product->id)
                        ->where('products.type',4)
                        ->where('products.can_modify_agent',1)
                        ->where('plan_prices.renew_price','!=','0')
                        ->pluck('plans.name', 'plans.id')
                        ->toArray();
                    $planIds = array_keys($plans);

                    $countryids = \App\Model\Common\Country::where('country_code_char2', \Auth::user()->country)->first();

                    $renewalPrices = \App\Model\Payment\PlanPrice::whereIn('plan_id', $planIds)
                        ->where('country_id',$countryids->country_id)
                        ->where('currency',getCurrencyForClient(\Auth::user()->country))
                        ->latest()
                        ->pluck('renew_price', 'plan_id')
                        ->toArray();

                    if(empty($renewalPrices)){
                        $renewalPrices = \App\Model\Payment\PlanPrice::whereIn('plan_id', $planIds)
                            ->where('country_id',0)
                            ->where('currency',getCurrencyForClient(\Auth::user()->country))
                            ->latest()
                            ->pluck('renew_price', 'plan_id')
                            ->toArray();
                    }

                    foreach ($plans as $planId => $planName) {
                        if (isset($renewalPrices[$planId])) {
                            if(in_array($product->id,[117,119])) {
                                $plans[$planId] .= " (Plan price-per agent: " . currencyFormat($renewalPrices[$planId], getCurrencyForClient(\Auth::user()->country), true) . ")";
                            }
                        }
                    }
                    // Add more cloud IDs until we have a generic way to differentiate
                    if(in_array($product->id,[117,119])){
                        $plans = array_filter($plans, function ($value) {
                            return stripos($value, 'free') === false;
                        });
                    }

                    $invoice_ids = \App\Model\Order\OrderInvoiceRelation::where('order_id', $id)->pluck('invoice_id')->toArray();
                    $invoice_id = \App\Model\Order\Invoice::whereIn('id', $invoice_ids)->latest()->value('id');
                    $planIdOld = \App\Model\Order\InvoiceItem::where('invoice_id', $invoice_id)->value('plan_id');
                    $planNameReal = \App\Model\Payment\Plan::where('id',$planIdOld)->value('name');
                    ?>
                    <label class="mb-1" style="margin-left: 14px;">Current Plan: {{$planNameReal}}</label>
                    <div class="form-group mb-3">
                        {!! Form::label('plan', 'Select a new plan:', ['class' => 'col-12']) !!}
                        <div class="col-12">
                            {!! Form::select('plan', ['' => 'Select'] + $plans, null, ['class' => 'form-control', 'onchange' => 'getPrice(this.value)']) !!}
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <div class="col-12">
                            <p>Total Credits remaining on your current plan: <span id="priceOldPlan" class="priceOldPlan"></span></p>
                        </div>
                        <div class="col-12"  style="margin-top: -13px;">
                            <p>Price for the new plan: <span id="priceNewPlan" class="priceNewPlan"></span></p>
                        </div>

                        <div class="col-12" style="margin-top: -13px;">
                            <p>Price to be paid: <span id="priceToPay" class="priceToPay"></span></p>
                        </div>
                    </div>
                    <div class="overlay" style="display: none;"></div> <!-- Add this line -->

                    <div class="loader-wrapper" style="display: none; background: white;" >
                        <i class="fas fa-spinner fa-spin" style="font-size: 40px;"></i>

                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -23px;">
                    <button type="button" class="btn btn-primary" id="upgradedowngrade">
                        <i class="fas fa-cloud-upload-alt">&nbsp;&nbsp;</i>Change Plan
                    </button>
                </div>
            </div>
        </div>
    </div>



               <div class="modal fade" id="renewal-modal" data-backdrop="static" data-keyboard="false" style="position: relative;bottom: 300px;">
        <div class="modal-dialog">
          <div class="modal-content" style="width:400px;">
            <div class="modal-header">
              <h4 class="modal-title">Select the payment</h4>
            </div>
            <div class="modal-body">
                        <div id="alertMessage-1"></div>
              <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                  {!! Form::label('name',Lang::get('Select the payment gateway'),['class'=>'required']) !!}
                   
        
                   <select name=""  id="sel-payment" class="form-control" >
                        <option value="" disabled selected>Choose your option</option>
                         @foreach($gateways as $key =>  $gateway)
                        <option value="{{strtolower($gateway)}}">{{$gateway}}</option>
                            @endforeach
                        <!-- <option value="razorpay">Razorpay</option> -->
                       </select>
                   
                   </div>
              <span id="payerr"></span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left closeandrefresh" id="srclose" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
              <button type="button" id="payment" class="btn btn-primary"><i class="fa fa-check">&nbsp;&nbsp;</i>Save</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        </div>



   
    <div class="modal fade" id="stripe-Modal" data-keyboard="false" data-backdrop="static" style="position: relative;bottom: 200px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button style="position: absolute; top: -10px; right: -10px; width: 30px; height: 30px; border-radius: 50%; background-color: black;" type="button" class="close custom-close" data-dismiss="modal" aria-hidden="true" onclick="refreshPage()">&times;</button>
                <h4 class="modal-title" id="defaultModalLabel" style="white-space: nowrap;">Stripe payment</h4>
                <div class="horizontal-images">
                    <img class="img-responsive" src="https://static.vecteezy.com/system/resources/previews/020/975/567/non_2x/visa-logo-visa-icon-transparent-free-png.png">
                    <img class="img-responsive" src="https://pngimg.com/d/mastercard_PNG23.png">
                    <img class="img-responsive" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ2lfp0fkZmeGd6aCOzuIBC1QDTvcyGcM6OGQ&usqp=CAU">
                </div>
            </div>
            <div id="alertMessage-2"></div>
            <div id="error-1"></div>
            <div class="col-md-12 ">
                <div class="modal-body">
                    <form id="valid-modal">
                        <div id="payment-element">
                            <!-- Information or instructions -->
                            <div class="form-group row">
                                <div class="col-md-12 alert alert-info">
                                Your card information is secure with us. We are performing a verification check of {{currencyFormat(1,Auth::user()->currency)}}, which will be automatically reversed within a week.
                                </div>
                            </div>
                            <!-- Card No. input -->
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="card_no" type="tel" class="form-control @error('card_no') is-invalid @enderror" name="card_no" value="{{ old('card_no') }}" required autocomplete="card_no" placeholder="Card No." autofocus>
                                    @error('card_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Exp. Month and Exp. Year inputs -->
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="exp_month" type="number" class="form-control @error('exp_month') is-invalid @enderror" name="exp_month" value="{{ old('exp_month') }}" required autocomplete="exp_month" placeholder="Exp. Month(02)" autofocus>
                                    @error('exp_month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input id="exp_year" type="number" class="form-control @error('exp_year') is-invalid @enderror" name="exp_year" value="{{ old('exp_year') }}" required autocomplete="exp_year" placeholder="Exp. Year(20)" autofocus>
                                    @error('exp_year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- CVV input -->
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="cvv" type="password" class="form-control @error('cvv') is-invalid @enderror" name="cvv" required autocomplete="current-password" placeholder="CVV">
                                    @error('cvv')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Amount input -->
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="amount" type="text" value={{currencyFormat(1,Auth::user()->currency)}} class="form-control @error('amount') is-invalid @enderror" required autocomplete="current-password" name="amount" placeholder="Amount" readonly>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Pay button -->
                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="button" id="pay" class="btn btn-primary btn-block">
                                        {{ __('PAY NOW') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <form name='razorpayform' action="{!!url('rzpRenewal-disable/'.$order->id)!!}" method="POST">
        {{ csrf_field() }}
        <!--<button id="rzp-button1" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">Pay Now</button>-->
        <!--<form name='razorpayform' action="verify.php" method="POST">                                -->
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >


    </form>



    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
   function refreshPage() {
            location.reload(); 
        }
        
$(document).ready(function() {
    $("#valid-modal").validate({
        rules: {
            card_no: {
                required: true,
                digits: true,
                minlength: 15,
                maxlength: 16,
            },
            exp_month: {
                required: true,
                digits: true,
                minlength: 2,
                maxlength: 2,
                range: [1, 12] 
            },
            exp_year: {
                required: true,
                digits: true,
                minlength: 2,
                maxlength: 2,
                notPastYear: true
            },
            cvv: {
                required: true,
                digits: true,
                rangelength: [3, 4] 
            }
        },
        messages: {
            card_no: {
                required: "Card number is required",
                digits: "Please enter digits only",
                minlength: "Card number must be at least 15 digits",
                maxlength: "Card number cannot exceed 16 digits"
            },
            exp_month: {
                required: "Expiration month is required",
                digits: "Please enter digits only",
                minlength: "Expiration month must be 2 digits",
                maxlength: "Expiration month must be 2 digits",
                range: "Expiration month cannot exceed 12"
            },
            exp_year: {
                required: "Expiration year is required",
                digits: "Please enter digits only",
                minlength: "Expiration year must be 2 digits",
                maxlength: "Expiration year must be 2 digits",
                notPastYear: "Expiration year cannot be in the past"
            },
            cvv: {
                required: "CVV is required",
                digits: "Please enter digits only",
                rangelength: "CVV must be either 3 or 4 digits"
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass("invalid-feedback");
            error.insertAfter(element);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        }
    });

    var $form = $("#submit_total");
    var $cardNo = $("#card_no");
    var $expMonth = $("#exp_month");
    var $expYear = $("#exp_year");
    var $cvv = $("#cvv");
    var $payButton = $("#pay");

    $form.on("submit", function(event) {
        // Check if each field is valid
        var isCardNoValid = $cardNo.valid();
        var isExpMonthValid = $expMonth.valid();
        var isExpYearValid = $expYear.valid();
        var isCvvValid = $cvv.valid();

        if (isCardNoValid && isExpMonthValid && isExpYearValid && isCvvValid) {
            $payButton.prop("disabled", true);
            $payButton.html("<i class='fa fa-circle-o-notch fa-spin fa-1x'></i> Processing ...");
        } else {
            event.preventDefault();
        }
    });

    $.validator.addMethod("notPastYear", function(value, element) {
        var currentYear = new Date().getFullYear() % 100;
        var enteredYear = parseInt(value, 10);
        return enteredYear >= currentYear;
    }, "Expiration year cannot be in the past");
});


    function validateForm() {
        var isValid = $("#valid-modal").valid();
        var isCardNoValid = $('#card_no').valid();
        var isExpMonthValid = $('#exp_month').valid();
        var isExpYearValid = $('#exp_year').valid();
        var isCvvValid = $('#cvv').valid();

        return isValid && isCardNoValid && isExpMonthValid && isExpYearValid && isCvvValid;
    }

</script>
    <script type="text/javascript">

        $('#srclose').click(function()
                 {
                 location.reload();
                 });
        // $('#strclose').click(function()
        //          {
        //          location.reload();
        //          });

        // Checkout details as a json
        var options = <?php echo $json; ?>


        /**
         * The entire list of Checkout fields is available at
         * https://docs.razorpay.com/docs/checkout-form#checkout-fields
         */
            options.handler = function (response){
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;

                document.razorpayform.submit();
            };

        // Boolean whether to show image inside a white frame. (default: true)
        options.theme.image_padding = false;

        options.modal = {
            ondismiss: function() {
            },
            // Boolean indicating whether pressing escape key
            // should close the checkout form. (default: true)
            escape: true,
            // Boolean indicating whether clicking translucent blank
            // space outside checkout form should close the form. (default: false)
            backdropclose: false
        };

        var rzp = new Razorpay(options);

        $(document).ready(function(){
            var status = $('.renewcheckbox').val();
            if(status ==1) {
                $('#renew').prop('checked',true)
            } else if(status ==0) {
                $('#renew').prop('checked',false)
            }
        });
        $('#renew').on('change',function () {
            if ($(this).prop("checked")) {
                cardUpdate();


            }else{
                var id = $('#order').val();
                $.ajax({
                    url : '{{url("renewal-disable")}}',
                    method : 'post',
                    data : {
                        "order_id" : id,

                    },
                    success: function(response){
                        $('#alertMessage-2').show();
                        var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+response.message+'.</div>';
                        $('#alertMessage-2').html(result+ ".");
                        $("#pay").html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
                        setInterval(function(){
                            $('#alertMessage-2').slideUp(3000);
                        }, 1000);
                        $('#updateButton').hide();
                    },
                })
            }
        });


        $('#cardUpdate').on('click',function(){
            cardUpdate();

        });

function cardUpdate() {
    $('#renewal-modal').modal('show');
    var id = $('#order').val();
    $('#payment').on('click', function () {
        var pay = $('#sel-payment').val();
        if (pay == null) {
            $("#payment").html("<i class='fa fa-check'></i> Validate");
            $('#payerr').show();
            $('#payerr').html("Please Select the Payment");
            $('#payerr').focus();
            $('#sel-payment').css("border-color", "red");
            $('#payerr').css({ "color": "red" });
            return false;
        }
        if (pay == 'stripe') {
            $('#renewal-modal').modal('hide');
            $('#stripe-Modal').modal('show');

            $('#pay').on('click', function () {
                var isValid = validateForm();
                if (isValid) {
                    $('#pay').html("<i class='fa fa-spinner fa-spin'></i> Please Wait..");
                    $.ajax({
                        url: '{{url("strRenewal-enable")}}',
                        type: 'POST',
                        data: {
                            "order_id": id,
                            "card_no": $('#card_no').val(),
                            "exp_month": $('#exp_month').val(),
                            "exp_year": $('#exp_year').val(),
                            "cvv": $('#password').val(),
                            "amount": $('#amount').val(),
                            "_token": "{!! csrf_token() !!}",
                        },
                        success: function (response) {
                            $('#stripe-Modal').modal('hide');
                            $('#alertMessage-2').show();
                            $('#updateButton').show();
                            var result = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>' + response.message + '.</div>';
                            $('#alertMessage-2').html(result + ".");
                            $("#pay").html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
                            location.reload();
                        },
                      error: function (data) {
                        var errorMessage = data.responseJSON.result;
                        $('#stripe-Modal').modal('show');
                        $("#pay").attr('disabled', false);
                        $("#pay").html("Pay now");
                        $('html, body').animate({ scrollTop: 0 }, 500);
                        var html = '<div class="alert alert-danger alert-dismissable alert-content"><strong><i class="fas fa-exclamation-triangle"></i>Oh Snap! </strong>' + data.responseJSON.result + ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><br><ul>';
                        $('#error-1').show();
                        document.getElementById('error-1').innerHTML = html;
                    }
                    });
                }
            });
        } else if (pay == 'razorpay') {
            $('#renewal-modal').modal('hide');
            rzp.open();
            e.preventDefault();
        }
    });
}
    </script>


    <script type="text/javascript">

        $('.done').click(function()
        {
            $(this).hide();
        });

        $('#showorder-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url":  '{!! Url('get-my-invoices/'.$order->id.'/'.$user->id) !!}',
                error: function(xhr) {
                    if(xhr.status == 401) {
                        alert('Your session has expired. Please login again to continue.')
                        window.location.href = '/login';
                    }
                }

            },
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },

            columns: [
                {data: 'number', name: 'number'},
                {data: 'products', name: 'products'},
                {data: 'date', name: 'date'},
                {data: 'total', name: 'total'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
            ],
            "fnDrawCallback": function( oSettings ) {
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip({
                        container : 'body'
                    });
                });
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <!--         <script type="text/javascript">
             $('#showAutopayment-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        "url":  '{!! Url('autoPayment-client/'.$order->id) !!}',
                           error: function(xhr) {
                           if(xhr.status == 401) {
                            alert('Your session has expired. Please login again to continue.')
                            window.location.href = '/login';
                           }
                        }

                        },

                    "oLanguage": {
                        "sLengthMenu": "_MENU_ Records per page",
                        "sSearch"    : "Search: ",
                        "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
                    },

                    columns: [
                        {data: 'number', name: 'number'},
                        {data: 'total', name: 'total'},
                        {data: 'payment_status', name: 'payment_status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action'}
                    ],
                    "fnDrawCallback": function( oSettings ) {
                         $(function () {
                          $('[data-toggle="tooltip"]').tooltip({
                            container : 'body'
                          });
                        });
                        $('.loader').css('display', 'none');
                    },
                    "fnPreDrawCallback": function(oSettings, json) {
                        $('.loader').css('display', 'block');
                    },
                });
        </script> -->

    <script type="text/javascript">
        $('#showpayment-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url":  '{!! Url('get-my-payment-client/'.$order->id.'/'.$user->id) !!}',
                error: function(xhr) {
                    if(xhr.status == 401) {
                        alert('Your session has expired. Please login again to continue.')
                        window.location.href = '/login';
                    }
                }

            },

            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },

            columns: [
                {data: 'number', name: 'invoice.number'},
                {data: 'total', name: 'total'},
                {data: 'payment_method', name: 'payment_method'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'created_at', name: 'created_at'},
            ],
            "fnDrawCallback": function( oSettings ) {
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip({
                        container : 'body'
                    });
                });
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });

        $("#reissueLic").click(function(){
            if ($('#domainRes').val() == 1) {
                var oldDomainId = $(this).attr('data-id');
                $("#orderId").val(oldDomainId);
                $("#domainModal").modal();
                $("#domainSave").on('click',function(){
                    var id = $('#orderId').val();
                    $.ajax ({
                        type: 'patch',
                        url : "{{url('reissue-license')}}",
                        data : {'id':id},
                        beforeSend: function () {
                            $('#response1').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

                        },

                        success: function (data) {
                            if (data.message =='success'){
                                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                $('#response1').html(result);
                                $('#response1').css('color', 'green');
                                setTimeout(function(){
                                    window.location.reload();
                                },3000);
                            }

                        }

                    });
                });
            } else {
                var oldDomainName = $(this).attr('data-name');
                var oldDomainId = $(this).attr('data-id');
                $("#licesnseModal").modal();
                $("#newDomain").val(oldDomainName);
                $("#orderId").val(oldDomainId);

                $("#licenseSave").on('click',function(){
                    var pattern = new RegExp(/^((?!-))(xn--)?[a-z0-9][a-z0-9-_]{0,61}[a-z0-9]{0,1}\.(xn--)?([a-z0-9\-]{1,61}|[a-z0-9-]{1,30}\.[a-z]{2,})$/);
                    var ip_pattern = new RegExp(/^\b((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.|$)){4}\b/);
                    if (pattern.test($('#newDomain').val()) || ip_pattern.test($('#newDomain').val())) {
                        $('#domaincheck').hide();
                        $('#newDomain').css("border-color","");
                    }
                    else{
                        $('#domaincheck').show();
                        $('#domaincheck').html("Please enter a valid Domain in the form domain.com or sub.domain.com or enter a valid IP");
                        $('#domaincheck').focus();
                        $('#newDomain').css("border-color","red");
                        $('#domaincheck').css({"color":"red","margin-top":"5px"});
                        domErr = false;
                        return false;

                    }
                    var domain = $('#newDomain').val();
                    var id = $('#orderId').val();

                    $.ajax ({
                        type: 'patch',
                        url : "{{url('change-domain')}}",
                        data : {'domain':domain,'id':id},
                        beforeSend: function () {
                            $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

                        },
                        success: function (data) {
                            if (data.message =='success'){
                                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> Well Done! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                                $('#response').html(result);
                                $('#response').css('color', 'green');
                                setTimeout(function(){
                                    window.location.reload();
                                },3000);
                            }

                        }, error: function(err) {
                            console.log(err);
                        }

                    });
                });
            }
        });
        function performThisAgentUpgrade(){
            var plan_value=$('#plan_upgrade_agents').val();
            var numberAgents = $('#number_agents').val();
            var order = {!! $order !!};
            var subId = {!! $subscription->id !!};

            $('#kok  ').click(function() {
                // $("#submitSub").html("<i class='fas fa-circle-notch fa-spin'></i>  Please Wait...");
                var status = ($('#is_subscribed').prop("checked"));
                alert(status);
                var id = $('#renew').val();
                alert(id);

                $.ajax({

                    url : '{{url("post-status")}}',
                    type : 'post',
                    data: {
                        data: { "is_subscribed" : status, "order_id" : id },
                    },
                    success: function (response) {
                        $('#alertMessage').show();
                        var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+response.update+'.</div>';
                        $('#alertMessage').html(result+ ".");
                        $("#submit").html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
                        setInterval(function(){
                            $('#alertMessage').slideUp(3000);
                        }, 1000);
                    },


                });

            });

            $.ajax ({
                type: 'post',
                url : "{{url('upgrade-plan-for-cloud')}}",
                data : {'plan_id':plan_value,'number_agents':numberAgents, 'order':order},
                beforeSend: function () {
                    $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

                },
                success: function (data) {
                    window.location.href = data.redirectTo;
                    if (data.message =='success'){
                        window.location = data.redirectTo;
                        var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> Well Done! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                        $('#response').html(result);
                        $('#response').css('color', 'green');
                        setTimeout(function(){
                            window.location.reload();
                        },3000);
                    }

                }, error: function(err) {
                }

            });

        }
    </script>
    <style>
        .mdm{
            background-color: aliceblue;
        }
    </style>

    <script>
        $('#stripe-button1').on('click',function(){
            $('#stripeModal').modal('show');
        })

        $(function() {
            var $form         = $(".require-validation");
            $('form.require-validation').bind('submit', function(e) {
                var $form         = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        e.preventDefault();
                    }
                });

                // if (!$form.data('cc-on-file')) {
                //   e.preventDefault();
                //   Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                //   Stripe.createToken({
                //     number: $('.card-number').val(),
                //     cvc: $('.card-cvc').val(),
                //     exp_month: $('.card-expiry-month').val(),
                //     exp_year: $('.card-expiry-year').val()
                //   }, stripeResponseHandler);
                // }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

        });
        /*
         * Increase No. Of Agents
         */
        $('#agentplus').on('click',function(){
            var $agtqty=$(this).parents('.quantity').find('.qty');
            var $productid = $(this).parents('.quantity').find('.productid');
            var $agentprice = $(this).parents('.quantity').find('.agentprice');
            var $currency = $(this).parents('.quantity').find('.currency');
            var $symbol  = $(this).parents('.quantity').find('.symbol');
            var currency = $currency.val();//Get the Currency for the Product
            var symbol = $symbol.val();//Get the Symbol for the Currency
            var productid = parseInt($productid.val()); //get Product Id
            var currentAgtQty = parseInt($agtqty.val()); //Get Current Quantity of Prduct
            var actualAgentPrice = parseInt($agentprice.val());//Get Initial Price of Prduct
            // console.log(productid,currentVal,actualprice);

            var finalAgtqty = $('#agtqty').val(currentAgtQty + 1).val();
            var finalAgtprice = $('#agentprice').val(actualAgentPrice * finalAgtqty).val();

            $.ajax({
                type: "POST",
                data:{'productid':productid},
                beforeSend: function () {
                    $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

                },
                url: "{{url('update-agent-qty')}}",
                success: function () {
                    location.reload();
                }
            });
        });
        /*
        *Decrease No. of Agents
         */
        $(document).ready(function(){
            var currentagtQty = $('#agtqty').val();
            if(currentagtQty>1) {
                $('#agentminus').on('click', function () {

                    var $agtqty = $(this).parents('.quantity').find('.qty');
                    var $productid = $(this).parents('.quantity').find('.productid');
                    var $agentprice = $(this).parents('.quantity').find('.agentprice');
                    var $currency = $(this).parents('.quantity').find('.currency');
                    var $symbol = $(this).parents('.quantity').find('.symbol');
                    var currency = $currency.val();//Get the Currency for the Product
                    var symbol = $symbol.val();//Get the Symbol for the Currency
                    var productid = parseInt($productid.val()); //get Product Id
                    var currentAgtQty = parseInt($agtqty.val()); //Get Current Agent of Prduct
                    var actualAgentPrice = parseInt($agentprice.val()); //Get Initial Price of Prduct
                    // console.log(productid,currentVal,actualprice);
                    console.log(actualAgentPrice);
                    if (!isNaN(currentAgtQty)) {
                        var finalAgtqty = $('#agtqty').val(currentAgtQty - 1).val(); //Quantity After decreasinf
                        var finalAgtprice = $('#agentprice').val(actualAgentPrice / 2).val(); //Final Price aftr decresing  qty
                    }
                    $.ajax({
                        type: "POST",
                        data: {'productid': productid},
                        beforeSend: function () {
                            $('#response').html("<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                        },
                        url: "{{url('reduce-agent-qty')}}",
                        success: function () {
                            location.reload();
                        }
                    });

                });
            }

        });




        /*
        *Increse Product Quantity
         */
        $('#quantityplus').on('click',function(){
            var $productid = $(this).parents('.quantity').find('.productid');
            var productid = parseInt($productid.val()); //get Product Id
            // console.log(productid,currentVal,actualprice);
            $.ajax({
                type: "POST",
                data: {'productid':productid},
                beforeSend: function () {
                    $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                },
                url: "{{url('update-qty')}}",
                success: function () {
                    location.reload();
                }
            });
        });

        /*
         * Reduce Procut Quantity
         */
        $('#quantityminus').on('click',function(){
            var $qty=$(this).parents('.quantity').find('.qty');
            var $productid = $(this).parents('.quantity').find('.productid');
            var $price = $(this).parents('.quantity').find('.quatprice');
            var productid = parseInt($productid.val()); //get Product Id
            var currentQty = parseInt($qty.val()); //Get Current Quantity of Prduct
            var incraesePrice = parseInt($price.val()); //Get Initial Price of Prduct
            if (!isNaN(currentQty)) {
                var finalqty = $('#qty').val(currentQty -1 ).val() ; //Quantity After Increasing
                var finalprice = $('#quatprice').val(incraesePrice).val(); //Final Price aftr increasing qty
            }
            $.ajax({
                type: "POST",
                data: {'productid':productid},
                beforeSend: function () {
                    $('#response').html( "<img id='blur-bg' class='backgroundfadein' style='width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                },
                url: "{{url('reduce-product-qty')}}",
                success: function () {
                    location.reload();
                }
            });
        });

        function Addon(id){
            $.ajax({
                type: "GET",
                data:{"id": id, "category": "addon"},
                url: "{{url('cart')}}",
                success: function (data) {
                    location.reload();
                }
            });
        }

        $(document).ready(function() {
            $('#changeDomain').on('click', function() {
                $('#changeDomain').attr('disabled',true);
                $('#changeDomain').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i> Please Wait...");
                $('.loader-wrapper').show();
                $('.overlay').show(); // Show the overlay
                $('.modal-body').css('pointer-events', 'none');
                var newDomain = $('#clouduserdomain').val();
                var currentDomain = "{!! \App\Model\Order\InstallationDetail::where('order_id', $id)->latest()->value('installation_path') !!}";
                var license_code = "{!!$order->serial_key!!}";
                var productId = "{!! $order->product !!}";

                $.ajax({
                    type: "POST",
                    data: { 'newDomain': newDomain, 'currentDomain': currentDomain,'lic_code':license_code,'product_id':productId},
                    beforeSend: function() {
                        $('#response').html("<img id='blur-bg' class='backgroundfadein' style='width: 50px; height: 50px; display: block; position: fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                    },
                    url: "{{ url('change/domain') }}",
                    success: function (data) {
                        if (data.success ==true){
                            var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> Well Done! </strong> '+data.message+'</div>';
                            $('#success-domain').html(result).css('color', 'green').show();
                            $('#changeDomain').attr('disabled',false);
                            $('#changeDomain').html("<i class='fa fa-globe'>&nbsp;&nbsp;</i>Change domain");
                            $('.loader-wrapper').hide();
                            $('.overlay').hide(); // Hide the overlay
                            $('.modal-body').css('pointer-events', 'auto');
                            // Auto-disappear after 5 seconds (5000 milliseconds)
                            setTimeout(function() {
                                $('#success-domain').fadeOut('slow', function() {
                                    $(this).empty().hide(); // Clear and hide the error message after fading out
                                });
                            }, 30000);
                        }

                    }, error: function(data) {
                        if (data.responseJSON.success === false) {
                            var result = '<div class="alert alert-danger alert-dismissable"><strong><i class="far fa-thumbs-down"></i> Oops! </strong> ' + data.responseJSON.message + ' </div>';
                            $('#failure-domain').html(result).css('color', 'red').show(); // Show the error message
                            $('#changeDomain').attr('disabled', false);
                            $('#changeDomain').html("<i class='fa fa-globe'>&nbsp;&nbsp;</i>Change domain");
                            $('.loader-wrapper').hide();
                            $('.overlay').hide(); // Hide the overlay
                            $('.modal-body').css('pointer-events', 'auto');

                            // Auto-disappear after 5 seconds (5000 milliseconds)
                            setTimeout(function() {
                                $('#failure-domain').fadeOut('slow', function() {
                                    $(this).empty().hide(); // Clear and hide the error message after fading out
                                });
                            }, 10000); // Change this timeout to your desired duration
                        }
                    }

                });
            });
        });

        $(document).ready(function() {
            $('#agentNumber').on('click', function() {
                $('#agentNumber').attr('disbaled',true);
                $('#agentNumber').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i> Please Wait...");
                $('.loader-wrapper').show();
                $('.overlay').show(); // Show the overlay
                $('.modal-body').css('pointer-events', 'none');
                var newAgents = $('#numberAGt').val();
                var orderId = {!! $id !!};
                var productId ={!! $product->id !!};
                var subId = {!! $subscription->id !!};

                $.ajax({
                    type: "POST",
                    data: { 'newAgents': newAgents, 'orderId': orderId, 'product_id':productId, 'subId': subId},
                    beforeSend: function() {
                        $('#response').html("<img id='blur-bg' class='backgroundfadein' style='width: 50px; height: 50px; display: block; position: fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                    },
                    url: "{{ url('changeAgents') }}",
                    success: function(response) {
                        $('#agentNumber').attr('disbaled',false);
                        $('#agentNumber').html("<i class='fa fa-users'>&nbsp;&nbsp;</i>  Update Agents");
                        $('.loader-wrapper').hide();
                        $('.overlay').hide(); // Hide the overlay
                        $('.modal-body').css('pointer-events', 'auto');
                        window.location.href = response;
                    },
                    error: function(data) {
                        if (data.responseJSON.success == false) {
                            $('#agentNumber').attr('disabled', false);
                            $('#agentNumber').html("<i class='fa fa-users'>&nbsp;&nbsp;</i>  Update Agents");
                            var result = '<div class="alert alert-danger alert-dismissable"><strong><i class="far fa-thumbs-down"></i> Oops! </strong> ' + data.responseJSON.message + ' </div>';
                            $('#failure-agent').html(result).css('color', 'red').show();
                            $('.loader-wrapper').hide();
                            $('.overlay').hide(); // Hide the overlay
                            $('.modal-body').css('pointer-events', 'auto');

                            // Auto-disappear after 5 seconds (5000 milliseconds)
                            setTimeout(function() {
                                $('#failure-agent').fadeOut('slow', function() {
                                    $(this).empty().hide();
                                });
                            }, 10000);
                        }
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#upgradedowngrade').on('click', function() {
                $('#upgradedowngrade').attr('disabled',true);
                $('#upgradedowngrade').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i> Please Wait...");
                $('.loader-wrapper').show();
                $('.overlay').show(); // Show the overlay
                $('.modal-body').css('pointer-events', 'none');
                var planId = $('select[name="plan"]').val();
                var user = $('input[name="user"]').val();
                var agents = {{$latestAgents}};
                var orderId = {!! $id !!};
                $.ajax({
                    type: "POST",
                    data: { 'id': planId,'agents': agents,'userId': user, 'orderId':orderId},
                    beforeSend: function() {
                        $('#response').html("<img id='blur-bg' class='backgroundfadein' style='width: 50px; height: 50px; display: block; position: fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");
                    },
                    url: "{{ url('upgradeDowngradeCloud') }}",
                    success: function (data) {
                        window.location.href = data.redirectTo;
                        if (data.success ==true){
                            window.location = data.redirectTo;
                            var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="far fa-thumbs-up"></i> Well Done! </strong> '+data.message+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                            $('#response-upgrade').html(result);
                            $('#response-upgrade').css('color', 'green');
                            $('#upgradedowngrade').attr('disabled',false);
                            $('#upgradedowngrade').html("<i class='fas fa-cloud-upload-alt'>&nbsp;&nbsp;</i>Change Plan");
                            $('.loader-wrapper').hide();
                            $('.overlay').hide(); // Hide the overlay
                            $('.modal-body').css('pointer-events', 'auto');
                        }

                    },  error: function(data) {
                        if (data.responseJSON.success == false) {
                            var result = '<div class="alert alert-danger alert-dismissable"><strong><i class="far fa-thumbs-down"></i> Oops! </strong> ' + data.responseJSON.message + '</div>';
                            $('#failure-upgrade').html(result).css('color', 'red').show();
                            $('#upgradedowngrade').attr('disabled',false);
                            $('#upgradedowngrade').html("<i class='fas fa-cloud-upload-alt'>&nbsp;&nbsp;</i>Change Plan");
                            $('.loader-wrapper').hide();
                            $('.overlay').hide(); // Hide the overlay
                            $('.modal-body').css('pointer-events', 'auto');

                            // Auto-disappear after 5 seconds (5000 milliseconds)
                            setTimeout(function() {
                                $('#failure-upgrade').fadeOut('slow', function() {
                                    $(this).empty().hide();
                                });
                            }, 10000);
                        }
                    }

                });
            });
        });


    </script>
    <script>
        function getPrice(val) {
            $('.loader-wrapper').show();
            $('.overlay').show(); // Show the overlay
            $('.modal-body').css('pointer-events', 'none');
            $.ajax({
                type: "POST",
                url: "{{url('get-cloud-upgrade-cost')}}",
                data: {'plan': val, 'agents': '{{$latestAgents}}', 'orderId': '{{$id}}'},
                success: function (data) {
                    $(".priceperagent").val(data.priceperagent);
                    $(".priceOldPlan").text(data.priceoldplan);
                    $(".priceNewPlan").text(data.pricenewplan);
                    $(".discount").text(data.discount);
                    $(".priceToPay").text(data.price_to_be_paid);
                    $('.loader-wrapper').hide();
                    $('.overlay').hide(); // Hide the overlay
                    $('.modal-body').css('pointer-events', 'auto');

                }
            });
        }


    </script>
    <script>

        $(document).ready(function () {
            $('#numberAGt').on('input', function () {
                $(this).prop("disabled", true);
                $('#agentNumber').attr('disabled',true);
                var selectedNumber = $(this).val();
                var oldAgents = '{{$latestAgents}}';
                var orderId = '{{$id}}';
                $('.loader-wrapper').show();
                $('.overlay').show(); // Show the overlay
                $('.modal-body').css('pointer-events', 'none');

                $.ajax({
                    type: 'POST',
                    url: "{{url('get-agent-inc-dec-cost')}}",
                    data: { 'number': selectedNumber, 'oldAgents':  oldAgents, 'orderId' : orderId},
                    success: function (data) {
                        // Update the other fields based on the API response
                        $('#priceagent').text(data.pricePerAgent);
                        $('#Totalprice').val(data.totalPrice);
                        $('#pricetopay').text(data.priceToPay);
                        $('#agentNumber').attr('disabled',false);
                        $('.loader-wrapper').hide();
                        $('.overlay').hide(); // Hide the overlay
                        $('.modal-body').css('pointer-events', 'auto');
                    },
                });
                $(this).prop("disabled", false);

            });
        });
    </script>
    <style>
        .modal {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .clickable-link.stretched-link {
            text-decoration: none !important;
        }
    </style>
    <style>
        .loader-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 9998; /* Below the loader */
        }


    </style>




@stop