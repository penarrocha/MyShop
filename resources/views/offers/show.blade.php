@extends('layouts.public')
@section('title', $offer->name . ' | ' .  config('app.name'))
@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header de la Oferta -->
    <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-lg shadowlg p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">{{ $offer->name }}</h1>
                <p class="text-xl">{{ $offer->description }}</p>
            </div>
            <div class="bg-white text-orange-600 rounded-full w-32 h-32 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ $offer->discount_percentage }}%</div>
                    <div class="text-sm">OFF</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Productos con esta oferta -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Productos en Oferta</h2>
        @if(!empty($offerProducts))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($offerProducts as $product)
            <x-product-card :product="$product" />
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-gray-100 rounded-lg">
            <p class="text-gray-500 text-lg">No hay productos con esta oferta actualmente.</p>
        </div>
        @endif
    </div>
    <!-- Botón volver -->
    <div class="mt-8">
        <a href="{{ route('offers.index') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">← Volver a Ofertas</a>
    </div>
</div>
@endsection