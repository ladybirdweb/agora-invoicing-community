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
                                <div class="col-md-3">
                                    <b>Date: </b>{{$order->created_at}} 
                                </div>
                                <div class="col-md-3">
                                    <b>Invoice No: </b> #{{$invoice->number}}
                                </div>
                                <div class="col-md-3">
                                    <b>Order No: </b>  #{{$order->number}} 

                                </div>
                                <div class="col-md-3">
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
                        
                          
                            <table class="table table-hover">
   
                              <input type="hidden" name="domainRes" id="domainRes" value={{$allowDomainStatus}}>
                                <tbody><tr><td><b>License Code:</b></td><td>{{($order->serial_key)}}</td></tr>
                                    <tr>
                                        
                                            <td>
                                                 <label name="domain">
                                                    <b>Licensed Domain/IP:</b></td><td contenteditable="false" id="domain">{{$order->domain}}
                                                       @if ($licenseStatus == 1)
                                      <button class='class="btn btn-danger mb-2 pull-right' style="border:none;" id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}">
                                Reissue Licesnse</button>
                                <tr><td><b>Installation Path:</b></td> 
                                        @if(count($installationDetails['installed_path']) > 0)
                                          <td>@foreach($installationDetails['installed_path'] as $paths)
                                              <li>{{$paths}}</li>
                                              @endforeach
                                          </td>
                                        @else
                                        <td>
                                        No Active Installation
                                      </td>
                                        @endif
                                      </tr>

                                      <tr><td><b>Installation IP:</b></td> 
                                      @if(count($installationDetails['installed_path']) > 0)  
                                          <td>
                                              @foreach($installationDetails['installed_ip'] as $paths)
                                              <li>{{$paths}}</li>
                                              @endforeach
                                          </td>
                                         @else
                                          <td>
                                        --
                                      </td>
                                        @endif
                                       </tr>
                                  <tr><td><b>Installation Limit:</b></td> 
                                          <td>
                                              {{$noOfAllowedInstallation}}
                                               <a class='class="btn btn-sm btn-primary btn-xs pull-right' id="installlimit" limit-id="{{$order->id}}" install-limit="{{$noOfAllowedInstallation}}" style='color:white;border-radius:0px;'><i class="fa fa-edit">&nbsp;</i>
                                                Edit</a>
                                              
                                          </td>
                                       
                                 </tr>

                                  <tr><td><b>Current Version:</b></td> 
                                          <td>
                                              {{$currenctVersion}}
              
                                          </td>
                                       
                                 </tr>

                                  <tr><td><b>Last Connection with License Manager:</b></td> 
                                          <td>
                                            <?php
                                             $date2 = new DateTime($lastActivity);
                                                $tz = \Auth::user()->timezone()->first()->name;
                                                $date2->setTimezone(new DateTimeZone($tz));
                                                $licdate = $date2->format('M j, Y, g:i a ');
                                                // $licenseEnd =  date('d/m/Y', strtotime($lastActivity));
                                                ?>
                                              {{$licdate}}
              
                                          </td>
                                       
                                 </tr>

                                  <tr><td><b>Installation Preference:</b></td>
                                            @if(Session::has('success'))
                                            <div class="alert alert-success">
                                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                   <strong><i class="fa fa-check"></i> Success!</strong>
                                               
                                                {!!Session::get('success')!!}
                                            </div>
                                            @endif
                                    <td>
                                      {!! Form::open(['url' => url('ip-or-domain'),'method'=>'post']) !!}
                                      <input type="hidden" name="order" value="{{$order->id}}">
                                        {{ Form::radio('domain', 0 , ($getInstallPreference == '0')) }} IP &nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ Form::radio('domain', 1 , ($getInstallPreference == '1')) }} Domain
                                          <button type="submit" class="btn btn-primary btn-xs pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
                                            {!! Form::close() !!}
                                       </td> 
                                 </tr>
                                @endif
                           
                            </td></tr>
                                    <?php
                                    $date = "--";
                                    $licdate = "--";
                                    $supdate= "--";
                                    if ($subscription) {
                                        if (strtotime($subscription->update_ends_at) >1) {
                                             $date1 = new DateTime($subscription->update_ends_at);
                                                $tz = \Auth::user()->timezone()->first()->name;
                                                $date1->setTimezone(new DateTimeZone($tz));
                                                $date = $date1->format('M j, Y, g:i a ');
                                                $updatesEnd = date('d/m/Y', strtotime($subscription->update_ends_at));
                                                
                                             }
                                             if ((strtotime($subscription->ends_at) > 1)) {
                                             $date2 = new DateTime($subscription->ends_at);
                                                $tz = \Auth::user()->timezone()->first()->name;
                                                $date2->setTimezone(new DateTimeZone($tz));
                                                $licdate = $date2->format('M j, Y, g:i a ');
                                                $licenseEnd =  date('d/m/Y', strtotime($subscription->ends_at));
                                             }
                                            if (strtotime($subscription->support_ends_at) > 1) {
                                             $date3 = new DateTime($subscription->support_ends_at);
                                                $tz = \Auth::user()->timezone()->first()->name;
                                                $date3->setTimezone(new DateTimeZone($tz));
                                                $supdate = $date3->format('M j, Y, g:i a ');
                                                $supportEnd =  date('d/m/Y', strtotime($subscription->support_ends_at));
                                                
                                             }
                                    }
                                    ?>
                                    <tr><td><b>Updates Expiry Date:</b></td><td>{{$date}}
                                      @if($date != '--')
                                    <a class='class="btn btn-sm btn-primary btn-xs pull-right' id="updates_end" updates-id="{{$order->id}}" data-date="{{$updatesEnd}}" style='color:white;border-radius:0px;'><i class="fa fa-edit">&nbsp;</i>
                                Edit</a>
                                @endif
                              </td></tr>

                                <tr><td><b>License Expiry Date:</b></td><td>{{$licdate}}
                                  @if($licdate != '--')
                                    <a class='class="btn btn-sm btn-primary btn-xs pull-right' id="license_end" license-id="{{$order->id}}" license-date="{{$licenseEnd}}" style='color:white;border-radius:0px;'><i class="fa fa-edit">&nbsp;</i>
                                Edit</a>
                                @endif
                              </td></tr>

                              <tr><td><b>Support Expiry Date:</b></td><td>{{$supdate}}
                                @if($supdate != '--')
                                    <a class='class="btn btn-sm btn-primary btn-xs pull-right' id="support_end" support-id="{{$order->id}}" support-date="{{$supportEnd}}" style='color:white;border-radius:0px;'><i class="fa fa-edit">&nbsp;</i>
                                Edit</a>
                                @endif
                              </td></tr>

                                </tbody></table>
                             
                             
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
                      <i class="fa fa-film"></i>
                        Transaction List
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
        var newdate = $("#newDate").val();
        var orderId = $("#order").val();
        $.ajax({
            type: "get",
            data: {'orderid': orderId , 'date': newdate},
            url: "{{url('edit-update-expiry')}}",
             beforeSend: function () {
                 $('#response2').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

            },
            success: function (response) {
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
        var newdate = $("#newDate2").val();
        var orderId = $("#order2").val();
        $.ajax({
            type: "get",
            data: {'orderid': orderId , 'date': newdate},
            url: "{{url('edit-license-expiry')}}",
             beforeSend: function () {
                 $('#response3').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

            },
            success: function (response) {
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
        var newdate = $("#newDate3").val();
        var orderId = $("#order3").val();
        $.ajax({
            type: "get",
            data: {'orderid': orderId , 'date': newdate},
            url: "{{url('edit-support-expiry')}}",
             beforeSend: function () {
                 $('#response4').html( "<img id='blur-bg' class='backgroundfadein' style='top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;' src='{!! asset('lb-faveo/media/images/gifloader3.gif') !!}'>");

            },
            success: function (response) {
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
