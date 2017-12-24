@extends('themes.default1.layouts.master')
@section('content')

<div class="box box-primary">

    <div class="box-header">
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

        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
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


        <h4>{{Lang::get('message.tax')}}
            
        </h4>
        

    </div>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("input[name='chkTax']").click(function () {
            if ($("#chkYes").is(":checked")) {
                $("#GST").show();
                $("#state").show();
            } else {
                $("#GST").hide();
                $("#state").hide();
            }
        });
    });
</script>



    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
               
   <form role="form" action="" method="PATCH">
                     {!! csrf_field() !!}
                <table class="table table-responsive">


                    <span>Do you have GST_ID?</span>
                     </br>
 <tr>              
<td><label for="chkYes">
    <input type="radio" id="chkYes" name="chkTax" />
    Yes
</label></td></tr>

<tr><td><div id="GST" style="display: none">
    GST Number:
    <input type="text" name="number" id="txtGSTnumber" />
</div></td></br>
<td><div id="state" style="display: none">
    <!-- State:
    <input type="text" id="txtGSTnumber" /> -->
    <select name="state" id="state-list" class="form-control">
                        <option value="">Select State</option>
                        @foreach($states as $id=>$name)
                            <option value="{{$id}}" name="state">{{$name}}</option>
                        @endforeach
                    </select>

     

</div></td>
</tr>





<tr>
<td><label for="chkNo">
    <input type="radio" id="chkNo" name="chkTax" />
    No
</label></td></tr>




  </br>
            </br>
                
                   
                        
                        <td>{!! Form::submit('save',['class'=>'btn btn-primary']) !!}</td>

                  
                </table>
 </form>
                   
              

                <div class="box">
                    <!-- <div class="box-header">
                        Classes
                    </div> -->
                    <div class="box-body">
                            <a href="#create-tax-option" id="create" class="btn btn-primary pull-right" data-toggle="modal" data-target="#create-tax-option">Add Class</a>
                             @include('themes.default1.payment.tax.create-tax-option')
                              @include('themes.default1.payment.tax.edit-tax-option')

                              
                        </div>

                       
                    </div>


               


         
   <div class="box-body">
        <div class="row">

            <div class="col-md-12">
                 <table id="tax-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

                    <thead><tr>
                            <th>Tax Class Name</th>
                            <th>Product Name</th>
                            <!-- <th>Country</th>
                             <th>State</th>  -->
                              <th>Rate(%)</th>
                               <th>Start Date</th>
                               <th>End Date</th>
                                <th>Time Zone</th>
                               <th>Action</th>


                        </tr></thead>


                </table>

            </div>
        </div>

    </div>

</div>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script> -->
<!-- <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script> -->

<script type="text/javascript">
        $('#tax-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('get-tax') !!}',
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
    
            columns: [
                {data: 'tax_name', name: 'tax_name'},
                 {data: 'product_name', name: 'product_Name'},
                   // {data: 'country', name: 'country'},
                   //   {data: 'state', name: 'state'},
                      {data: 'rate', name: 'rate'},
                       {data: 'startdate', name: 'start_Date'},
                        {data: 'enddate', name: 'end_Date'},
                         {data: 'timezone', name: 'time_Zone'},
                
                            {data: 'action', name: 'Action'}
                
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
    //     $('#create').on('click' , function(e){
    //    e.preventDefault();
    //   $('create-tax-option').modal('show');
    // });

    $('#edit-tax-option').on('show.bs.modal', function(e){
        var tax_id = $(e.relatedTarget).data('id')
        var tax_name = $(e.relatedTarget).data('name')
        var rate = $(e.relatedTarget).data('tax_rate')
        // console.log(selected)
        $("#tax-name").val(tax_name)
        $("#rate-name").val(rate)
        var url = "{{url('taxes/')}}"+"/"+tax_id
        $("#tax-edit-form").attr('action', url)
    })

    //  $('#edit-tax-option').on('show.bs.modal', function(e){
    //     var selected = $(e.relatedTarget).data('id')
    //     // var tax_name = 
    //     console.log(tax_name)
        
    // })

    //  $('#edit-tax-option').on('show.bs.modal', function(e){
    //     var selected = $(e.relatedTarget).data('id')
    //     var tax_name = $(e.relatedTarget).data('data-state')
    //     console.log(tax_name)
    //     $("#state-name").val(tax_name)
    // })
 
// $('#edit-tax-option').on('show.bs.modal', function(e){
//         var selected = $(e.relatedTarget).data('id')
//         var tax_name = $(e.relatedTarget).data('country')
//         console.log(tax_name)
//         $("#countre").val(tax_name)
//     })

   
</script>
@stop