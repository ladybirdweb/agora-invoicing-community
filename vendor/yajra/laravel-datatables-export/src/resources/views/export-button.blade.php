<div class="d-flex align-items-center" x-data>
    <form class="mr-2"
          x-on:submit.prevent="
                $refs.exportBtn.disabled = true;
                var oTable = LaravelDataTables['{{ $tableId }}'];
                var baseUrl = oTable.ajax.url() === '' ? window.location.toString() : oTable.ajax.url();

                var params = new URLSearchParams({
                    action: 'exportQueue',
                    exportType: '{{$fileType}}',
                    sheetName: '{{$sheetName}}',
                    emailTo: '{{urlencode($emailTo)}}',
                });

                $.get(baseUrl + '?' + params.toString() + '&' + $.param(oTable.ajax.params())).then(function(exportId) {
                    $wire.export(exportId)
                }).catch(function(error) {
                    $wire.exportFinished = true;
                    $wire.exporting = false;
                    $wire.exportFailed = true;
                });
              "
    >
        <button type="submit"
                x-ref="exportBtn"
                :disabled="$wire.exporting"
                class="{{ $class }}"
        >Export
        </button>
    </form>

    @if($exporting && $emailTo)
        <div class="d-inline">Export will be emailed to {{ $emailTo }}.</div>
    @endif

    @if($exporting && !$exportFinished)
        <div class="d-inline" wire:poll="updateExportProgress">Exporting...please wait.</div>
    @endif

    @if($exportFinished && !$exportFailed && !$autoDownload)
        <span>Done. Download file <a href="#" class="text-primary" wire:click.prevent="downloadExport">here</a></span>
    @endif

    @if($exportFinished && !$exportFailed && $autoDownload && $downloaded)
        <span>Done. File has been downloaded.</span>
    @endif

    @if($exportFailed)
        <span>Export failed, please try again later.</span>
    @endif
</div>
