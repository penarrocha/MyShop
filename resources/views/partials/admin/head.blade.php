@include('partials.shared.meta', ['title' => isset($title) ? $title . ' | ' . config('app.name') : config('app.name')])

<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])

@stack('meta')
@stack('styles')