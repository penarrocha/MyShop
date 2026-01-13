@extends('layouts.public')
@section('title', $category->name . ' - ' . config('app.name'))
@push('styles')
<style>
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }
</style>
@endpush
@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $category->name }}</h1>
        <p class="text-gray-600 mb-4">{{ $category->description }}</p>
    </div>
    @if(!empty($categoryProducts))
    <div class="product-grid">
        @foreach($categoryProducts as $product)
        <x-product-card :product="$product" />
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <p class="text-gray-500 text-lg">No hay productos en esta categoría.</p>
    </div>
    @endif
</div>
@endsection