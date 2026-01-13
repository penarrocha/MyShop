@extends('layouts.public')

@section('title', 'Tu cesta de la compra - ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">🛒 Tu compra</h1>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="min-w-[760px] w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Producto</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Precio</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Cantidad</th>
                        <th class="hidden md:table-cell px-6 py-4 text-left text-sm font-semibold text-gray-700">Subtotal</th>
                        <th class="hidden md:table-cell px-6 py-4 text-center text-sm font-semibold text-gray-700">Eliminar</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach($cartProducts as $product)
                    @include('cart.partials.cart-product', ['product' => $product])
                    @endforeach
                </tbody>

                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700">Total:</td>
                        <td class="px-6 py-4 font-bold text-xl text-primary-600">€{{ number_format($cartState['total'] ?? 0, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3">← Seguir Comprando</a>

        <form action="{{ route('cart.checkout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-600 text-white font-bold px-6 py-3 rounded">Realizar Pedido →</button>
        </form>
    </div>
</div>

{{-- Modal confirmar eliminación --}}
@include('cart.partials.delete-modal')

<x-page-blocker text="Actualizando carrito" />

@endsection