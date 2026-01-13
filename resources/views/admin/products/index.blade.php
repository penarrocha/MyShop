<x-admin-layout>
    <x-slot name="title">Administrar productos</x-slot>
    {{-- Header interno de la página --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            Gestión de Productos
        </h1>

        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Crear Nuevo Producto
        </a>
    </div>

    {{-- Tabla --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-48">
                            Imagen
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Producto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Categoría
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Precio
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-44">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $product)
                    <tr class="hover:bg-gray-50">

                        {{-- Imagen --}}
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.products.edit', $product->id) }}">
                                <x-cloudinary-image
                                    :public-id="$product->image"
                                    :alt="$product->name"
                                    :height="100"
                                    class="object-cover rounded-md" />
                            </a>
                        </td>

                        {{-- Producto --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $product->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                            </div>
                        </td>

                        {{-- Categoría --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $product->category->name ?? '—' }}
                            </span>
                        </td>

                        {{-- Precio --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                €{{ number_format($product->price, 2, ',', '.') }}
                            </div>
                            @if ($product->offer)
                            <div class="text-xs text-orange-600">
                                -{{ $product->offer->discount_percentage }}%
                            </div>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <x-admin.admin-table-actions
                                :edit-url="route('admin.products.edit', $product->id)"
                                :delete-url="route('admin.products.destroy', $product->id)"
                                :item-name="$product->name"
                                delete-title="Eliminar producto" />
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-4xl mb-3">📦</div>
                            No hay productos todavía
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($products->hasPages())
            <div class="bg-white rounded-lg border border-gray-200 px-6 py-4 mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>