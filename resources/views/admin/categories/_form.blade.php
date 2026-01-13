@php
/** @var \App\Models\Category|null $category */
$isEdit = isset($category);
@endphp

<div class="grid gap-6 md:grid-cols-2">
    {{-- Nombre --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input id="name" name="name" type="text" required
            value="{{ old('name', $category->name ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Slug --}}
    <div>
        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
        <input id="slug" name="slug" type="text"
            value="{{ old('slug', $category->slug ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('slug') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Descripción --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea id="description" name="description" rows="4"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Imagen --}}
    <div class="md:col-span-2">
        <label for="image" class="block text-sm font-medium text-gray-700">Imagen</label>

        @if($isEdit)
        <x-image-picker
            name="image"
            :public-id="$category->getRawOriginal('image')"
            :alt="$category->name"
            class="object-cover h-96" />
        @else
        <x-image-picker name="image" class="object-cover h-96" />
        @endif

        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Acciones --}}
<div class="mt-6 flex justify-end gap-4">
    <a href="{{ route('admin.categories.index') }}"
        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
        Cancelar
    </a>

    <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        {{ $isEdit ? 'Actualizar categoría' : 'Crear categoría' }}
    </button>
</div>