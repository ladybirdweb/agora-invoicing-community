<a onclick="getTable({{$productid}},{{$clientid}},{{$invoiceid}})" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#list">  <i class='fa fa-download' style="color:white;" title=Download></i></a>
<div class="modal fade" id="list">
    <div class="modal-dialog">
        <div class="modal-content" style="width:700px;">
             <div class="modal-body" >
               <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="hideModals()"><span aria-hidden="true">&times;</span></button>
                <?php
                //Name of the product
                $products = \App\Model\Product\Product::where('id', $productid)->pluck( 'name')->toArray();
                //End Date of the Current Product Version
                $endDate = \App\Model\Product\Subscription::select('ends_at')->where('product_id', $productid)->first();
                ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Product Versions</h3>

                            </div>
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-md-12">

                                        <table id="version-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

                                            <thead><tr>

                                                    <th>Version</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>File</th>

                                                </tr></thead>

                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
                <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
                <script type="text/javascript">
                     function hideModals(){
                        $('#list').modal('hide');
                        }
    function getTable($productid, $clientid, $invoiceid){
    $('#version-table').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            stateSave: true,
            order: [[ 0, "desc" ]],
            ajax: "{!! Url('get-versions') !!}/" + $productid + '/' + $clientid + '/' + $invoiceid,
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
            {data: 'version', name: 'version'},
            {data: 'title', name: 'title'},
            {data: 'description', name: 'description'},
            {data: 'file', name: 'file'},
            ],
            "fnDrawCallback": function(oSettings) {
            $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
            $('.loader').css('display', 'block');
            },
    });
    }
                </script>












            </div>


        </div>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<!-- /.modal -->


<script>
    $(function () {
    $('[data-toggle="popover"]').popover()
    })
</script>
