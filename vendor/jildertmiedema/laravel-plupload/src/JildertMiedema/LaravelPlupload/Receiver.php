<?php

namespace JildertMiedema\LaravelPlupload;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class Receiver
{
    private $maxFileAge = 600; //600 secondes

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPath()
    {
        $path = storage_path().'/plupload';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    public function receiveSingle($name, Closure $handler)
    {
        if ($this->request->file($name)) {
            return $handler($this->request->file($name));
        }

        return false;
    }

    private function appendData($filePathPartial, UploadedFile $file)
    {
        if (!$out = @fopen($filePathPartial, 'ab')) {
            throw new PluploadException('Failed to open output stream.', 102);
        }

        if (!$in = @fopen($file->getPathname(), 'rb')) {
            throw new PluploadException('Failed to open input stream', 101);
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);
    }

    public function receiveChunks($name, Closure $handler)
    {
        $result = false;

        if ($this->request->file($name)) {
            $file = $this->request->file($name);
            $chunk = (int) $this->request->get('chunk', false);
            $chunks = (int) $this->request->get('chunks', false);
            $originalName = $this->request->get('name');

            $filePath = $this->getPath().'/'.$originalName.'.part';
            $this->removeOldData($filePath);

            $this->appendData($filePath, $file);

            if ($chunk == $chunks - 1) {
                $file = new UploadedFile($filePath, $originalName, 'blob', sizeof($filePath), UPLOAD_ERR_OK, true);

                $result = $handler($file);

                @unlink($filePath);
            }
        }

        return $result;
    }

    public function removeOldData($filePath)
    {
        if (file_exists($filePath) && filemtime($filePath) < time() - $this->maxFileAge) {
            @unlink($filePath);
        }
    }

    public function hasChunks()
    {
        return (bool) $this->request->get('chunks', false);
    }

    public function receive($name, Closure $handler)
    {
        $response = [];
        $response['jsonrpc'] = '2.0';

        if ($this->hasChunks()) {
            $result = $this->receiveChunks($name, $handler);
        } else {
            $result = $this->receiveSingle($name, $handler);
        }

        $response['result'] = $result;

        return $response;
    }
}
