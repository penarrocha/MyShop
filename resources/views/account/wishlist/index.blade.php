<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi cuenta</h2>
    </x-slot>

    <x-account-layout>
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Mis favoritos</h3>
            <p class="text-sm text-gray-600">Tus productos guardados para más tarde.</p>
        </div>

        @if($products->isEmpty())
        <div class="rounded-lg border border-gray-200 p-6 text-gray-600">
            Aún no tienes favoritos.
        </div>
        @else
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach($products as $product)
            <div class="rounded-lg border border-gray-200 p-4 flex gap-4">
                @if($product->image_url)
                <img src="{{ $product->image_url }}" alt=""
                    class="h-16 w-16 rounded object-cover border">
                @endif

                <div class="min-w-0 flex-1">
                    <a href="{{ route('products.show', $product) }}"
                        class="font-semibold text-primary-600 hover:underline">
                        {{ $product->name }}
                    </a>

                    <div class="text-sm text-gray-700 mt-1">
                        {{ number_format($product->final_price ?? $product->price, 2) }} €
                    </div>

                    <div class="mt-3 flex items-center gap-2">
                        <form method="POST" action="{{ route('account.wishlist.destroy', $product) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-sm font-semibold text-red-600 hover:underline"
                                onclick="return confirm('¿Quitar de favoritos?')">
                                Quitar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </x-account-layout>
</x-app-layout>