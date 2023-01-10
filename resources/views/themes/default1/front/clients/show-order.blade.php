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
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li><a href= "{{url('my-orders')}}">My Order</a></li>
<li class="active">View Order</li>
@stop

@section('content')
 @include('themes.default1.front.clients.reissue-licenseModal')
 @include('themes.default1.front.clients.domainRestriction')
    <div class="row pb-4">
        <div class="col-lg-12 mb-12 mb-lg-0">
            <div class="alert alert-tertiary" style="padding-bottom: 5px; background-color: #49b1bf">
                <div class="row">
                    <div class="col col-md-4">Order No: #{{$order->number}}</div>
                    <div class="col col-md-4">Date: {!! getDateHtml($order->created_at) !!}</div>
                    <div class="col col-md-4">Status: {{$order->order_status}}</div>
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

            @component('mini_views.navigational_view', [
                'navigations'=>[
                    ['id'=>'license-details', 'name'=>'License Details', 'active'=>1, 'slot'=>'license','icon'=>'fas fa-file'],
                    ['id'=>'user-details', 'name'=>'User Details', 'slot'=>'user','icon'=>'fas fa-users'],
                    ['id'=>'invoice-list', 'name'=>'Invoice List', 'slot'=>'invoice','icon'=>'fas fa-credit-card'],
                    ['id'=>'payment-receipts', 'name'=>'Payment Receipts', 'slot'=>'payment','icon'=>'fas fa-briefcase']
                ]
            ])
               

                @slot('license')
                 
                    <table class="table">
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
                                    <button class="btn btn-danger mb-2 btn-sm"  id="reissueLic" data-id="{{$order->id}}" data-name="{{$order->domain}}" {{!Storage::disk('public')->exists('faveo-license-{'.$order->number.'}.txt') || $order->license_mode!='File' ? "enabled" : "disabled"}}>
                                        Reissue License</button></td>
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
                
                 <th >Installation Path</th>
                  <th>Installation IP</th>
                   
                    <th>Current Version </th>
                    <th>  Last Active</th>
                    
                </tr></thead>
                    <tbody>
                    
                        @foreach($installationDetails['installed_path'] as $key => $ins)
                        <?php
                        
                        $Latestversion = DB::table('product_uploads')->where('product_id', $order->product)->latest()->value('version');
                     
                        $productversion = DB::table('installation_details')->where('installation_path',$installationDetails['installed_path'])->first();
                       
                        $date = getTimeInLoggedInUserTimeZone($productversion->updated_at, 'M j, Y');
                        $dateTime = getTimeInLoggedInUserTimeZone($productversion->updated_at);
                       
                      $active = (new Carbon\Carbon('-30 days'))->toDateTimeString() && $productversion->updated_at != $productversion->created_at ;
                     
                       
                       
                        ?>
                            <tr>
                            <td>{{$ins}}</td>
                            <td>{{$installationDetails['installed_ip'][$key]}}</td>
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

            @endcomponent
        </div>
    </div>



<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>


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

    </script>

@stop