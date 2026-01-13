@php
// $breadcrumbs viene del paquete (colección de crumbs)
$items = [];
$position = 1;

foreach ($breadcrumbs as $crumb) {
$items[] = [
'@type' => 'ListItem',
'position' => $position++,
'name' => $crumb->title,
// Schema.org recomienda 'item' con URL. Para el último, puedes usar la URL actual.
'item' => $crumb->url ?: url()->current(),
];
}

$jsonLd = [
'@context' => 'https://schema.org',
'@type' => 'BreadcrumbList',
'itemListElement' => $items,
];
@endphp

{{-- Visible breadcrumb --}}
<nav class="mb-6 text-sm text-gray-500" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2">
        @foreach ($breadcrumbs as $crumb)
        @php $isLast = $loop->last; @endphp

        @if (!$loop->first)
        <li class="text-gray-400">/</li>
        @endif

        <li class="flex items-center">
            @if (!$isLast && $crumb->url)
            <a href="{{ $crumb->url }}" class="hover:text-gray-700 transition">
                {{ $crumb->title }}
            </a>
            @else
            <span class="text-gray-900 font-medium">
                {{ $crumb->title }}
            </span>
            @endif
        </li>
        @endforeach
    </ol>
</nav>

{{-- JSON-LD BreadcrumbList --}}
<script type="application/ld+json">
    {
        !!json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!
    }
</script>