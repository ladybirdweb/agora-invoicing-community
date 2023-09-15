<div class="container ">
<div >
<div>
<div id="content" role="main">
<div class="page-content ">
<div>

<strong>Thank you. Your Payment has been received. A confirmation Mail has been sent to you on <a>{{\Auth::user()->email}}</a> 
</strong><br>
@foreach($invoiceItems as $invoiceItem)
<?php
$currency = $invoice->currency;
$date = getDateHtml($invoiceItem->created_at);
?>

@endforeach


<section>

@foreach($orders as $order)
<?php
$product = \App\Model\Product\Product::where('id', $order->product)->select('id', 'name','type')->first();
$cont = new \App\Http\Controllers\License\LicensePermissionsController();
$downloadPermission = $cont->getPermissionsForProduct($order->product);
?>
<table class="table table-bordered table-hover ">
<thead class="thead-light">
<tr>
<th>Order Details</th>
<th>Total</th>
</tr>
</thead>
<tbody>
<tr>


<td>
<strong>{{$product->name}} × {{$order->qty}} </strong>
</td>


<td class="woocommerce-table__product-total product-total">
<?php
$invoiceTotal = $invoiceItem->regular_price;
?>
<span class="amount">{{currencyFormat($invoiceTotal,$code = $currency)}}</span>
</td>


</tr>


</tbody>
<tfoot>
<tr>
<th scope="row">Order No:</th>
<td><span class="woocommerce-Price-amount amount"> {{$order->number}}</span></td>
</tr>
<tr>
<th scope="row">Invoice No:</th>
<td><span class="woocommerce-Price-amount amount"> {{$invoice->number}}</span></td>
</tr>
<tr>
<th scope="row">Payment method:</th>
<td>{{Session::get('payment_method')}}</td>
</tr>
<tr>
<th scope="row">Date:</th>
<td>{!! $date !!}</td>
</tr>
<tr>
<th scope="row">Total:</th>
<?php
$orderTotal = $order->price_override;
?>
<td><span class="amount">{{currencyFormat($orderTotal,$code = $currency)}}</span></td>
</tr>
</tfoot>
</table>


@if($downloadPermission['downloadPermission'] == 1 && $product->type != '4')


<a href= product/download/{{$order->product}}/{{$invoice->number}} " class="btn btn-sm btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>&nbsp;&nbsp;Download the latest version here</a>
@else
@include('themes.default1.front.clients.deploy-popup', ['orderNumber' => $order->number])


<button style="visibility: hidden;" class="btn btn-sm btn-primary btn-xs deploy-button" value="{{$order->number}}" style="margin-bottom:15px;" onclick="deploy(this)">
<i class="fa fa-download" style="color:white;"></i>&nbsp;&nbsp;Deploy
</button>
@endif
<div class="modal fade open-createTenantDialog" id="tenant" data-backdrop="static" data-keyboard="false">
<!-- Modal content -->
</div>
@endforeach
<br>
</section>




</div>
</div>


</div>



        

</div><!-- end main content -->

    
    </div>
        </div>
<script>
  function deploy(button) {
    var orderNumber = button.value;
    openModal(orderNumber);
  }

  function openModal(orderNumber) {
    $('#tenant .modal-body').text('Order Number: ' + orderNumber);
    $('#tenant').modal('show');
  }
</script>