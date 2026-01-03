<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageUploadTrait
{
    /**
     * Upload image and return the path
     */
    public function uploadImage(UploadedFile $image, string $directory = 'images', ?string $oldImagePath = null): string
    {
        // Delete old image if exists
        if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
            Storage::disk('public')->delete($oldImagePath);
        }

        // Generate unique filename
        $filename = time().'_'.Str::random(10).'.'.$image->getClientOriginalExtension();

        // Store the image
        $path = $image->storeAs($directory, $filename, 'public');

        return $path;
    }

    /**
     * Delete image from storage
     */
    public function deleteImage(string $imagePath): bool
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->delete($imagePath);
        }

        return false;
    }

    /**
     * Get image URL
     */
    public function getImageUrl(?string $imagePath = null): string
    {
        if (! $imagePath) {
            return asset('images/default-avatar.svg');
        }

        if (Storage::disk('public')->exists($imagePath)) {
            return url('storage/'.$imagePath);
        }

        return asset('images/default-avatar.svg');
    }

    /**
     * Validate image file
     */
    public function validateImage(UploadedFile $image): array
    {
        $errors = [];

        // Check file size (max 2MB)
        if ($image->getSize() > 2048 * 1024) {
            $errors[] = 'Image size must be less than 2MB';
        }

        // Check file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower($image->getClientOriginalExtension());

        if (! in_array($extension, $allowedTypes)) {
            $errors[] = 'Image must be a valid image file (jpg, jpeg, png, gif)';
        }

        // Check image dimensions (optional)
        $imageInfo = getimagesize($image->getPathname());
        if ($imageInfo) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];

            // Maximum dimensions
            if ($width > 2000 || $height > 2000) {
                $errors[] = 'Image dimensions must be less than 2000x2000 pixels';
            }
        }

        return $errors;
    }
}
