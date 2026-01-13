<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.shared.head')
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @include('partials.public.header')

        {{-- Page header --}}
        @isset($header)

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        {{-- Flash messages --}}
        @include('partials.shared.flash-messages')

        {{-- Main layout --}}
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    {{-- Sidebar --}}
                    <aside class="lg:col-span-3">
                        @include('admin.partials.sidebar')
                    </aside>

                    {{-- Content --}}
                    <section class="lg:col-span-9 min-w-0">
                        {{ $slot }}
                    </section>

                </div>
            </div>
        </main>

    </div>

    @stack('scripts')
</body>

</html>