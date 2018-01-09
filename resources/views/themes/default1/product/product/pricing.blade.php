@extends('themes.default1.layouts.master')
@section('content')
<div class="content-wrapper" style="width:100%;margin-left:0px">
  <section class="content-header" style="margin-top:20px;">
    <h1>
      Manage Price
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#">Products</a></li>
      <li class="active">Manage Price</li>
    </ol>
  </section>
    
<section class="content">

<div class="box box-primary">


@if(!empty($price_lists))

  <?php foreach ($price_lists as $price_list): ?>


<div class="box-body" style="opacity: 1;" id="Pricing-chart">
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-12" style="padding-left:0px;padding-right:0px;">
        <div class="box" style="border-top:0px">
          <div class="box-body no-padding">
<table class="table table-striped table-bordered">
<thead >
<tr>
<th style="text-align:center;padding:15px;width:300px;background-color:#f2f3ab;color:black;">Pricing</th>
<th style="text-align:center;padding:15px;background-color:#f2f3ab;color:black;">Selling Price</th>
<th style="text-align:center;padding:15px;background-color:#5b5b5b;color:white;">Renewal Price</th>
<th style="width:150px;text-align:center;padding:15px;background-color:#5b5b5b;color:white;">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td>
  <p style="text-align:center;">{{$price_list->duration}} Month</p>
  <p style="text-align:center;">Selling Price</p>
  <p style="display:block;margin-left:120px;"><input  type="checkbox"  checked disabled><label><b>  INR</b></label></p>
  <p style="display:block;margin-left:120px;"><input  type="checkbox" <label><b>  USD</b></label></p>
  <!-- <p style="display:block;margin-left:120px;"><input  type="checkbox" <label><b>  EURO</b></label></p> -->
  
 <input style="width:50px;"  class="form-control" type="text" name="" >
</td>
<td>
  <ul style="list-style-type:none">
    <li>
      <p style="text-align:center;">Total</p>
      <p style="text-align:center;margin-left:30px;margin-top:2px;font-weight:700;font-size:18px;"> Rs {{$price_list->new_pricing}}</p><!-- <input style="width:50%;height:25px;margin-left:50px;" class="form-control" type="number" min="0" name=""> -->
 <br><!-- <p style="text-align:center;"></p>
      <p style="text-align:center;"><b></b></p> -->
    </li>
  </ul>
  <!-- <input style="width:50%;margin-left:60px;"  class="form-control" type="number" min="0" name=""> -->
</td>
<td>
  <ul style="list-style-type:none">
      <li>
        <p style="text-align:center;">Per Month</p>
        <p style="text-align:center;margin-left:30px;margin-top:2px;font-weight:700;font-size:18px;">Rs {{$price_list->renew_pricing}}</p><!-- <input style="width:50%;height:25px;margin-left:50px;"  class="form-control" type="number" min="0" name="" -->
        <br><p style="text-align:center;"></p>
        <p style="text-align:center;"><b></b></p>
      </li>
    </ul>
  <!-- <input style="width:50%;margin-left:60px;"  class="form-control" type="number" min="0" name="" > -->
</td>
<td>
  <i class="fa fa-minus-circle btn btn-danger"  style="margin-top:50px;margin-left:25px;"></i>
  <i class="fa fa-plus-circle btn btn-success"  style="margin-top:50px;margin-left:10px;"></i>
</td>
</tr>
</tbody>
</table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



                                <?php endforeach; ?>

                                @endif
















</div>
<!-- <div class="multiple-chart" > -->
  <!-- </div> -->
</section>
</div> 

<footer style="padding:15px;">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0
        </div>
        <strong>Copyright &copy; 2017 <a href="#"> Ladybird Web Solution</a>.</strong> All rights
        reserved.Powered by <img src="assets/images/Ladybird1.png">
      </footer>
      <script type="text/javascript">
        $(document).ready(function() {
  var div_clone = null;
  div_clone = $('#Pricing-chart').clone();
  $('.fa-plus-circle').click(function() {
    alert('ok');
    //we select the box clone it and insert it after the box
    var _elm = div_clone.clone();
    // _elm.find('.box-title').html('Other Address');
    // _elm.find("input[type='text']").val("");
    // _elm.find('.remove_btn').show();
    _elm.appendTo('.multiple-chart');
  })

  $(document).on("click", ".fa-minus-circle", function(e) {
     alert('remove');
    var $e = $(e.currentTarget);
    $e.closest('#Pricing-chart').remove();
  });
});

      </script>

      @stop