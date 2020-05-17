@extends('themes.default1.layouts.master')
@section('title')
Order
@stop
@section('content-header')
<h1>
Order Details
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('orders')}}">All Orders</a></li>
        <li class="active">View Order</li>
      </ol>
@stop
@section('content')
<style type="text/css">
  .box-header.with-border {
    border-bottom: 0px!important;
  }
  a {
    color: currentColor;!important;
  }

</style>
<div class="row">
    <div class="col-md-12">
        
         
            <div class="box-body">
                <div class="box-group" id="accordion">
                  <div class="panel box box-primary">
                   <div class="box-header with-border">
                    <h4 class="box-title">
                     
                         <i class="fa fa-users"></i>
                        Overview
                     
                    </h4>
                  </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                       <div class="box-body">
                        <div class="callout callout-info">
                            <div class="row">
                                <div class="col-md-4">
                                    <b>Date: </b>{{$order->created_at}} 
                                </div>
                                <div class="col-md-4">
                                    <b>Order No: </b>  #{{$order->number}} 

                                </div>
                                <div class="col-md-4">
                                    <b>Status: </b>{{$order->order_status}}
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6">
                     
                           
                          
                           <div class="panel box box-success">
                            <div class="box-body">
                              <div class="box-header with-border">
                              <h4 class="box-title">
                              User Details
                             </h4>
                            </div>
                            
                        
                       
    
                            <table class="table table-hover">

                                <tbody><tr><td><b>Name:</b></td><td><a href="{{url('clients/'.$user->id)}}">{{ucfirst($user->first_name)}}</a></td></tr>
                                    <tr><td><b>Email:</b></td><td>{{$user->email}}</td></tr>
                                    <tr><td><b>Mobile:</b></td><td>@if($user->mobile_code)(<b>+</b>{{$user->mobile_code}})@endif&nbsp;{{$user->mobile}}</td></tr>
                                    <tr><td><b>Address:</b></td><td>{{$user->address}}, 
                                            {{ucfirst($user->town)}}, 
                                            @if(key_exists('name',\App\Http\Controllers\Front\CartController::getStateByCode($user->state)))
                                            {{\App\Http\Controllers\Front\CartController::getStateByCode($user->state)['name']}}
                                            @endif
                                        </td></tr>
                                    <tr><td><b>Country:</b></td><td>{{\App\Http\Controllers\Front\CartController::getCountryByCode($user->country)}}</td></tr>

                                </tbody></table>
                              </div>
                           
                            </div>
                        
                     
                    </div>
                        <div class="col-md-6">
                            <div class="panel box box-success">
                            <div class="box-body">
                               @include('themes.default1.front.clients.reissue-licenseModal')
                               @include('themes.default1.front.clients.domainRestriction')
                               @include('themes.default1.order.installationLimit')
                               @include('themes.default1.order.update_ends-modal')
                               @include('themes.default1.order.license_end-modal')
                               @include('themes.default1.order.support_end-modal')
                               
                                  
                            <div class="box-header with-border">
                              <h4 class="box-title">
                                License Details
                              </h4>
                            </div>
                        
                          
                            <table id="lic_details" class="table table-hover">
   
                              <input type="hidden" name="domainRes" id="domainRes" value={{$allowDomainStatus}}>
                                <tbody><tr><td><b>License Code:</b></td>
                                  <td id="s_key" data-type="serialkey">{{($order->serial_key)}}</td>
                                  <td><span class="label label-success pull-right" id="copied" style="display:none;margin-top:-15px;margin-left:-20px;position: absolute;">Copied</span>
                                    <span data-type="copy" style="pointer-events: initial; cursor: pointer; display: block;" id="copyBtn" title="Click to copy to clipboard"><i class="fa fa-clipboard"></i></span>
                                  </td>
                                </tr>
                                 @if ($licenseStatus == 1)
                                    <tr>
                                        
                                          <td>
                                              <label name="domain">
                                            <b>Licensed Domain:</b>
                                          </td>
                                          <td contenteditable="false" id="domain">{{$order->domain}}</td>
                                         
                                          <td>
                                               <button class='class="btn btn-danger' style="width:max-content;border:none;" id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}">
                                           Reissue License</button>
                                          </td>
                                      </tr>
                                      <tr>
                                        <td><b>Installation Path:</b></td> 
                                        @if(count($installationDetails['installed_path']) > 0)
                                          <td>@foreach($installationDetails['installed_path'] as $paths)
                                              {{$paths}}<br>
                                              @endforeach
                                          </td>
                                        @else
                                        <td>
                                        No Active Installation
                                      </td>
                                        @endif
                                        <td></td>
                                      </tr>

                                      <tr>
                                        <td><b>Installation IP:</b></td> 
                                      @if(count($installationDetails['installed_path']) > 0)  
                                          <td>
                                              @foreach($installationDetails['installed_ip'] as $paths)
                                              {{$paths}}
                                              @endforeach
                                          </td>
                                         @else
                                          <td>
                                        --
                                      </td>
                                        
                                        <td></td>
                                       </tr>
                                  <tr>
                                    <td><b>Installation Limit:</b></td> 
                                    <td>
                                        {{$noOfAllowedInstallation}}
                                      </td>
                                      <td>
                                         <a class="btn btn-sm btn-primary btn-xs" id="installlimit" limit-id="{{$order->id}}" install-limit="{{$noOfAllowedInstallation}}" style='color:white;border-radius:0px;'><i class="fa fa-edit">&nbsp;</i>
                                          Edit</a>
                                        
                                    </td>
                                       
                                  </tr>
                                  @endif
                                  <tr>
                                    <td><b>Current Version:</b></td> 
                                    <td>{!! $versionLabel !!} </td>
                                    <td></td>
                                 </tr>

                                  <tr>
                                    <td><b><label data-toggle="tooltip" data-placement="top" title="" data-original-title="Last connection with License Manager">Last Active:</label></b></td> 
                                    <td>
                                      {!! $lastActivity !!}
                                    </td>
                                    <td></td> 
                                  </tr>
                                   @endif
                                
                                   
                                  <tr>
                                    <td><b>Updates Expiry:</b></td>
                                    <td class="brtags"> {!! $date !!} </td>
                                    <td>
                                      @if($date != '--')
                                     <a class="btn btn-sm btn-primary btn-xs" id="updates_end" updates-id="{{$order->id}}" data-date="{{getTimeInLoggedInUserTimeZone($subscription->update_ends_at,'d/m/Y')}}" style='color:white;border-radius:0px;'><i class="fa fa-edit">&nbsp;</i>
                                Edit</a>
                                @endif
                                    </td>
                                    </tr>

                                <tr>
                                  <td><b>License Expiry:</b></td>
                                  <td class="brtags">{!! $licdate !!} </td>
                                  <td>
                                  @if($licdate != '--')
                                    <a class="btn btn-sm btn-primary btn-xs" id="license_end" license-id="{{$order->id}}" license-date="{{getTimeInLoggedInUserTimeZone($subscription->ends_at,'d/m/Y')}}" style='color:white;border-radius:0px;'><i class="fa fa-edit">&nbsp;</i>
                                Edit</a>
                                @endif
                                  </td>
                                </tr>

                              <tr>
                                <td><b>Support Expiry:</b></td>
                                <td class="brtags">{!! $supdate !!}</td>
                                <td>
                                @if($supdate != '--')
                                    <a class="btn btn-sm btn-primary btn-xs" id="support_end" support-id="{{$order->id}}" support-date="{{getTimeInLoggedInUserTimeZone($subscription->support_ends_at,'d/m/Y')}}" style='color:white;border-radius:0px;'><i class="fa fa-edit">&nbsp;</i>
                                Edit</a>
                                @endif
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
        </div>
    </div>


        <div class="row">
            <div class="col-md-12">
            <div class="box-body">
                <div class="box-group" id="accordion3">
                  <div class="panel box box-primary">
                     <a data-toggle="collapse" data-parent="#accordion3" href="#collapseFour">
                   <div class="box-header with-border">
                    <h4 class="box-title">
                      <i class="fa fa-credit-card"></i>
                        Invoice List
                    </h4>
                  </div>
                </a>
                    <div id="collapseFour" class="panel-collapse collapse in">
                       <div class="box-body">
                       <div class="col-md-12">
                         <table id="editorder-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                             

                    <thead><tr>
                        
                         <th >Number</th>
                          <th>Products</th>
                           
                            <th>Date</th>
                            <th>Total</th>
                            
                             <th>Status</th>
                             
                            <th>Action</th>
                        </tr></thead>
                        </table>

                          </div>
      

                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#editorder-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: true,
               ajax: "{{Url('get-my-invoices/'.$order->id.'/'.$user->id)}}",
           
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
                columnDefs: [
                { 
                    targets: 'no-sort', 
                    orderable: false,
                    order: []
                }
            ],

            columns: [
            
                {data: 'number', name: 'number'},
                {data: 'products', name: 'invoice_item'},
                {data: 'date', name: 'created_at'},
                {data: 'total', name: 'total'},
                  {data: 'status', name: 'status'},
                 

                {data: 'action', name: 'action'}
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
      </script>
         <div class="row">
            <div class="col-md-12">
            <div class="box-body">
                <div class="box-group" id="accordion4">
                  <div class="panel box box-primary">
                     <a data-toggle="collapse" data-parent="#accordion4" href="#collapseFive">
                   <div class="box-header with-border">
                    <h4 class="box-title">
                       <i class="fa fa-bars"></i>
                       Payment Receipts
                    </h4>
                  </div>
                </a>
                    <div id="collapseFive" class="panel-collapse collapse in">
                       <div class="box-body">
                       <div class="col-md-12">
                           <table id="order1-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                 <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class= "fa fa-trash"></i>&nbsp;&nbsp;Delete Selected</button><br /><br />
                    <thead><tr>
                        <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                         <th>Invoice Number</th>
                          <th>Total</th>
                           
                            <th>Method</th>
                            <th>Status</th>
                            
                             <th>Payment Date</th>
                             
                        </tr></thead>
                     </table>

                          </div>
      

                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>


<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
 <script src="{{asset('common/js/licCode.js')}}"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#order1-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: true,
     

           ajax: "{{Url('get-my-payment/'.$order->id.'/'.$user->id)}}",
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
                 columnDefs: [
                { 
                    targets: 'no-sort', 
                    orderable: false,
                    order: []
                }
            ],
            columns: [
            {data: 'checkbox', name: 'checkbox'},
                {data: 'number', name: 'number'},
                {data: 'amount', name: 'amount'},
                {data: 'payment_method', name: 'payment_method'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'created_at', name: 'created_at'},
                 
               
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
     

    </script>
@stop


@section('icheck')
<script>

    function checking(e){
          
          $('#order1-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
     }
     

     $(document).on('click','#bulk_delete',function(){
      var id=[];
      if (confirm("Are you sure you want to delete this?"))
        {
            $('.payment_checkbox:checked').each(function(){
              id.push($(this).val())
            });
            if(id.length >0)
            {
               $.ajax({
                      url:"{!! route('payment-delete') !!}",
                      method:"get",
                      data: $('#check:checked').serialize(),
                      beforeSend: function () {
                $('#gif').show();
                },
                success: function (data) {
                $('#gif').hide();
                $('#response').html(data);
                location.reload();
                }
               })
            }
            else
            {
                alert("Please select at least one checkbox");
            }
        }  

     });
</script>
@stop
@section('datepicker')
<script type="text/javascript">
      $(function () {
   //Datemask dd/mm/yyyy
  $('[data-mask]').inputmask()
  });
</script>
<!-----------------------------------For Reissuing License Domain------------------------------------------------------------->
<script>
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
                 var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+data.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                  $('#response').html(result);
                     $('#response').css('color', 'green');
                setTimeout(function(){
                    window.location.reload();
                },3000);
                  }
               
                }
                
             });
            });
          }
           
        });



 
<!------------------------------------------------------------------------------------------------------------------------------->
/*
* Update Updates Expiry date 
 */

 $("#updates_end").click(function(){
        var olddate = $(this).attr('data-date');
        var orderId = $(this).attr('updates-id');
        $("#updateEndsModal").modal();
        $("#order").val(orderId);
        $("#newDate").val(olddate);
        });

 //When Submit Button is Clicked in Modal Popup, passvalue through Ajax
    $("#updatesSave").on('click',function(){
      $('#updatesSave').attr('disabled',true);
      $('#updatesSave').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
        var newdate = $("#newDate").val();
        var orderId = $("#order").val();
        $.ajax({
            type: "get",
            data: {'orderid': orderId , 'date': newdate},
            url: "{{url('edit-update-expiry')}}",
            success: function (response) {
              $("#updatesSave").attr('disabled',false);
              $("#updatesSave").html("Save");
                if (response.message =='success') {

                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+response.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                     $('#response2').html(result);
                     $('#response2').css('color', 'green');
                 setTimeout(function(){
                    window.location.reload();
                },3000);
                }
            },
            error: function(response) {
              $("#updatesSave").attr('disabled',false);
              $("#updatesSave").html("Save");
                var myJSON = JSON.parse(response.responseText).errors;
                var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                  for (var key in myJSON)
                  {
                      html += '<li>' + myJSON[key][0] + '</li>'
                  }
                 html += '</ul></div>';
                 $('#error2').show(); 
                 $('#response2').html(''); 
                  document.getElementById('error2').innerHTML = html;
                }
        });
    });
<!--------------------------------------------------------------------------------------------------------------------->

 /*
* Update License Expiry date 
 */

 $("#license_end").click(function(){
        var olddate = $(this).attr('license-date');
        var orderId = $(this).attr('license-id');
        $("#licenseEndsModal").modal();
        $("#order2").val(orderId);
        $("#newDate2").val(olddate);
        });

 //When Submit Button is Clicked in Modal Popup, passvalue through Ajax
    $("#licenseExpSave").on('click',function(){
       $('#licenseExpSave').attr('disabled',true);
      $('#licenseExpSave').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
        var newdate = $("#newDate2").val();
        var orderId = $("#order2").val();
        $.ajax({
            type: "get",
            data: {'orderid': orderId , 'date': newdate},
            url: "{{url('edit-license-expiry')}}",
            success: function (response) {
              $("#licenseExpSave").attr('disabled',false);
              $("#licenseExpSave").html("Save");
                if (response.message =='success') {
                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+response.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                     $('#response3').html(result);
                     $('#response3').css('color', 'green');
                 setTimeout(function(){
                    window.location.reload();
                },3000);
                }
            },
            error: function(response) {
              $("#licenseExpSave").attr('disabled',false);
              $("#licenseExpSave").html("Save");
                  var myJSON = JSON.parse(response.responseText).errors;
                       var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                          for (var key in myJSON)
                          {
                              html += '<li>' + myJSON[key][0] + '</li>'
                          }
                         html += '</ul></div>';
                         $('#error1').show(); 
                         $('#response3').html(''); 
                          document.getElementById('error1').innerHTML = html;
                }
            })
        });
  <!-------------------------------------------------------------------------------------------------------------------------->  
  

/*
* Update Support Expiry date 
 */

 $("#support_end").click(function(){
        var olddate = $(this).attr('support-date');
        var orderId = $(this).attr('support-id');
        $("#supportEndsModal").modal();
        $("#order3").val(orderId);
        $("#newDate3").val(olddate);
        });

 //When Submit Button is Clicked in Modal Popup, passvalue through Ajax
    $("#supportExpSave").on('click',function(){
       $('#supportExpSave').attr('disabled',true);
       $('#supportExpSave').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
        var newdate = $("#newDate3").val();
        var orderId = $("#order3").val();
        $.ajax({
            type: "get",
            data: {'orderid': orderId , 'date': newdate},
            url: "{{url('edit-support-expiry')}}",
            success: function (response) {
               $("#supportExpSave").attr('disabled',false);
              $("#supportExpSave").html("Save");
                if (response.message =='success') {
                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+response.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                     $('#response4').html(result);
                     $('#response4').css('color', 'green');
                 setTimeout(function(){
                    window.location.reload();
                },3000);
                }
            },
            error: function(response) {
               $("#supportExpSave").attr('disabled',false);
              $("#supportExpSave").html("Save");
                  var myJSON = JSON.parse(response.responseText).errors;
                       var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                          for (var key in myJSON)
                          {
                              html += '<li>' + myJSON[key][0] + '</li>'
                          }
                         html += '</ul></div>';
                         $('#error3').show(); 
                         $('#response4').html(''); 
                          document.getElementById('error3').innerHTML = html;
                }
            })
        });

      <!-------------------------------------------------------------------------------------------------------------------------->  
  

/*
* Update Support Expiry date 
 */

 $("#installlimit").click(function(){
        var oldlimit = $(this).attr('install-limit');
        var orderId = $(this).attr('limit-id');
        $("#limitModel").modal();
        $("#order5").val(orderId);
        $("#limitnumber").val(oldlimit);
        });

 //When Submit Button is Clicked in Modal Popup, passvalue through Ajax
    $("#installLimitSave").on('click',function(){
        var newlimit = $("#limitnumber").val();
        var orderId = $("#order5").val();
        $.ajax({
            type: "get",
            data: {'orderid': orderId , 'limit': newlimit},
            url: "{{url('edit-installation-limit')}}",
             beforeSend: function () {
                 $('#response5').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

            },
            success: function (response) {
                if (response.message =='success') {
                var result =  '<div class="alert alert-success alert-dismissable"><strong><i class="fa fa-check"></i> Success! </strong> '+response.update+' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                     $('#response5').html(result);
                     $('#response5').css('color', 'green');
                 setTimeout(function(){
                    window.location.reload();
                },3000);
                }
            },
            error: function(response) {
                  var myJSON = JSON.parse(response.responseText).errors;
                       var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
                          for (var key in myJSON)
                          {
                              html += '<li>' + myJSON[key][0] + '</li>'
                          }
                         html += '</ul></div>';
                         $('#error5').show(); 
                         $('#response5').html(''); 
                          document.getElementById('error5').innerHTML = html;
                }
            })
        });


   
</script>
@stop
