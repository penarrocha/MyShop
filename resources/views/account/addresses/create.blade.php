<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi cuenta
        </h2>
    </x-slot>

    <x-account-layout>
        <h3 class="text-lg font-semibold mb-4">
            Nueva dirección
        </h3>

        <form method="POST"
            action="{{ route('account.addresses.store') }}"
            class="space-y-4">
            @csrf

            @include('account.addresses._form')

            <div class="pt-2">
                <button type="submit"
                    class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                    Guardar
                </button>
            </div>
        </form>
    </x-account-layout>
</x-app-layout>