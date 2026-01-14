<?php

namespace App\View\Components;

use App\Support\CloudinaryUrl;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ImagePicker extends Component
{
    public string $name;          // input file name
    public ?string $publicId;     // public_id (raw) o null

    public string $initialUrl;    // url de la imagen actual (cloudinary o default)
    public string $defaultUrl;    // url de la imagen por defecto

    public string $alt;
    public string $class;
    public string $accept;
    public string $id;

    public string $buttonTextClear;
    public string $buttonTextRemove;

    public string $removeName = 'remove_image'; // hidden input name
    public bool $showRemove;    // mostrar botón papelera
    public bool $hasRealImage;


    public function __construct(
        string $name,
        ?string $publicId = null,
        string $alt = '',
        string $class = 'object-cover rounded border',
        ?string $accept = null,
        ?string $id = null,
        string $buttonTextClear = 'Quitar selección',
        string $buttonTextRemove = 'Desvincular imagen',
        string $removeName = 'remove_image',
        bool $showRemove = true
    ) {
        $this->name = $name;

        $publicId = is_string($publicId) ? trim($publicId) : null;
        $this->publicId = ($publicId !== '') ? $publicId : null;
        $this->hasRealImage = !empty($this->publicId);

        $this->alt = $alt;
        $this->class = $class;
        $this->accept = $accept ?? config('app.image_accept');
        $this->id = $id ?: 'image-picker-' . Str::uuid();

        $this->buttonTextClear = $buttonTextClear;
        $this->buttonTextRemove = $buttonTextRemove;

        $this->removeName = $removeName;
        $this->showRemove = $showRemove;

        $this->defaultUrl = $this->buildUrl(env('APP_NO_IMAGE'));
        $this->initialUrl = $this->buildUrl($this->publicId);
    }

    private function buildUrl(?string $publicId): string
    {
        $publicId = ($publicId && trim($publicId) !== '')
            ? $publicId
            : config('app.no_image');

        return CloudinaryUrl::url($publicId);
    }

    public function render(): View
    {
        return view('components.image-picker');
    }
}
