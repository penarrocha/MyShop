<div class="bg-white rounded-lg shadow-lg overflow-hidden product-card {{ $class }} relative group flex flex-col h-full{{ $product->offer ? ' ring-2 ring-orange-400' : '' }}" data-product-card>

    {{-- Enlace overlay: toda la tarjeta clicable --}}
    <a href="{{ route('products.show', $product) }}" class="absolute inset-0 z-10" aria-label="Ver {{ $product->name }}"></a>

    {{-- Badge de oferta destacado (esquina superior derecha) --}}
    @if($product->offer)
    <div class="absolute top-0 right-0 bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-bl-lg font-bold shadow-lg z-40">
        <span class="text-lg">-{{ $product->offer->discount_percentage }}%</span>
    </div>
    @endif

    {{-- Slot opcional para acción superior izquierda --}}
    @isset($topAction)
    <div class="absolute top-2 left-2 z-40">
        {{ $topAction }}
    </div>
    @endisset

    {{-- Imagen --}}
    <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden{{ $product->offer ? ' bg-gradient-to-br from-orange-50 to-red-50' : '' }} relative" data-product-image>
        <x-cloudinary::image :public-id="$product->image" :alt="$product->name" class="w-full h-full object-covertransition-transform duration-300group-hover:scale-105" />
    </div>

    {{-- Contenido --}}
    <div class="p-6 relative flex flex-col flex-1">
        <h4 class="text-xl font-bold mb-2 text-gray-900">{{ $product->name }}</h4>
        <p class="text-gray-600 mb-4 line-clamp-3">{{ $product->description }}</p>

        {{-- Badge nombre de la oferta --}}
        @if($product->offer)
        <div class="mb-4">
            <span class="inline-block bg-orange-100 text-orange-800 text-xs px-3 py-1 rounded-full font-semibold">🏷 {{ $product->offer->name }}</span>
        </div>
        @endif

        {{-- Precio --}}
        <div class="mb-4">
            @if($product->offer)
            <div class="flex items-baseline gap-2">
                <span class="text-sm text-gray-400 line-through">€ {{ number_format($product->price, 2) }}</span>
                <span class="text-2xl font-bold text-orange-600">€ {{ number_format($product->final_price, 2) }}</span>
            </div>
            @else
            <span class="text-2xl font-bold text-primary-600">€ {{ number_format($product->price, 2) }}</span>
            @endif
        </div>

        {{-- Footer fijo (CTA siempre alineado) --}}
        <div class="mt-auto pt-4 flex flex-col items-center gap-3">

            {{-- CTA visual --}}
            @isset($actions)
            {{ $actions }}
            @else
            <span class="inline-block bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition cursor-pointer select-none" aria-hidden="true">Ver Detalles</span>
            @endisset

            {{-- Quick add en móvil --}}
            <form action="{{ route('cart.store') }}" method="POST" class="relative z-20 md:hidden w-full" data-add-to-cart-form>
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition font-semibold shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">🛒 Añadir al carrito</button>
            </form>
        </div>
    </div>

    {{-- Overlay oscuro (desktop) --}}
    <div class="hidden md:block absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition z-20 pointer-events-none"></div>

    {{-- Quick add centrado (desktop) --}}
    <div class="hidden md:flex absolute inset-0 z-30 items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
        <form action="{{ route('cart.store') }}" method="POST" class="pointer-events-auto" data-add-to-cart-form>
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">🛒 Añadir al carrito</button>
        </form>
    </div>
</div>