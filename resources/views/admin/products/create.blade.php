<x-admin-layout>
    <x-slot name="title">Crear Nuevo Producto</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nuevo Producto</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('admin.products._form', ['categories' => $categories, 'offers' => $offers])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>