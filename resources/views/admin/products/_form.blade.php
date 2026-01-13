@php
/** @var \App\Models\Product|null $product */
$isEdit = isset($product);
@endphp

<div class="grid gap-6 md:grid-cols-2">
    {{-- Nombre --}}
    <div class="md:col-span-1">
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input id="name" name="name" type="text" required
            value="{{ old('name', $product->name ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Slug --}}
    <div class="md:col-span-1">
        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
        <input id="slug" name="slug" type="text"
            value="{{ old('slug', $product->slug ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('slug') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Descripción --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea id="description" name="description" rows="4" required
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Imagen --}}
    <div class="md:col-span-2">
        <label for="image" class="block text-sm font-medium text-gray-700">Imagen</label>

        @if($isEdit)
        <x-image-picker
            name="image"
            :public-id="$product->getRawOriginal('image')"
            :alt="$product->name"
            class="object-cover h-96" />
        @else
        <x-image-picker name="image" class="object-cover h-96" />
        @endif

        @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Precio --}}
    <div class="md:col-span-1">
        <label for="price" class="block text-sm font-medium text-gray-700">Precio</label>
        <input id="price" name="price" type="number" step="0.01" min="0" required
            value="{{ old('price', $product->price ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('price') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Categoría --}}
    <div class="md:col-span-1">
        <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría</label>
        <select id="category_id" name="category_id" required
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
            <option value="">Selecciona una categoría</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected(old('category_id', $product->category_id ?? null) == $category->id)>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Oferta --}}
    <div class="md:col-span-1">
        <label for="offer_id" class="block text-sm font-medium text-gray-700">Oferta</label>
        <select id="offer_id" name="offer_id"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
            <option value="">Sin oferta</option>
            @foreach ($offers as $offer)
            <option value="{{ $offer->id }}"
                @selected(old('offer_id', $product->offer_id ?? null) == $offer->id)>
                {{ $offer->name }} (-{{ $offer->discount_percentage }}%)
            </option>
            @endforeach
        </select>
        @error('offer_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Remove image (solo edit) --}}
    @if($isEdit)
    <input type="hidden" name="remove_image" value="0">
    @endif
</div>

{{-- Acciones --}}
<div class="mt-6 flex justify-end gap-4">
    <a href="{{ route('admin.products.index') }}"
        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
        Cancelar
    </a>

    <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        {{ $isEdit ? 'Actualizar producto' : 'Crear producto' }}
    </button>
</div>