<?php

namespace App\Traits\Upload;

use App\Model\Common\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

trait ChunkUpload
{
    public function uploadFile(Request $request)
    {
        try {
            $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }

            $save = $receiver->receive();
            // check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                // save the file and return any response you need, current example uses `move` function. If you are
                // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
                return $this->saveFile($save->getFile());
            }
            // we are in chunk mode, lets send the current progress
            /** @var AbstractHandler $handler */
            $handler = $save->handler();

            return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true,
        ]);
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    /**
     * Saves the file.
     *
     * @param UploadedFile $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function saveFile(UploadedFile $file)
    {
        $fileName = $this->createFilename($file);
        // Group files by mime type
        //$mime = str_replace('/', '-', $file->getMimeType());
        // Group files by the date (week
        // $dateFolder = date("Y-m-W");
        // Build the file path
        //   $filePath = "upload/{$mime}/{$dateFolder}/";
        $filePath = Setting::find(1)->value('file_storage');
        $finalPath = Setting::find(1)->value('file_storage');
        // move the file name
        $file->move($finalPath, $fileName);

        return response()->json([
            'path' => $filePath,
            'name' => $fileName,
        ]);
    }

    /**
     * Create unique filename for uploaded file.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace('.'.$extension, '', $file->getClientOriginalName()); // Filename without extension
        // Add timestamp hash to name of the file
        $filename .= '_'.md5(time()).'.'.$extension;

        return $filename;
    }
}
