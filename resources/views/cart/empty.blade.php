@extends('layouts.public')
@section('title', 'Tu cesta de la compra - ' . config('app.name'))
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">🛒 Carrito de Compras</h1>

    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="text-6xl mb-4">🛒</div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            Tu carrito está vacío
        </h2>

        <p class="text-gray-600 mb-6">
            ¡Añade productos para comenzar tu compra!
        </p>

        <!-- Botones -->
        <div class="flex justify-center gap-6">
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center justify-center bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition">
                Ver Productos
            </a>

            <a
                href="{{ route('offers.index') }}"
                class="inline-flex items-center justify-center bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition">
                Ver Ofertas
            </a>
        </div>
    </div>
</div>

@endsection