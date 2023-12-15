<?php

namespace Yajra\DataTables\Jobs;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Auth\Events\Login;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\Creator\WriterFactory;
use OpenSpout\Writer\XLSX\Helper\DateHelper;
use OpenSpout\Writer\XLSX\Writer as XLSXWriter;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DataTableExportJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public string $dataTable = '';

    public array $attributes = [];

    public array $request = [];

    public string $sheetName = '';

    /**
     * @var int|string
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @param  array  $dataTable
     * @param  array  $request
     * @param  int|string  $user
     * @param  string  $sheetName
     */
    public function __construct(array $dataTable, array $request, $user, string $sheetName = 'Sheet1')
    {
        $this->dataTable = $dataTable[0];
        $this->attributes = $dataTable[1];
        $this->request = $request;
        $this->user = $user;
        $this->sheetName = $sheetName;
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     * @throws \OpenSpout\Writer\Exception\WriterNotOpenedException
     * @throws \OpenSpout\Writer\Exception\InvalidSheetNameException
     */
    public function handle()
    {
        if ($this->user) {
            Event::forget(Login::class);
            Auth::loginUsingId($this->user);
        }

        /** @var DataTable $oTable */
        $oTable = resolve($this->dataTable);
        request()->merge($this->request);

        $query = app()->call([$oTable->with($this->attributes), 'query']);

        /** @var \Yajra\DataTables\QueryDataTable $dataTable */
        $dataTable = app()->call([$oTable, 'dataTable'], compact('query'));
        $dataTable->skipPaging();

        $exportType = strtolower(strval(request('exportType')));

        $type = Str::startsWith($exportType, 'csv') ? 'csv' : 'xlsx';
        $filename = $this->batchId.'.'.$type;

        $path = Storage::disk($this->getDisk())->path($filename);

        $writer = WriterFactory::createFromFile($filename);
        $writer->openToFile($path);

        if ($writer instanceof XLSXWriter) {
            $sheet = $writer->getCurrentSheet();

            $sheet->setName(substr($this->sheetName, 0, 31));
        }

        $columns = $this->getExportableColumns($oTable);
        $headers = [];

        $columns->each(function (Column $column) use (&$headers) {
            $headers[] = strip_tags($column->title);
        });

        $writer->addRow(Row::fromValues($headers));

        if ($this->usesLazyMethod()) {
            $chunkSize = intval(config('datatables-export.chunk', 1000));
            $query = $dataTable->getFilteredQuery()->lazy($chunkSize);
        } else {
            $query = $dataTable->getFilteredQuery()->cursor();
        }

        foreach ($query as $row) {
            $cells = [];

            $row = $row instanceof Arrayable ? $row->toArray() : (array) $row;

            if ($this->usesLazyMethod() && is_array($row)) {
                $row = Arr::dot($row);
            }

            $defaultDateFormat = strval(config('datatables-export.default_date_format', 'yyyy-mm-dd'));

            $columns->map(function (Column $column) use ($row, &$cells, $defaultDateFormat) {
                $property = $column->data;

                /* Handles orthogonal data */
                if (is_array($property)) {
                    $property = $property['_'] ?? $column->name;
                }

                /** @var array|bool|int|string|null|DateTimeInterface $value */
                $value = $row[$property] ?? '';

                if (is_array($value)) {
                    $value = json_encode($value);
                }

                switch (true) {
                    case $this->wantsText($column):
                        $cellValue = strval($value);
                        $format = $column->exportFormat ?? '@';
                        break;
                    case $this->wantsDateFormat($column):
                        $cellValue = $value ? DateHelper::toExcel(Carbon::parse(strval($value))) : '';
                        $format = $column->exportFormat ?? $defaultDateFormat;
                        break;
                    case $this->wantsNumeric($column):
                        $cellValue = floatval($value);
                        $format = $column->exportFormat;
                        break;
                    case $value instanceof DateTimeInterface:
                        $cellValue = $value;
                        $format = $column->exportFormat ?? $defaultDateFormat;
                        break;
                    default:
                        $cellValue = $this->isNumeric($value) ? floatval($value) : $value;
                        $format = $column->exportFormat ?? NumberFormat::FORMAT_GENERAL;
                }

                $cells[] = Cell::fromValue($cellValue, (new Style)->setFormat($format));
            });

            $writer->addRow(new Row($cells));
        }

        $writer->close();

        if ($this->getS3Disk()) {
            Storage::disk($this->getS3Disk())->putFileAs('', (new File($path)), $filename);
        }

        if (request('emailTo')) {
            $data = ['email' => urldecode(strval(request('emailTo'))), 'path' => $path];
            $this->sendResults($data);
        }
    }

    /**
     * @return string
     */
    protected function getDisk(): string
    {
        return strval(config('datatables-export.disk', 'local'));
    }

    /**
     * @param  \Yajra\DataTables\Services\DataTable  $dataTable
     * @return \Illuminate\Support\Collection<array-key, Column>
     */
    protected function getExportableColumns(DataTable $dataTable): Collection
    {
        $columns = $dataTable->html()->getColumns();

        return $columns->filter(fn (Column $column) => $column->exportable);
    }

    /**
     * @return bool
     */
    protected function usesLazyMethod(): bool
    {
        return config('datatables-export.method', 'lazy') === 'lazy';
    }

    /**
     * @param  \Yajra\DataTables\Html\Column  $column
     * @return bool
     */
    protected function wantsText(Column $column): bool
    {
        if (! isset($column['exportFormat'])) {
            return false;
        }

        return in_array($column['exportFormat'], (array) config('datatables-export.text_formats', ['@']));
    }

    /**
     * @param  \Yajra\DataTables\Html\Column  $column
     * @return bool
     */
    protected function wantsDateFormat(Column $column): bool
    {
        if (! isset($column['exportFormat'])) {
            return false;
        }

        /** @var array $formats */
        $formats = config('datatables-export.date_formats', []);

        return in_array($column['exportFormat'], $formats);
    }

    /**
     * @param  \Yajra\DataTables\Html\Column  $column
     * @return bool
     */
    protected function wantsNumeric(Column $column): bool
    {
        return Str::contains($column->exportFormat, ['0', '#']);
    }

    /**
     * @param  int|bool|string|null  $value
     * @return bool
     */
    protected function isNumeric($value): bool
    {
        // Skip numeric style if value has leading zeroes.
        if (Str::startsWith(strval($value), '0')) {
            return false;
        }

        return is_numeric($value);
    }

    /**
     * @return string
     */
    protected function getS3Disk(): string
    {
        return strval(config('datatables-export.s3_disk', ''));
    }

    /**
     * @param  array  $data
     * @return void
     */
    public function sendResults(array $data): void
    {
        Mail::send('datatables-export::export-email', $data, function ($message) use ($data) {
            $message->attach($data['path']);
            $message->to($data['email'])
                    ->subject('Export Report');
            $message->from(config('datatables-export.mail_from'));
        });
    }
}
