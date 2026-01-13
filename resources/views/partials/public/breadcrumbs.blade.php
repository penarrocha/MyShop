@php
// Si la vista trae breadcrumb manual, lo usamos. Si no, tomamos la ruta actual.
$name = (isset($breadcrumb['name']) && $breadcrumb['name'] !== null) ? request()->route()?->getName() : false;
$params = $breadcrumb['params'] ?? [];
@endphp

@if ($name && Breadcrumbs::exists($name))
<div class="container mx-auto px-6 pt-6 pb-0 mb-0">
    {{ Breadcrumbs::render($name, ...$params) }}
</div>
@endif