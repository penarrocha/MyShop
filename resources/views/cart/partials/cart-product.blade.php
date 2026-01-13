<tr class="hover:bg-gray-50">
    {{-- PRODUCTO --}}
    <td class="px-6 py-4">
        <div class="flex items-start gap-4">
            <x-product-image
                :public-id="$product->image"
                :alt="$product->name"
                class="h-16 w-16 object-cover rounded-md" />

            <div class="flex-1">
                <div class="font-semibold text-gray-900">
                    {{ $product->name }}
                </div>

                <div class="text-sm text-gray-600">
                    {{ $product->category->name ?? 'Sin categoría' }}

                    @if ($product->offer)
                    <span class="inline-block bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs ml-2">
                        🏷️ -{{ $product->offer->discount_percentage }}%
                    </span>
                    @endif
                </div>

                {{-- MOBILE: subtotal + eliminar --}}
                <div class="mt-3 flex items-center justify-between gap-3 md:hidden">
                    <div class="text-sm font-semibold text-gray-900">
                        Subtotal:
                        €{{ number_format($product->final_price * $product->quantity, 2) }}
                    </div>

                    <form action="{{ route('cart.destroy', $product->id) }}"
                        method="POST"
                        class="delete-form"
                        data-block-ui>
                        @csrf
                        @method('DELETE')

                        <button type="button"
                            class="inline-flex items-center justify-center rounded-full border border-red-300 bg-white p-2 text-red-600 shadow-sm hover:bg-red-50 active:scale-95 transition"
                            aria-label="Eliminar"
                            title="Eliminar"
                            data-delete-button
                            data-product-name="{{ $product->name }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-5 w-5">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                <path d="M10 11v6" />
                                <path d="M14 11v6" />
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </td>

    {{-- PRECIO --}}
    <td class="px-6 py-4">
        @if ($product->offer)
        <div>
            <span class="text-sm text-gray-400 line-through">
                €{{ number_format($product->price, 2) }}
            </span>
            <div class="font-semibold text-orange-600">
                €{{ number_format($product->final_price, 2) }}
            </div>
        </div>
        @else
        <div class="font-semibold text-gray-900">
            €{{ number_format($product->price, 2) }}
        </div>
        @endif
    </td>

    {{-- CANTIDAD --}}
    <td class="px-6 py-4">
        <form action="{{ route('cart.update', $product->id) }}"
            method="POST"
            class="flex items-center"
            data-block-ui>
            @csrf
            @method('PUT')

            <input type="number"
                name="quantity"
                value="{{ $product->quantity }}"
                min="1"
                class="w-20 rounded-md border-gray-300 shadow-sm p-1">

            <button type="submit"
                title="Actualizar cantidad"
                aria-label="Actualizar cantidad"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 ml-2 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 active:scale-[0.98] transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="h-4 w-4">
                    <path d="M21 12a9 9 0 1 1-2.64-6.36" />
                    <path d="M21 3v6h-6" />
                </svg>
            </button>
        </form>
    </td>

    {{-- SUBTOTAL DESKTOP --}}
    <td class="hidden md:table-cell px-6 py-4 font-semibold text-gray-900">
        €{{ number_format($product->final_price * $product->quantity, 2) }}
    </td>

    {{-- ELIMINAR DESKTOP --}}
    <td class="hidden md:table-cell px-6 py-4 text-center">
        <form action="{{ route('cart.destroy', $product->id) }}"
            method="POST"
            class="inline delete-form"
            data-block-ui>
            @csrf
            @method('DELETE')

            <button type="button"
                class="inline-flex items-center justify-center rounded-full border border-red-300 bg-white p-2 text-red-600 shadow-sm hover:bg-red-50 active:scale-95 transition"
                aria-label="Eliminar"
                title="Eliminar"
                data-delete-button
                data-product-name="{{ $product->name }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="h-5 w-5">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    <path d="M10 11v6" />
                    <path d="M14 11v6" />
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                </svg>
            </button>
        </form>
    </td>
</tr>