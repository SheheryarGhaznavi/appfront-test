<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;

interface FileServiceInterface
{
    /**
     * Store an uploaded file
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string The path to the stored file
     */
    public function storeUploadedFile(UploadedFile $file, string $directory = 'uploads'): string;

    /**
     * Get the default image path
     *
     * @return string
     */
    public function getDefaultImagePath(): string;
}
