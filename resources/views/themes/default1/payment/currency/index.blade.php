@extends('themes.default1.layouts.master')
@section('title')
Currency
@stop
@section('content-header')
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<h1>
All Currencies
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('settings')}}">Settings</a></li>
         <li class="active">Currency</li>
      </ol>
@stop
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
        <div class="alert alert-success alert-dismissable" style="display: none;">
    <i class="fa  fa-check-circle"></i>
    <span class="success-msg"></span>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

      </div>
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
                <table id="currency-table" styleClass="borderless">
                    

                    <thead>
                        <tr>
                         <th>CurrencyName</th>
                          <th>Currency Code</th>
                          <th>Currency symbol</th>
                          <th>Dashboard Currency</th>
                          <th>Status</th>
                         
                        </tr>
                    </thead>
                     </table>
                

            </div>
        </div>

    </div>

</div>




<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#currency-table').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            ajax: '{!! route('get-currency.datatable') !!}',
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
                {data: 'name', name: 'name'},
                {data: 'code', name: 'code'},
                {data: 'symbol', name: 'symbol'},
                {data: 'dashboard', name: 'dashboard'},
                {data: 'status', name: 'status'},
                
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
                bindChangeStatusEvent();
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
          
          $('#currency-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
     }
     

     $(document).on('click','#bulk_delete',function(){
      var id=[];
      if (confirm("Are you sure you want to delete this?"))
        {
            $('.currency_checkbox:checked').each(function(){
              id.push($(this).val())
            });
            if(id.length >0)
            {
               $.ajax({
                      url:"{!! route('currency-delete') !!}",
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


     function bindChangeStatusEvent() {
        $('.toggle_event_editing').change(function(){
            var current_id = $(this).children('.module_id');
            var current_status = $(this).children('.modules_settings_value');

            $.ajax({
                type: 'POST',
                url: '{{route("change.currency.status")}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    current_id: current_id.val(),
                    current_status: current_status.val()
                }
            }).done(function(result) {
                current_status.val( current_status.val() == 1 ? 0 : 1);
                $(window).scrollTop(0);
                $('.success-msg').html(result);
                $('.alert-success').css('display', 'block');
                setInterval(function() {
                    $('.alert-success').slideUp(3000);
                }, 500);
                location.reload();
            });
        });
    }

  


</script>
@stop