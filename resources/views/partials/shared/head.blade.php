<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stack('meta')
<title>@yield('title', config('app.name'))</title>

<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="{{ asset('assets/cart.js') }}" defer></script>
@stack('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>