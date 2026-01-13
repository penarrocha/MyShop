@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Pedidos</h1>
        <p class="mt-1 text-sm text-gray-600">Listado y seguimiento de pedidos.</p>
    </div>

    {{-- Tabla --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Pedido
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Fecha
                        </th>
                        <th class="w-32 px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                #{{ $order->id }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">
                                {{ $order->user->name ?? '—' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $order->user->email ?? '' }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-700">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ number_format($order->total, 2, ',', '.') }} €
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <x-admin.admin-table-actions
                                :edit-url="route('admin.orders.show', $order)"
                                :show-delete="false"
                                :item-name="'Pedido #' . $order->id" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-600">
                            No hay pedidos.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($orders, 'links'))
        <div class="border-t border-gray-200 bg-white px-6 py-4">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection