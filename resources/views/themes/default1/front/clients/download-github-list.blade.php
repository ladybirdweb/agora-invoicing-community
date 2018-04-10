
<a onclick="getTables({{$productid}},{{$clientid}},{{$invoiceid}})" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#lists">  <i class='fa fa-download' title=Download></i></a>
<div class="modal fade" id="lists">
    <div class="modal-dialog">
        <div class="modal-content" style="width:700px;">




            <div class="modal-body" >

                <?php
                //All the versions of Uploades Files
                $versions = \App\Model\Product\ProductUpload::where('product_id', $productid)->select('id', 'title', 'description', 'version', 'file', 'created_at')->get();

                //End Date of the Current Product Version
                $endDate = \App\Model\Product\Subscription::select('ends_at')->where('product_id', $productid)->first();
                ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Transcation list</h3>
                            </div>
                            <div class="box-body">

                                <div class="row">

                                    <div class="col-md-12">

                                        <table id="github-version-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">

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
    function getTables($productid, $clientid, $invoiceid){
    $('#github-version-table').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            bDeferRender: true,
            stateSave: true,
            order: [[ 0, "desc" ]],
            ajax: "{!! Url('get-github-versions') !!}/" + $productid + '/' + $clientid + '/' + $invoiceid,
            "oLanguage": {
            "sLengthMenu": "_MENU_ Records per page",
                    "sSearch"    : "Search: ",
                    "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
            columnDefs: [
            {
                
                    render:function(data){
                          var maxLength = 300;
                        $("#github-version-table tbody tr td").each(function(){
                            console.log($(this));
                            var myStr = $(this).text();
                            if($.trim(myStr).length > maxLength){
                                var newStr = myStr.substring(0, maxLength);
                                 $(this).empty().html(newStr);
                                var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                                $(this).append('<span class="more-text">' + removedStr + '</span>');
                                $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
                            }
                          });
                        return data;
                    },
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    order: []
            }
            ],
            columns: [
            {data: 'version', name: 'version'},
            {data: 'name', name: 'name'},
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
    $(document).on('click','#github-version-table tbody tr td .read-more',function(){
        var hari=$(this).siblings(".more-text").text().replace('read more...','');
        console.log(hari)
        $(this).siblings(".more-text").html(hari);
        $(this).siblings(".more-text").contents().unwrap();
        $(this).remove();
    });
    $(function () {
    $('[data-toggle="popover"]').popover()
    })
</script>
