<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi cuenta</h2>
    </x-slot>

    <x-account-layout>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Mis pedidos</h3>
            <p class="text-sm text-gray-600">Consulta tu historial de pedidos y el detalle de cada uno.</p>
        </div>

        <div class="mt-6">
            @if($orders->count() === 0)
            <div class="rounded-lg border border-gray-200 p-6 text-gray-600">
                Todavía no tienes pedidos.
            </div>
            @else
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="text-left font-semibold px-6 py-3">Pedido</th>
                            <th class="text-left font-semibold px-6 py-3">Fecha</th>
                            <th class="text-left font-semibold px-6 py-3">Estado</th>
                            <th class="text-right font-semibold px-6 py-3">Total</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y bg-white">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                #{{ $order->id }}
                                @if(isset($order->items_count))
                                <span class="ml-2 text-xs text-gray-500">({{ $order->items_count }} items)</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                {{ number_format($order->total, 2) }} €
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('account.orders.show', $order) }}"
                                    class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </x-account-layout>
</x-app-layout>