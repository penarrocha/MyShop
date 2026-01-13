<?php

namespace App\View\Composers;

use Illuminate\View\View;

class PublicLayoutComposer
{
    public function compose(View $view): void
    {
        $data = $view->getData();

        $view->with('breadcrumbName', $data['breadcrumb']['name'] ?? null);
        $view->with('breadcrumbParams', $data['breadcrumb']['params'] ?? []);

        $view->with('cartUrls', [
            'index' => route('cart.index'),
            'state' => route('cart.state'),
            'ajaxStore' => route('cart.items.store'),
            'ajaxDestroy' => route('cart.items.destroy', ['id' => '__ID__']),
        ]);
    }
}
