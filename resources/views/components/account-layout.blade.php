<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="mb-4">
                    <div class="text-sm text-gray-500">Conectado como</div>
                    <div class="font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                    <div class="text-sm text-gray-600">{{ auth()->user()->email }}</div>
                </div>

                @php
                $link = 'block rounded-md px-3 py-2 text-sm font-semibold transition';
                $active = 'bg-primary-50 text-primary-700';
                $idle = 'text-gray-700 hover:bg-gray-50';
                @endphp

                <nav class="space-y-1">
                    <a href="{{ route('account.index') }}"
                        class="{{ $link }} {{ request()->routeIs('account.index') ? $active : $idle }}">
                        Resumen
                    </a>

                    <a href="{{ route('account.profile.edit') }}"
                        class="{{ $link }} {{ request()->routeIs('account.profile.*') ? $active : $idle }}">
                        Datos personales
                    </a>

                    <a href="{{ route('account.addresses.index') }}"
                        class="{{ $link }} {{ request()->routeIs('account.addresses.*') ? $active : $idle }}">
                        Direcciones
                    </a>

                    <a href="{{ route('account.orders.index') }}"
                        class="{{ $link }} {{ request()->routeIs('account.orders.*') ? $active : $idle }}">
                        Mis pedidos
                    </a>

                    <a href="{{ route('account.wishlist.index') }}"
                        class="{{ $link }} {{ request()->routeIs('account.wishlist.*') ? $active : $idle }}">
                        Mis favoritos
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
        </aside>

        {{-- Content --}}
        <section class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow p-6">
                {{ $slot }}
            </div>
        </section>
    </div>
</div>