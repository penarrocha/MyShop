@php $isEdit = isset($address); @endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Alias (opcional)</label>
        <input name="label" type="text"
            value="{{ old('label', $isEdit ? $address->label : '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600"
            placeholder="Casa, Trabajo...">
        @error('label') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Nombre del destinatario</label>
        <input name="recipient_name" type="text" required
            value="{{ old('recipient_name', $isEdit ? $address->recipient_name : '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
        @error('recipient_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Teléfono (opcional)</label>
        <input name="phone" type="text"
            value="{{ old('phone', $isEdit ? $address->phone : '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Dirección (línea 1)</label>
        <input name="line1" type="text" required
            value="{{ old('line1', $isEdit ? $address->line1 : '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
        @error('line1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Dirección (línea 2) (opcional)</label>
        <input name="line2" type="text"
            value="{{ old('line2', $isEdit ? $address->line2 : '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
        @error('line2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Ciudad</label>
        <input name="city" type="text" required
            value="{{ old('city', $isEdit ? $address->city : '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
        @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Provincia (opcional)</label>
        <input name="province" type="text"
            value="{{ old('province', $isEdit ? $address->province : '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
        @error('province') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Código postal</label>
        <input name="postcode" type="text" required
            value="{{ old('postcode', $isEdit ? $address->postcode : '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
        @error('postcode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">País (ISO-2)</label>
        <input name="country" type="text" maxlength="2" required
            value="{{ old('country', $isEdit ? $address->country : 'ES') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600"
            placeholder="ES">
        @error('country') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_default" value="1"
                class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-600"
                {{ old('is_default', $isEdit ? (int)$address->is_default : 0) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Marcar como predeterminada</span>
        </label>
        @error('is_default') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>