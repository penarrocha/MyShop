@php
$link = 'block rounded-md px-3 py-2 text-sm font-semibold transition';
$active = 'bg-primary-50 text-primary-700';
$idle = 'text-gray-700 hover:bg-gray-50';
@endphp

<div class="bg-white rounded-lg shadow p-4">
    <div class="mb-4">
        <div class="text-sm text-gray-500">Administración</div>
        <div class="font-semibold text-gray-900">{{ auth()->user()->name }}</div>
        <div class="text-sm text-gray-600">{{ auth()->user()->email }}</div>
    </div>

    <nav class="space-y-1">
        <a href="{{ route('admin.products.index') }}"
            class="{{ $link }} {{ request()->routeIs('admin.products.*') ? $active : $idle }}">
            Productos
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="{{ $link }} {{ request()->routeIs('admin.categories.*') ? $active : $idle }}">
            Categorías
        </a>

        <a href="{{ route('admin.offers.index') }}"
            class="{{ $link }} {{ request()->routeIs('admin.offers.*')  ? $active : $idle }}">
            Ofertas
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'customer']) }}"
            class="{{ $link }} {{ request()->routeIs('admin.users.*') && request('role') === 'customer' ? $active : $idle }}">
            Clientes
        </a>

        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
            class="{{ $link }} {{ request()->routeIs('admin.users.*') && request('role') === 'admin' ? $active : $idle }}">
            Administradores
        </a>

    </nav>

    <div class="mt-6 border-t pt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Cerrar sesión
            </button>
        </form>
    </div>
</div>