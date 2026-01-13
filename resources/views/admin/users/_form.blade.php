@php
$isEdit = !empty($user);

// slugs seleccionados: primero old(), si no los actuales del usuario
$selectedRoles = collect(old('roles', $isEdit ? $user->roles->pluck('slug')->all() : []));
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-1">
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input id="name" name="name" type="text" required
            value="{{ old('name', $user->name ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('name')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-1">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" required
            value="{{ old('email', $user->email ?? '') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
        @error('email')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-1">
        <span class="block text-sm font-medium text-gray-700">Roles</span>

        <div class="mt-2 space-y-2">
            @foreach($roles as $role)
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="roles[]"
                    value="{{ $role->slug }}"
                    @checked($selectedRoles->contains($role->slug))
                class="rounded border-gray-300 text-gray-900 shadow-sm focus:border-gray-400 focus:ring-gray-400"
                >
                <span class="text-sm text-gray-700">{{ $role->name }}</span>
            </label>
            @endforeach
        </div>

        @error('roles')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('roles.*')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Contraseña
                    @if($isEdit)
                    <span class="text-xs font-normal text-gray-500">(dejar en blanco para no cambiar)</span>
                    @endif
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    {{ $isEdit ? '' : 'required' }}
                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
                @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Confirmar contraseña
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    {{ $isEdit ? '' : 'required' }}
                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
            </div>
        </div>
    </div>


    @if($isEdit)
    <div class="md:col-span-2">
        <p class="text-sm text-gray-600">
            Última actualización: <span class="font-medium text-gray-800">{{ optional($user->updated_at)->format('d/m/Y H:i') }}</span>
        </p>
    </div>
    @endif
</div>