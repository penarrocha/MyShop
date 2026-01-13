<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi cuenta</h2>
    </x-slot>

    @include('account._layout')

    @section('account.content')
    <h3 class="text-lg font-semibold mb-4">Editar dirección</h3>

    <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('account.addresses._form', ['address' => $address])

        <button class="rounded-md bg-primary-600 px-4 py-2 text-white">
            Guardar cambios
        </button>
    </form>
    @endsection
</x-app-layout>