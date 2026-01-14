<?php

namespace App\View\Components;

use App\Support\CloudinaryUrl;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ProductImage extends Component
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

        return CloudinaryUrl::url($this->publicId);
    }

    public function render(): View
    {
        return view('components.product-image');
    }
}
