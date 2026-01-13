<x-admin-layout>
    <x-slot name="title">Editar oferta</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar oferta
            </h2>

            <a href="{{ route('admin.offers.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.offers.update', $offer) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('admin.offers._form', ['offer' => $offer])

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.offers.index') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>

                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Zona peligrosa (opcional) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Zona peligrosa</h3>
                        <p class="mt-1 text-sm text-gray-600">Eliminar esta oferta la enviará a la papelera.</p>
                    </div>

                    <x-admin.admin-table-actions
                        :edit-url="null"
                        :delete-url="route('admin.offers.destroy', $offer)"
                        :item-name="$offer->name"
                        :show-edit="false"
                        delete-title="Eliminar oferta"
                        delete-message-html="¿Seguro que quieres eliminar <code class='px-1 py-0.5 rounded bg-gray-100 text-gray-800'>{{ e($offer->name) }}</code>?" />
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>