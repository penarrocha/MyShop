<x-admin-layout>
    <x-slot name="title">Administrar categorías</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Categorías') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Nueva Categoría
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-48">
                                    Imagen
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Categoría
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50">

                                {{-- Imagen --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}">
                                        <x-cloudinary-image
                                            :public-id="$category->image"
                                            :alt="$category->name"
                                            :height="100"
                                            class="object-cover rounded-md" />
                                    </a>
                                </td>
                                {{-- Categoría --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                </td>

                                {{-- Acciones --}}

                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <x-admin.admin-table-actions
                                        :edit-url="route('admin.categories.edit', $category->id)"
                                        :delete-url="route('admin.categories.destroy', $category->id)"
                                        :item-name="$category->name"
                                        delete-title="Eliminar categoría" />
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 text-4xl mb-4">📦</div>
                                    <p class="text-gray-500 text-lg font-medium">No hay categorías aún</p>
                                    <p class="text-gray-400 text-sm mt-2">Crea tu primera categoría usando el botón "Crear Nueva Categoría"</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($categories->hasPages())
                    <div class="bg-white rounded-lg border border-gray-200 px-6 py-4">
                        {{ $categories->withQueryString()->links() }}
                    </div>
                    @endif
                </div> <!-- overflow-x-auto -->
            </div> <!-- bg-white -->

        </div> <!-- w-full -->
    </div> <!-- py-12 -->
</x-admin-layout>