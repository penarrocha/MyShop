<x-admin-layout>
    <x-slot name="title">Administrar ofertas</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Gestión de Ofertas
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Crea y gestiona campañas de descuento.
                </p>
            </div>

            <a href="{{ route('admin.offers.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Crear nueva oferta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Oferta
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descuento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Plazos
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="w-32 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($offers as $offer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $offer->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $offer->slug }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        -{{ number_format((float)$offer->discount_percentage, 2, ',', '.') }}%
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    <div>{{ optional($offer->start_date)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">→ {{ optional($offer->end_date)->format('d/m/Y') }}</div>
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-col gap-2">
                                        <span class="inline-flex w-fit items-center rounded-full border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-700">
                                            {{ $offer->is_active_label }}
                                        </span>

                                        <span class="inline-flex w-fit items-center rounded-full border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-700">
                                            {{ $offer->window_label }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <x-admin.admin-table-actions
                                        :edit-url="route('admin.offers.edit', $offer->id)"
                                        :delete-url="route('admin.offers.destroy', $offer->id)"
                                        :item-name="$offer->name"
                                        delete-title="Eliminar oferta"
                                        delete-message-html="¿Seguro que quieres eliminar <code class='px-1 py-0.5 rounded bg-gray-100 text-gray-800'>{{ e($offer->name) }}</code>?" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 text-4xl mb-4">🏷️</div>
                                    <p class="text-gray-500 text-lg font-medium">No hay ofertas aún</p>
                                    <p class="text-gray-400 text-sm mt-2">Crea tu primera oferta con el botón "Crear nueva oferta"</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($offers->hasPages())
            <div class="bg-white rounded-lg border border-gray-200 px-6 py-4">
                {{ $offers->withQueryString()->links() }}
            </div>
            @endif

        </div>
    </div>
</x-admin-layout>