<?php

namespace App\Helper;

use App\FileSystemSettings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class AttachmentHelper
{
    public function put($directory, $contents, $disk = null, $uniqueFilename = null, $visibility = 'private')
    {
        $adapter = $this->getStorageAdapter($disk);

        if (isS3Enabled()) {
            $visibility = 'private';
        }

        $fileUniqueName = $uniqueFilename
            ? $this->createFilename($contents)
            : $contents->getClientOriginalName();

        $sanitizedFileName = Str::ascii($fileUniqueName);

        $fileUniqueName = $sanitizedFileName ?: $fileUniqueName;

        return $adapter->putFileAs($directory, $contents, $fileUniqueName, ['visibility' => $visibility]);
    }

    public function delete($path, $disk = null): bool
    {
        return $this->getStorageAdapter($disk)->delete($path);
    }

    public function deleteDirectory($path, $disk = null): bool
    {
        return $this->getStorageAdapter($disk)->deleteDirectory($path);
    }

    public function download($path, $disk = null)
    {
        $adapter = $this->getStorageAdapter($disk);

        $filename = Str::ascii(basename($path)) ?: basename($path);

        if (isS3Enabled()) {
            return $adapter->temporaryUrl($path, now()->addHour());
        }

        return $adapter->download($path, $filename);
    }

    private function getStorageAdapter($disk = null): \Illuminate\Filesystem\FilesystemAdapter
    {
        $disk = $disk ?: FileSystemSettings::value('disk');

        if (! $disk) {
            throw new \Exception(trans('lang.attach_helper_no_default_disk'));
        }

        return \Storage::disk($disk);
    }

    /**
     * Create unique filename for uploaded file.
     *
     * @param  UploadedFile  $file
     * @return string
     */
    public function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Filename without extension
        // Add timestamp hash to name of the file
        $filename .= '_'.md5(time()).'.'.$extension;

        return $filename;
    }

    public function getUrlPath($path, $disk = null)
    {
        $adapter = $this->getStorageAdapter($disk);

        if (isS3Enabled()) {
            return $adapter->temporaryUrl($path, now()->addWeek());
        }

        return asset($adapter->url($path));
    }

    public function exists($path, $disk = null)
    {
        $adapter = $this->getStorageAdapter($disk);

        return $adapter->exists($path);
    }

    public function readStream($path, $disk = null)
    {
        $adapter = $this->getStorageAdapter($disk);

        return $adapter->readStream($path);
    }
}
