<?php

namespace App\Traits\Upload;

use App\Facades\Attach;
use App\FileSystemSettings;
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
                'done' => $handler->getPercentageDone(),
                'status' => true,
            ]);
        } catch (Exception $ex) {
            $response = ['success' => 'false', 'message' => $ex->getMessage()];

            return response()->json(compact('response'), 500);
        }
    }

    /**
     * Saves the file.
     *
     * @param  UploadedFile  $file
     * @return \Illuminate\Http\JsonResponse
     */
    protected function saveFile(UploadedFile $file)
    {
        $fileName = Attach::createFilename($file);
        $fileStoragePath = FileSystemSettings::find(1)->value('local_file_storage_path');

        if (isS3Enabled()) {
            $folder = 'products';
            $filePath = Attach::put($folder, $file, null, true);

            return response()->json([
                'path' => $filePath,
                'name' => $fileName,
            ]);
        }

        // Move the file to the local storage path
        $file->move($fileStoragePath, $fileName);

        return response()->json([
            'path' => $fileStoragePath,
            'name' => $fileName,
        ]);
    }
}
