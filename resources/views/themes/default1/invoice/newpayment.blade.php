@extends('themes.default1.layouts.master')
 @section('title')
Payment
@stop
    @section('content-header')
    <h1>
    Create New Payment
    </h1>
      <ol class="breadcrumb">
            <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('clients')}}">All Users</a></li>
            <li><a href="{{url('clients/'.$clientid)}}">View User</a></li>
            <li class="active">New Payment</li>
          </ol>
    @stop
    @section('content')
    <div class="box box-primary">
    	 <div class="box-header">
          
            @if (count($errors) > 0)
                    <div class="alert alert-danger">
                         <i class="fa fa-alert"></i>
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Whoops!</strong> There were some problems with your input.<br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

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
           <div id="alertMessage"></div>
           <div id="error1"></div>
            <h4>{{Lang::get('message.new-payment')}} <button type="submit" class="form-group btn btn-primary pull-right" onclick="multiplePayment()" id="submit"><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>

        </div>

    	<div class="box-body">

            <div class="row">

                <div class="col-md-12">

                    

                    <div class="row">

                        <div class="col-md-4 form-group {{ $errors->has('invoice_status') ? 'has-error' : '' }}">
                            <!-- first name -->
                            {!! Form::label('payment_date',Lang::get('message.date-of-payment'),['class'=>'required']) !!}
                            {!! Form::text('payment_date',null,['class' => 'form-control','id'=>'payment_date']) !!}

                        </div>

                        <div class="col-md-4 form-group {{ $errors->has('payment_method') ? 'has-error' : '' }}">
                            <!-- last name -->
                            {!! Form::label('payment_method',Lang::get('message.payment-method'),['class'=>'required']) !!}
                            {!! Form::select('payment_method',[''=>'Choose','cash'=>'Cash','check'=>'Check','online payment'=>'Online Payment','razorpay'=>'Razorpay'],null,['class' => 'form-control','id'=>'payment_method']) !!}

                        </div>

                        
                        <div class="col-md-4 form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                            <!-- first name -->
                            {!! Form::label('amount',Lang::get('message.amount'),['class'=>'required']) !!}
                            {!! Form::text('amount',null,['class' => 'form-control','id'=>'amount']) !!}
                            <input type="hidden" name="hidden" id="amount1">
                        </div>

                        


                    </div>


                </div>

            </div>

        </div>



    </div>
    <div class= "box box-primary">
        
                        <div class="box-body">
                        <div class="row">
                            @if(count($invoices)!=0)
                            <div class="col-md-12">
                                <table id="payment-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                             <th></th>
                                            <th>{{Lang::get('message.date')}}</th>
                                            <th>{{Lang::get('message.invoice_number')}}</th>
                                            <th>{{Lang::get('message.total')}}</th>
                                            <th>Due</th>
                                            <th>{{Lang::get('message.pay')}}</th>
                                            
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoices as $invoice)
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
                                        @if ($invoice->status == 'Pending')
                                        <tr>

                                             <td class="selectedbox1">
                                                 <input type="checkbox"  id="check" class="selectedbox" name='selectedcheckbox' value="{{$invoice->id}}">
                                            </td>
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
                                            <td class="invoice-total"> 
                                               {{$invoice->grand_total}}
                                            </td>
                                            <td id="pendingamt">
                                                  <input type="text" class="pendingamt" name="pending" value ="{{$pendingAmount}}" id="pending_{{$invoice->id}}" disabled="disabled">
                                               
                                            </td>
                                            <td class="changeamt">
                                                <input type="text" class="changeamt" name="amount" id="{{$invoice->id}}" disabled="disabled">
                                                
                                            </td>
                                           

                                        </tr>
                                        @endif
                                        @empty 
                                        <tr>
                                            <td>No Invoices</td>
                                        </tr>
                                        @endforelse



                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                          <h3>Amount To Credit : {{$symbol}} <span class="creditAmount">0</span></h3>
                    </div>
    </div>
    @stop
    @section('datepicker')
    <script type="text/javascript">
    $(function () {
        $('#payment_date').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
    </script>
    <script>
         function checking(e){

            $('#payment-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
         }

         $(document).ready(function () {
        /* Get the checkboxes values based on the class attached to each check box */
        $(".selectedbox").click(function() {
            getValueUsingClass();
        });
         });

         function getValueUsingClass(){
        /* declare an checkbox array */
        var chkArray = [];
        
        /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
        $(".selectedbox:checked").each(function() {
            chkArray.push($(this).val());
        });      
        amountChange();
      var amt = $("#amount1").val();
      function amountChange()
        {
        $('.selectedbox').change(function () {


      if(!this.checked ){
        var revertValue =  $('#'+ $(this).val()).val();
        $('.creditAmount').html((+revertValue + + $('.creditAmount').html()));
        $("#amount1").val((+revertValue + + $('.creditAmount').html()))
        $('#'+ $(this).val()).val('');
        }
        else{

          if(chkArray[0] > 0 &&  +amt != 0 ){
             for (var i in chkArray) {

                 var pending= $('#pending_'+ chkArray[i]).val();
                      
                       if(+amt > +pending){
                        $('#' + chkArray[i]).val(pending);
                        $('.creditAmount').html((amt - pending));
                        $("#amount1").val(amt - pending);
                       }
                     
                       else{
                        // console.log(chkArray[chkArray.length - 1]);
                        $('#' + chkArray[chkArray.length - 1]).val(amt);
                        $('.creditAmount').html((0));
                        $("#amount1").val(0);
                       }
                
                  }
             }
        }

        });
          }
        }
         
         $('#amount').change(function () {
        $("#amount1").val($("#amount").val());
          var chkArray = [];
        
        $(".selectedbox:checked").each(function() {
            chkArray.push($(this).val());
        });   
        for (var i in chkArray) {
             $('#'+chkArray[i]).val('');
            }
       $(".selectedbox").prop("checked", false);
       $('.creditAmount').html($("#amount1").val());
    }); 
    
    function multiplePayment(){
     $("#submit").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
    var invoice = [];
    var invoiceAmount = [];
    $(":checked").each(function() {
      if($(this).val() != ""){
       var value = $('#'+ $(this).val()).val();
       console.log(value);
        invoice.push($(this).val());
        invoiceAmount.push(value);

      }
  
    });


console.log(invoice);
    var data = {
            "totalAmt":   $('#amount').val(),
            "invoiceChecked"  : invoice,
            "invoiceAmount"  : invoiceAmount,
            "amtToCredit"   :   $('.creditAmount').html(),
            "payment_date"  : $('#payment_date').val(),
            "payment_method" :$('#payment_method').val(),
        };
    $.ajax({
      url: '{{url('newMultiplePayment/receive/'.$clientid)}}',
      type: 'POST',
      data: data,
          success: function (response) {
            $('#alertMessage').show();
            var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>'+response.message+'.</div>';
            $('#alertMessage').html(result+ ".");
            $("#submit").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
          },
          error: function (ex) {
            var errors = ex.responseJSON;
             $("#submit").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
               $('#error1').show();
            var html = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-ban"></i>Alert! </strong>'+ex.responseJSON.message+' <br><ul>';
            
            for (var key in ex.responseJSON.errors)
            {
                html += '<li>' + ex.responseJSON.errors[key][0] + '</li>'
            }
            html += '</ul></div>';
             $('#alertMessage').hide(); 
             // $('#alertMessage2').hide();
            $('#error1').show();
             document.getElementById('error1').innerHTML = html;

           
          }
    }); 
  }
 </script>

    @stop