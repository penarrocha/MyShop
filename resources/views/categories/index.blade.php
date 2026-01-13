@extends('layouts.public')
@section('title', 'Categorías - ' . config('app.name'))
@push('styles')
<style>
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }
</style>
@endpush
@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Nuestras Categorías</h1>
        <p class="text-gray-600">Explora nuestros productos por categoría.</p>
    </div>
    <div class="category-grid">
        @forelse($categories as $category)
        <x-category-card :category="$category" />
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500 text-lg">No hay categorías disponibles.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection