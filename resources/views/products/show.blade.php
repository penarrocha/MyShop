@extends('layouts.public')
@section('title', $product->name . ' | ' . config('app.name'))
@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Imagen del Producto -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="bg-gray-200 flex items-center justify-center">
                <x-product-image :public-id="$product->image" :alt="$product->name" class="w-full h-full object-cover" />
            </div>
        </div>
        <!-- Información del Producto -->
        <div class="bg-white rounded-lg shadow-lg p-6 h-full">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
            <p class="text-gray-600 mb-6">{{ $product->description }}</p>
            <!-- Precio -->
            <div class="mb-6">
                @if($product->offer)
                <div class="flex items-baseline gap-3">
                    <span class="text-2xl text-gray-400 line-through">€{{ number_format($product->price, 2) }}</span>
                    <span class="text-4xl font-bold text-orange-600">€{{ number_format($product->final_price, 2) }}</span>
                </div>
                <p class="text-sm text-orange-600 mt-2">¡Ahorra €{{ number_format($product->price - $product->final_price, 2) }}!</p>
                @else
                <span class="text-4xl font-bold text-primary-600">€{{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            <!-- Categoría -->
            @if(isset($category))
            <div class="mb-6">
                <span class="text-sm text-gray-500">Categoría:</span>
                <a href="{{ route('categories.show', $category->slug) }}" class="ml-2 bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm hover:bg-primary-200 transition">{{ $category->name }}</a>
            </div>
            @endif
            <!-- Oferta -->
            @if($product->offer)
            <div class="mb-6">
                <span class="text-sm text-gray-500">Oferta activa:</span>
                <div class="mt-2">
                    <span class="inline-block bg-orange-100 textorange-800 text-sm px-3 py-1 rounded-full">🏷 {{ $product->offer->name }} (-{{ $product->offer->discount_percentage }}%)</span>
                </div>
            </div>
            @endif

            <!-- Botones de Acción -->
            <div class="flex items-center space-x-4">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">🛒 Añadir al Carrito</button>
                </form>

                {{-- Botón de Wishlist (solo para usuarios autenticados) --}}
                @auth
                @if ($inWishlist)
                <form action="{{ route('account.wishlist.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="border-2 border-red-500 text-red-500 px-6 py-3 rounded-lg hover:bg-red-50 transition-colors">
                        💔 Quitar de favoritos
                    </button>
                </form>
                @else
                <form action="{{ route('account.wishlist.store', $product) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="border-2 border-green-600 text-green-600 px-6 py-3 rounded-lg hover:bg-green-50 transition-colors">
                        ❤️ Guardar en favoritos
                    </button>
                </form>
                @endif
                @endauth

                <a href="{{ route('products.index') }}" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-100 transition">← Volver a Productos</a>
            </div>
        </div>
    </div>
</div>
@endsection