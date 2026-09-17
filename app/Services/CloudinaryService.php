<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    /**
     * Upload an image to Cloudinary.
     */
    public function upload(UploadedFile $file, string $folder): array
    {
        $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
            // This controls the folder shown in
            // Cloudinary Media Library.
            'asset_folder' => $folder,

            // Use the original filename.
            'use_filename' => true,

            // Prevent duplicate filenames from overwriting each other.
            'unique_filename' => true,

            // Use the original filename as the display name.
            'use_filename_as_display_name' => true,

            // Automatically detect the resource type.
            'resource_type' => 'image',
        ]);

        return [
            'url' => $upload['secure_url'],
            'public_id' => $upload['public_id'],
        ];
    }

    /**
     * Delete an image from Cloudinary.
     */
    public function delete(?string $publicId): void
    {
        if (!$publicId) {
            return;
        }

        try {
            Cloudinary::uploadApi()->destroy($publicId, [
                'invalidate' => true,
                'resource_type' => 'image',
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
