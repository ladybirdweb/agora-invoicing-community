<div class="container ">
<div >
<div>
<div id="content" role="main">
<div class="page-content ">
<div>
<div class="alert alert-success">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong><i class="far fa-thumbs-up"></i>
 @if($show)
 <strong>Your Payment has been received. A confirmation Mail has been sent to you on <a>{{\Auth::user()->email}}</a></strong>
 @else
  <strong>Your Payment has been received.</strong>
 @endif

</div>
</strong><br>

@foreach($orders as $order)
<?php
$product = \App\Model\Product\Product::where('id', $order->product)->select('id', 'name','type')->first();
$cont = new \App\Http\Controllers\License\LicensePermissionsController();
$downloadPermission = $cont->getPermissionsForProduct($order->product);
?>
@endforeach
<section>



<table class="table table-bordered table-hover ">
<thead class="thead-light">
<tr>
<th colspan="4">Order Details</th>


</tr>
</thead>


<tfoot>
<tr>
<th scope="row">Product Name:</th>
<td><span class="woocommerce-Price-amount amount"> {{$product->name}}</span></td>
</tr>
<tr>
<th scope="row">Quantity:</th>
<td><span class="woocommerce-Price-amount amount"> {{$items[0]->quantity}} </span></td>
</tr>
<tr>
 
<th scope="row">Order No:</th>
<td><span class="woocommerce-Price-amount amount"> {{$orderNumber}}</span></td>
</tr>
<tr>
<th scope="row">Invoice No:</th>
<td><span class="woocommerce-Price-amount amount"> {{$invoice->number}}</span></td>
</tr>
<tr>
 <th scope="row">Payment method:</th>
 <td><span class="woocommerce-Price-amount amount"> {{Session::get('payment_method')}}</span></td>
</tr>
<tr>
<tr>
<th scope="row">Date:</th>
<td>{!! $date !!}</td>
</tr>
<tr>
<th scope="row">Total:</th>
<td><span class="amount">{{currencyFormat($invoice->grand_total,$code = $invoice->currency)}}</span></td>
</tr>
</tfoot>
</table>
<br>
 @if($show)
@if($downloadPermission['downloadPermission'] == 1 && $product->type != '4')

 <a href="{{ url("product/download/$order->product/$invoice->number") }}" class="btn btn-sm btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>  Download the Latest Version here</a>
@else

@endif
 @endif
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

