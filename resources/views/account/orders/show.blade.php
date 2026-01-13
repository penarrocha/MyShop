<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pedido #{{ $order->id }}
        </h2>
    </x-slot>

    @include('account._layout')

    @section('account.content')
    <div class="mb-4">
        <div class="text-sm text-gray-600">Estado: {{ $order->status }}</div>
        <div class="text-sm text-gray-600">Total: {{ number_format($order->total, 2) }} €</div>
    </div>

    <h4 class="font-semibold mb-2">Productos</h4>

    @foreach($order->items as $item)
    <div class="border rounded-lg p-3 mb-2 flex justify-between">
        <div>
            @if($item->product)
            <a href="{{ route('products.show', $item->product) }}"
                class="font-semibold text-primary-600 hover:underline">
                {{ $item->product->name }}
            </a>
            @else
            <span class="text-gray-500">Producto eliminado</span>
            @endif
        </div>

        <div class="text-sm text-gray-700">
            {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} €
        </div>
    </div>
    @endforeach
    @endsection
</x-app-layout>