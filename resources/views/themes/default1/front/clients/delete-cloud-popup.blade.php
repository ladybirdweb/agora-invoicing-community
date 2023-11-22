
<form action="{{url('delete/domain/'.$orderNumber.'/1')}}" method="GET" onsubmit="return ConfirmDelete()">
    <button class="btn btn-light-scale-2 btn-sm text-dark open-deleteTenantDialog" data-toggle="modal"><i class="fa fa-trash" data-toggle="tooltip" title="Click here to delete the cloud"></i>&nbsp;</button>
</form>
<script type="text/javascript">
    function ConfirmDelete() {
        return confirm("Are you sure you want to delete your cloud instance? This would mean it will delete all traces of your cloud instance starting from Domain, Database, s3 Bucket, Cron etc. And no backups will be provided, Please proceed with caution.");
    }
</script>

<!-- creating this modal balde to enhance a lot more once we go live -->