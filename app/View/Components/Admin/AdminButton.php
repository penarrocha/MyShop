<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class AdminButton extends Component
{
    public string $type;
    public string $title;

    public function __construct(string $type)
    {
        $this->type = $type;

        $this->title = match ($type) {
            'edit'   => 'Editar',
            'delete' => 'Eliminar',
            default  => '',
        };
    }

    public function render()
    {
        return view('components.admin.admin-button');
    }
}
