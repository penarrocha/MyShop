@php
$isEdit = !empty($offer);
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-1">
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input id="name" name="name" type="text" required
            value="{{ old('name', $offer->name ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-1">
        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
        <input id="slug" name="slug" type="text" required
            value="{{ old('slug', $offer->slug ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('slug') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-1">
        <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Descuento (%)</label>
        <input id="discount_percentage" name="discount_percentage" type="number" step="0.01" min="0" max="99.99" required
            value="{{ old('discount_percentage', $offer->discount_percentage ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('discount_percentage') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-1 flex items-center gap-3 pt-7">
        <input id="active" name="active" type="checkbox" value="1"
            @checked(old('active', (int)($offer->active ?? 1)) === 1)
        class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-400">
        <label for="active" class="text-sm font-medium text-gray-700">Activa</label>
        @error('active') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-1">
        <label for="start_date" class="block text-sm font-medium text-gray-700">Inicio</label>
        <input id="start_date" name="start_date" type="datetime-local" required
            value="{{ old('start_date', optional($offer->start_date ?? null)?->format('Y-m-d\TH:i')) }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('start_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-1">
        <label for="end_date" class="block text-sm font-medium text-gray-700">Fin</label>
        <input id="end_date" name="end_date" type="datetime-local" required
            value="{{ old('end_date', optional($offer->end_date ?? null)?->format('Y-m-d\TH:i')) }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('end_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea id="description" name="description" rows="4"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">{{ old('description', $offer->description ?? '') }}</textarea>
        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>