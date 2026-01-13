<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.shared.head')
</head>

<body class="bg-gray-50" data-cart-urls='@json($cartUrls ?? [])'>
    {{-- Header --}}
    @include('partials.public.header')

    {{-- Breadcrumbs (si los usas) --}}
    @include('partials.public.breadcrumbs')

    {{-- Flash --}}
    @include('partials.shared.flash-messages')

    {{-- Encabezado tipo Breeze (opcional) --}}
    @isset($header)
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            {{ $header }}
        </div>
    </header>
    @endisset

    <main class="min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-8">
            {{ $slot }}
        </div>
    </main>

    {{-- Footer --}}
    @include('partials.public.footer')

    @stack('scripts')
</body>

</html>