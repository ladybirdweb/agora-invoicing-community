@extends('themes.default1.layouts.master')
@section('title')
User
@stop
@section('content-header')
<style type="text/css">
    .read-more-show{
      cursor:pointer;
      color: #ed8323;
    }
    .read-more-hide{
      cursor:pointer;
      color: #ed8323;
    }

    .hide_content{
      display: none;
    }
   


</style>
<h1>
User Details
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('clients')}}">All Users</a></li>
        <li class="active">View User</li>
      </ol>
@stop
@section('content')
@if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div id="error">
        </div>
        <div id="success">
        </div>
        <div id="fails">
        </div>
        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
             <i class="fa fa-check"></i>
               <b>{{Lang::get('message.success')}}!</b> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('success')}}
        </div>
        @endif
        <!-- fail message -->
        @if(Session::has('fails'))
        <div class="alert alert-danger alert-dismissable">
            <i class="fa fa-ban"></i>
            <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('fails')}}
        </div>
        @endif
<div class="box-header with-border widget-user-2  bg-aqua widget-user-header padfull">
    

    <div class="col-md-8 col-sm-4 padzero">
        <div class="widget-user-image">

            <img class="img-circle" src="{{ $client->profile_pic }}" alt="User Avatar">

        </div>
        <h3 class="widget-user-username">{{ucfirst($client->first_name)}}  {{ucfirst($client->last_name)}}</h3>
        <h5 class="widget-user-desc">{{ucfirst($client->town)}}</h5>
        <h6 class="widget-user-desc">{{$client->email}}<br>@if($client->mobile_code)<b>+</b>{{$client->mobile_code}}@endif{{$client->mobile}}</h6>

        </div>
    <div class="col-md-2 col-sm-4 padzero">
        
          
          <?php

         if($currency == 'INR')
            $currency = '₹';
            else
            $currency = '$'; 
          ?>
                   
                      
        <div class="padright">
             
            
            <h6 class="rupee colorblack margintopzero"><span class="font18">Invoice Total </span><br>{{$client->currency_symbol }} {{$invoiceSum}}</h6> 
            <h6 class="rupee colorgreen" style="color:green;"><span class="font18">Paid </span><br>{{$client->currency_symbol }} {{$amountReceived}}</h6> 
            <h6 class="rupee colorred"><span class="font18">{{Lang::get('message.balance')}} </span><br>{{$client->currency_symbol }} {{$pendingAmount}}</h6> 
           <!--   <h6 class="rupee colorred"><span class="font18">{{Lang::get('message.extra')}} </span><br>{{$client->currency_symbol }} {{$extraAmt}}</h6>  -->
          
        </div>
     
        
       
    </div>

    <div class="box-tools pull-right col-md-2 col-sm-4 padfull paddownfive">
        
          <a href="{{url('clients/'.$client->id.'/edit')}}" class="btn btn-block btn-default btn-sm btn-flat ">
            {{Lang::get('message.edit')}}
        </a>
        <div class="dropdown">

            <a class="btn btn-block btn-primary btn-sm btn-flat dropdown-toggle" data-toggle="dropdown" href="#">
                New Transaction <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="{{url('invoice/generate?clientid='.$client->id)}}">{{Lang::get('message.create-invoice')}}</a>
                </li>
               <!--  <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="{{url('order/execute?clientid='.$client->id)}}">{{Lang::get('message.order_execute')}}</a>
                </li> -->
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="{{url('newPayment/receive?clientid='.$client->id)}}">{{Lang::get('message.create-payment')}}</a>
                </li>
<!--                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Estimate</a>
                </li>
                <li role="presentation" class="divider"></li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Sales recepit</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Credit Note</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Delayed Charge</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Time Activity</a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="#">Statement</a>
                </li>-->
            </ul>
        </div>


    </div>

</div>



<div class="margintop20">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" id="myTab">
            <li><a href="#activity" data-toggle="tab">{{Lang::get('message.transation_detail')}}</a>
            </li>
            <li><a href="#settings" onclick="customer_detail()" onload="customer_detail()" data-toggle="tab">{{Lang::get('message.customer_detail')}}</a>
            </li>
            <li><a href="#timeline" onclick="payment_detail()" data-toggle="tab">{{Lang::get('message.payment_detail')}}</a>
            </li>
            <li><a href="#order" onclick="order_detail()" data-toggle="tab">{{Lang::get('message.order_detail')}}</a>
            </li>
            <li><a href="#comment" data-toggle="tab">{{Lang::get('message.comments')}}</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="active tab-pane" id="activity">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-12">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{Lang::get('message.date')}}</th>
                                        <th>{{Lang::get('message.invoice_number')}}</th>
                                        <th>{{Lang::get('message.total')}}</th>
                                        <th>{{Lang::get('message.amount_received')}}</th>
                                        <th>{{Lang::get('message.amount_pending')}}</th>
                                        <th>{{Lang::get('message.status')}}</th>
                                        <th>{{Lang::get('message.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice) 
                                    <?php
                                    
                                     $payment = \App\Model\Order\Payment::where('invoice_id',$invoice->id)->select('amount')->get();
                                     $c=count($payment);
                                       $sum= 0;
                                       for($i=0 ;  $i <= $c-1 ; $i++)
                                       {
                                         $sum = $sum + $payment[$i]->amount;
                                       }
                                       $pendingAmount = ($invoice->grand_total)-($sum);
                                     ?>
                                    <tr>
                                        <td>
                                            <?php
                                          $date1 = new DateTime($invoice->date);
                                          $tz = $client->timezone()->first()->name;
                                          $date1->setTimezone(new DateTimeZone($tz));
                                          $date = $date1->format('M j, Y, g:i a ');
                                            echo $date;
                                            ?>
                                        </td>
                                        <td class="invoice-number">
                                            <a href="{{url('invoices/show?invoiceid='.$invoice->id)}}">{{$invoice->number}}</a>
                                        </td>
                                        <td contenteditable="false" class="invoice-total"> 
                                           {{$invoice->grand_total}}
                                        </td>
                                        <td>{{$sum}}</td>
                                        <td>{{$pendingAmount}}</td>
                                        <td>
                                            {{ucfirst($invoice->status)}}
                                        </td>
                                        
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">{{Lang::get('message.action')}}</button>
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <?php
                                                $template_controller = new \App\Http\Controllers\Common\TemplateController();
                                                $user = $client;
                                                $invoiceItems = \App\Model\Order\InvoiceItem::where('invoice_id', $invoice->id)->get();
                                                $body = Form::open(['url' => 'order/execute', 'method' => 'GET']);
                                                $body .= "<input type=hidden value=$invoice->id name=invoiceid />";
                                                $body .= view('themes.default1.invoice.pdfinvoice', compact('invoice', 'user', 'invoiceItems'))->render();
                                                $model_popup = $template_controller->popup('Invoice', $body, 897, 'execute order', 'invoice' . $invoice->id);
                                                ?>
                                                <ul class="dropdown-menu" role="menu">
                                                    @if($invoice->order()->get()->count()==0)
                                                    <li><a href=# class=null  data-toggle='modal' data-target="#editinvoice{{$invoice->id}}">Execute order</a></li>
                                                    @endif
                                                    
                                                  <!--   @if($invoice->status!='success')
                                                    <li><a href="{{url('payment/receive?invoiceid='.$invoice->id)}}">{{Lang::get('message.payment')}}</a></li>
                                                    @endif -->
                                                     <li><a href="{{url('invoices/edit/'.$invoice->id)}}">Edit {{Lang::get('message.invoice')}}</a></li>
                                                     <li><a href="{{url('invoices/show?invoiceid='.$invoice->id)}}">View {{Lang::get('message.invoice')}}</a></li>

                                                     <li><a href="{{url('invoices/'.$invoice->id.'/delete')}}">{{Lang::get('message.delete')}}</a></li>
                                                </ul>
                                                {!! $model_popup !!}

                                            </div>
                                        </td>

                                    </tr>
                                    @empty 
                                    <tr>
                                        <td>No Invoices</td>
                                    </tr>
                                    @endforelse


                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
            <div class="tab-pane" id="timeline">
                <div>
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Date</th>
                                    <th>Payment Method</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($invoices)>0)
                                @forelse($client->payment()->orderBy('created_at','desc')->get() as $payment)
                                 <tr>
                                    <td class="invoice-no invoice">
                                       
                                    </td>
                                    <td class="date">
                                    </td>
                                    <td class="paymethod">
                                    </td>
                                    @if($payment->invoice_id == 0)
                                     <td class="payment-total" data-count="{{$payment->id}}"></td>
                                     @endif
                                       @if($payment->invoice_id != 0)
                                    <td contenteditable="false" class="payment-total" data-count="{{$payment->id}}"></td>
                                     @endif
                                      
                                    <td class="payment_status"></td>
                                    <td>
                                        <input type="hidden" class="paymentid" value="{{$payment->id}}">
                                      
                                         @if($payment->invoice_id == 0)
                                          <a href="{{url('payments/'.$payment->id.'/edit')}}" class="btn btn-primary btn-xs" value="{{$payment->id}}"><i class="fa fa-edit"></i>
                                          {{Lang::get('message.edit')}}</a>
                                          @endif
                                         
                                          <a href="{{url('payments/'.$payment->id.'/delete')}}" class="btn btn-danger btn-xs" onclick = "return myFunction()" ><i class="fa fa-trash">&nbsp;</i>{{Lang::get('message.delete')}}</a>
                                  
                                    </td>
                                </tr>
                                @empty 
                                <tr>
                                    <td>
                                        {{Lang::get('message.no-record')}}
                                    </td>
                                </tr>
                                @endforelse
                                @endif



                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.tab-pane -->

            <div class="tab-pane" id="settings">

                <div>
                    <div class="box box-widget widget-user">

                        <div>
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header clientemail"></h5>
                                        <span class="description-text">{{Lang::get('message.email')}}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header clientcompanyname"></h5>
                                        <span class="description-text">{{Lang::get('message.company')}}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header  clientuser_name"></h5>
                                        <span class="description-text">User Name</span>
                                    </div>
                                    <!-- /.description-block -->

                                </div>
                                <!-- /.col -->

                            </div>
                            <!-- /.row -->
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    <li>
                                        <a href="#">
                                            <strong>Business :</strong> <span class="pull-right clientbusiness"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.mobile')}} :</strong> <span class="pull-right clientmobile">@if($client->mobile_code)<b>+</b>{{$client->mobile_code}}@endif{{$client->mobile}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.address')}} :</strong> <span class="pull-right clientaddress"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.town')}} :</strong> <span class="pull-right clienttown"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.state')}} :</strong> <span class="pull-right clientstate">
                                               
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.country')}} :</strong> <span class="pull-right clientcountry">
                                             </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.zip')}} :</strong> <span class="pull-right clientzip"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.role')}} :</strong> <span class="pull-right clientrole"></span>
                                        </a>
                                    </li>
                                     <li>
                                        <a href="#">
                                            <strong>{{Lang::get('message.currency')}} :</strong> <span class="pull-right clientcurrency"></span>
                                        </a>
                                    </li>
                                     <li>
                                        <a href="#">
                                            <strong>Company Type :</strong> <span class="pull-right clientcompany"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>Company Size :</strong> <span class="pull-right clientcomsize"></span>
                                        </a>
                                    </li>
                                     <li>
                                        <a href="#">
                                            <strong>IP :</strong> <span class="pull-right clientip"></span>
                                        </a>
                                    </li>
                                    @if($client && $client->skype)
                                    <li>
                                        <a href="#">
                                            <strong>Skype :</strong> <span class="pull-right clientskype"></span>
                                        </a>
                                    </li>
                                    @endif
                                    <?php $manager = $client->manager()->select('id','first_name','last_name')->first(); ?>
                                    @if($client && $manager)
                                    <li>
                                        <a href="{{url('clients/'.$manager->id)}}">
                                            <strong>Account Manager :</strong> <span class="pull-right clientmanager">{{$manager->first_name}} {{$manager->last_name}}</span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box box-widget widget-user -->
                </div>
            </div>

            <!-- order !-->


            <div class="tab-pane" id="order">

                <div>
                    <div class="box box-widget widget-user">
                         <div class="box-body">
                        <table id="example3" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Number</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client->order()->orderBy('created_at','desc')->get() as $order)
                                <tr>
                                    <td class="orderDate"></td>
                                    <td class="orderName">
                                    </td>
                                    <td class="orderNumber"></td>
                                    <td class="orderPrice"></td>
                                    <td class="orderStatus"></td>
                                    <td><a href="{{url('orders/'.$order->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>&nbsp;View</a>
                                    <a href="{{url('orders/'.$order->id.'/delete')}}"  onclick = "return orderDelFunction()" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;{{Lang::get('message.delete')}}</a></td>
                                </tr>
                                @empty 
                                <tr>
                                    <td>
                                        {{Lang::get('message.no-record')}}
                                    </td>
                                </tr>
                                @endforelse



                            </tbody>
                        </table>
                    </div>  

                    </div>
                    <!-- /.box box-widget widget-user -->
                </div>
            </div>
             @include('themes.default1.user.client.editComment')
            <div class="tab-pane" id="comment">

                  <!-- timeline time label -->
                
                     <a href="#comment" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#createComment">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;{{Lang::get('message.add_comment')}}</a>
                 @include('themes.default1.user.client.createComment')
                  <br/> <br/> <br/>
                  <table id="example4" class="table table-bordered table-striped">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                    
                
               

                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                    @forelse($comments as $comment)
                    <?php 
                  $userId = $comment ->updated_by_user_id;
                  $user = \App\User::where('id',$userId)->first();
                  ?> 
                  <li>
                    
                    <i class="fa fa-comments bg-yellow" title="Posted by {{$user->role}}"></i>

                    <div class="timeline-item">
                          <div class="user-block" >
                     <img src = "{{$user->profile_pic}}" class="img-circle img-bordered-sm" alt="User Image" width="35" height="35" style="margin-top:5px;margin-left:10px;">
                     <span class ="username" style="margin-left:10px;margin-top:5px">
                     <a href="{{url('clients/'.$user->id)}}"  style="margin-left:10px;">{{$user->first_name}} {{$user->last_name}}</a>
                 </span>
                 <span class="description">
                     <i class="fa fa-clock-o" style="margin-left:10px;">
                        <?php
                            $date1 = new DateTime($comment->created_at);
                            $tz = \Auth::user()->timezone()->first()->name;
                            $date1->setTimezone(new DateTimeZone($tz));
                            $date = $date1->format('M j, Y, g:i a ');

                            echo $date;
                            ?>
                     </i>
                 </span>
             </div>
           
                      <div class="timeline-body" id="longdesc" >
                        
                            <div class="comment more">
                                @if(strlen($comment->description) > 100)
                                {{substr($comment->description,0,100)}}
                                <span class="read-more-show hide_content">More<i class="fa fa-angle-down"></i></span>
                                <span class="read-more-content"> {{substr($comment->description,100,strlen($comment->description))}} 
                                <span class="read-more-hide hide_content">Less <i class="fa fa-angle-up"></i></span> </span>
                                @else
                                {{$comment->description}}
                                @endif
                               
                    
                        <!-- {{$comment->description}} -->
                        
                    </div>
                    <br/>
                  
                      <div class="timeline-footer">
                        <button type="submit" class="btn btn-primary btn-xs edit-comment" data-description="{{$comment->description}}" data-comment-id="{{$comment->id}}" data-user_id="{{$comment->user_id}}" data-admin_id="{{$comment->updated_by_user_id}}"><i class='fa fa-edit' style='color:white;'> </i>&nbsp;{{Lang::get('message.edit')}}</button>
                        <a href="{{url('comment/'.$comment->id.'/delete')}}" class="btn btn-danger btn-xs" onclick="return delCommentFunction()"><i class='fa fa-trash' style='color:white;'> </i>&nbsp;Delete</a>
                      </div>
                    </div>
                    
                  </li>
                   @empty 
                    <tr>
                    <td>
                        {{Lang::get('message.no-comments')}}
                    </td>
                    </tr>
                      @endforelse

                </ul>
            </table>
            </div>

           </div>

        </div>
         
    </div>
</div>
<div class='modal fade' id=editinvoice23>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title'>Invoice</h4>
            </div>
            <div class='modal-body'>
                body
            </div>
            <div class='modal-footer'>
                <button type=button id=close class='btn btn-default pull-left' data-dismiss=modal>Close</button>
                <input type=submit class='btn btn-primary' value=Save>
            </div>
        </div>
    </div>
</div>


@stop

@section('icheck')

<script>
    $(function () {


        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });


    });

     
</script>
<script>
     function myFunction() {
      if(!confirm("Are You Sure to delete this Payment?"))
      event.preventDefault();
  }
  function orderDelFunction() {
      if(!confirm("Are You Sure to delete this Order?"))
      event.preventDefault();
  }
  

  function delCommentFunction() {
     if(!confirm("Are you sure you want to delete this comment?"))
        event.preventDefault();
  }
</script>
<script>

    $(".invoice-total").blur(function () {
        var data = $(this).text().trim();
        var number = $(this).siblings('.invoice-number').find('a').text().trim();


        $.ajax({
        type: "GET",
                url: "{{route('change-invoiceTotal')}}",
                data: {'total':data,'number':number},
                success: function () {
                    alert('Invoice Total Updated');
                },
                error: function () {
                    alert('Invalid URL');
                }

        });
    });
</script>
<script>
    $(".payment-total").blur(function (){
        var id = $(this).attr("data-count");
        var data = $(this).text().trim();

        $.ajax({
        type: "GET",
                url: "{{route('change-paymentTotal')}}",
                data: {'total':data,'id':id},
                success: function () {
                    alert('Payment Total Updated');
                },
                error: function () {
                    alert('Invalid URL');
                }

        });
    })
</script>
<script>
  $(function () {
     $('#example1').DataTable();
      $('#example').DataTable();
       $('#example3').DataTable();
       $('#example4').DataTable();
 
    
  })

    $('.edit-comment').click(function(){
        var commentDescription = $(this).attr('data-description');
        var commentid = $(this).attr('data-comment-id');
        var userid  = $(this).attr('data-user_id');
        var adminid = $(this).attr('data-admin_id');

        $('#edit-comment').modal('show');
        $('#desc').val(commentDescription);
        $('#user-id').val(userid);
        $('#admin-id').val(adminid);
        var url = "{{url('comment')}}"+ "/"+ commentid
        $('#comment-edit-form').attr('action', url)
    });

   
   </script>
<script type="text/javascript">
// Hide the extra content initially, using JS so that if JS is disabled, no problemo:
            $('.read-more-content').addClass('hide_content')
            $('.read-more-show, .read-more-hide').removeClass('hide_content')

            // Set up the toggle effect:
            $('.read-more-show').on('click', function(e) {
              $(this).next('.read-more-content').removeClass('hide_content');
              $(this).addClass('hide_content');
              e.preventDefault();
            });

            // Changes contributed by @diego-rzg
            $('.read-more-hide').on('click', function(e) {
              var p = $(this).parent('.read-more-content');
              p.addClass('hide_content');
              p.prev('.read-more-show').removeClass('hide_content'); // Hide only the preceding "Read More"
              e.preventDefault();
            });
</script>
  <script>

//Do your stuff, JS!

 function customer_detail() {
    '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 20px; height:250 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
        $.ajax({
          url: '{{url("getClientDetail/".$client->id)}}',
          type: 'get',
           success: function (response) {
            $('.clientemail').html((response.client).email);
            $('.clientcompanyname').html((response.client).company);
              $('.clientuser_name').html((response.client).user_name);
              $('.clientbusiness').html((response.client).bussiness);
              $('.clientmobile').val((response.client).clientmobile);
              $('.clientaddress').html((response.client).address);
              $('.clienttown').html((response.client).town);
              $('.clientstate').html((response.client).state);
               $('.clientcountry').html((response.client).country);
               $('.clientzip').html((response.client).zip);
               $('.clientrole').html((response.client).role);
               $('.clientcurrency').html((response.client).currency);
               $('.clientcompany').html((response.client).company_type);
               $('.clientcomsize').html((response.client).company_size);
               $('.clientip').html((response.client).ip);
               $('.clientskype').html((response.client).skype);
               $('.clientmanager').val((response.client).clientmanager);
         }
      })
    }

    function payment_detail() {
        $.ajax({
        url: '{{url("getPaymentDetail/".$client->id)}}',
        type: 'get',
        success: function (response) {
            console.log(response)
            for(var i = 0; i < response.length; i++) {
                $('.invoice-no.invoice').eq(i).html(response[i].number);
                $('.paymethod').eq(i).html(response[i].pay_method);
                $('.payment-total').eq(i).html(response[i].amount);
                $('.payment_status').eq(i).html(response[i].status);
                $('.date').eq(i).html(response[i].date);
            }
         
        }
    })
    }

    function order_detail() {
      
       $.ajax({
        url: '{{url("getOrderDetail/".$client->id)}}',
        type:'get',
        success: function (response) {
            for(var i =0; i < response.length ; i++)
            {
                $('.orderDate').eq(i).html(response[i].date);
                $('.orderName').eq(i).html(response[i].productName);
                $('.orderNumber').eq(i).html(response[i].number);
                $('.orderPrice').eq(i).html(response[i].price);
                $('.orderStatus').eq(i).html(response[i].status);
            }
           
        }
       })
    }

    // $('#myTab a').click(function(e) {
    //   e.preventDefault();
    //   $(this).tab('show');
    // });

    // // store the currently selected tab in the hash value
    // $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
    //   var id = $(e.target).attr("href").substr(1);
    //   window.location.hash = id;
    // });

    // // on load of the page: switch to the currently selected tab
    // var hash = window.location.hash;
    
    // $('#myTab a[href="' + hash + '"]').tab('show');
</script>
@stop