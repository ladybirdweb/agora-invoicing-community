@extends('themes.default1.layouts.master')
@section('title')
Email Logs
@stop
@section('content-header')
<h1>
Email Log
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
        <li class="active">Email Log</li>
      </ol>
@stop
@section('content')


<style type="text/css">
    table { table-layout:fixed; word-break:break-all; word-wrap:break-word; }

    .more-text{
     display:none;
}
</style>
    <div class="box box-primary">
 <div class="box-header">
       @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
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
        <div id="response"></div>
         

 </div>

<div class="box-body">

  <div class="row">
          <div class="col-md-12">

	
         
                           
             <table id="email-table" class="table display" cellspacing="0"  styleClass="borderless">
                     <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash">&nbsp;&nbsp;</i> Delete Selected</button><br /><br />
                     
                    <thead><tr>

                            <th class="no-sort" style="width:1px"><input type="checkbox" name="select_all" onchange="checking(this)"></th>

                            <th>Date</th>
                            <th>From</th>
                             <th>To</th>   
                               <th>Subject</th>      
                           
                             <th>Status</th>
                               </tr></thead>

                   </table>
            
        

   
</div>
</div>
</div>
</div>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<!--  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script> -->
<script type="text/javascript">
  
        $('#email-table').DataTable({
             processing: true,
             serverSide: true,
             stateSave: true,
              order: [[ 0, "desc" ]],
            ajax: '{!! route('get-email') !!}',
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
                {data: 'date', name: 'date'},
                {data: 'from', name: 'from'},
                {data: 'to', name: 'to'},
                 {data: 'subject', name: 'subject'},
                
                 {data: 'status', name: 'status'},
                
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>
<!-- <script>
    $(document).on('click','#email-table tbody tr td .read-more',function(){
        var text=$(this).siblings(".more-text").text().replace('read more...','');
        console.log(text)
        $(this).siblings(".more-text").html(text);
        $(this).siblings(".more-text").contents().unwrap();
        $(this).remove();
    });
    $(function () {
    $('[data-toggle="popover"]').popover()
    })
</script> -->
    <script>

       function checking(e){
              $('#email-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
         }
         

         $(document).on('click','#bulk_delete',function(){
          var id=[];
          if (confirm("Are you sure you want to delete this?"))
            {
                $('.email:checked').each(function(){
                  id.push($(this).val())
                });
                if(id.length >0)
                { 
                   $.ajax({
                          url:"{!! route('email-delete') !!}",
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














