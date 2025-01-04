<!-- Button to trigger modal -->
<a onclick="getTable({{$productid}}, {{$clientid}}, {{$invoiceid}})" class="btn btn-light-scale-2 btn-sm text-dark" data-toggle="modal" data-target="#list">
    <i class='fa fa-download' data-toggle="tooltip" title="Click here to download"></i>&nbsp;
</a>

<style>
    .more-text {
        display: none;
    }
    .modal-body {
        overflow-y: auto; /* Enable vertical scrolling */
        overflow-x: auto; /* Enable horizontal scrolling */
    }
    .table-responsive {
        overflow-y: auto; /* Enable vertical scrolling */
        overflow-x: auto; /* Enable horizontal scrolling */
    }
    /* New styles for table cells */
    #version-table td {
        word-wrap: break-word; /* Allows long words to break */
        white-space: normal;   /* Ensures text wraps onto the next line */
        max-width: 200px;     /* Set a maximum width for cells */
        overflow: hidden;      /* Hides overflow content */
        text-overflow: ellipsis; /* Adds ellipsis for overflowed text */
    }
    @media (max-width: 600px) {
        #version-table {
            display: block; /* Change display for mobile */
        }
        #version-table tr {
            display: flex;
            flex-direction: column; /* Stack rows vertically */
        }
    }
</style>

<!-- Modal Structure -->
<div class="modal fade" id="list" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Versions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php
                    try{
                        //Name of the product
                        $products = \App\Model\Product\Product::where('id', $productid)->pluck( 'name')->toArray();
                        //End Date of the Current Product Version
                        $endDate = \App\Model\Product\Subscription::select('ends_at')->where('product_id', $productid)->first();
                    }catch (Exception $e){
                    }
                ?>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="version-table" class="table display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Version</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>File</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>


<script type="text/javascript">
    function hideModals() {
        $('#list').modal('hide');
    }

    function readmore() {
        var maxLength = 300;
        $("#version-table tbody tr td").each(function() {
            var myStr = $(this).text();
            if ($.trim(myStr).length > maxLength) {
                var newStr = myStr.substring(0, maxLength);
                $(this).empty().html(newStr);
                var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                $(this).append('<span class="more-text">' + removedStr + '</span>');
                $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
            }
        });
    }

    function getTable(productid, clientid, invoiceid) {
        $('#version-table').DataTable({
            destroy: true,
            responsive: true, // Enable responsive mode
            "initComplete": function(settings, json) {
                readmore();
            },
            processing: true,
            serverSide: true,
            order: [[0, "desc"]],
            ajax: "{!! Url('get-versions') !!}/" + productid + '/' + clientid + '/' + invoiceid,
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch": "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50px; display: block; position: fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
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
                readmore();
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    }

    $(document).on('click', '#version-table tbody tr td .read-more', function() {
        var text = $(this).siblings(".more-text").text().replace('read more...', '');
        $(this).siblings(".more-text").html(text);
        $(this).siblings(".more-text").contents().unwrap();
        $(this).remove();
    });
</script>