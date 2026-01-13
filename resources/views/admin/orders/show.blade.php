@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Pedido #{{ $order->id }}
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $order->created_at->format('d/m/Y H:i') }}
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Volver
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Resumen --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="text-base font-semibold text-gray-900">Resumen</h2>

            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Cliente</dt>
                    <dd class="text-right">
                        <div class="font-medium text-gray-900">
                            {{ $order->user->name ?? '—' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $order->user->email ?? '' }}
                        </div>
                    </dd>
                </div>

                <div class="flex justify-between">
                    <dt class="text-gray-600">Estado</dt>
                    <dd class="font-medium text-gray-900">
                        {{ ucfirst($order->status) }}
                    </dd>
                </div>

                <div class="border-t border-gray-200 pt-3 flex justify-between">
                    <dt class="font-medium text-gray-900">Total</dt>
                    <dd class="font-semibold text-gray-900">
                        {{ number_format($order->total, 2, ',', '.') }} €
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Líneas del pedido --}}
        <div class="lg:col-span-2 overflow-hidden rounded-lg border border-gray-200 bg-white">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-900">Productos</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Producto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Precio unidad
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Total
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($order->items as $item)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $item->product->name ?? 'Producto eliminado' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ number_format($item->unit_price, 2, ',', '.') }} €
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $item->quantity }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ number_format($item->line_total, 2, ',', '.') }} €
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-600">
                                Este pedido no tiene líneas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection