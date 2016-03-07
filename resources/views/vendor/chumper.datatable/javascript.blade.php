<!--<script type="text/javascript" src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>-->

<!--<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>-->

<!--<link href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />-->
<link href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />


<script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>


<script type="text/javascript">
        jQuery(document).ready(function(){
// dynamic table
oTable = jQuery('#{!! $id !!}').dataTable(
{!! $options !!}
);
});
</script>
