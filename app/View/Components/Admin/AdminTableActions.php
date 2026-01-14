<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class AdminTableActions extends Component
{
    public function __construct(
        public ?string $editUrl = null,
        public ?string $deleteUrl = null,
        public ?string $itemName = null,
        // Textos del modal
        public string $deleteTitle = 'Eliminar',
        public ?string $deleteMessage = null,
        public string $confirmText = 'Eliminar',
        public string $cancelText = 'Cancelar',
        // Control
        public bool $showEdit = true,
        public bool $showDelete = true,
        public bool $disabledDelete = false,
    ) {
    }

    public function render()
    {
        return view('components.admin.admin-table-actions');
    }
}
