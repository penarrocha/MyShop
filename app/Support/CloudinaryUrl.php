<?php

namespace App\Support;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryUrl
{
    public static function url(string $publicId, ?int $width = null, ?int $height = null): string
    {
        $transformations = [];

        if ($width) {
            $transformations[] = 'w_' . $width;
        }

        if ($height) {
            $transformations[] = 'h_' . $height;
        }

        // Opciones por defecto
        $transformations[] = env('CLOUDINARY_IMAGE_TRANSFORM', 'c_limit,f_auto,q_auto');

        return Cloudinary::image($publicId)
            ->addTransformation(implode(',', $transformations))
            ->toUrl();
    }
}
