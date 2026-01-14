<?php

namespace App\View\Components;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Support\CloudinaryUrl;

class CloudinaryImage extends Component
{
    public string $publicId;
    public string $alt;
    public string $class;
    public ?int $width;
    public ?int $height;

    public function __construct(string $publicId, string $alt = '', string $class = '', ?int $width = null, ?int $height = null)
    {
        $this->publicId = $publicId;
        $this->alt = $alt;
        $this->class = $class;
        $this->width = $width;
        $this->height = $height;
    }

    public function url(): string
    {
        return CloudinaryUrl::url($this->publicId, $this->width, $this->height);
    }

    public function urlOld(): string
    {
        $transformations = [];

        if ($this->width) {
            $transformations[] = 'w_' . $this->width;
        }

        if ($this->height) {
            $transformations[] = 'h_' . $this->height;
        }

        // Opciones por defecto
        $transformations[] = env('CLOUDINARY_IMAGE_TRANSFORM', 'c_limit,f_auto,q_auto');

        return Cloudinary::image($this->publicId)
            ->addTransformation(implode(',', $transformations))
            ->toUrl();
    }

    public function render(): View
    {
        return view('components.cloudinary-image');
    }
}
