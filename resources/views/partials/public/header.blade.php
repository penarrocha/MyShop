<!-- Header con navegación -->
<header class="bg-white shadow-lg relative">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('welcome') }}" class="text-2xl font-bold text-primary-600">{{ config('app.name') }}</a>
            </div>

            <!-- Navegación -->
            @include('partials.public.navigation')

            <!-- Carrito -->
            <div class="relative flex items-center" id="iplCart">

                {{-- Botón carrito --}}
                <button type="button" id="iplCartButton" class="inline-flex items-center gap-2 text-gray-700 hover:text-primary-600 transition" aria-haspopup="dialog" aria-expanded="false">
                    <span id="cartIcon" class="relative inline-flex items-center">🛒<span id="iplCartBadge" class="ml-1 inline-flex items-center justify-center rounded-full bg-primary-600 px-2 py-0.5 text-xs font-semibold text-white transition-transform">{{ $cartQuantity }}</span></span>
                    <span class="hidden sm:inline">Carrito</span>
                </button>

                {{-- Dropdown carrito --}}
                <div id="iplCartDropdown" class="absolute right-0 top-full mt-3 w-80 origin-top-right rounded-2xl border border-gray-200 bg-white shadow-xl ring-1 ring-black/5 hidden z-50" role="dialog" aria-label="Carrito de la compra">
                    {{-- Header --}}
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold text-gray-900">
                                Tu carrito
                            </div>
                            <button type="button" id="iplCartClose" class="text-gray-500 hover:text-gray-700" aria-label="Cerrar carrito">✕</button>
                        </div>

                        <div class="mt-1 text-sm text-gray-600">
                            <span id="iplCartSummary">{{ $cartQuantity > 0 ? $cartQuantity . ' artículo(s)' : 'Tu carrito está vacío.' }}</span>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div id="iplCartItems" class="max-h-96 overflow-auto p-4 space-y-3">
                        {{-- Se rellena dinámicamente por cart.js --}}
                        @if ($cartQuantity === 0)
                        <div class="text-sm text-gray-600">
                            Nada por aquí
                        </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="p-4 border-t border-gray-200 flex items-center justify-between{{ ($cartQuantity ?? 0) === 0 ? ' hidden' : '' }}" id="iplCartFooter">
                        <a href="{{ route('cart.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Ver carrito</a>
                        <div class="text-sm font-semibold text-gray-900">
                            Total: <span id="iplCartTotal">—</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</header>