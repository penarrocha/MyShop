<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi cuenta</h2>
    </x-slot>

    <x-account-layout>
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Mis datos</h3>
            <p class="text-sm text-gray-600">Actualiza tu información y tu contraseña.</p>
        </div>

        <div class="space-y-6">
            <div class="p-4 sm:p-6 bg-white border border-gray-200 rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white border border-gray-200 rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white border border-red-200 rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </x-account-layout>
</x-app-layout>