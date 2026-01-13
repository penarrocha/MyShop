<x-admin-layout>
    <x-slot name="title">Crear oferta</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear oferta
            </h2>

            <a href="{{ route('admin.offers.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.offers.store') }}" class="space-y-6">
                    @csrf

                    @include('admin.offers._form', ['offer' => null])

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.offers.index') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>

                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                            Crear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>