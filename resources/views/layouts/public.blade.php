<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.shared.head')
</head>

<body class="bg-gray-50" data-cart-urls='@json($cartUrls)'>
    {{-- Header --}}
    @include('partials.public.header')

    {{-- BREADCRUMBS --}}
    @include('partials.public.breadcrumbs')

    {{-- Notificaciones Flash --}}
    @include('partials.shared.flash-messages')

    {{-- Contenido principal --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer  --}}
    @include('partials.public.footer')

    {{-- scripts --}}
    @stack('scripts')
</body>

</html>