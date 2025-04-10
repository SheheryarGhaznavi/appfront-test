<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;

class FileService implements FileServiceInterface
{
    /**
     * Store an uploaded file
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string The path to the stored file
     */
    public function storeUploadedFile(UploadedFile $file, string $directory = 'uploads'): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($directory), $filename);
        
        return $directory . '/' . $filename;
    }

    /**
     * Get the default image path
     *
     * @return string
     */
    public function getDefaultImagePath(): string
    {
        return config('products.image.default', 'product-placeholder.jpg');
    }
}
