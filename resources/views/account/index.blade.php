<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi cuenta
        </h2>
    </x-slot>

    <x-account-layout>
        <h3 class="text-lg font-semibold mb-4">Resumen</h3>

        <div class="grid sm:grid-cols-2 gap-4">
            <a href="{{ route('account.profile.edit') }}" class="border rounded-lg p-4 hover:bg-gray-50">
                <div class="font-semibold">Datos personales</div>
                <div class="text-sm text-gray-600">Nombre, email y contraseña</div>
            </a>

            <a href="{{ route('account.addresses.index') }}" class="border rounded-lg p-4 hover:bg-gray-50">
                <div class="font-semibold">Direcciones</div>
                <div class="text-sm text-gray-600">Envío y facturación</div>
            </a>

            <a href="{{ route('account.orders.index') }}" class="border rounded-lg p-4 hover:bg-gray-50">
                <div class="font-semibold">Mis pedidos</div>
                <div class="text-sm text-gray-600">Historial y detalle</div>
            </a>
        </div>
    </x-account-layout>
</x-app-layout>